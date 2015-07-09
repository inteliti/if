<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/******************************************************************
 * Clase _If_Sys
 * 
 * Clase que define funciones de sistema como: autenticacion 
 * de usuarios (Login), registro de usuarios (SignIn), gestión 
 * de variables de configuración y mas.
 * 
 * Dependecias: Clase _If_Controller.
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 *****************************************************************/

include APPPATH . 'core/_if_controller.php';

class _If_Sys extends _If_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * index
	 * 
	 * Función principal (main) desde donde se carga la vista inicial. 
	 * Ej.: si el sistema requiere autenticación se llama a la funcion 
	 * publica login (definida en esta clase) sino se llama a la 
	 * vista correspondiente a traves de la funcion tmpl de la clase 
	 * _If_Controller.
	 * 
	 * No recibe parametros
	 */
	public function index()
	{
		$this->login();
	}
	
	//-----------------------------------------------------------------
	//LOGIN
	//-----------------------------------------------------------------
	
	/*
	 * login
	 * 
	 * Funcion publica que llama a la vista login. Recupera las
	 * varibles enviadas a traves del metodo post y delega la 
	 * validacion de usuarios en el sistema.
	 * 
	 * No recibe parametros
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
			if(!empty($in->post('usuario')) && empty($in->post('md5')))
			{
				$D->usuario = $in->post('usuario');
				
				$r = $this->USUARIO->validar($D->usuario);
				
				$D->is_usuario = !$r ? FALSE : TRUE;
				//intentos
			}
			else
			{
				echo $in->post('md5');
			}
			
			$this->_login($D);
		}
		
	}
	
	//-----------------------------------------------------------------
	
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
		$this->load->view(
			'../templates/'.$this->config->item('tmpl').'/login', 
				(object) $D
		);
	}
	
	//-----------------------------------------------------------------

	/*
	 * _validar
	 *  
	 * Funcion privada que valida el acceso de usuarios.
	 * 
	 * $usuario: nombre de usuario del sistema.
	 * $clave: clave de acceso del usuario. Si no es pasada por 
	 * parametro solo se valida el nombre de usuario.
	 */
	private function _validar($usuario, $clave = NULL)
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

