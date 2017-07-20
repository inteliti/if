<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Model.php');

class Usuario_model extends IF_Model {

    public $model = array(
        array('field' => 'id',
            'label' => 'id',
            'rules' => 'required',
            'default' => -1),
        array('field' => 'usuario',
            'label' => 'usuario',
            'rules' => 'required',
            'default' => ''),
        array('field' => 'clave',
            'label' => 'clave',
            'rules' => '',
            'default' => ''),
        array('field' => 'rol_id',
            'label' => 'rol_id',
            'rules' => '',
            'default' => ''),
        array('field' => 'estado',
            'label' => 'estado',
            'rules' => '',
            'default' => '1'),
    );
    public $query = "
		SELECT usu.*, rol.rol AS rol_name
		FROM cdt_usuarios usu,
			cdt_roles rol
		WHERE %s
			AND rol.id = usu.rol_id
		ORDER BY %s
		LIMIT %d,%d";
    public $query_count = "
		SELECT COUNT(*) AS c
		FROM cdt_usuarios usu
		WHERE %s";
    public $sp_methods = array(
        'login' => 'sp_login',
        'get' => 'sp_get_usuario',
            //'count'	=> 'count_usuarios',
    );
    protected $primary_key = "usu.id";
    public $f_methods = array(
    );

    public function __construct() {
        parent::__construct();
    }

    function store(&$D, $skip_validation = TRUE, $is_nuevo = FALSE) {
        if (!empty($D->clave)) {
            $D->clave = md5($D->clave);
        } else {
            unset($D->clave);
        }

        $D->estado = empty($D->estado) ? 0 : 1;
        $D->email = empty($D->email) ? NULL : strtolower($D->email);

        return parent::store($D, $skip_validation,$is_nuevo);
    }

    // ------------------------------------------------------------------------

    /*
     * validar
     * 
     * Validamos que el usuario existe. Si no existe retorna FALSE 
     * Si existe devuelve el usuario. Si el parametro $clave no es 
     * pasado por parametro solo se valida el nombre de usuario.
     * 
     * @param string $usuario	nombre del usuario a validar 
     * @param string $clave		clave de usuario a validar
     * @return boolean o objetc
     */
    public function validar($usuario, $clave = NULL) {
        if (empty($usuario)) {
            return FALSE;
        }
		
        $r = $this->db->get_where($this->_table, array(
            'usuario' => $usuario,
            'clave' => $clave
        ));
		
        return count($r->row()) <= 0 ? FALSE :
                $r->row_object();
    }
	
    public function usuario_check($usuario) {
        $id = $this->input->post('id');
        $rows = $this->getWhere("usu.usuario='{$usuario}'");

        if ($rows && $rows[0]) {
            if ($rows[0]->id == $id) {
                return TRUE;
            }
            $this->form_validation->set_message(
                    'external_callbacks', 'Este nombre de usuario ya está registrado'
            );
            return FALSE;
        } else {
            return TRUE;
        }
    }

	
	
}
