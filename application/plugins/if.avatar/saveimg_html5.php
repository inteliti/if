<?php

$filename = empty($_POST['filename']) ? date('Y-m-d-H-i-s') : $_POST['filename'];
$contents = $_POST['contents'];
$uploadPath = //rtrim($_SERVER['DOCUMENT_ROOT'], '/') .
	rtrim($_POST['upload_path'], '/') . '/'
;

$encodedData = str_replace(' ', '+', $contents);
$decodedData = base64_decode($encodedData);
$fp = fopen($uploadPath . $filename . '.jpg', 'w');
fwrite($fp, $decodedData);
fclose($fp);

//150 x 150
$img = imagecreatefromjpeg($uploadPath . $filename . '.jpg');
$img2 = ImageCreateTrueColor(150, 150);

imagecopyresampled(
	$img2, $img, 0, 0, 25, 0, 150, 150, 150, 150
);
imagejpeg($img2, $uploadPath . $filename . '.jpg', 100);
