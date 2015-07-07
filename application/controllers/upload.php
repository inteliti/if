<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'core/_if_controller.php';

class Upload extends _If_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->tmpl('demos/upload');
	}
	
	
	
	
}

