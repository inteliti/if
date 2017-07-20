<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');
/* * ****************************************************************
 * 
 * Clase IF_Sys
 * 
 * Clase que define funciones de sistema como: autenticacion 
 * de usuarios (Login), registro de usuarios (SignIn), gestión 
 * de variables de configuración y mas.
 * 
 * Dependecias: Clase IF_Controller.
 * 
 * Derechos Reservados (c) 2017 INTELITI SOLUCIONES, C.A.
 * Para su uso solo con autorizacion.
 *
 * *************************************************************** */

include APPPATH . 'core/IF_Controller.php';

class IF_Sys extends IF_Controller
{

	public function __construct()
	{
		parent::__construct(FALSE);

		//TODO: crear archivo de configuracion que llame 
		//a esta funcion dependiendo de la configuracion
		//$this->_is_browser_compatible();	
	}

	//-----------------------------------------------------------------

	/*
	 * index
	 * 
	 * Función principal (main) desde donde se carga la vista inicial. 
	 * Ej.: si el sistema requiere autenticación se llama a la funcion 
	 * publica login (definida en esta clase) sino se llama a la 
	 * vista correspondiente a traves de la funcion tmpl de la clase 
	 * IF_Controller.
	 */
	public function index()
	{
		
		//si esta logueado
		if($this->session->userdata('auth'))
		{
			$this->home();
		}
		//sino lo esta hay que loguearse
		else
		{
			$this->login();
		}
	}

	public function home()
	{
		//Enviar a Controller method que manejara el home del sistema
		$this->load->helper("url");
		redirect('Demos/index');	//TODO: Manejar a traves de archivo de configuracion
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
		$post = $this->input->post();
		if(empty($post))
		{
			$this->_login();
			return;
		}

		$D = new stdClass();
		
		$error = $this->_validar($D);

		if(empty($error))
		{
			$this->home();
		}
		else
		{
			$this->_login(array(
				'SYSMSG'=>$error
			));
		}
	}

	/**
	 * logout
	 * 
	 * Fncion para cerrar sesion
	 */
	public function logout()
	{
		$this->_destroy_session();
		$this->login();
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

		$this->view(
			'../templates/' . $this->config->item('tmpl') . $view, (object) $D
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
		$this->load->model('Usuario_model', 'USUARIO');
		$in = &$this->input;
		
		$D = new stdClass();

		$usuario = $this->USUARIO->validar(
			$in->post('usuario'), $in->post('md5')
		);
		
		if($usuario === FALSE) //usuario invalido
		{
			return 'INVALID_LOGIN';
		}
		else
		{
			//usuario bloqueado??
			if($usuario->estado==1)
			{
				return 'BLOCKED_USER';
			}
			
			//DESTRUIR CLAVE DEL OBJ
			$usuario->clave = NULL;

			$this->_set_session($usuario);
			return NULL;
		}
	}
	
	/**
	 * 
	 * @param type $usuario
	 */
	private function _set_session($usuario)
	{
		$this->load->library('session');

		$this->session->set_userdata(array(
			'auth'=>TRUE,
			'id'=>$usuario->id,
			'usuario'=>$usuario->usuario,
			'rol_id'=>$usuario->rol_id,
			'ultima_actividad'=>time()
		));
	}

	/**
	 * Destruye los datos de la sesion
	 */
	private function _destroy_session()
	{
		$this->session->sess_destroy();
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
		if(
			$this->agent->is_browser() && !($this->agent->browser() === 'Firefox' || $this->agent->browser() === 'Chrome')
		)
		{
			show_error(
				'EL navegador ' .
				$this->agent->browser() . ' ' .
				$this->agent->version() .
				' no es compatible con esta aplicación.'
			);

			return FALSE;
		}
	}

}
