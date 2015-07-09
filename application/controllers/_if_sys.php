<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/******************************************************************
 *
 * Class _If_Sys
 * 
 * Clase que define funciones de sistema como la autenticacion 
 * de usuarios (Login), registro de usuarios (SignIn), gestión 
 * de variables de configuración y otras.
 * 
 * Dependecias: Clase _If_Controller.
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 *****************************************************************/

include APPPATH . 'core/_if_controller.php';

class _If_Sys extends _If_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * index en _If_Sys
	 * 
	 * Es la funcion inicial (main) desde donde se carga la vista principal. 
	 * 
	 * Por ejemplo: si el sistema requiere autenticación se llama a la 
	 * funcion publica login definida en esta clase sino se llama a la 
	 * vista correspondiente a traves de la funcion tmpl de _If_Controller.
	 * 
	 * No recibe parametros
	 * 
	 */
	public function index()
	{
		$this->login();
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * _login
	 * 
	 * Metodo privado para cargar el template relacionado al login 
	 * en la aplicacion
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
	 * Metodo publico que llama a la vista login y recibe datos 
	 * para la autenticacion de usuarios del sistema
	 */
	public function login()
	{
		$D	= new stdClass();
		$in	= &$this->input;
		
		if(empty($in->post()))
		{
			$this->_login();
		}
		else
		{
			if(!empty($in->post('usuario')))
			{
				$D->usuario = $in->post('usuario');
			}
			else
			{

			}
			
			$this->_login($D);
		}
		
		
		
		
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

