<?php

$option_group = 'cnj_htpassws_options_ftp';

function cnj_htpasswd_generator_register_options() {
    register_setting($option_group, 'ftp_enabled');
    register_setting($option_group, 'ftp_username');
    register_setting($option_group, 'ftp_password');
    register_setting($option_group, 'ftp_server');
    register_setting($option_group, 'ftp_port');
    register_setting($option_group, 'ftp_ftp_dest_path');
}

?>
<div class="wrap">
    <h2>Htpasswd Generator Options</h2>
    <p>Here you can enable FTP upload feature to send .htpasswd file another FTP server after each update</p>
    <form method="post" action="options.php">
        <?php 
        settings_fields($option_group);
        do_settings_sections($option_group);
        ?>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Enable FTP .htaccess upload</th>
                <td><input type="checkbox" name="ftp_enabled" value="<?php echo esc_attr( get_option('ftp_enabled') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Username</th>
                <td><input type="text" name="ftp_username" value="<?php echo esc_attr( get_option('ftp_username') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Password</th>
                <td><input type="password" name="ftp_password" value="<?php echo esc_attr( get_option('ftp_password') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">FTP ip/domain</th>
                <td><input type="text" name="ftp_server" value="<?php echo esc_attr( get_option('ftp_server') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Port (default 21)</th>
                <td><input type="text" name="ftp_port" value="<?php echo esc_attr( get_option('ftp_port') ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Destination path (must exists)</th>
                <td><input type="text" name="ftp_ftp_dest_path" value="<?php echo esc_attr( get_option('ftp_ftp_dest_path') ); ?>" /></td>
            </tr>
        </table>
        
        <?php 
        submit_button();
        ?>
    </form>
</div>