<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'core/IF_Controller.php';

class Login extends IF_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->_login();
	}
	
	/**
	 * Metodo para cargar el template relacionado al login en la aplicacion
	 * 
	 * $D: datos para la vista
	 */
	private function _login($D = NULL)
	{
		$D = (object) $D;
		$this->load->view('../templates/'.$this->config->item('tmpl').'/login', $D);
	}
	
	
}
