<?php

	$main_upload_url = isset($main_upload_url) ? $main_upload_url : "";
	
	//parametros de entrada del plugin	
	$PLG_URL = PLUGINS_URL . 'if.upload/';
	$PLG_PATH = PLUGINS_PATH . 'if.upload/';
	    
    $UPLOAD_URL = $id>0 ? $main_upload_url . $id . "/" : $main_upload_url . "nuevo-" . md5(rand()) . "/" ;
	
	$FILES_ARRAY = array(
		'file1' => $file1,
		'file2' => $file2,
		'file3' => $file3,
		'file4' => $file4,
		'file5' => $file5,
		'file6' => $file6
	);
?>

<?php 
	//cargando plugin
	include $PLG_PATH . "__upload_area.php"; 
?>