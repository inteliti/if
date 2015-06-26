<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl {
	
	var $CI;
	var $model;
	
	function __construct()
	{
		//Obtener instancia de codeigniter
		$this->CI =& get_instance();
		
		//Obtener configuracion de ACL
		$this->CI->config->load('acl');
		
		//Cargar libreria de sesion
		$this->CI->load->library('session');
		
		$source = $this->CI->config->item('acl_source');
		
		$this->CI->load->model('Acl_Model', 'ACL');
		
	}
	
	function isAllowed( $modulo = '', $accion = '' )
	{
		//traerse los roles que tienen permitido este modulo y/o accion
		$roles_id = $this->CI->ACL->get_roles($modulo,$accion);
		
		//verificar si hay roles asociados a este modulo y/o accion
		if(!empty($roles_id))
		{
			
			//obetener rol del usuario actual
			$rol_id = $this->CI->session->userdata('rol_id');
			
			//verificar si rol de usuario tiene permitido acceder a este modulo y/o accion
			if(!in_array($rol_id, $roles_id))
			{
				//si hay roles para este modulo y/o accion
				//y no se esta dentro de los roles permitido
				//retorna falso
				return FALSE; 
			}
		}
		//si no se tienes roles asociados a este modulo y/o accion
		//o si si existen roles asociados y se esta dentro de estos roles
		//retorna verdadero
		return TRUE;
	}
	
	
	
}

