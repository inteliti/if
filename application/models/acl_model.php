<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl_Model extends CI_Model
{
	protected $db_roles;
	protected $db_acciones;
	protected $db_roles_acciones;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db_roles = $this->config->item('acl_db_roles');
		$this->db_acciones = $this->config->item('acl_db_acciones');
		$this->db_roles_acciones = $this->config->item('acl_db_roles_acciones');
		
	}
	
	public function get_roles($modulo='',$accion='')
	{
		$roles_id = array();
		
		if(!empty($modulo))
		{
			$modulo = strtolower($modulo);
			$accion = strtolower($accion);
			
			$where = empty($accion) ?	"a.modulo='{$modulo}' AND a.accion IS NULL" : 
										"a.modulo='{$modulo}' AND a.accion='{$accion}'";
			
			
			$query = $this->db->query($this->_get_query($where));
			
			//si se verifico por modulo y accion y da vacio entonces se revisa solo por el modulo
			if(count($query->result())==0 && !empty($accion))
			{
				$where = "a.modulo='{$modulo}' AND a.accion IS NULL";
				$query = $this->db->query($this->_get_query($where));
			}
			
			foreach ($query->result() as $row)
			{
				array_push($roles_id, $row->rol_id);
			}
		}
		
		return $roles_id;
	}
	
	
	private function _get_query($where)
	{
		return "
		SELECT 
			ra.rol_id
		FROM
			{$this->db_roles_acciones} ra 
		WHERE ra.accion_id IN(
			SELECT id 
			FROM
				{$this->db_acciones} a
			WHERE {$where})";
	}
	
}
