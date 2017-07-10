<?php
if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Avatar.php');

class Avatar extends IF_Avatar
{
	public function __construct()
	{
		$config = NULL;
		parent::__construct('avatar/', $config);
	}
	
	
}
