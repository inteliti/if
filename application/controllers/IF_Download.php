<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * http://php.net/manual/en/zip.installation.php
 */

include_once APPPATH . 'core/IF_Controller.php';

/**
 * HEREDAR de esta clase, no sobreescribirla directamente.
 */
abstract class IF_Download extends IF_Controller
{

	public function __construct($download_dir = 'uploads/', $config = NULL)
	{
		parent::__construct();

		//Asegurarse que $upload_dir termina en /
		$download_dir = trim($download_dir, '/');

		//Default Config
		$this->CONFIG = (object) (empty($config) ? array() : $config);
		$this->CONFIG->CONTROLLER = !isset($this->CONFIG->CONTROLLER) ? 'IF_Download' :
				$this->CONFIG->CONTROLLER;
		
		$this->CONFIG->CONTROLLER = !isset($this->CONFIG->AUTHORIZATION_REQUIRED) ? FALSE :
				$this->CONFIG->AUTHORIZATION_REQUIRED;
		
		$this->download_path_client = IF_PATH_ASSETS_CLIENT . $download_dir . '/';
		$this->download_path_server = IF_PATH_ASSETS_SERVER . $download_dir .
				DIRECTORY_SEPARATOR
		;
	}

	abstract protected function auth_download($id);
	
	/**
	 * Wrapper used if not authoriztion is required
	 * @param type $id
	 */
	public function download($id)
	{
		if(!$this->CONFIG->AUTHORIZATION_REQUIRED)
		{
			$this->_init_download($id);
		}
		else
		{
			header("HTTP/1.1 403 Forbidden" );
			die('Forbidden');
		}
	}
	
	/**
	 * You must call from auth_download function that validate the authorization 
	 * to download acording to buissness logic or to any wraper download function
	 * 
	 * @param type $id
	 */
	protected function _init_download($id,$AUTHORIZED = TRUE)
	{
		if(($AUTHORIZED && $this->CONFIG->AUTHORIZATION_REQUIRED) || !$this->CONFIG->AUTHORIZATION_REQUIRED)
		{
			$file = $this->_get_zipped_folder($id);
			$this->_send_to_user($file,TRUE);
		}
		else
		{
			header("HTTP/1.1 403 Forbidden" );
			die('Forbidden');
		}

	}

	private function _send_to_user($file,$delete_after_download = FALSE)
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo, $file);
		
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $mime_type);
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		
		ob_clean();
		flush();
		
		readfile($file);
		
		if($delete_after_download)
		{
			@unlink($file);
		}
		
		exit;
	}

	private function _get_files($id)
	{
		$dir = $this->download_path_server . $id . DIRECTORY_SEPARATOR;

		$files = array();

		if (is_dir($dir) && ($files = scandir($dir)))
		{
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			unset($files[array_search('index.html', $files)]);
		}

		return $files;
	}

	private function _get_zipped_folder($id)
	{
		$dir = $zip_path = $this->download_path_server . 'tmp_downloads' . DIRECTORY_SEPARATOR;
		$files = $this->_get_files($id);
		
		if(!file_exists($dir))
		{
			mkdir($dir, 0777);
		}
		
		if(!class_exists('ZipArchive'))
		{
			//echo 'no existe la clase<br>';
		}
		
		$tempName = 'iftemp-' . md5(rand()) .'.zip';
		$zip_path = $dir . $tempName;
		$zip = new ZipArchive;
		$zip->open($zip_path, ZIPARCHIVE::CREATE);
		
		foreach ($files as $file)
		{
			
			$curr_file = $this->download_path_server . $id . DIRECTORY_SEPARATOR . $file;
			
			$output_file_name = $file;
			
			$zip->addFile($curr_file,$output_file_name);
		}
		$zip->close();

		return $zip_path;
	}
	
}
