<?php

$FILE = & $_FILES['file'];

//get EXT
$a = explode('.', $FILE['name']);
$ext = array_pop($a);

$uplPath = $_POST['upload_path'];
//$absPath = rtrim($_SERVER['DOCUMENT_ROOT'],'/') . $uplPath;

$filename = empty($_POST['file_name']) ?
	basename($FILE['name']) : $_POST['file_name'] . '.' . $ext
;
$file = $uplPath . $filename;


//Cambiar tamano a 150x150
$max_width = 150;
$max_height = 150;

$fn = $FILE['tmp_name'];
$size = getimagesize($fn);
$width = $size[0];
$height = $size[1];

$width_new = $height * $max_width / $max_height;
$height_new = $width * $max_height / $max_width;

$src = imagecreatefromstring(file_get_contents($fn));
$dst = imagecreatetruecolor($max_width, $max_height);

//cut point by height
if($width_new > $width)
{
	$h_point = (($height - $height_new) / 2);
	//copy image
	imagecopyresampled($dst, $src, 0, 0, 0, $h_point, $max_width,
		$max_height, $width, $height_new);
}
//cut point by width
else
{
	$w_point = (($width - $width_new) / 2);
	imagecopyresampled($dst, $src, 0, 0, $w_point, 0, $max_width,
		$max_height, $width_new, $height);
}

imagedestroy($src);
imagejpeg($dst, $file); // adjust format as needed
imagedestroy($dst);

//RETORNAR PATH DE ARCHIVO PARA CLIENTE
$avatarFolder = $_POST['avatar_folder'];
echo $avatarFolder.$filename;
