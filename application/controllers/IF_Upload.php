<?php

/**
 * 3.2.3
 */
include_once APPPATH . 'core/IF_Controller.php';

/**
 * HEREDAR de esta clase, no sobreescribirla directamente.
 */
class IF_Upload extends IF_Controller
{

	public function __construct($upload_dir = 'uploads/', $config = NULL)
	{
		parent::__construct();

		//to avoid unwanted warnings
		ini_set("gd.jpeg_ignore_warning", 1);

		//Asegurarse que $upload_dir termina en /
		$upload_dir = trim($upload_dir, '/');

		//Default Config
		$this->CONFIG = (object) (empty($config) ? array() : $config);
		$this->CONFIG->CONTROLLER = !isset($this->CONFIG->CONTROLLER) ? 'IF_Upload' : $this->CONFIG->CONTROLLER;
		if (empty($this->CONFIG->FILE_COUNT))
		{
			//Cantidad de archivos permitidos para subir. -1 para infinitos.
			$this->CONFIG->FILE_COUNT = -1;
		}
		if (empty($this->CONFIG->FILE_SIZE_MAX))
		{
			//Tamaño en BYTES permitido
			$this->CONFIG->FILE_SIZE_MAX = 10000000;
		}
		if (empty($this->CONFIG->FILE_TYPE))
		{
			//Tipos de archivo permitidos
			$this->CONFIG->FILE_TYPE = array(
				'application/pdf', 'image/jpeg', 'image/png',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/msword', 'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.ms-powerpoint',
				'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'audio/mpeg' . 'video/mpeg'
			);
		}
		if (empty($this->CONFIG->IMAGE_SIZE_MAX))
		{
			//Formato: array(ancho,alto) en px
			$this->CONFIG->IMAGE_SIZE_MAX = NULL;
		}
		if (empty($this->CONFIG->IMAGE_COPIES))
		{
			//Generar copias de las imagenes automaticamente, ej de config:
			/**
			 * Esto generará dos copias de la imagen a 50% y 25% del tamaño
			 * original (porcentajes para mantener relación de aspecto),
			 * también colocará un sufijo al nombre de cada copia:
			 * array(
			  array('resize'=>0.50, 'suffix'=>'-mobile'),
			  array('resize'=>0.25, 'suffix'=>'-thumbnail'),
			  )
			 */
			$this->CONFIG->IMAGE_COPIES = NULL;
		}

		$this->upload_path_client = IF_PATH_ASSETS_CLIENT . $upload_dir . '/';
		$this->upload_path_server = IF_PATH_ASSETS_SERVER . $upload_dir .
				DIRECTORY_SEPARATOR
		;

		$this->garbageCollect();
	}

	public function detail_compos($id = -1, $nombre_objeto = 'IF_UPLOADER')
	{
		if (!extension_loaded('fileinfo'))
		{
			die('Se requiere la extensión PHP fileinfo.');
		}

		//d('EL ID ES; '.$id);

		$D = new stdClass();
		$D->FILES_URL = $this->upload_path_client . $id . '/';
		$D->NOMBRE_OBJETO = $nombre_objeto;
		$D->ID = $id;
		$D->FILES = array();
		$D->CONFIG = $this->CONFIG;
		$D->PLG_URL = IF_PATH_PLUGINS_CLIENT . 'if.upload/';
		$D->CONTROLLER = IF_PATH_INDEX_CLIENT . $this->CONFIG->CONTROLLER
				. '/ajax_save/';

		$D->NAMESPACE = 'if-upload-' . strtolower($nombre_objeto);

		//busca archivos ya subidos para este id
		$dir = $this->upload_path_server . $id . DIRECTORY_SEPARATOR;
		if (is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			unset($files[array_search('index.html', $files)]);
			
			foreach($files as $k=>$v)
			{
				if(
					preg_match("/-mobile/", $v) === 1 ||
					preg_match("/-thumbnail/", $v) === 1
				)
				{
					//echo $v.'<br />';
					unset($files[$k]);
				}
			}
			
			$D->FILES = $files;
		}

		$this->load->view('../plugins/if.upload/detail_compos.php', $D);
	}

