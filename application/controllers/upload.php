<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include APPPATH . 'core/IF_Controller.php';

class Upload extends IF_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_model', 'upload_m');

		//MODIFICAR AL DIRECTORIO A SUBIR, incluir / al final
		$upload_dir = 'uploads/';

		//NO modificar estos
		$this->main_upload_url = ASSETS_URL . $upload_dir;
		$this->main_upload_path = ASSETS_PATH . $upload_dir;

		//$this->output->enable_profiler(TRUE);
	}

	public function index($id = -1)
	{
		$D = new stdClass();
		$D->id = $id;
		$this->tmpl('demos/fotos/index', $D);
	}

	public function detail($id = -1, $nombre_objeto = 'UPLOADER')
	{
		//obtener datos del objeto
		$D = $id <= 0 ? $this->upload_m->vacio() :
			$this->upload_m->get($id);

		//si existe algun agregado para el objeto hay que anexarlo
		if(!empty($ADDON))
		{

			$D = (object) array_merge((array) $D, (array) $ADDON);
		}

		/*
		 * Se envia URL donde se subiran los archivos
		 */
		$D->main_upload_url = $this->main_upload_url;
		$D->NOMBRE_OBJETO = $nombre_objeto;
		$D->ID = $id;

		$this->load->view('demos/fotos/upload_detail', $D);
	}

	//$id contiene el ID del elemento
	public function ajax_save()
	{
		$E = (object)$_POST;
		$D = new stdClass();
		$D->id = $E->id;
		
		//carpeta especifica
		$upload_dir = $this->main_upload_path . $D->id . '/';
		if(mkdir($upload_dir))
		{
			//Subir archivos
			foreach($_FILES as $i=> $v)
			{
				$ext = pathinfo($v['name'], PATHINFO_EXTENSION);
				$dbCol = 'file' . ($i + 1);
				$D->$dbCol = $file_name = md5(mt_rand()).".{$ext}";
				move_uploaded_file($v["tmp_name"], $upload_dir . $file_name);
			}
			//$this->upload_m->store($D);
		}
		else
		{
			die('1'); //Cod Error 1: No se pudo crear el directorio
		}
		
		echo 0; //No error code
	}

}
