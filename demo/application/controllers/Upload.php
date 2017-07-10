<?php
if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Upload.php');

class Upload extends IF_Upload
{
	public function __construct()
	{
		$config = (object) array(
				'FILE_COUNT'=>10,
				'CONTROLLER'=>'Upload'
		);
		parent::__construct('uploads/', $config);
	}
}
