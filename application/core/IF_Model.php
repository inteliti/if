<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*********************************************************
 * 
 * _If_Model
 * 
 * Funciones compartidas entre todos los modelos
 * 
 * Dependencias:
 *		mysqli_helper (incluido en directorio helpers)
 * 
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 * Se utiliza parte parcial de codigós de clase MY_Model de Jamie Rumbelow:
 * 
 *	@link http://github.com/jamierumbelow/codeigniter-base-model
 *	@copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 * 
 *********************************************************/

class IF_Model extends CI_Model
{
	/**
	 * El nombre de la tabla en la Base de Datos. Solo configurar si no se usa nombre standar en BD (Plural del Modelo).
	 *
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Nombre de columna de clave primaria de la tabla en la BD, por defecto 'id'.
	 *
	 * @var string
	 */
	protected $primary_key = 'id';
	
	/**
	 * Un array de funciones que se ejecutan antes de crear un registro.
	 *
	 * @var array
	 */
	protected $before_create = array();

	/**
	 * Un array de funciones que se ejecutan despues de crear un registro.
	 *
	 * @var array
	 */
	protected $after_create = array();
	
	/**
	 * String con el query para traerse los registros de la BD
	 *
	 * @var string
	 */
	protected $query = FALSE;
	
	/**
	 * Un array con todos los campos del modelo.
	 * 
	 *	Cada campo se define con un @var array con 4 datos:
	 * 
	 *		* $var string field => nombre del campo (identifica el campo en la BD).
	 *		* $var string label => etiqueta del campo (identifica el campo en la vista).
	 *		* $var string rules => reglas de validacion (utiliza form_validation de CI).
	 *		* $var any default => valor por defecto del campo.
	 *
	 * @var array
	 */
	protected $model = array();
	
	/**
	 * Salta las validaciones al guardar y actualizar.
	 *
	 * @var bool
	 */
	protected $skip_validation = FALSE;
	
	/**
	 * Un array con los procedimientos almacenados definidos para este modelo.
	 *
	 * @var array
	 */
	protected $sp_methods = array();
	
	/**
	 * Un array con las funciones definidos para este modelo.
	 *
	 * @var array
	 */
	protected $f_methods = array();
	
	/**
	 * Contructor de la clase.
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->_fetch_table();
	}
	
	/**
	 * Call a stored procedure of the database.
	 *
	 * $sp: stored procedure name
	 * $param: parameters
	 */
	protected function call($sp, $param = '')
	{
		//Es necesario que exista este helper en el directorio helpers
		$this->load->helper('mysqli');
		
		if($param !== '')
		{
			$param = implode(',', $param);
		}
			
		$r = $this->db->query("CALL sp_{$sp}({$param})");
		
		//Es necesario haber cargado mysqli_helper
		//(sin el se puede producir fallos por que las consultas pueden retornar varios resultados)
		clean_mysqli_connection($this->db->conn_id); 
		
		return $r ? $r->result() : NULL;
	}
	
	/**
	 * Call a function of the database.
	 * 
	 * $f: function name
	 * $param: parameters
	 */	
	protected function func($f, $param = '')
	{
		if($param !== '')
		{
			$param = implode(',', $param);
		}
		
		$r = $this->db->query("SELECT f_{$f}({$param}) AS r")->row();
		return $r ? $r->r : NULL;
	}
	
	/**
	 * functions to sanitize data.
	 *
	 * $str: string que se desea sanitizar
	 */
	protected function sanitize($str)
	{
		//se sanitiza menos ciertos elemento HTML
		$r = strip_tags(trim($str),'<p><a><h1><h2><h3><h4><h5><h6><span><b><i><u><ol><ul><li><br>');
		return empty($r) ? NULL : $r;
	}

	/**
	 * functions to sanitize all data.
	 *
	 * $data: objeto con los datos que se desean sanitizar
	 */
	protected function sanitizeAll(&$data)
	{
		foreach($data as &$d)
		{
			if(!is_null($d)) $d = $this->sanitize($d);
		}
		return $data;
	}
	
	/**
	 * Permite obtener un registro.
	 *
	 * $id: int con id del registro
	 */
	public function get($id=-1)
	{
        $rows = $this->getWhere($this->primary_key.'='.$id);
		return $rows && $rows[0]? $rows[0] : FALSE;
    }
	
