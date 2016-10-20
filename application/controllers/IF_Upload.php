<?php
/**
 * 3.1.1
 */

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once APPPATH . 'core/IF_Controller.php';

/**
 * HEREDAR de esta clase, no sobreescribirla directamente.
 */
class IF_Upload extends IF_Controller
{

	public function __construct($upload_dir = 'uploads/', $config = NULL)
	{
		parent::__construct();

		//Asegurarse que $upload_dir termina en /
		$upload_dir = trim($upload_dir, '/');

		//Default Config
		$this->CONFIG = (object) (empty($config) ? array() : $config);
		if(empty($this->CONFIG->FILE_COUNT))
		{
			//Cantidad de archivos permitidos para subir. -1 para infinitos.
			$this->CONFIG->FILE_COUNT = -1;
		}
		if(empty($this->CONFIG->FILE_SIZE_MAX))
		{
			$this->CONFIG->FILE_SIZE_MAX = 10000000;
		}
		if(empty($this->CONFIG->FILE_TYPE))
		{
			$this->CONFIG->FILE_TYPE = array(
				'application/pdf', 'image/jpeg', 'image/png'
			);
		}
		if(empty($this->CONFIG->IMAGE_SIZE_MAX))
		{
			//Formato: array(ancho,alto) en px
			$this->CONFIG->IMAGE_SIZE_MAX = NULL;
		}

		$this->upload_path_client = ASSETS_URL . $upload_dir . '/';
		$this->upload_path_server = ASSETS_PATH . $upload_dir .
			DIRECTORY_SEPARATOR
		;

		$this->garbageCollect();
	}

	public function detail_compos($id = -1, $nombre_objeto = 'IF_UPLOADER')
	{
		if(!extension_loaded('fileinfo'))
		{
			die('Se requiere la extensión PHP fileinfo.');
		}

		$D = new stdClass();
		$D->FILES_URL = $this->upload_path_client . $id . '/';
		$D->NOMBRE_OBJETO = $nombre_objeto;
		$D->ID = $id;
		$D->FILES = array();
		$D->CONFIG = $this->CONFIG;
		$D->PLG_URL = PLUGINS_URL . 'if.upload/';
		$D->CONTROLLER = INDEX_URL . get_class($this) . '/ajax_save';
		$D->NAMESPACE = 'if-upload-' . strtolower($nombre_objeto);

		//busca archivos ya subidos para este id
		$dir = $this->upload_path_server . $id . DIRECTORY_SEPARATOR;
		if(is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			unset($files[array_search('index.html', $files)]);
			$D->FILES = $files;
		}

		$this->load->view('../plugins/if.upload/detail_compos.php', $D);
	}

	//Retorna codigos de error numerico. 0 = no error.
	public function ajax_save()
	{
		$D = (object) $_POST;

		$RESPONSE = (object) array(
				'error'=>0,
				'id'=>$D->id,
		);

		//carpeta especifica
		$upload_dir = $this->upload_path_server . $D->id . DIRECTORY_SEPARATOR;

		if(file_exists($upload_dir) || mkdir($upload_dir))
		{
			//Subir archivos
			foreach($_FILES as $i=> $v)
			{
				//verificar dimensiones de imagenes
				if(
					is_array($this->CONFIG->IMAGE_SIZE_MAX) &&
					$size = getimagesize($v["tmp_name"])
				)
				{
					$ancho = $size[0];
					$alto = $size[1];
					$anchoMax = $this->CONFIG->IMAGE_SIZE_MAX[0];
					$altoMax = $this->CONFIG->IMAGE_SIZE_MAX[1];

					if($ancho > $anchoMax || $alto > $altoMax)
					{
						$RESPONSE->error = 2; //Wrong image size
						continue;
					}
				}

				$ext = pathinfo($v['name'], PATHINFO_EXTENSION);
				$dbCol = 'file' . ($i + 1);
				$D->$dbCol = $file_name = md5(mt_rand()) . ".{$ext}";
				move_uploaded_file($v["tmp_name"], $upload_dir . $file_name);
			}

			//Remover archivos remotos
			$elim = explode(',', $D->remove_remote_files);
			foreach($elim as $v)
			{
				if(empty($v))
				{
					continue;
				}
				@unlink($upload_dir . $v);
			}

			if(empty($RESPONSE->error))
			{
				$RESPONSE->error = 0; //No error code
			}
		}
		else
		{
			$RESPONSE->error = 1; //Cod Error 1: No se pudo crear el directorio
		}

		echo json_encode($RESPONSE);
	}

	//Crea un directorio temporal para alojar archivos, ideal para
	//entidades que se esten creando y aun no posean un ID.
	static function createTempFolder($upload_dir)
	{
		//Asegurarse que $upload_dir termina en /
		$upload_dir = trim($upload_dir, '/') . DIRECTORY_SEPARATOR;

		$tempName = 'iftemp-' . md5(time());
		$dir = ASSETS_PATH . $upload_dir . $tempName . DIRECTORY_SEPARATOR;
		mkdir($dir, 0777);

		//crear index.html vacio
		fclose(fopen($dir . "index.html", "w+"));

		return $tempName;
	}

	//Renombra una carpeta temporal con su ID definitivo
	static function renameTempFolder($upload_dir, $tempName, $elementId)
	{
		if(strpos($tempName, 'iftemp-') >= 0)
		{
			//Asegurarse que terminan en /
			$upload_dir = trim($upload_dir, '/') . DIRECTORY_SEPARATOR;
			$tempName = trim($tempName, '/') . DIRECTORY_SEPARATOR;

			@rename(ASSETS_PATH . $upload_dir . $tempName,
					ASSETS_PATH . $upload_dir . $elementId . DIRECTORY_SEPARATOR
			);
		}
	}

	//Elimina carpetas temporales que no se hayan usado en algun tiempo
	private function garbageCollect()
	{
		$dir = $this->upload_path_server;
		if(is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			foreach($files as $v)
			{
				//Borrar carpetas temp con 30mins de creada
				if(
					strpos(strtolower($v), 'iftemp-') !== FALSE &&
					(time() - filectime($dir . $v . DIRECTORY_SEPARATOR) > 1000)
				)
				{
					@unlink($dir . $v . DIRECTORY_SEPARATOR);
				}
			}
		}
	}

}
