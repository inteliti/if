<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include APPPATH . 'core/IF_Controller.php';

/**
 * Es recomendable heredar de esta clase si se necesitan
 * varias instancias para subir a dos rutas distintas (ej: fotos de usuarios
 * y archivos PDF)
 */
class IF_Upload extends IF_Controller
{
	public function __construct($upload_dir='uploads/')
	{
		parent::__construct();

		$this->upload_path_client = ASSETS_URL . $upload_dir;
		$this->upload_path_server = ASSETS_PATH . $upload_dir;
	}

	public function detail($id = -1, $nombre_objeto = 'IF_UPLOADER')
	{
		if(!extension_loaded('fileinfo'))
		{
			die('Se requiere la extensión PHP fileinfo.');
		}
		
		$D = new stdClass();
		$D->FILES_URL = $this->upload_path_client.$id.'/';
		$D->NOMBRE_OBJETO = $nombre_objeto;
		$D->ID = $id;
		$D->FILES = array();
		
		//busca archivos ya subidos para este id
		$dir = $this->upload_path_server.$id.'/';
		if(is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			$D->FILES = $files;
		}

		$this->load->view('demos/fotos/upload_detail', $D);
	}

	//Retorna codigos de error numerico. 0 = no error.
	public function ajax_save()
	{
		$D = (object)$_POST;
		
		//carpeta especifica
		$upload_dir = $this->upload_path_server . $D->id . '/';
		
		if(file_exists($upload_dir) || mkdir($upload_dir))
		{
			//Subir archivos
			foreach($_FILES as $i=> $v)
			{
				$ext = pathinfo($v['name'], PATHINFO_EXTENSION);
				$dbCol = 'file' . ($i + 1);
				$D->$dbCol = $file_name = md5(mt_rand()).".{$ext}";
				move_uploaded_file($v["tmp_name"], $upload_dir . $file_name);
			}
			
			//Remover archivos remotos
			$elim = explode(',',$D->remove_remote_files);
			foreach($elim as $v)
			{
				if(empty($v))
				{
					continue;
				}
				@unlink($upload_dir.$v);
			}
		}
		else
		{
			die('1'); //Cod Error 1: No se pudo crear el directorio
		}
		
		echo 0; //No error code
	}

}
