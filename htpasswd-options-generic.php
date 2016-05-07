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
    
    public static function from($option_map) {
        return new HtpasswdGenericOptions($option_map);
    }
    
    public function getPaths() {
        return $this->options[HtpasswdGenericOptions::$resource_paths];
    }
    
    public function hasPaths() {
        $val = $this->options[HtpasswdGenericOptions::$resource_paths];
        return !empty($val);
    }
    
    public function getPathsAsArray() {
        $val = $this->options[HtpasswdGenericOptions::$resource_paths];
        if (!empty($val)) {
            return $this->sanitize(explode(",", $val));
        } else {
            return array();
        }
    }
    
    private function sanitize($from_array) {
        $to_array = array();
        foreach ($from_array as $v) {
            $val = trim($v);
            $to_array[] = substr($val, 0, 1) === '/' ? substr($val, 1, strlen($val)) : $val;
        }
        return $to_array;
    }
}