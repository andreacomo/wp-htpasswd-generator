<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class FtpClient {
    
    private $conn_id;
    
    public static function connect($serverName, $port = 21) {
        $ftp = new FtpClient();
        $ftp->conn_id = ftp_connect($serverName, $port);
        if (!$ftp->conn_id) {
            throw new Exception('Cannot connect to ' . $serverName . ' on port '. $port);
        }
        return $ftp;
    }
    
    public function withCredentials($username, $password) {
        $this->login_result = ftp_login($this->conn_id, $username, $password);
        if (!$this->login_result) {
            throw new Exception('Cannot login as ' . $username);
        }
        return $this;
    }
    
    public function upload($fromFile, $toFile) {
        $upload = ftp_put($this->conn_id, $toFile, $fromFile, FTP_BINARY);
        if (!$upload) {
            throw new Exception('Ftp upload error!');
        }
        return $this;
    }
    
    public function close() {
        ftp_close($this->conn_id);
    } 
}
?>