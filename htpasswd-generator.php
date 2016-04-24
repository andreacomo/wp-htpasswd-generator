<?php
/*
Plugin Name: Htpasswd Generator
Plugin URI: https://wordpress.org/plugins/health-check/
Description: Sync Wordpress user and password with .htpasswd file 
Version: 1.0.0
Author: Andrea Como
Author URI: http://codingjam.it
Text Domain: Security
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'user_register', 'cnj_htpasswd_generator_add', 10, 1 );
add_action( 'profile_update', 'cnj_htpasswd_generator_add', 10, 2 );
add_action( 'delete_user', 'cnj_htpasswd_generator_remove', 10, 1 );

function cnj_htpasswd_generator_add($user_id, $user_data) {
    if ($user_data == null) {
        $username = $_POST['user_login'];
    } else {
        $username = $user_data->user_login;
    }
    if (isset($_POST['pass1-text'])) {
        update_htpasswd($username, $_POST['pass1-text']);
    }
}

function cnj_htpasswd_generator_remove($user_id) {
    $user_data = get_userdata( $user_id );
    update_htpasswd($user_data->user_login);
}

function update_htpasswd( $username, $password ) {
    $file = ".htpasswd";
    if (!file_exists($file)) {
        touch($file);
    }
    
    $passwdFile = fopen($file, "r+") or die("Unable to open file!");
    $newContent = '';
    while(!feof($passwdFile)) {

        $line = trim(fgets($passwdFile));
        preg_match("/^" . $username . ":.*/", $line, $matches);
        if (!empty($line) && !$matches) {
            $newContent .= $line . "\r\n";
        }
    }
    if (!empty($password)) {
        $newContent .= $username . ":" . crypt($password, base64_encode($password)) . "\r\n";
    }
    file_put_contents($file, $newContent);

    fclose($passwdFile);
}

?>
