<?php
include_once(APPPATH . 'controllers/IF_Upload.php');

class Second_upload extends IF_Upload
{
	public function __construct()
	{
		$config = (object) array(
				'FILE_COUNT'=>10,
				'CONTROLLER'=>'Second_upload'
		);
		parent::__construct('second_uploads/', $config);
	}

}
