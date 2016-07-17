<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once APPPATH . 'core/IF_Controller.php';

/**
 * HEREDAR de esta clase, no sobreescribirla directamente.
 */
class IF_Avatar extends IF_Controller
{

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
		if(empty($this->CONFIG->FILE_TYPE))
		{
			$this->CONFIG->FILE_TYPE = array(
				'image/jpeg', 'image/png'
			);
		}

		$this->upload_path_server = str_replace(
			'\\', '/', ASSETS_PATH . $upload_dir . '/'
		);
	}

	public function detail_compos($id = -1)
	{
		$D = new stdClass();
		$D->UPLOAD_PATH = $this->upload_path_server;
		$D->UPLOAD_FILE_TYPES = $this->CONFIG->FILE_TYPE;
		$D->UPLOAD_FILE_SIZE_MAX = $this->CONFIG->FILE_SIZE_MAX / 1000;
		$D->FILE_NAME = "p" . $id;
		
		$this->load->view('../plugins/if.avatar/detail_compos.php', $D);
	}

}
