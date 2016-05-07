<?php
include 'htpasswd-options-generic.php';

class GenericSettingsPage
{
    private $page_id;
    
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    
    /**
    * Page section
    */
    private $settings_section = 'htpasswd_setting_generic_section';
    
    /**
    * Option group
    */
    private $option_group;

    /**
     * Start up
     */
    public function __construct($page_id, $option_group)
    {
        add_action( 'admin_init', array( $this, 'page_init' ) );
        $this->options = HtpasswdGenericOptions::load();
        $this->page_id = $page_id;
        $this->option_group = $option_group;
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            $this->option_group, // Option group
            HtpasswdGenericOptions::$name, // Option name
            array( $this, 'sanitize_on_submit' ) // Sanitize
        );

        add_settings_section(
            $this->settings_section,
            'Generic Settings',
            array( $this, 'print_section_info' ), // Callback
            $this->page_id // Page
        );
        
        $this->add_form_field('resource_paths', 'Resource/s path to protect (MUST exist)', 'resource_paths_callback');
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
            $new_input[$key] = sanitize_text_field($value);
        }
        $this->copy_htaccess($new_input);
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print '<p>You can specify <strong>resources path you want to protect</strong> on your server, coma separated folders such as \'<tt>/folder1, /folder2</tt>\', starting from <em>web server root path</em></p>' .
        '<p>If you want to remove security on a folder, you have to remove <tt>.htaccess</tt> file manually</p>';
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function resource_paths_callback()
    {
        $val = $this->options->getPaths();
        printf(
            '<input type="text" autocomplete="off" id="resource_paths" name="cnj_htpasswd_generic_options[resource_paths]" value="%s" />',
            isset( $val ) ? esc_attr($val) : ''
        );
    }
    
    private function copy_htaccess($input) {
        HtaccessUtils::generateHtaccess(HtaccessUtils::getHtpasswdPath());
        $options = HtpasswdGenericOptions::from($input);
        if ($options->hasPaths()) {
            foreach ($options->getPathsAsArray() as $destination) {
                HtaccessUtils::copyHtaccessTo($destination);
            }
        }
    }
}