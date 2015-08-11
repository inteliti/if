<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Model.php');

class Upload_Model extends IF_Model
{
    public $model = array(
        array( 'field' => 'id',
               'label' => 'id',
               'rules' => 'required',
               'default' => -1),
		array( 'field' => 'file1',
				'label' => 'file1',
				'default' => ''),
		array( 'field' => 'file2',
				'label' => 'file2',
				'default' => ''),
		array( 'field' => 'file3',
				'label' => 'file3',
				'default' => ''),
		array( 'field' => 'file4',
				'label' => 'file4',
				'default' => ''),
		array( 'field' => 'file5',
				'label' => 'file5',
				'default' => ''),
		array( 'field' => 'file6',
				'label' => 'file6',
				'default' => ''),
    );
	
	public $query = "
		SELECT upl.* 
		FROM if_uploads upl 
		WHERE %s
		ORDER BY %s
		LIMIT %d,%d";
	
	public $query_count = "
		SELECT COUNT(*) AS c
		FROM if_uploads upl
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
