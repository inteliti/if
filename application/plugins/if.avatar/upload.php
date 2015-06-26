<?php

$FILE = & $_FILES['file'];

//get EXT
$a = explode('.', $FILE['name']);
$ext = array_pop($a);

$uplPath = $_POST['upload_path'];
$absPath = rtrim($_SERVER['DOCUMENT_ROOT'],'/') . $uplPath;

$filename = empty($_POST['file_name']) ?
		basename($FILE['name']) : $_POST['file_name'] . '.' . $ext
;

$file = $absPath . $filename;

if(@move_uploaded_file($FILE['tmp_name'], $file))
{
	echo $uplPath.$filename;
}
else
{
	echo 0;
}