	//Retorna codigos de error numerico. 0 = no error.
	public function ajax_save()
	{
		$D = (object) $_POST;

		//automatically creates temp folder
		if ($D->id <= 0)
		{
			//d('CREANDO ARCHIVO TEMPORTAL');
			$D->id = $this->createTempFolder($this->upload_path_server);
			//d($D->id);
		}

		$RESPONSE = (object) array(
					'error' => 0,
					'id' => $D->id,
					'folder_provisional' => $D->id <= 0,
		);

		//carpeta especifica
		$upload_dir = $this->upload_path_server . $D->id . DIRECTORY_SEPARATOR;

		//Remover archivos remotos
		$elim = explode(',', $D->remove_remote_files);
		foreach ($elim as $v)
		{
			if (empty($v))
			{
				continue;
			}
			@unlink($upload_dir . $v);
		}

		//Subir nuevos archivos
		if (file_exists($upload_dir) || mkdir($upload_dir))
		{
			//Determinar siguiente en la secuencia
			$time = time();
			$i = 0;

			//Subir archivos
			foreach ($_FILES as $v)
			{
				//No usar time() poruqe al subir varios archivos a la vez
				//algunos podrian terminar con el mismo timestamp.
				$file_name = str_replace(".", "", microtime(TRUE));

				$ext = pathinfo($v['name'], PATHINFO_EXTENSION);

				//Imagenes
				if ($size = getimagesize($v["tmp_name"]))
				{
					$ancho = $size[0];
					$alto = $size[1];

					//Dimensiones
					if (is_array($this->CONFIG->IMAGE_SIZE_MAX))
					{
						$anchoMax = $this->CONFIG->IMAGE_SIZE_MAX[0];
						$altoMax = $this->CONFIG->IMAGE_SIZE_MAX[1];

						if ($ancho > $anchoMax || $alto > $altoMax)
						{
							$RESPONSE->error = 2; //Wrong image size
							continue;
						}
					}

					//Copia
					if (is_array($this->CONFIG->IMAGE_COPIES))
					{
						foreach ($this->CONFIG->IMAGE_COPIES as &$copy)
						{
							$newAncho = $ancho * $copy['resize'];
							$newAlto = $alto * $copy['resize'];

							$dest = imagecreatetruecolor($newAncho, $newAlto);

							//Let's check allowed $ext, we use PHP SWITCH statement here
							switch (strtolower($ext))
							{
								case 'png':
									//Create a new image from file 
									$source = imagecreatefrompng($v["tmp_name"]);
									break;
								case 'gif':
									$source = imagecreatefromgif($v["tmp_name"]);
									break;
								case 'jpg':
								case 'jpeg':
									$source = imagecreatefromjpeg($v["tmp_name"]);
									break;
								default:
									$RESPONSE->error = 1;
							}


							$destFile = imagecopyresized(
									$dest, $source, 0, 0, 0, 0
									, $newAncho, $newAlto, $ancho, $alto
							);
							
							switch (strtolower($ext))
							{
								case 'png':
									//Create a new image from file 
									imagepng(
											$dest
											, $upload_dir . $file_name
											. $copy['suffix'] . ".png"
									);
									break;
								case 'gif':
									imagejpeg(
											$dest
											, $upload_dir . $file_name
											. $copy['suffix'] . ".gif"
									);
									break;
								case 'jpg':
								case 'jpeg':
									imagejpeg(
											$dest
											, $upload_dir . $file_name
											. $copy['suffix'] . ".jpg"
									);
									break;
								default:
									$RESPONSE->error = 1;
							}
						}
					}
				}

				move_uploaded_file(
						$v["tmp_name"], $upload_dir . $file_name . ".{$ext}"
				);
			}

			if (empty($RESPONSE->error))
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
		$upload_dir = trim($upload_dir, '/\\') . DIRECTORY_SEPARATOR;

		$tempName = 'iftemp-' . md5(rand());
		$dir = $upload_dir . $tempName . DIRECTORY_SEPARATOR;

		mkdir($dir, 0777);

		//crear index.html vacio
		fclose(fopen($dir . "index.html", "w+"));

		return $tempName;
	}

	//Renombra una carpeta temporal con su ID definitivo
	static function renameTempFolder($upload_dir, $tempName, $elementId)
	{
		if (strpos($tempName, 'iftemp-') >= 0)
		{
			//Asegurarse que terminan en /
			$upload_dir = trim($upload_dir, '/') . DIRECTORY_SEPARATOR;
			$tempName = trim($tempName, '/') . DIRECTORY_SEPARATOR;

			@rename(IF_PATH_ASSETS_SERVER . $upload_dir . $tempName,
		   IF_PATH_ASSETS_SERVER . $upload_dir . $elementId
							. DIRECTORY_SEPARATOR
			);
		}
	}

	//Elimina carpetas temporales que no se hayan usado en algun tiempo
	private function garbageCollect()
	{
		$dir = $this->upload_path_server;
		if (is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			foreach ($files as $v)
			{
				//Borrar carpetas temp con 30mins de creada
				if (
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
