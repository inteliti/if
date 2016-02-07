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
		//Ejecutar Garbage Collector (NO EJECUTAR EN CONSTRUCTOR)
		$this->garbage_collector();

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

		$this->load->view('demos/fotos/upload_detail', $D);
	}

	public function save()
	{
		$D = (object) $this->input->post();
		
		d($_FILES); exit;

		/*
		 * Se llama antes de guardar un objeto que tenga archivos 
		 * que han sido subidos a la aplicacion. 
		 * 
		 * Esta funcion permite guardar la URL donde se guardaron los archivos.
		 * 
		 * Es necesario para poder usar el plugin en objetos que aun no tienen un id
		 */
		$upload_url = getUploadURL($D);
		/*
		 * Se llama para guardar el objeto
		 */
		$upload = $this->upload_m->store($D);

		/*
		 * Se llama despues de guardar objeto que tenga archivos
		 * que han sido subidos a la aplicacion.
		 * 
		 * Esta funcion permite actualizar la URL donde se guardaron los archivos
		 * 
		 * Es necesario para poder usar el plugin en objetos que aun no tienen un id
		 */
		updateUploadURL($upload, $upload_url, $this->main_upload_url);

		$this->detail(NULL, $upload);
	}

	//-----------------------------------------
	//GARBAGE COLLECTOR
	//-----------------------------------------
	private function garbage_collector()
	{
		$dirs = scandir($this->main_upload_path);
		foreach($dirs as $v)
		{
			//Borrar directorios basura con +30min de creados.
			//Si se borran inmediatamente podrian borrarse directorios
			//que se estan creando concurrentemente por varios usuarios
			//subiendo fotos a la vez
			$file_created = filectime($this->main_upload_path . $v);
			$now = time();

			if(
				($now - $file_created > 1800) &&
				strpos($v, "nuevo-") === 0 && $v != '.' && $v != '..'
			)
			{
				$this->garbage_collector_delete_dir(
					$this->main_upload_path . $v
				);
			}
		}
	}

	private function garbage_collector_delete_dir($dir)
	{
		$objects = scandir($dir);
		foreach($objects as $object)
		{
			if($object != "." && $object != "..")
			{
				if(filetype($dir . "/" . $object) == "dir")
					rrmdir($dir . "/" . $object);
				else
					unlink($dir . "/" . $object);
			}
		}
		reset($objects);
		rmdir($dir);
	}

}

function updateUploadURL($upload,$upload_url=NULL,$main_upload_url=NULL)
{
	if($upload->success)
	{
		if(!empty($upload_url))
		{
			//obtiene la posicion donde empieza el directorio especifico del objeto
			// de archivos que se esta guardando. Luego se saca la carpeta del objeto y se eliminan los /
			$upload_object_folder = trim( substr ( $upload_url , strlen ( $main_upload_url ) ), "/" );
			if( $upload_object_folder != $upload->id && $upload->id > 0)
			{
				$upload->upload_url_error = moveDirectory($upload_url,$main_upload_url . $upload->id);
			}
		}
	}
}

function getUploadURL($D)
{
	$upload_url = NULL;
	
	if(isset($D->upload_url))
	{
		$upload_url = $D->upload_url;
	}
	
	return $upload_url;
}