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
 * Para su uso solo con autorizacion.
 *
 *****************************************************************/

include APPPATH . 'core/_if_controller.php';

class _If_Sys extends _If_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->_is_browser_compatible();
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
	 */
	public function login()
	{
		if(empty($this->input->post()))
		{
			$this->_login();
			return;
		}
		
		$D = new stdClass();

		//Nombre de usuario invalido redirige a login
		/*if(
			$this->input->post('is_valid') !== NULL
				&& !$this->input->post('is_valid')
		)
		{
			$D->error = 'Usuario inválido.';
			$this->_login($D);
			return;
		}*/
		
		$this->_validar($D);
		$this->_login($D, TRUE);
		
		
		
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * _login
	 * 
	 * Metodo privado para cargar el template y la vista 
	 * relacionada al login en la aplicacion
	 * 
	 * @param mixed $D				datos para la vista
	 * @param boolean $ONLY_FORM	carga solo el formulario si es TRUE
	 */
	private function _login($D = NULL, $ONLY_FORM = FALSE)
	{
		$view = '/login';
	
		if($ONLY_FORM)
		{
			$view = '/partial/login_form';
		}
		
		$this->load->view(
			'../templates/'.$this->config->item('tmpl').$view, 
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
		$in = &$this->input;
		
		$ONLY_NAME_USER = count($in->post()) === 1 
							&& !empty($in->post('usuario'));
		
		//solo nombre de usuario fue pasado por parametro
		if($ONLY_NAME_USER)
		{
			$D->usuario = $in->post('usuario');
			
			$r = $this->USUARIO->validar($D->usuario);
			
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
		}
		else	//validar nombre de usuario y clave
		{
			$D	= new stdClass();
			
			$r = $this->USUARIO->validar(
					$in->post('usuario'), 
					$in->post('md5')
				);
			
			if($r === FALSE)	//usuario invalido
			{
				$D->error = 'Usuario inválido.';
			}
			else				//usuario valido
			{
				$D->error = 'Usuario válido.';
			}
		}
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * _is_max_acceso_invalid
	 * 
	 * Determina si el numero de acceso invalidos es mayor al 
	 * maximo permitido
	 * 
	 * @param int $accesos_invalid numero de accesos invalidos
	 * @return boolean
	 */
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
	
	
	//-----------------------------------------------------------------
	//BROWSER
	//-----------------------------------------------------------------
	
	/*
	 * is_browser_compatible
	 * 
	 * Funcion que obtiene datos del navegador (cliente) y realiza 
	 * las instrucciones correspondientes  entre los diferentes tipos 
	 * y plataformas.
	 
	 * Ver: http://www.codeigniter.com/user_guide/libraries/user_agent.html
	 */
	private function _is_browser_compatible()
	{
		$this->load->library('user_agent');
		
		//solo firefox o chrome
		if (
			$this->agent->is_browser() 
			&& !($this->agent->browser() === 'Firefox'
			|| $this->agent->browser() === 'Chrome')
		)
		{
			show_error(
				'EL navegador '.
				$this->agent->browser().' '.
				$this->agent->version().
				' no es compatible con esta aplicación.'
			);
			
			return FALSE;
		}		
	}
	
	
}

