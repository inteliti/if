<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once APPPATH . 'core/IF_Controller.php';

/**
 * HEREDAR de esta clase, no sobreescribirla directamente.
 */
class IF_Avatar extends IF_Controller
{
	
	public static $ERROR = 0;
	public static $SUCCESS = 1;
	
	public function __construct($upload_dir = 'avatar/', $config = NULL)
	{
		parent::__construct();

		//Asegurarse que $upload_dir termina en /
		$upload_dir = trim($upload_dir, '/');

		//Default Config
		$this->CONFIG = (object) (empty($config) ? array() : $config);
		if(empty($this->CONFIG->FILE_SIZE_MAX))
		{
			$this->CONFIG->FILE_SIZE_MAX = 10000000;
		}

		//Formatos permitidos
		if(empty($this->CONFIG->FILE_TYPE))
		{
			$this->CONFIG->FILE_TYPE = array(
				'image/jpeg', 'image/png'
			);
		}

		//Si se establece, fuerza el tamaño del avatar a este tamaño
		if(empty($this->CONFIG->RESIZE))
		{
			$this->CONFIG->RESIZE = array(200, 200);
		}

		$this->upload_path_server = str_replace(
			'\\', '/', IF_PATH_ASSETS_SERVER . $upload_dir . '/'
		);
		$this->upload_path_client = str_replace(
			'\\', '/', IF_PATH_ASSETS_CLIENT . $upload_dir . '/'
		);
	}

	public function load_avatar($id = -1, $isMobile = false)
	{
		$D = new stdClass();
		$D->UPLOAD_PATH = $this->upload_path_server;
		$D->AVATAR_FOLDER = $this->upload_path_client;
		$D->UPLOAD_FILE_TYPES = $this->CONFIG->FILE_TYPE;
		$D->UPLOAD_FILE_SIZE_MAX = $this->CONFIG->FILE_SIZE_MAX / 1000;
		$D->FILE_NAME = "p" . $id;
		$D->PLG_URL = IF_PATH_PLUGINS_CLIENT . 'if.avatar/';
		$D->PLG_PATH = IF_PATH_PLUGINS_SERVER . 'if.avatar/';

		$vista = $isMobile ? 'compos_mobile' : 'compos_desktop';

		$this->load->view("../plugins/if.avatar/{$vista}.php", $D);
	}

	public function crop_load()
	{
		$this->load->view("../plugins/if.avatar/compos_crop.html");
	}
	
	public function file_upload()
	{
		$D = (object) $_POST;
		$this->blobToImg($D->id, $D->img_data);
	}

	public function crop_upload()
	{
		$D = (object) $_POST;
		$this->blobToImg($D->id, $D->img_data);
	}

	public function cam_upload()
	{
		$D = (object) $_POST;
		$this->blobToImg($D->id, $D->img_data);
	}

	private function blobToImg($id, $blob)
	{
		//Trabajamos el blob
		list($type, $data) = explode(';', $blob);
		list(, $data) = explode(',', $data);
		$data = base64_decode($data);
		
		$filename = "p{$id}.jpg";
		$pathServer = $this->upload_path_server . $filename;
		$pathClient = $this->upload_path_client . $filename;

		file_put_contents($pathServer, $data);

		//Forzar resize
		list($ancho, $alto) = getimagesize($pathServer);
		$nuevo_ancho = $this->CONFIG->RESIZE[0];
		$nuevo_alto = $this->CONFIG->RESIZE[1];

		$newImg = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
		
		if (exif_imagetype($pathServer) ===IMAGETYPE_PNG )
		{
			$imagen = imagecreatefrompng($pathServer);
		}
		else
		{
			$imagen = imagecreatefromjpeg($pathServer);
		}
		
		imagecopyresampled(
			$newImg, $imagen, 0, 0, 0, 0
			, $nuevo_ancho, $nuevo_alto, $ancho, $alto
		);
		
		imagejpeg($newImg, $pathServer);

		echo $pathClient;
	}

	public function delete_avatar($id)
	{
		$filename = 'p' . $id;
		@unlink($this->upload_path_server . $filename . '.jpg');
		echo IF_Avatar::$SUCCESS;
	}

}
