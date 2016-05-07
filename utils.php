<?php

class HtaccessUtils {
    
    private static $htpasswd_generated = '.htpasswd_generated';
    
    private static $htaccess_seed = 'rename_me_to_.htaccess';
    
    public static function getHtpasswdPath() {
        return plugin_dir_path(__FILE__) . self::$htpasswd_generated;
    }
    
    public static function getHtaccessPath() {
        return plugin_dir_path(__FILE__) . self::$htaccess_seed;
    }
    
    public static function generateHtaccess($htpasswd) {
        $htaccess_path = self::getHtaccessPath();
        if (!file_exists($htaccess_path)) {
            $content = "# enable basic authentication\r\n";
            $content .= "AuthType Basic\r\n";
            $content .= "# this text is displayed in the login dialog\r\n";
            $content .= "AuthName \"Protected Resources\"\r\n";
            $content .= "# The absolute path of the Apache htpasswd file\r\n";
            $content .= "AuthUserFile " . $htpasswd . "\r\n";
            $content .= "#Allows any user in the .htpasswd_generated file to access the directory\r\n";
            $content .= "require valid-user";
            
            file_put_contents($htaccess_path, $content) or die("Unable to open file " . $htaccess_path);
        }
    }
    
    public static function copyHtaccessTo($dest) {
        $to_file = self::getSiteRootPath() . $dest . '/.htaccess';
        if (!file_exists($to_file)) {
            copy(self::getHtaccessPath(), $to_file);
        }
    }

    /**
    * Override of 'get_home_path' since is not available when user is not logged in
    */
    private static function getSiteRootPath() {
        if (function_exists( 'get_home_path' )) {
            return get_home_path();
        } else {
            $home    = set_url_scheme( get_option( 'home' ), 'http' );
            $siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
            if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
                $wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
                $pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
                $home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
                $home_path = trailingslashit( $home_path );
            } else {
                $home_path = ABSPATH;
            }

            return str_replace( '\\', '/', $home_path );
        }
    }
}

?>