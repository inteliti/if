<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH . 'core/_if_model.php');

class Usuario_Model extends _If_Model
{
    public $model = array(
        array( 'field' => 'id',
               'label' => 'id',
               'rules' => 'required',
               'default' => -1),
        array( 'field' => 'usuario',
               'label' => 'usuario',
               'rules' => 'required|callback_external_callbacks[usuario_model,usuario_check]',
               'default' => ''),
        array( 'field' => 'clave',
               'label' => 'clave',
               'rules' => 'required',
               'default' => ''),
    );
	
	public $query = "
		SELECT usu.* 
		FROM if_usuarios usu 
		WHERE %s
		ORDER BY %s
		LIMIT %d,%d";
	
	public $query_count = "
		SELECT COUNT(*) AS c
		FROM if_usuarios usu
		WHERE %s";
	
    public $sp_methods = array(
		'login'		=> 'sp_login',
		'get'		=> 'sp_get_usuario',
		//'count'	=> 'count_usuarios',
    );
	
    public $f_methods = array(
        
    );
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	function store(&$D, $skip_validation = FALSE)
	{
		if($D->id > 0 && !isset($D->clave))
		{
			$usuario = parent::get($D->id);
			$D->clave = $usuario->clave;
			return parent::store($D, $skip_validation);
		}
		return parent::store($D, $skip_validation);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * validate_user_name
	 * 
	 * Validamos que el nombre de usuario pasado
	 * por paramentro existe en la base de datos
	 * 
	 * Si no existe retorna FALSE
	 * Si existe devuelve el usuario
	 */
	public function validate_user_name($usuario)
	{
		$r = $this->db->get_where(
				'usuarios', 
				array('usuario' => $usuario)
			);
		
		return count($r->row()) <= 0 
				? FALSE 
				: $r->row();
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * 
	 */
    function validateLogin($usuario, $clave)
	{
		$o = new stdClass();
		
		$usuario = $this->sanitize($usuario);
		$clave = $this->sanitize($clave);
		
		$r = $this->call($usuario, $clave);

		d($usuario.' - '.$clave);
		d($r);exit;		
				
        if(!$r || count($r) <= 0)
		{
            $o->error = 'Autenticación fallo';
			$o->success	= FALSE;
			return $o;
        };
		
		$o = $r[0];
		$o->success = TRUE;
		unset($o->clave);
		
        return $o;
    }
	
	public function usuario_check($usuario)
	{
		$id = $this->input->post('id');
		$rows = $this->getWhere("usu.usuario='{$usuario}'");
		
		if($rows && $rows[0])
		{
			if($rows[0]->id==$id)
			{
				return TRUE;
			}
			$this->form_validation->set_message(
                  'external_callbacks', 
                  'Este nombre de usuario ya está registrado'
			);
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
}
