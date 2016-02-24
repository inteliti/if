<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

include APPPATH . 'core/IF_Controller.php';

class Demos extends IF_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->view('demos/index');
	}

	public function masterdetail()
	{
		$this->view('demos/masterdetail/index');
	}
	
	public function upload()
	{
		$this->view('demos/upload/index');
	}
	
	public function modal()
	{
		$this->view('demos/modal/index');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	protected function view($view)
	{
		$D = new stdClass();
		$D->VIEW = $view;

		$this->load->view('demos/_template.php', $D);
	}
	
	//--------------------------------------------
	//EJEMPLOS DE MASTERDETAIL
	//--------------------------------------------
	public function masterdetail_mt()
	{
		//$_POST recibe de Bootgrid:
		//current=1&rowCount=10&sort[sender]=asc&searchPhrase=&id=b0df282a-0d67-40e5-8558-c9e93b7befed

		$filter = (object)$_POST;
		
		$r = new stdClass();
		$r->current = 1;
		$r->rowCount = 10;
		$r->rows = array();
		
		//simulamos data de BD
		$total_elementos = 100;
		$inicio = $filter->current * $filter->rowCount;

		for($i = $inicio; $i < $inicio+$filter->rowCount; $i++)
		{
			$r->rows[] = (object)  array(
				'id' => $i,
				'name' => 'Nikola Tesla',
				'received' => date('d/m/Y'),
			);
		}
		$r->total = $total_elementos;
		
		echo json_encode($r);
	}
	
	function masterdetail_intro()
	{
		$this->load->view('demos/masterdetail/intro', NULL);
	}
	
	function masterdetail_detail($id)
	{
		$D = new stdClass();
		$D->ID = $id;
		
		$this->load->view('demos/masterdetail/detail', $D);
	}

}
