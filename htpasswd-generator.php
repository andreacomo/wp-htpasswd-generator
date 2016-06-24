<?php
/*
Plugin Name: Htpasswd Generator
Plugin URI: https://it.wordpress.org/plugins/wp-htpasswd-generator/
Description: Sync Wordpress user and password with .htpasswd file 
Version: 1.1.2
Author: Andrea Como
Author URI: http://codingjam.it
Text Domain: Security
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'user_register', 'cnj_htpasswd_generator_on_add', 10, 1 );
add_action( 'profile_update', 'cnj_htpasswd_generator_on_add', 10, 2 );
add_action( 'delete_user', 'cnj_htpasswd_generator_on_remove', 10, 1 );
add_action( 'password_reset', 'cnj_htpasswd_generator_on_reset', 10, 2 );

include_once 'htpasswd-options-page.php';
include_once 'htpasswd-options-ftp.php';
include_once 'htpasswd-options-generic.php';
include_once 'ftp-client.php';
include_once 'utils.php';
include_once 'crypt_apr1_md5.php';

function cnj_htpasswd_generator_on_add($user_id, $user_data) {
    if ($user_data == null) {
        $username = $_POST['user_login'];
    } else {
        $username = $user_data->user_login;
    }
    if (isset($_POST['pass1-text'])) {
        cnj_update_htpasswd($username, $_POST['pass1-text']);
    }
}

function cnj_htpasswd_generator_on_remove($user_id) {
    $user_data = get_userdata( $user_id );
    cnj_update_htpasswd($user_data->user_login);
}

function cnj_htpasswd_generator_on_reset($user, $password) {
    cnj_update_htpasswd($user->user_login, $password);
}

function cnj_update_htpasswd( $username, $password ) {
    $file = HtaccessUtils::getHtpasswdPath();
    if (!file_exists($file)) {
        touch($file);
        HtaccessUtils::generateHtaccess($file);
    }
    
    $passwdFile = fopen($file, "r+") or die("Unable to open file " . $file);
    $newContent = '';
    while(!feof($passwdFile)) {

        $line = trim(fgets($passwdFile));
        preg_match("/^" . $username . ":.*/", $line, $matches);
        if (!empty($line) && !$matches) {
            $newContent .= $line . "\r\n";
        }
    }
    if (!empty($password)) {
        $newContent .= $username . ":" . crypt_apr1_md5($password) . "\r\n";
    }
    file_put_contents($file, $newContent);

    fclose($passwdFile);
    
    $ftp = HtpasswdFtpOptions::load();
    if ($ftp->isEnabled()) {
        include 'ftp-uploader.php';
        cnj_upload_via_ftp($file, $ftp);
    }
    $options = HtpasswdGenericOptions::load();
    if ($options->hasPaths()) {
        foreach ($options->getPathsAsArray() as $destination) {
            HtaccessUtils::copyHtaccessTo($destination);
        }
    }
}

function cnj_upload_via_ftp($file, $ftp) {
    FtpClient::connect($ftp->getServer(), $ftp->getPort())
        ->withCredentials($ftp->getUsername(), $ftp->getPassword())
        ->upload($file, $ftp->getDestinationPath() . basename($file))
        ->close();
}
?>
