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

class _If_Controller extends CI_Controller
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
		require_once APPPATH.'core/_if_constants.php';
		
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
	}
	
	/*********************************************************
	* JQGRID
	*********************************************************/	

	/**
	 * Metodo jqgrid que permite traerse los datos para alimentar el maestro jqgrid.
	 *
	 * $model: referencial al modelo donde se definen los datos que se estan consultando
	 * $additionalWhere: Setencia SQL para consulta de los datos
	 */
	protected function jqgrid(&$model, $additionalWhere = NULL)
	{
		$in = & $this->input->post();

		$sort	= empty($in['sidx'])	? NULL		: $in['sidx'];
		$dir	= empty($in['sord'])	? NULL		: $in['sord'];
		$filter = empty($in['filter'])	? ''		: $in['filter'];
		$limit	= empty($in['rows'])	? NULL		: intval($in['rows']);
		$page	= empty($in['page'])	? 1			: intval($in['page']);

		$where = $this->filter2SqlWhere($filter);
		
		if($additionalWhere)
		{
			$where .= " AND ({$additionalWhere})";
		}
		
		$total = $model->count( $where );
		
		$total_pages = $total > 0 ? ceil( $total / $limit ) : 0;
		
		if($page > $total_pages) $page = $total_pages;
		
		$start = $limit * $page - $limit; // do not put $limit * ($page - 1)
		
		if($start < 0) $start = 0;
		
		$rows = $model->getWhere( $where, $sort . ' ' . $dir, $start, $limit );
		
		$R			= new stdClass();
		$R->page	= $page;
		$R->total	= $total_pages;
		$R->records = $total;
		$R->_rows	= $rows;
		$R->rows	= array();
		
		return $R;
	}
	
	/**
	 * Metodo para generar el where dado unos parametros del filter.
	 * 
	 * $filter: arreglo con campos para filtrar
	 */
	protected function filter2SqlWhere($filter='')
	{
		$filter		= trim($filter);
		$db			=& $this->db;
		$where		= array();
		
		if( !empty($filter) && $filter!='null' )
		{
			foreach( explode('||',$filter) as $query )
			{
				$query = trim($query);
				if(empty($query)) continue;
				
				list($attr,$op,$val) = explode('~', $query);
				if(empty($val)) continue;
				
				//-----------------------
				//especial cases
				//-----------------------
				if(strpos( $attr,'__from')>0 )
				{
					$attr = str_replace('__from', '', $attr);
					$op = '>=';
                    
					$val = $val.' 00:00:00';
				}
				else if( strpos($attr,'__to')>0 )
				{
					$attr = str_replace('__to', '', $attr);
					$op = '<=';
                    
					$val = $val.' 23:59:59';
				}
				if( $val=="NULL" )
				{
					$op = "";
					$a = "{$attr} IS NULL";
				}
				
				switch( strtolower($op) )
				{
					case 'like':
						$a = "{$attr} LIKE ".$db->escape("%{$val}%");
					break;
					case '=': case '<': case '>': case '<=': case '>=': case '<>':
						$a = "{$attr}{$op}".$db->escape($val);
					break;
					case 'is':
						$a = "{$attr} IS ".$val;
					break;
				}
				$where[] = $a;
			}
		}
		
		return empty($where)? '1=1' : implode(' AND ', $where);
	}
	
	/*********************************************************
	* LOAD VIEWS
	*********************************************************/	
	
	/**
	 * Metodo para cargar el template principal
	 * 
	 * $view: vista para cargar en el template
	 * $D: datos para la vista
	 * $page: pagina del template que se desea cargar
	 */
	protected function tmpl( $view , $D = NULL , $page='index' )
	{
		$D = (object) $D;
		$D->VIEW = $view;
		$this->load->view('../templates/'.$this->config->item('tmpl').'/'.$page, $D);
	}

	/**
	 * Metodo para cargar el template generico
	 * 
	 * $view: vista para cargar en el template
	 * $D: datos para la vista
	 */
	protected function tmplGeneric($view, $D = NULL)
	{
		$D = (object) $D;
		$D->VIEW = $view;
		$this->load->view('../templates/'.$this->config->item('tmpl').'/generic', $D);
	}
	
	/*********************************************************
	* DATA VALIDATION
	*********************************************************/	
	
	/*
	 * Este metodo permite ejecutar funciones de validacion en el modelo
	 * para la clase validation de codeigniter.
	 * 
	 * ADVERTENCIA:
	 * 
	 * HAY QUE VALIDAR QUE NO SE PUEDA ACCEDER A ESTE METODO 
	 * DESDE FUERA DE LA APLICACION, YA QUE ESTA EN UN CONTROLADOR Y ES PUBLICO
	 * 
	 * http://ellislab.com/forums/viewthread/205469/
	 * external_callbacks method handles form validation callbacks that are not located
	 * in the controller where the form validation was run.
	 *
	 * $param is a comma delimited string where the first value is the name of the model
	 * where the callback lives. The second value is the method name, and any additional 
	 * values are sent to the method as a one dimensional array.
	 *
	 * EXAMPLE RULE:
	 *  callback_external_callbacks[some_model,some_method,some_string,another_string]
	 * 
	 * $this->form_validation->set_rules('required|integer|callback_external_callbacks[formval_callbacks,test]');
	 * 
	 **/
	public function external_callbacks( $postdata, $param )
	{
		$param_values = explode( ',', $param ); 
		
		// Make sure the model is loaded
		$model = $param_values[0];
		$this->load->model( $model );
		
		// Rename the second element in the array for easy usage
		$method = $param_values[1];
		
		//agregado por Eduardo Diaz (Inteliti Soluciones CA) 29/04/2014
		if ( ! method_exists($model, $method) )
		{
			return TRUE;
		}
		
		// Check to see if there are any additional values to send as an array
		if( count( $param_values ) > 2 )
		{
			// Remove the first two elements in the param_values array
			array_shift( $param_values );
			array_shift( $param_values );

			$argument = $param_values;
		}
		
		// Do the actual validation in the external callback
		if( isset( $argument ) )
		{
			$callback_result = $this->$model->$method( $postdata, $argument );
		}
		else
		{
			$callback_result = $this->$model->$method( $postdata );
		}

		return $callback_result;

	}//fin_external_callbacks
}