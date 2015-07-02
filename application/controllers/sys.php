<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'core/_if_controller.php';

class Sys extends _If_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{			
		/*
		 * Verificamos si hay una sesion activa 
		 * 
		 * Si no existe una sesion activa 
		 *
		 * Nota: comentar esta parte si no se requiere
		 * sistema de autenticacion
		 *  
		 */
		
		//$this->load->library('session');
		
		/*$this->session->set_userdata(array(
			'auth'				=> TRUE,
			'id'				=> 1,
			'usuario'			=> 'jtorres',
			'rol_id'			=> 1,
			'last_activity'		=> time()
		));*/
		
		//IMPORTANTE: validar vigencia (last_activity) de la sesion
		
		$session	= empty($this->session->get_userdata());
		$post		= empty($this->input->post());
		
		if(!$session && (!$post || count($post <= 0)))
		{		
			$this->login();
		}
		else
		{
			
		}
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
		
		$usuario	= $this->input->post('usuario');
		$clave		= $this->input->post('md5');
		
		$u = $this->USR->validateLogin($usuario, $clave);
		
		if($u->success)
		{
			//$this->_set_session($u);
		}
		else
		{
			$this->login(array('error' => -1));
		}
	}
	
	private function _set_session($usuario)
	{
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

