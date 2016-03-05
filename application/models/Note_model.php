<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Model.php');

class Note_Model extends IF_Model
{
    public $model = array(
        array( 'field' => 'id',
               'label' => 'id',
               'rules' => 'required',
               'default' => -1),
        array( 'field' => 'note',
               'label' => 'note',
			   'rules' => '',
               'default' => ''),
		array( 'field' => 'usuario_id',
               'label' => 'usuario_id',
               'rules' => 'required',
			   'belong_to' => 'usuario',
               'default' => -1),
    );
	
	public $query = "
		SELECT nte.* 
		FROM if_notes nte 
		WHERE %s
		ORDER BY %s
		LIMIT %d,%d";
	
	public $query_count = "
		SELECT COUNT(*) AS c
		FROM if_notes nte 
		WHERE %s";
	
    public $sp_methods = array(

    );
	
    public $f_methods = array(
        
    );
	
	public function __construct()
	{
		parent::__construct();
	}
	

	
	
}
