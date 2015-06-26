<?php 

include PLUGINS_PATH . "if.avatar/_loader.php"; 

//pass parameters
$FILE_NAME = 'avatar';
$UPLOAD_PATH = ASSETS_URL;
$CALLBACK = 'testing';

$UPLOAD_FILE_SIZE_MAX = 100; //KB
$UPLOAD_FILE_TYPES = array('image/jpeg', 'image/png');

include PLUGINS_PATH . "if.avatar/compos.php";

?>
