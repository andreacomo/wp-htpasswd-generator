<?php
class HtpasswdSettingsPage
{
    private $page_id = 'htpasswd-generator-options';
    
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    
    /**
    * Page section
    */
    private $settings_section = 'htpasswd_setting_section_ftp';
    
    /**
    * Option group
    */
    private $option_group = 'htpasswd_ftp_option_group';

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Htpasswd Generator Options', 
            'Htpasswd Generator', 
            'manage_options', 
            $this->page_id, 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = HtpasswdFtpOptions::load();
        ?>
        <div class="wrap">
            <h2>Htpasswd Generator Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields($this->option_group);   
                do_settings_sections($this->page_id );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            $this->option_group, // Option group
            'cnj_htpasswd_ftp_options', // Option name
            array( $this, 'sanitize_on_submit' ) // Sanitize
        );

        add_settings_section(
            $this->settings_section,
            'FTP Upload Settings',
            array( $this, 'print_section_info' ), // Callback
            $this->page_id // Page
        );
            
        $this->add_form_field('ftp_enabled', 'Enable FTP .htaccess upload', 'enable_ftp_callback');
        
        $this->add_form_field('ftp_username', 'Username', 'ftp_username_callback'); 
        
        $this->add_form_field('ftp_password', 'Password', 'ftp_password_callback');
        
        $this->add_form_field('ftp_server', 'FTP ip/domain', 'ftp_server_callback');
        
        $this->add_form_field('ftp_port', 'FTP port (default 21)', 'ftp_port_callback');
        
        $this->add_form_field('ftp_dest_path', 'FTP destination path (must exists)', 'ftp_dest_path_callback');
    }
    
    private function add_form_field($id, $label, $callback_in_class) {
        add_settings_field(
            $id, 
            $label, 
            array( $this, $callback_in_class ), 
            $this->page_id, 
            $this->settings_section
        );
    }
    
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize_on_submit( $input )
    {
        $new_input = array();
        foreach ($input as $key => $value) {
            switch ($key) {
                case HtpasswdFtpOptions::$ftp_enabled:
                    $new_input[$key] = absint($value);
                    break;
                case HtpasswdFtpOptions::$ftp_dest_path:
                    $new_input[$key] = $value != '' ? sanitize_text_field($value) : '/';
                    break;
                case HtpasswdFtpOptions::$ftp_port:
                    $new_input[$key] = $value != '' ? absint($value) : '21';
                    break;
                default:
                    $new_input[$key] = sanitize_text_field($value);
                    break;
            }
        }
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print '<p>Here you can enable FTP upload feature to send <tt>.htpasswd_generated</tt> file to another FTP server after each user update</p>';
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function enable_ftp_callback()
    {
        $val = $this->options->isEnabled();
        printf(
            '<input type="checkbox" id="ftp_enabled" name="cnj_htpasswd_ftp_options[ftp_enabled]" %s value="1"/>',
            isset( $val ) ? 'checked' : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_username_callback()
    {
        $val = $this->options->getUsername();
        printf(
            '<input type="text" autocomplete="off" id="ftp_username" name="cnj_htpasswd_ftp_options[ftp_username]" value="%s" />',
            isset( $val ) ? esc_attr($val) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_password_callback()
    {
        $val = $this->options->getPassword();
        printf(
            '<input type="password" autocomplete="off" id="ftp_password" name="cnj_htpasswd_ftp_options[ftp_password]" value="%s" />',
            isset($val) ? esc_attr($val) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_server_callback()
    {
        $val = $this->options->getServer();
        printf(
            '<input type="text" id="ftp_server" name="cnj_htpasswd_ftp_options[ftp_server]" value="%s" />',
            isset($val) ? esc_attr($val) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_port_callback()
    {
        $val = $this->options->getPort();
        printf(
            '<input type="text" id="ftp_port" name="cnj_htpasswd_ftp_options[ftp_port]" value="%s" />',
            isset($val) ? esc_attr($val) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_dest_path_callback()
    {
        $val = $this->options->getDestinationPath();
        printf(
            '<input type="text" id="ftp_dest_path" name="cnj_htpasswd_ftp_options[ftp_dest_path]" value="%s" />',
            isset($val) ? esc_attr($val) : ''
        );
    }
}

if( is_admin() ) {
    $htpasswd_settings_page = new HtpasswdSettingsPage();
}