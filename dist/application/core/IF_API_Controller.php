<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*********************************************************
 * 
 * Class _If_Controller
 * 
 * Funciones compartidas entre todos los controladores.
 *		En los controladores se encuentran los puntos de acceso a la aplicacion,
 *		por lo tanto hay que ser muy cuidados de los metodos que aqui se definen.
 * 
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 *********************************************************/

require APPPATH . '/libraries/REST_Controller.php';

class IF_API_Controller extends REST_Controller
{
	/**
	 * Contructor de la clase.
	 * Este contructor se ejecuta siempre que se accede a la aplicacion
	 * a un controlador que herede de _If_Controller.
	 * 
	 * Aqui se hacen las validaciones ACL y se ejecutan metodos filtro
	 *
	 */
	function __construct()
	{
		//contructor padre
		parent::__construct();
		
		//constantes que se comparten en toda la aplicacion
		require_once APPPATH.'core/IF_Constants.php';
		
		//llamar a la libreira ACL
		$this->load->library(array('acl'));
		
		//indagar el modulo y metodo que se esta solicitando
		$modulo = $this->uri->segment(1);
		$accion = $this->uri->segment(2);
		
		//validar autorizacion ACL
		if(!$this->acl->isAllowed($modulo,$accion))
		{
			show_error('Acceso no autorizado!!!');
			return FALSE;
		}
		
		//$this->session->set_userdata('ultima_actividad',time());
	}
	
}