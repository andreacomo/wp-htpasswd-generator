<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include 'ftp-client.php';

function cnj_upload_via_ftp($file, $ftp) {
    FtpClient::connect($ftp->getServer(), $ftp->getPort())
        ->withCredentials($ftp->getUsername(), $ftp->getPassword())
        ->upload($file, $ftp->getDestinationPath() . basename($file))
        ->close();
}

?>