<?php

$r = new stdClass();
$r->success = TRUE;

if(isset($_POST))
{
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		$r->success = FALSE;
		$r->error = 'NO AJAX';
		die(json_encode($r));
	}
	
	$ImagePath 	=	isset($_POST['ImagePath']) ?	rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 
													rtrim($_POST['ImagePath'], '/')
													: NULL;
	$ThumbPath 	=	isset($_POST['ThumbPath']) ?	rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 
													rtrim($_POST['ThumbPath'], '/')
													: NULL;
	
	if (empty($ImagePath) && empty($ThumbPath)){
		$r->success = FALSE;
		$r->error = 'NO IMAGE';
		die(json_encode($r));
	}
	
	if(unlink($ImagePath) && unlink($ThumbPath))
	{
		echo json_encode($r);
	}
	else
	{
		$r->success = FALSE;
		$r->error = 'NO SE PUDO BORRAR';
		die(json_encode($r));
	}
	
	
}