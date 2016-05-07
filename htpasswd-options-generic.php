<?php
class HtpasswdGenericOptions
{
    public static $name = 'cnj_htpasswd_generic_options';
    
    public static $resource_paths = 'resource_paths';
    
    private $options;
    
    private function __construct($options) {
        $this->options = $options;
    }
    
    public static function load() {
        return new HtpasswdGenericOptions(get_option(HtpasswdGenericOptions::$name));
    }
    
    public function getPaths() {
        return $this->options[HtpasswdGenericOptions::$resource_paths];
    }
}