	/**
	 * Permite obtener N cantidad de registros que cumplan con parametro where.
	 *
	 * $where: string con porcion SQL del where para obtener los registros
	 * $order: string con porcion SQL que define orden y campo de ordenamiento
	 * $limit_begin: int con numero de registro de inicio para limitar resultados
	 * $limit_offset: int con numero de registro final para limitar resultados
	 */
	public function getWhere($where='1=1', $order=NULL , $limit_begin=0 , $limit_offset=9999)
	{
		$order_clause = !empty($order) ? $order : $this->primary_key . " ASC";
		
		//Si el procedimientos almacenado get existe es llamado 
		if(array_key_exists('get', $this->sp_methods))
		{
			return $this->call($this->sp_methods['get'], 
					array($this->db->escape($where) ,
								"'{$order_clause}'" ,
								$limit_begin ,
								$limit_offset));
		}
		//Sino se hace de la manera tradicional
		else
		{
			//si en el modelo se define un query
			if($this->query)
			{
				//return $this->db->query($this->query, array($where, $order_clause, $limit_begin, $limit_offset)); 
				return $this->db->query(sprintf($this->query,$where,
						$order_clause, $limit_begin, $limit_offset))->result(); 
			}
			//sino se utiliza active record de codeigniter
			else
			{
				$this->db->where($where, NULL, FALSE);
				
				$order_clause_sep = explode(' ', $order_clause);
				$_orderby = $order_clause_sep[0];
				$direction = $order_clause_sep[1];
				$orderby = explode('.',$_orderby);
				
				$this->db->order_by(end($orderby),$direction); 
				
				return $this->db->get($this->_table, $limit_offset, $limit_begin)->result();
			}
			
		}
	}
	
	/**
	 * Permite contar numero de registros en una consulta dado un where.
	 *
	 * $where: string con porcion SQL del where para obtener los registros
	 */
	public function count($where='1=1')
	{
		//Si el procedimientos almacenado count existe es llamado 
		if(array_key_exists('count', $this->sp_methods))
		{
			$result = $this->call($this->sp_methods['count'], array($this->db->escape($where)));
			if(count($result)>0){
				$row = $result[0];
				return isset($row->c) ? $row->c : 0;
			}
			return 0;
		}
		//Sino se hace de la manera tradicional obviando el where
		else
		{
			//si en el modelo se define un query_count
			if($this->query_count)
			{
				//$result = $this->db->query($this->query_count, array($where)); 
				$result = $this->db->query(sprintf($this->query_count,$where))->result();
				if(count($result)>0){
					$row = $result[0];
					return isset($row->c) ? $row->c : 0;
				}
				return 0;
			}
			//sino se utiliza active record de codeigniter
			else
			{
				$this->db->where($where);
				
				return $this->db->count_all($this->_table); //sin el where
			}
			
			
		}
	}
     
	/**
	 * Retorna un objeto vacio con datos asociados al Modelo.
	 *
	 */
	public function vacio($default = array())
	{
		$o = new stdClass();
		
		if(isset($this->model))
		{
			foreach($this->model as $field)
			{
				if(isset($default[$field['field']]))
				{
					$o->{$field['field']} = $default[$field['field']];
				}
				else
				{
					$o->{$field['field']} = $field['default'];
				}
				
			}
		}
		
		return $o;
	}
	
	/**
	 * Registra en la base de datos (INSERT & UPDATE).
	 * 
	 * $D: objeto con datos que se desean registrar
	 * $skip_validation: boolean que indica si se desea saltar validacion antes de registrar
	 */
	public function store(&$D, $skip_validation = FALSE)
	{
		$this->_remove_extra_data($D);
		
		if ($skip_validation === FALSE)
		{
			if ( ! $this->_run_validation($D))
			{
				$_errors = $this->_errors();
				return (object) array_merge((array) $D, (array) $_errors);
			}
		}
		
		$this->_run_before_store($D);
		
		$id = $D->id;
		unset($D->id);
		
		$D = $this->sanitizeAll($D);
		
		if($id<=0)
		{
			$this->db->insert($this->_table, $D);
			$id = $this->db->insert_id();
		}
		else
		{
			$this->db->update($this->_table, $D, array(
				'id' => $id
			));
		}
		
		$this->_run_after_store($D, $id);
		
		$this->skip_validation = FALSE;
		
		$r = $this->get($id);
		$r->success = TRUE;
		
		return $r;
	}
	
