<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'core/_if_controller.php';

class _If_Sys extends _If_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->login();
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * _login
	 * 
	 * Metodo privado para cargar el template relacionado 
	 * al login en la aplicacion
	 * 
	 * $D: datos para la vista
	 */
	private function _login($D = NULL)
	{
		$D = (object) $D;
		$this->load->view(
			'../templates/'.$this->config->item('tmpl').'/login', 
			$D
		);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * login
	 * 
	 * Metodo publico qeu llama a la vista login y recibe datos a autenticar
	 */
	public function login()
	{
		if(!empty($this->input->post('usuario')))
		{
			$usuario = $this->input->post('usuario');
		}
		
		$this->_login();
		
		/*if(empty())
		{
			$this->_login();
		}
		else
		{
			d($this->input->post());
		}*/
	}
	
	/*
	 * función donde validamos el ingreso de usuarios al sistema
	 * 
	 * 
	 * 
	 */
	private function _validar()
	{
		$this->load->model('Usuario_Model', 'USR');
		
		$usuario = $this->input->post('usuario');
		
		$r = $this->usuario->validate_user_name($usuario);
	}
	
	private function _set_session($usuario)
	{	
		$this->load->library('session');
		
		$this->session->set_userdata(array(
			'auth'				=> TRUE,
			'id'				=> $usuario->id,
			'usuario'			=> $usuario->usuario,
			'rol_id'			=> $usuario->rol_id,
			'ultima_actividad'	=> time()
		));
			
		d($this->session->userdata);
	}
	
	public function destroy_session()
	{
		$this->session->sess_destroy();
	}
	
	/** demos **/
	public function avatar()
	{
		$this->tmpl('demos/avatar');
	}
	
	public function masterDetail()
	{
		$this->tmpl('demos/masterdetail');
	}
}

