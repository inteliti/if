<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'core/IF_Controller.php';

class Upload extends IF_Controller
{
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('upload_model','upload_m');
		
		/*
		 * Se define parametro de url donde se subiran los archivos
		 */
		$this->main_upload_url = ASSETS_URL . 'uploads/';
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index($id=-1)
	{
		$D = new stdClass();
		
		$D->id = $id;
		
		$this->tmpl('demos/upload_master',$D);
	}
	
	public function detail( $id=-1, $ADDON = FALSE )
	{
		//obtener datos del objeto
		$D = $id <= 0 ? $this->upload_m->vacio() :
						$this->upload_m->get($id);
		
		//si existe algun agregado para el objeto hay que anexarlo
		if(!empty($ADDON))
		{
			
			$D = (object) array_merge((array) $D, (array) $ADDON);
		}
		
		/*
		 * Se envia URL donde se subiran los archivos
		 */
		$D->main_upload_url = $this->main_upload_url;
		
		$this->load->view('demos/upload_detail',$D);
	}
	
	public function save()
	{
		$D = (object) $this->input->post();
		
		/*
		 * Se llama antes de guardar un objeto que tenga archivos 
		 * que han sido subidos a la aplicacion. 
		 * 
		 * Esta funcion permite guardar la URL donde se guardaron los archivos.
		 * 
		 * Es necesario para poder usar el plugin en objetos que aun no tienen un id
		 */
		$upload_url = getUploadURL($D);
		/*
		 * Se llama para guardar el objeto
		 */
		$upload = $this->upload_m->store($D);
		
		/*
		 * Se llama despues de guardar objeto que tenga archivos
		 * que han sido subidos a la aplicacion.
		 * 
		 * Esta funcion permite actualizar la URL donde se guardaron los archivos
		 * 
		 * Es necesario para poder usar el plugin en objetos que aun no tienen un id
		 */
		updateUploadURL($upload,$upload_url,$this->main_upload_url);
		
		$this->detail(NULL,$upload);
	}
	
	
	
}

