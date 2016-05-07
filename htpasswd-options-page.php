<?php
class HtpasswdSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

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
            'htpasswd-generator-options', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'cnj_htpasswd_ftp_options' );
        ?>
        <div class="wrap">
            <h2>Htpasswd Generator Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'htpasswd_ftp_option_group' );   
                do_settings_sections( 'htpasswd-generator-options' );
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
            'htpasswd_ftp_option_group', // Option group
            'cnj_htpasswd_ftp_options', // Option name
            array( $this, 'sanitize_on_submit' ) // Sanitize
        );

        add_settings_section(
            'htpasswd_setting_section_ftp',
            'FTP Settings',
            array( $this, 'print_section_info' ), // Callback
            'htpasswd-generator-options' // Page
        );
        
        add_settings_field(
            'enable-ftp', 
            'Enable FTP .htaccess upload', 
            array( $this, 'enable_ftp_callback' ), // Callback
            'htpasswd-generator-options', // Page
            'htpasswd_setting_section_ftp' // Section
        );

        add_settings_field(
            'ftp_username',
            'Username', 
            array( $this, 'ftp_username_callback' ),
            'htpasswd-generator-options',
            'htpasswd_setting_section_ftp'           
        );      

        add_settings_field(
            'ftp_password', 
            'Password', 
            array( $this, 'ftp_password_callback' ), 
            'htpasswd-generator-options', 
            'htpasswd_setting_section_ftp'
        ); 
        
        add_settings_field(
            'ftp_server', 
            'FTP ip/domain', 
            array( $this, 'ftp_server_callback' ), 
            'htpasswd-generator-options', 
            'htpasswd_setting_section_ftp'
        );
        
         add_settings_field(
            'ftp_port', 
            'FTP port (default 21)', 
            array( $this, 'ftp_port_callback' ), 
            'htpasswd-generator-options', 
            'htpasswd_setting_section_ftp'
        ); 
        
        add_settings_field(
            'ftp_dest_path', 
            'FTP destination path (must exists)', 
            array( $this, 'ftp_dest_path_callback' ), 
            'htpasswd-generator-options', 
            'htpasswd_setting_section_ftp'
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
            if ($key == 'ftp_enabled' || $key == 'ftp_port') {
                $new_input[$key] = absint($value);
            } else {
                $new_input[$key] = sanitize_text_field($value);
            }
        }
        return $new_input;
        
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print '<p>Here you can enable FTP upload feature to send .htpasswd file another FTP server after each update</p>';
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function enable_ftp_callback()
    {
        printf(
            '<input type="checkbox" id="ftp_enabled" name="cnj_htpasswd_ftp_options[ftp_enabled]" %s value="1"/>',
            isset( $this->options['ftp_enabled'] ) ? 'checked' : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_username_callback()
    {
        printf(
            '<input type="text" autocomplete="off" id="ftp_username" name="cnj_htpasswd_ftp_options[ftp_username]" value="%s" />',
            isset( $this->options['ftp_username'] ) ? esc_attr( $this->options['ftp_username']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_password_callback()
    {
        printf(
            '<input type="password" autocomplete="off" id="ftp_password" name="cnj_htpasswd_ftp_options[ftp_password]" value="%s" />',
            isset( $this->options['ftp_password'] ) ? esc_attr( $this->options['ftp_password']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_server_callback()
    {
        printf(
            '<input type="text" id="ftp_server" name="cnj_htpasswd_ftp_options[ftp_server]" value="%s" />',
            isset( $this->options['ftp_server'] ) ? esc_attr( $this->options['ftp_server']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_port_callback()
    {
        printf(
            '<input type="text" id="ftp_port" name="cnj_htpasswd_ftp_options[ftp_port]" value="%s" />',
            isset( $this->options['ftp_port'] ) ? esc_attr( $this->options['ftp_port']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ftp_dest_path_callback()
    {
        printf(
            '<input type="text" id="ftp_dest_path" name="cnj_htpasswd_ftp_options[ftp_dest_path]" value="%s" />',
            isset( $this->options['ftp_dest_path'] ) ? esc_attr( $this->options['ftp_dest_path']) : ''
        );
    }
}

if( is_admin() ) {
    $htpasswd_settings_page = new HtpasswdSettingsPage();
}