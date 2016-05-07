<?php
class HtpasswdFtpOptions
{
    public static $name = 'cnj_htpasswd_ftp_options';
    
    public static $ftp_enabled = 'ftp_enabled';
    
    public static $ftp_username = 'ftp_username';
    
    public static $ftp_password = 'ftp_password';
    
    public static $ftp_server = 'ftp_server';
    
    public static $ftp_port = 'ftp_port';
    
    public static $ftp_dest_path = 'ftp_dest_path';
    
    private $options;
    
    private function __construct($options) {
        $this->options = $options;
    }
    
    public static function load() {
        return new HtpasswdFtpOptions(get_option(HtpasswdFtpOptions::$name));
    }
    
    public function isEnabled() {
        return $this->options[HtpasswdFtpOptions::$ftp_enabled];
    }
    
    public function getUsername() {
        return $this->options[HtpasswdFtpOptions::$ftp_username];
    }
    
    public function getPassword() {
        return $this->options[HtpasswdFtpOptions::$ftp_password];
    }
    
    public function getServer() {
        return $this->options[HtpasswdFtpOptions::$ftp_server];
    }
    
    public function getPort() {
        return $this->options[HtpasswdFtpOptions::$ftp_port];
    }
    
    public function getDestinationPath() {
        return $this->options[HtpasswdFtpOptions::$ftp_dest_path];
    }
}