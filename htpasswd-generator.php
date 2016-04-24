<?php
/*
Plugin Name: Htpasswd Generator
Plugin URI: https://github.com/andreacomo/wp-htpasswd-generator
Description: Sync Wordpress user and password with .htpasswd file 
Version: 1.0.0
Author: Andrea Como
Author URI: http://codingjam.it
Text Domain: Security
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'user_register', 'cnj_htpasswd_generator_on_add', 10, 1 );
add_action( 'profile_update', 'cnj_htpasswd_generator_on_add', 10, 2 );
add_action( 'delete_user', 'cnj_htpasswd_generator_on_remove', 10, 1 );
add_action( 'password_reset', 'cnj_htpasswd_generator_on_reset', 10, 2 );

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
    $file = plugin_dir_path(__FILE__) . ".htpasswd_generated";
    if (!file_exists($file)) {
        touch($file);
        cnj_generate_htaccess($file);
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
        $newContent .= $username . ":" . crypt($password, base64_encode($password)) . "\r\n";
    }
    file_put_contents($file, $newContent);

    fclose($passwdFile);
}

function cnj_generate_htaccess($htpasswd) {
    $content = "# enable basic authentication\r\n";
    $content .= "AuthType Basic\r\n";
    $content .= "# this text is displayed in the login dialog\r\n";
    $content .= "AuthName \"Protected Resources\"\r\n";
    $content .= "# The absolute path of the Apache htpasswd file\r\n";
    $content .= "AuthUserFile " . $htpasswd . "\r\n";
    $content .= "#Allows any user in the .htpasswd file to access the directory\r\n";
    $content .= "require valid-user";
    
    $htaccess = plugin_dir_path(__FILE__) . "rename_me_to_.htaccess";
    file_put_contents($htaccess, $content) or die("Unable to open file " . $htaccess);
}
?>
