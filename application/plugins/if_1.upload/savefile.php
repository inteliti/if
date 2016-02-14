<?php
/*****************************************************
 * Archivo para el guardado de archivos en el servidor
 * para la carga de archivos IF.UPLOAD
 * v2.0.0
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/

$r = new stdClass();
$r->success = TRUE;

if(isset($_POST))
{
	############ Edit settings ##############
	//$ThumbSquareSize 		= 230; //Thumbnail will be 200x200
	$ThumbMaxSize			= 230;
	$BigImageMaxSize 		= 1024; //Image Maximum height or width
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	$DestinationDirectory	= $uploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 
								rtrim($_POST['upload_url'], '/') . '/'; //specify upload directory ends with / (slash)
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
	$_name = $_POST['name'];
	$_type = explode('/',$_FILES['contents']['type']);
	$FileName 		=	str_replace(' ','-',strtolower($_name)) . '.' .
						end($_type); //get image name
	/*$FileName 		=	str_replace(' ','-',strtolower($_POST['name'])) . '.' .
						end(explode('/',$_FILES['contents']['type'])); //get image name*/
	$TempSrc	 	= $_FILES['contents']['tmp_name']; // Temp name of image file stored in PHP tmp folder
	$FileType	 	= $_FILES['contents']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.

	//Let's check allowed $FileType, we use PHP SWITCH statement here
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
			$r->success = FALSE;
			$r->error = 'Unsupported File!';
			die(json_encode($r)); //output error and exit
	}
	
	//Get file extension from Image name, this will be added after random name
	$FileExt = substr($FileName, strrpos($FileName, '.'));
	$FileExt = str_replace('.','',$FileExt);
	
	//remove extension from filename
	$FileName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $FileName); 
	
	//Construct a new name with random number and extension.
	$NewFileName = $FileName.'-'.$RandomNumber.'.'.$FileExt;
	
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
		
		if (!file_exists($DestinationDirectory))
		{
			//echo $DestinationDirectory;
			if(!mkdir($DestinationDirectory, 0777, true))
			{
				$r->success = FALSE;
				$r->error = 'ERROR CREATING DIRECTORY';
				die(json_encode($r));
			}
		}

		//set the Destination Image
		$thumb_DestRandImageName 	= $DestinationDirectory.$ThumbPrefix.$NewFileName; //Thumbnail name with destination directory
		$DestRandImageName 			= $DestinationDirectory.$NewFileName; // Image with destination directory
		
		//Resize image to Specified Size by calling resizeImage function.
		if(resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$FileType))
		{

			//Create a square Thumbnail right after, this time we are using cropImage() function
			if(!resizeWidthImage($CurWidth,$CurHeight,$ThumbMaxSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$FileType))
			{

				$r->error = 'Error Creating thumbnail';
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
function resizeWidthImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$FileType)
{
        
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}   
	//Construct a proportional size of new image
	$ImageScale      	= $MaxSize/$CurWidth; 
        if($CurWidth <= $MaxSize)
        {
            $NewWidth  			= $CurWidth;
            $NewHeight 			= $CurHeight;
        }
        else
        {
            $NewWidth  			= ceil($ImageScale*$CurWidth);
            $NewHeight 			= ceil($ImageScale*$CurHeight);
        }
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	 
	// Resize Image
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{    
		switch(strtolower($FileType))
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
		if(is_resource($NewCanves))
		{
			imagedestroy($NewCanves);
		} 
		return true;
	}
        
}


// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$FileType)
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
		switch(strtolower($FileType))
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
function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$FileType)
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
		switch(strtolower($FileType))
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
		if(is_resource($NewCanves))
		{
			imagedestroy($NewCanves);

		}

		return true;
	}
}

