<?php
/*
 * Mueve los archivos hacia la izquierda si se borra un archivo
 * 
 * Array
(
    [img2] => 
    [img3] => img3-36878236.jpeg
    [img4] => 
    [img5] => 
    [img6] => 
)
 * 
rtrim($_SERVER['DOCUMENT_ROOT'], '/') . rtrim($_POST['ImagePath'], '/')
 * 
 * 
$animals = array('cat', 'dog', 'horse', 'elephant');
for ($animal = current($animals), $index = key($animals); 
       $animal; 
       $animal = next($animals), $index = key($animals)) {
  print "$index:";
  var_dump($animal);
  next($animals);
} 
 * 
$animals = array('cat', 'dog', 'horse', 'elephant');
for (reset($animals); key($animals) !== null; next($animals)) {
    $animal = current($animals);
    var_dump($animal);
    next($animals);
}
 * 
 */
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
	
	$UploadPath 	=	isset($_POST['UploadPath']) ?	rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 
														rtrim($_POST['UploadPath'], '/')
														: NULL;
	
	$ImagesToShift 	=	isset($_POST['ImagesToShift']) ?	$_POST['ImagesToShift']
													: array();
	
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	
	if (empty($ImagesToShift) || empty($UploadPath)){
		$r->success = FALSE;
		$r->error = 'NO COMPLETE PARAMS';
		die(json_encode($r));
	}
	
	//print_r($ImagesToShift);
	
	$ImagesShifted = array();
	
	$prev_k = null;
	$prev_v = null;
	
	$count = count($ImagesToShift);
	$i = 1;
	
	foreach($ImagesToShift as $k => $v)
	{
		if ($prev_v !== null){
			if(!empty($v))
			{
				$ExplodeName = explode('-', $v);
				$TrailName = $ExplodeName[1];
				$NewImageName = $prev_k . '-' . $TrailName;
				$NewThumbName = $ThumbPrefix . $prev_k . '-' . $TrailName;

				$r_image = rename(
					$UploadPath . '/' . $v,
					$UploadPath . '/' . $NewImageName);
				
				if($r_image)
				{
					$r_thumb = rename(
						$UploadPath . '/' . $ThumbPrefix . $v,
						$UploadPath . '/' . $NewThumbName);
					
					if($r_thumb)
					{
						$ImagesShifted[$prev_k] = $NewImageName;
					}
				}
			}
			else
			{
				$ImagesShifted[$prev_k] = "";
				if($i==$count)
				{
					$ImagesShifted[$k] = "";
				}
			}
		}
		$prev_k = $k;
		$prev_v = $v;
		$i++;
	}
	
	$r->ImagesShifted = (object) $ImagesShifted;
	
	echo json_encode($r);
	
	
}
