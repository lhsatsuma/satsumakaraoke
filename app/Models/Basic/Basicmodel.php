<?php
namespace App\Models\Basic;

use CodeIgniter\Model;
use Exception;

class Basicmodel extends Model
{
	/*
	Table name for this model
	*/
	public $table = null;
	public $where = array();
	public $force_deletado = false;
	public $order_by = array();
	public $select = '*';
	public $join = array();
	public $group_by = array();
	public $last_error = '';
	public $id_by_name = false;
	
	//If have ID and you know that it doesn't exists on database, set's this with true
	public $new_with_id = false;
	
	/*
	Field map for this model, with definitions 
	@Example
	'id' => array(
		'lbl' => 'ID', //label for view
		'type' => 'varchar', //type of field
		'min_length' => 2, //min length value
		'max_length' => 255, //max length value
		'required' => true, //is required
		'validations' => 'callback_test', //Extras validations forms
		'dont_load_layout' => true, //If isset, LayoutLib won't load for Smarty
	),
	
	ALL BASE FIELDS!!! MUST HAVE IN EVERY TABLE OF DATABASE
	*/
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'nome' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		),
		'deletado' => array(
			'lbl' => 'Deletado',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'data_criacao' => array(
			'lbl' => 'Data Criação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_criacao' => array(
			'lbl' => 'Usuário Criação',
			'type' => 'related',
			'table' => 'caminhadas_usuarios',
			'dont_load_layout' => true,
		),
		'data_modificacao' => array(
			'lbl' => 'Data Modificação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_modificacao' => array(
			'lbl' => 'Usuário Modificação',
			'type' => 'related',
			'table' => 'caminhadas_usuarios',
			'dont_load_layout' => true,
		),
	);
	
	/*
	Fields ($this->f) defined for database porpourse
	*/
	public $f = array();
	
	
	/*
	ToDo: Fetched row and use always $this->name, $this->data_criacao for setting fields
	*/
	public $fetched = array();
	

	private $auth_user_id;

	public function __construct()
	{
		parent::__construct();
		$this->session = \Config\Services::session();
		$this->helper = $this->db->table($this->table);
		$this->dropdown = new \App\Libraries\DropdownLib();
		$this->request = \Config\Services::request();

		if($this->session->get('auth_user')){
			$this->auth_user_id = $this->session->get('auth_user')['id'];
		}elseif($this->session->get('auth_user_admin')){
			$this->auth_user_id = $this->session->get('auth_user_admin')['id'];
		}
	}
	
	/*
	Get record from DataBase with $this->f['id']
	*/
	public function get()
	{
		$this->helper->select($this->select);
		$this->helper->where('id', $this->f['id']);
		if(!$this->force_deletado){
			$this->helper->where('deletado', '0');
		}
		$query = $this->helper->get(1);
		
		if ($this->db->error()['message']) {
			$this->RegisterLastError("Error fetching total rows: ");
			return null;
        }
		
		if($query->resultID->num_rows > 0){
			return $query->getResult('array')[0];
		}
		return false;
	}

	public function getAtivo()
	{
		//Procurar o registro ativo
		$this->where['id'] = ['EQUAL', $this->f['id']];
		$this->where['status'] = ['EQUAL', 'ativo'];
		return $this->search(1)[0];
	}
	
	/*
	Fill F var with values except the control fields
	*/
	public function fillF(Array $fields)
	{
		foreach($fields as $field => $val){
			if($this->fields_map[$field] && !in_array($field, ['data_criacao', 'usuario_criacao', 'data_modificacao', 'usuario_modificacao'])){
				$this->f[$field] = $val;
			}
		}
	}
	
	/*
	Get related of field with ID
	This is why it's important to have "nome" on each table
	*/
	public function getRelated($table, $id)
	{
		$helper2 = $this->db->table($table);
		$helper2->select('id, nome');
		$helper2->where('id', $id);
		$query = $helper2->get(1);
		if ($this->db->error()['message']) {
			$this->RegisterLastError("Error fetching total rows: ");
			return null;
        }
		
		if($query->resultID->num_rows > 0){
			return $query->getResult('array')[0];
		}
		return false;
	}
	
	/*
	Total Rows with joins and where
	*/
	function total_rows($debug = false)
	{
        $result = 0;
        $this->helper->select('COUNT('.$this->table.'.id) AS total');
		$this->get_join();
        $this->get_where();
		// $this->get_order_by();
		try{
			$result = $this->helper->get();
			if($debug){
				echo '<Pre>';var_dump((string)$this->db->getLastQuery());exit;
			}
			return $result->getResult()[0]->total;
		}catch(Exception $e){
			if ($this->db->error()['message']) {
				$this->RegisterLastError("Error fetching total rows: ");
				return false;
			}
		}
    }
	
	
		
	/* Explain this it's a little too hard, so the main concept is:
	CodeIgniter database helper don't do conditions with parenthesys like:
	deletado = '0' OR (nome = 'test' AND tipo = 'test')
	So i improvised the code of where condition with begin and end parenthesys according to the $field
	Beta test: Operator on condition $value[0] = OPERATOR, $value[1] = ACTUAL VALUE
	There's a hard work to optimize this
	*/
	function get_where()
	{
		$return_string = '';
		$c_where = '';
		$c_where_or = '';
		if(!empty($this->where)){
			if(is_array($this->where)){
				foreach($this->where as $field => $value){
					$field = explode('RPLAFTER', $field)[0];
					if(is_array($value)){
						//VALUE ITS AN ARRAY, SO THERES OPERATOR
						switch($value[0]){
							case 'GREATHER_EQUAL':
								$value[1] = $this->antiSqlInjection($value[1]);
								$cond_value = " >= '{$value[1]}'";
								break;
							case 'IS_NULL':
								$cond_value = " IS NULL";
								break;
							case 'NOT_NULL':
								$cond_value = " IS NOT NULL";
								break;
							case 'LESS_EQUAL':
								$value[1] = $this->antiSqlInjection($value[1]);
								$cond_value = " <= '{$value[1]}'";
								break;
							case 'LESS':
								$cond_value = " < '{$value[1]}'";
								break;
							case 'GREATHER':
								$cond_value = " > '{$value[1]}'";
								break;
							case 'BETWEEN':
								$value[1] = $this->antiSqlInjection($value[1]);
								$value[2] = $this->antiSqlInjection($value[2]);
								$cond_value = " BETWEEN '{$value[1]}' AND '{$value[2]}'";
								break;
							case 'NOT IN':
								foreach($value[1] as $kVal => $vVal){
									$value[1][$kVal] = $this->antiSqlInjection($vVal);
								}
								$cond_value = " NOT IN ('".implode("','", $value[1])."')";
								break;
							case 'IN':
								foreach($value[1] as $kVal => $vVal){
									$value[1][$kVal] = $this->antiSqlInjection($vVal);
								}
								$cond_value = " IN ('".implode("','", $value[1])."')";
								break;
							case 'LIKE':
								$value[1] = $this->antiSqlInjection($value[1]);
								$cond_value = " LIKE '{$value[1]}'";
								break;
							case 'EQUAL':
								$value[1] = $this->antiSqlInjection($value[1]);
								$cond_value = " = '{$value[1]}'";
								break;
							case 'DIFF':
								$value[1] = $this->antiSqlInjection($value[1]);
								$cond_value = " <> '{$value[1]}'";
								break;
							default:
								//Operator it's not valid, throw error?
								break;
						}
					}else{
						$value = $this->antiSqlInjection($value);
						$cond_value = " = '{$value}'"; //DEFAULT EQUAL
					}
					
					if($value !== ''){
						if(strpos($field, 'BEGINENDORWHERE_') !== false){
							$new_field = str_replace('BEGINENDORWHERE_', '', $field);
							$return_string .= $c_where."(";
							$return_string .= $new_field.$cond_value;
							
							$return_string .= ")";
							$c_where_or = '';
							$c_where = ' AND ';

						}elseif(strpos($field, 'BEGINORWHERE_') !== false){
							$new_field = str_replace('BEGINORWHERE_', '', $field);
							
							$return_string .= $c_where."(";
							$return_string .= $c_where_or.$new_field.$cond_value;
							$c_where_or = ' OR ';
							$c_where = ' AND ';

						}elseif(strpos($field, 'MIDORWHERE_') !== false){
							$new_field = str_replace('MIDORWHERE_', '', $field);

							$return_string .= $c_where_or.$new_field.$cond_value;
							$c_where_or = ' OR ';
							$c_where = ' AND ';
						}elseif(strpos($field, 'ENDORWHERE_') !== false){
							$new_field = str_replace('ENDORWHERE_', '', $field);
							
							$return_string .= $c_where_or.$new_field.$cond_value;
							
							$return_string .= ")";
							$c_where = ' AND ';
							$c_where_or = '';
						}else{
							if(strpos($field, '.') == false){
								$field = $this->table.'.'.$field;
							}
							$return_string .= $c_where.$field.$cond_value;
							$c_where = "\nAND ";
						}
					}
				}
			}else{
				$return_string = $this->where;
				$c_where = "\nAND ";
			}
		}
		if(!$this->force_deletado){
			$return_string .= $c_where."{$this->table}.deletado = '0'";
			foreach($this->join as $table_name => $on){
				if(strpos($table_name, ' AS ') !== false){
					$new_table_name = explode(' AS ', $table_name);
				}else{
					$new_table_name = explode(' as ', $table_name);
				}
				$new_table_name = $new_table_name[count($new_table_name)-1];
				
				if(strpos($table_name, 'LEFTJOIN_') !== false
				|| strpos($table_name, 'RIGHTJOIN_') !== false){
					//If the join it's left or right, check deletado for null
					$new_table_name = str_replace(['LEFTJOIN_', 'RIGHTJOIN_'], '', $new_table_name);
					$return_string .= $c_where." ({$new_table_name}.deletado = '0' OR {$new_table_name}.deletado IS NULL)";
				}else{
					//If the join it's inner, check only deleted is 0
					$return_string .= $c_where."{$new_table_name}.deletado = '0'";
				}
				$c_where = "\nAND ";
			}
		}
		if(!empty($return_string)){
			$this->helper->where($return_string);
		}
        return true;
    }
	
	function get_order_by()
	{
		$comp = '';
		$return_string = '';
		foreach($this->order_by as $field => $value){
			if(strpos($field, '.') == false && strpos($field, '(*)') == false){
				$field = $this->table.'.'.$field;
			}
			if($field == $this->table.'.nome' && $this->id_by_name){
				$field = 'convert('.$field.', decimal)';
			}
			$return_string .= $comp.$field.' '.$value;
			$comp = ', ';
		}
		$this->helper->orderBy($return_string);
        return true;
    }
	
	function get_join()
	{
		foreach($this->join as $table_name => $on){
			if(strpos($table_name, 'LEFTJOIN_') !== false){
				$table_name = str_replace('LEFTJOIN_', '', $table_name);
				$this->helper->join($table_name, $on, 'left');
			}elseif(strpos($table_name, 'RIGHTJOIN_') !== false){
				$table_name = str_replace('RIGHTJOIN_', '', $table_name);
				$this->helper->join($table_name, $on, 'right');
			}else{
				$this->helper->join($table_name, $on);
			}
		}
	}
	
	function get_group_by()
	{
		$this->helper->groupBy($this->group_by);
	}
	
	function search($limit = 0, $page = 0, $debug = false)
	{
		if(!$limit){
			$limit = 0;
		}
		
		$offset = ($page > 1) ? ($page - 1) * $limit : 0;
		
		$this->helper->select($this->select);
		$this->get_join();
		
		$this->get_where();
		$this->get_order_by();
		$this->get_group_by();

		if($debug){
			echo '<pre>';var_dump($this->helper->getCompiledSelect());exit;
		}
		try{
			$q = $this->helper->get($limit, $offset);
			if($debug){
				echo '<Pre>';var_dump((string)$this->db->getLastQuery());exit;
			}
		
			if($q->resultID->num_rows > 0){
				return $q->getResult('array');
			}
			return array();
		}catch(Exception $e){
			if ($this->db->error()['message']) {
				$this->RegisterLastError("Error fetching total rows: ");
				return null;
			}
		}
	}
	
	public function before_save()
	{
		//Nothing to do in base
		return true;
	}
	
	public function after_save()
	{
		//Nothing to do in base
		return true;
	}
	public function formatDBValues($type, $value)
	{
		switch($type){
			case 'date':
				//Sometimes value it's already in format
				if(strpos($value, '/') !== false){
					$ex = explode('/', $value);
					$value = $ex[2].'-'.$ex[1].'-'.$ex[0];
				}
				break;
			case 'datetime':
				//Sometimes value it's already in format
				if(strpos($value, '/') !== false){
					$ex = explode(' ', $value);
					$ex2 = explode('/', $ex[0]);
					$value = $ex2[2].'-'.$ex2[1].'-'.$ex2[0].' '.$ex[1];
				}
				break;
			case 'password':
				//Well, let's assume the value it's not converted yet
				$value = md5($value);
				break;
			case 'bool':
				$value = ($value) ? true : false;
				break;
			case 'float':
			case 'currency':
				if(strpos($value, ',') !== false){
					$value = (float)str_replace(',', '.', str_replace('.', '', $value));
				}
				break;
			case 'int':
				$value = (int)($value);
				break;
			case 'link':
				//Fields of type link ALWAYS needs to begin with http:// or https://
				if(substr($value, 0, 7) !== 'http://' && substr($value, 0, 8) !== 'https://'){
					$value = 'http://'.$value;
				}
				break;
			case 'multidropdown':
				if(is_array($value)){
					$value = implode('|^|', $value);
				}
				break;
			default:
				break;
		}
		return $value;
	}
	public function saveRecord($execute_logics = true)
	{
		if($execute_logics){
			$returnBefore = $this->before_save();
			if($returnBefore !== false){
				foreach($this->fields_map as $field => $options){
					if($options['dont_save']){
						unset($this->f[$field]);
						continue;
					}
					$value = (isset($this->f[$field])) ? $this->f[$field] : null;
					if(isset($this->f[$field]) && in_array($options['type'], ['date', 'datetime']) && empty($value)){
						$this->f[$field] = null;
					}elseif(!is_null($value)){
						$this->f[$field] = $this->formatDBValues($options['type'], $value);
					}
				}
				if(!empty($this->f['id']) && !$this->new_with_id){
					$this->f['data_modificacao'] = date("Y-m-d H:i:s");
					$this->f['usuario_modificacao'] = $this->auth_user_id;
					$this->helper->where('id', $this->f['id']);
					$executed = $this->helper->update($this->f);
				}else{
					if(empty($this->f['id'])){
						if(empty($this->f['nome']) && $this->id_by_name){
							/*
							Let's mount "nome" field as an INT AUTO INCREMENT
							*/
							$this->where = array();
							$this->select = "MAX(CAST(nome as UNSIGNED))+1 as codigo_ult";
							$number = $this->search(1);
							$codigo = (int) $number[0]['codigo_ult'];
							if(empty($codigo)){
								$codigo = 1;
							}
							if(!empty($this->min_number_name) && $codigo < $this->min_number_name){
								$codigo = $this->min_number_name;
							}
							$this->f['nome'] = $codigo;
						}
						if(!$this->fields_map['id']['dont_generate']){
							$this->f['id'] = create_guid();
						}
					}
					$this->f['data_criacao'] = date("Y-m-d H:i:s");
					$this->f['usuario_criacao'] = $this->auth_user_id;
					$this->f['data_modificacao'] = date("Y-m-d H:i:s");
					$this->f['usuario_modificacao'] = $this->auth_user_id;
					$this->f['deletado'] = false;
					$executed = $this->helper->insert($this->f);
					
					//If ID it's an AUTO INCREMENT column, let's get the inserted ID
					if(empty($this->f['id']) && $this->fields_map['id']['dont_generate']){
						$this->f['id'] = $this->insertID();
					}
				}
				if($executed &&
				!$this->db->error()['message']){
					/* CHECK IF HAS FILES TO CREATE AND NEEDS TO EXECUTE AFTER_SAVE */
					if($execute_logics){
						$this->checkUploadFiles();
						$this->after_save();
					}
					return $this->f;
				}else{
					$this->RegisterLastError("Query failed: ");
				}
			}
		}

		return false;
	}
	
	public function checkUploadFiles()
	{
		$update = [];
		foreach($this->fields_map as $field => $attrs){
			if($attrs['type'] == 'file'){
				$value = $this->request->getFile($field);
				if($value && $value->isValid() && !$value->hasMoved()){
					if($this->table != 'arquivos'){
						$arquivos = new \App\Models\Arquivos\Arquivosmodel();
						if($this->f[$field]){
							$arquivos->f['id'] = $this->f[$field];
							$result = $arquivos->get();
							if($result){
								$arquivos->fillF($result);
							}
						}
						$arquivos->f['tabela'] = $this->table;
						$arquivos->f['campo'] = $field;
						$arquivos->f['mimetype'] = $value->getClientMimeType();
						$arquivos->f['nome'] = $value->getClientName();
						if(!$arquivos->f['tipo']){
							$arquivos->f['tipo'] = ($attrs['parameter']['private']) ? $attrs['parameter']['private'] : 'public';
						}
						$arquivos->f['registro'] = $this->f['id'];
						$arquivos->saveRecord();
						
						$arquivos->helper->where('id', $arquivos->f['id']);
						$arquivos->helper->update(['arquivo' => $arquivos->f['id']]);
						// $value->move(ROOTPATH.'public/uploads', $arquivos->f['id']);
						$this->mdl->f[$field] = $arquivos->f['id'];
						$update[$field] = $arquivos->f['id'];
					}else{
						if($this->f['id']){
							$update['id'] = $this->f['id'];
							$update['mimetype'] = $value->getClientMimeType();
							$update['nome'] = $value->getClientName();
							if(!$this->f['tipo']){
								$update['tipo'] = ($attrs['parameter']['private']) ? $attrs['parameter']['private'] : 'public';
							}
							$update['arquivo'] = $this->f['id'];
							$value->move(ROOTPATH.'public/uploads', $this->f['id']);
						}
					}
				}
			}
		}
		if(!empty($update)){
			$this->helper->where('id', $this->f['id']);
			$this->helper->update($update);
		}
	}
	
	public function DeleteRecord()
	{
		if(isset($this->f['id']) && !empty($this->f['id'])){
			$oldRecord = $this->get();
			$updateDeleted['deletado'] = true;
			$updateDeleted['data_modificacao'] = date("Y-m-d H:i:s");
			$updateDeleted['usuario_modificacao'] = $this->auth_user_id;
			$this->helper->where('id', $this->f['id']);

			$executed = $this->helper->update($updateDeleted);

			if($executed && !$this->db->error()['message']){
				if($oldRecord['arquivo']){
					unlink(ROOTPATH . 'public/uploads/'.$oldRecord['arquivo']);
				}
				return true;
			}else{
				$this->RegisterLastError("Query failed: ");
			}
		}
		return false;
	}
	
	public function RegisterLastError($msg, $log_error = true)
	{
		if($log_error){
			$msg .= (string)$this->db->getLastQuery(). ' | '.$this->db->error()['message'];
		}
		log_message('critical', $msg);
		$this->last_error = (string)$this->db->getLastQuery(). ' | '.$this->db->error()['message'];
	}
	
	public function formatRecordsView($result, $ignore_raw = false)
	{
		if($result){
			if($result[0]){
				foreach($result as $key => $fields){
					$result[$key] = $this->formatSingleRecordView($fields, $ignore_raw);
				}
			}else{
				$result = $this->formatSingleRecordView($result, $ignore_raw);
			}
			
		}
		return $result;
	}
	public function formatSingleRecordView($record, $ignore_raw = false){
		$record = (array) $record;
		foreach($this->fields_map as $field => $options){
			$value = $record[$field];
			if(!$ignore_raw){
				$record['raw'][$field] = $value;
			}
			if($options['type'] == 'currency'){
				$record[$field] = number_format($value, 2, ',', '.');
			}elseif($value){
				switch($options['type']){
					case 'datetime':
						$value = $this->formatDateTime($value);
						break;
					case 'date':
						$value = $this->formatDate($value);
						break;
					case 'time':
						$value = $this->formatTime($value);
						break;
					case 'int':
						$value = $this->formatInt($value);
						break;
					case 'float':
					case 'currency':
						$value = $this->formatFloat($value, $options['parameter']['precision']);
						break;
					case 'bool':
						$value = ($value) ? true : false;
						break;
					case 'dropdown':
						$value = $this->formatDropdown($options['parameter'], $value);
						break;
					case 'multidropdown':
						$value = $this->formatMultidropdown($options['parameter'], $value);
						break;
					case 'related':
						if($this->table == 'arquivos' && $field == 'registro'){
							$table_name = $record['tabela'];
						}else{
							$table_name = $options['table'];
						}
						if($value){
							$related = $this->getRelated($table_name, $value);
						}
						$field_nome = $field.'_nome';
						$record[$field_nome] = ($related) ? $related['nome'] : null;
						break;
					case 'file':
						if($this->table !== 'arquivos' && $value){
							$arquivosmdl = new \App\Models\Arquivos\Arquivosmodel();
							$arquivosmdl->f['id'] = $value;
							$result = $arquivosmdl->get();
							$field_nome = $field.'_nome';
							$record[$field_nome] = ($result) ? $result['nome'] : '';
						}
						break;
					default:
						break;
				}
				$record[$field] = $value;
			}elseif($options['type'] == 'bool'){
				$record[$field] = ($value) ? true : false;
			}
		}
		return $record;
	}
	
	public function formatFloat($value, $precision = 2)
	{
		return number_format($value, $precision, ',', '.');
	}
	
	public function formatDate($value)
	{
		return date("d/m/Y", strtotime($value));
	}
	
	public function formatDateTime($value)
	{
		return date("d/m/Y H:i", strtotime($value));
	}
	
	public function formatTime($value)
	{
		$value = explode(':', $value);
		return str_pad($value[0], 2, 0, STR_PAD_LEFT).':'.str_pad($value[1], 2, 0, STR_PAD_LEFT);
	}
	
	public function formatInt($value)
	{
		$value = preg_replace('/\D/', '', $value);
		return (int)$value;
	}
	
	public function formatDropdown($which, $value)
	{
		return $this->dropdown->translate($which, $value);
	}
	
	public function formatMultidropdown($which, $value)
	{
		return $this->dropdown->multitranslate($which, explode('|^|', $value));
	}

	public function formatDateExtend($value)
	{
        $formatter = new \IntlDateFormatter(
            'pt_BR',
			\IntlDateFormatter::LONG,
			\IntlDateFormatter::NONE,
			'America/Sao_Paulo',          
			\IntlDateFormatter::GREGORIAN
        );

		return $formatter->format(new \DateTime($value));
	}

	public function antiSqlInjection($val)
	{
		$val = str_replace("\\", "\\\\", $val);
		$val = str_replace("'", "\\'", $val);
		return $val;
	}
}
?>