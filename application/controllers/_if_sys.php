<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/******************************************************************
 * 
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
 *
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
	 */
	public function index()
	{
		$this->_login();
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
	 */
	public function login()
	{
		echo 1;
		
		
		/*$D = new stdClass();
		
		d($this->input->raw_input_stream);

		if(empty($this->input->post()))
		{
			$this->_login();
			return;
		}
		
		//Nombre de usuario invalido redirige a login
		if(
			$this->input->post('is_valid') !== NULL
				&& !$this->input->post('is_valid')
		)
		{
			$D->error = 'Usuario inválido.';
			$this->_login($D);
			return;
		}

		$D->ONLY_NAME_USER = count($this->input->post()) === 1 
								&& !empty($this->input->post('usuario'));
		
		$this->_validar($D);*/
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * _login
	 * 
	 * Metodo privado para cargar el template relacionado al login 
	 * en la aplicacion
	 * 
	 * @param mixed $D		datos para la vista
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
	 * Funcion privada que autentica el acceso de usuarios al sistema. 
	 * Si el parametro $clave no es pasado por parametro solo se valida 
	 * el nombre de usuario.
	 * 
	 * @param mixed $D			parametro por referencia
	 */
	private function _validar(&$D)
	{
		$this->load->model('Usuario_Model', 'USUARIO');
		
		if($D->ONLY_NAME_USER)
		{
			$D->usuario = $this->input->post('usuario');
			
			$r = $this->USUARIO->validar($this->input->post('usuario'));
			
			if($r === FALSE)	//nombre de usuario invalido
			{
				$D->is_valid = FALSE;
				$D->enable_captcha = TRUE;
			}
			else				//nombre de usuario valido
			{
				$D->is_valid = TRUE;
				$D->enable_captcha = $this->_is_max_acceso_invalid(
											(int) $r->acceso_invalid);
			}
			
			$this->_login($D);
		}
		else
		{
			$D	= new stdClass();
			
			$r = $this->USUARIO->validar(
					$this->input->post('usuario'), 
					$this->input->post('md5')
				);
			
			if($r === FALSE)	//usuario invalido
			{
				$D->error = 'Usuario inválido.';
			}
			else				//usuario valido
			{
				$D->error = 'Usuario válido.';
			}
			
			$this->_login($D);
		}
	}
	
	private function _is_max_acceso_invalid($accesos_invalid)
	{
		//LLAMAR FUNCION QUE RETORNA VALOR MAX
		return $accesos_invalid >= 3 ? TRUE : FALSE;
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

