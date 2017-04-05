<?php
if(!defined('BASEPATH'))
	exit('No direct script access allowed');


include_once(APPPATH . 'controllers/IF_Download.php');

class Download extends IF_Download
{
	public function __construct()
	{
		$config = (object) array(
				'CONTROLLER'=>'Download',
				'AUTHORIZATION_REQUIRED' =>TRUE
		);
		parent::__construct('second_uploads/', $config);
	}
	
	public function auth_download($id)
	{
		$this->_init_download($id,TRUE);
	}
	
}
