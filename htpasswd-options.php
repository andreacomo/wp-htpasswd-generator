<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function cnj_htpasswd_generator_menu() {
    add_options_page(
        'Htpasswd Generator Options', 
        'Htpasswd Generator', 
        'manage_options', 
        'cnj-htpasswd-generator-id', 
        'cnj_htpasswd_generator_options'
    );
}

function cnj_htpasswd_generator_options() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} 
    
    include 'htpasswd-options-form.php';
}

?>