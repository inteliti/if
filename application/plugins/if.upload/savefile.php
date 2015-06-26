<?php

$r = new stdClass();
$r->success = TRUE;

if(isset($_POST))
{
	############ Edit settings ##############
	$ThumbSquareSize 		= 220; //Thumbnail will be 200x200
	$BigImageMaxSize 		= 1900; //Image Maximum height or width
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	$DestinationDirectory	= $uploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 
								rtrim($_POST['upload_path'], '/') . '/'; //specify upload directory ends with / (slash)
	$Quality 				= 90; //jpeg quality
	##########################################
	
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		$r->success = FALSE;
		$r->error = 'NO AJAX';
		die(json_encode($r));
	}
	
	// check $_FILES['ImageFile'] not empty
	if(!isset($_FILES['contents']) || !is_uploaded_file($_FILES['contents']['tmp_name']))
	{
		$r->success = FALSE;
		$r->error = 'NO FILE';
		die(json_encode($r)); // output error when above checks fail.
	}
	
	// Random number will be added after image name
	$RandomNumber 	= rand(0, 9999999999); 

	$FileName 		=	str_replace(' ','-',strtolower($_POST['name'])) . '.' .
						end(explode('/',$_FILES['contents']['type'])); //get image name
	//$ImageSize 	= $_FILES['contents']['size']; // get original image size
	$TempSrc	 	= $_FILES['contents']['tmp_name']; // Temp name of image file stored in PHP tmp folder
	$FileType	 	= $_FILES['contents']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.

	//Let's check allowed $ImageType, we use PHP SWITCH statement here
	switch(strtolower($FileType))
	{
		case 'image/png':
			//Create a new image from file 
			$CreatedImage =  imagecreatefrompng($_FILES['contents']['tmp_name']);
			break;
		case 'image/gif':
			$CreatedImage =  imagecreatefromgif($_FILES['contents']['tmp_name']);
			break;			
		case 'image/jpeg':
		case 'image/pjpeg':
			$CreatedImage = imagecreatefromjpeg($_FILES['contents']['tmp_name']);
			break;
		case 'application/pdf':
			//$CreatedImage = imagecreatefromjpeg($_FILES['contents']['tmp_name']);
			break;
		default:
			die('Unsupported File!'); //output error and exit
	}
	
	//Get file extension from Image name, this will be added after random name
	$FileExt = substr($FileName, strrpos($FileName, '.'));
	$FileExt = str_replace('.','',$FileExt);

	//remove extension from filename
	$FileName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $FileName); 

	//Construct a new name with random number and extension.
	$NewFileName = $FileName.'-'.$RandomNumber.'.'.$FileExt;
	//$NewImageName = $ImageName.'.'.$ImageExt;
	
	if (!file_exists($DestinationDirectory))
	{
		mkdir($DestinationDirectory, 0777, true);
	}

	if(strtolower($FileType)!='application/pdf')
	{
		//PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
		//Get first two values from image, width and height. 
		//list assign svalues to $CurWidth,$CurHeight
		list($CurWidth,$CurHeight)=getimagesize($TempSrc);
		
		/*
		$existing_images = glob($DestinationDirectory . $ImageName . '*');
		$existing_thumbs = glob($DestinationDirectory . $ThumbPrefix .$ImageName . '*');

		if (count($existing_images)>0)
		{
			unlink($existing_images[0]);
		}

		if (count($existing_thumbs)>0)
		{
			unlink($existing_thumbs[0]);
		}*/
		
		//set the Destination Image
		$thumb_DestRandImageName 	= $DestinationDirectory.$ThumbPrefix.$NewFileName; //Thumbnail name with destination directory
		$DestRandImageName 			= $DestinationDirectory.$NewFileName; // Image with destination directory
		//
		//Resize image to Specified Size by calling resizeImage function.
		if(resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$FileType))
		{
			//Create a square Thumbnail right after, this time we are using cropImage() function
			if(!cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$FileType))
			{
				$r->error = 'Error Creating thumbnail';
				//echo 'Error Creating thumbnail';
			}

			$r->name = $_POST['name'];
			$r->filename = $NewFileName;
			
			echo json_encode($r);

		}
		else
		{
			$r->success = FALSE;
			$r->error = 'Resize Error';
			die(json_encode($r));
			//die('Resize Error'); //output error
		}
	}
	else
	{
		$DestRandFilePDFName 	= $DestinationDirectory.$NewFileName; // Image with destination directory
		if(move_uploaded_file($TempSrc,$DestRandFilePDFName))
		{
			$r->name = $_POST['name'];
			$r->filename = $NewFileName;
			
			echo json_encode($r);

		}
		else
		{
			$r->success = FALSE;
			die(json_encode($r));
		}
	}

}
else
{
	$r->success = FALSE;
	$r->error = 'NO POST';
	die(json_encode($r)); // output error when above checks fail.
}


// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//Construct a proportional size of new image
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	
	// Resize Image
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{
		switch(strtolower($ImageType))
		{
			case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
			case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
			default:
				return false;
		}
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}

//This function corps image to create exact square images, no matter what its original size!
function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{	 
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
	if($CurWidth>$CurHeight)
	{
		$y_offset = 0;
		$x_offset = ($CurWidth - $CurHeight) / 2;
		$square_size 	= $CurWidth - ($x_offset * 2);
	}else{
		$x_offset = 0;
		$y_offset = ($CurHeight - $CurWidth) / 2;
		$square_size = $CurHeight - ($y_offset * 2);
	}
	
	$NewCanves 	= imagecreatetruecolor($iSize, $iSize);	
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
	{
		switch(strtolower($ImageType))
		{
			case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
			case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
			default:
				return false;
		}
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;

	}
	  
}

