<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH . 'core/IF_Controller.php');

class Usuario extends IF_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
	/*	if(!$this->session->userdata('auth'))
		{
			$this->load->helper('url');
			redirect('/IF_Sys/login', 'refresh');
		}
		*/
		$this->load->model('usuario_model','usuario_m');
	}
	
	public function index()
	{
		$D = new stdClass();
		
		//cargar template + vista
		$this->tmpl('usuario/master_usuario',$D);
	}
	
	public function md_intro()
	{
		$this->load->view('usuario/intro_usuario');
	}
	
	public function md_mt()
	{
        $obj = $this->jqgrid($this->usuario_m);
		
		//la data cruda viene en _rows
		foreach($obj->_rows as $i=> $r)
		{
			//procesamos la fila solo con la data que realmente
			//necesita jqGrid y la anadimos de una vez a rows
			$id = $r->id;
			$obj->rows[$i]['id'] = $id;
			$obj->rows[$i]['cell'] = array(
				$r->usuario
			);
		}
		
		//ELIMINAMOS la data cruda
		unset($obj->_rows);
		
		//entregamos el JSON al jqGrid
		echo json_encode($obj);
	}
	
	public function md_detail($id = -1, $ADDON = FALSE)
	{
		$D = $id <= 0 ? $this->usuario_m->vacio() :
				$this->usuario_m->get($id);
		
		if(!empty($ADDON))
		{
			$D = (object) array_merge((array) $D, (array) $ADDON);
		}
		
		$this->load->view('usuario/detail_usuario', $D);
	}
	
	public function save()
	{
		$D = (object) $_POST;
		
		$usuario = $this->usuario_m->store($D);
		
		$this->md_detail(NULL,$usuario);
	}
	
	public function delete($id = -1)
	{
		$r = $this->usuario_m->delete($id);
		
		echo json_encode($r);
	}
	
	
	public function test_has_many()
	{
		
		
		
		$_usuario = array(
			'id' => -1,
			'usuario' => 'test888',
			'clave' => '1111',
			'rol_id' => 1,
			'acceso_invalid' => 1,
			'notes' => array()
		);
		
		$usuario = (object) $_usuario;
		
		for($i=0;$i<=10;$i++)
		{
			$_note = array(
				'id' => -1,
				'note' => 'test ' . $i,
				'usuario_id' => -1,
			);
			
			$note = (object) $_note;
			
			array_push($usuario->notes, $note);
		}
		
		$usuario = $this->usuario_m->store($usuario);
		
		d($usuario);
		
	}
	
}