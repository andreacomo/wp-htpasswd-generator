<?php
include 'htpasswd-options-ftp-page.php';
include 'htpasswd-options-generic-config-page.php';

class HtpasswdSettingsPage
{
    private $page_id = 'htpasswd-generator-options';
    
    /**
    * Option group
    */
    private $option_group = 'htpasswd_option_group';
    
    private $ftp_page_section;
    
    private $generic_page_section;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->generic_page_section = new GenericSettingsPage($this->page_id, $this->option_group);
        $this->ftp_page_section = new FtpSettingsPage($this->page_id, $this->option_group);
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
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
        ?>
        <div class="wrap">
            <h2>Htpasswd Generator Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields($this->option_group);
                do_settings_sections($this->page_id);
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
}

if( is_admin() ) {
    $htpasswd_settings_page = new HtpasswdSettingsPage();
}