	/**
	 * Elimina un registro dado el id.
	 * 
	 * $id: int con id del registro que se desea eliminar
	 */
	public function delete($id)
	{
		$o = new stdClass();
		
		$key = explode('.', $this->primary_key);
		
		$key = count($key) == 2 ? $key[1] : $key[0];
		
		$r = $this->db->where($key, $id,FALSE)
							->delete($this->_table);
		
		//Desactivar db_debug para que funcione
		if(!$this->db->db_debug)
		{
			if($db_error = $this->db->_error_number())
			{
				if($db_error==1451)
				{
					$err = 'Existe otra entidad relacionada';
				}
				else
				{
					$err = 'No se pudo completar la tarea';
				}
			}
			
			if(!empty($err))
			{
				$o->error = $err;
				$o->success = FALSE;
				return $o;
			}
		}
		
		$o->success = $r ? TRUE : FALSE;
		
		return $o;
	}
	
	/**
	 * Ejecuta las validaciones en un cojunto de datos.
	 * 
	 * $data: objeto de datos que hay que validar
	 */
	private function _run_validation($data)
	{
		if ($this->skip_validation)
		{
			return TRUE;
		}

		if (empty($this->model))
		{
			return TRUE;
		}
		$this->load->library('form_validation');
		
		$array_data = (array) $data;
		$this->form_validation->set_data($array_data);
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		
		if (is_array($this->model))
		{
			$rulesModel = array();
			foreach($this->model as $atributo)
			{
				if(isset($atributo['rules']))
				{
					if(!empty($atributo['rules']))
					{
						array_push($rulesModel, $atributo);
					}
				}
			}
			
			$this->form_validation->set_rules($rulesModel);
			return $this->form_validation->run();
		}
		else
		{
			return $this->form_validation->run($this->model);
		}
	}
	
	/**
	 * Genera un objeto con los errores asociados a cada campo luego de una validacion.
	 * 
	 */
	private function _errors()
	{
		$errors = array();
		
		if (is_array($this->model))
		{
			foreach ($this->model as $m)
			{
				$f_error = form_error($m['field']);
				if(!empty($f_error))
				{
					$errors['success'] = FALSE;
					$i_error = $m['field'] . '_error';
					$errors[$i_error] = $f_error;
				}
			}
		}
		
		return (object) $errors;
	}
	
	/**
	 * Ejecuta metodos antes de ejecutar el registro de datos.
	 *
	 * @param array $data The array of actions
	 * @return mixed
	 */
	private function _run_before_store(&$D)
	{
		foreach ($this->before_create as $method)
		{
			$D = call_user_func_array(array($this, $method), array($D));
		}

		return $D;
	}
	
	/**
	 * Elimina los datos adicionales que no forman parte del modelo
	 * 
	 * @param array $data The array of actions
	 * @return mixed
	 */
	private function _remove_extra_data(&$D)
	{
		if (empty($this->model))
		{
			return $D;
		}
		
		//se recorre el objeto
		foreach($D as $key => $value)
		{
			if(is_array($this->model))
			{
				//bandera para eliminar atributo
				$must_remove = TRUE;
				
				foreach ($this->model as $m)
				{
					//si el atributo existe, se sale del ciclo y apaga la bandera de borrado
					if($m['field']==$key)
					{
						$must_remove = FALSE;
						break;
					}
				}
				
				//si la bandera de borrado sigue arriba se borra el elemento
				if($must_remove)
				{
					unset($D->$key);
				}	
			}	
		}
		
		return $D;
	}

	/**
	 * Ejecuta metodos luego de ejecutar el registro de datos..
	 *
	 * @param array $data The array of actions
	 * @param int $id
	 */
	private function _run_after_store(&$D, $id)
	{
		foreach ($this->after_create as $method)
		{
			call_user_func_array(array($this, $method), array($D, $id));
		}
	}
	
	/**
	 * Consigue nombre de la tabla asociada al modelo.
	 *
	 */
	private function _fetch_table()
	{
		if ($this->_table == NULL)
		{
			$this->load->helper('inflector');
			$class = preg_replace('/(_m|_model|_M|_Model)?$/', '', get_class($this));
			$this->_table = plural(strtolower($class));
		}
	}
}
