<?php
namespace App\Models\Basic;

use CodeIgniter\Model;
use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Example;

class Basic extends Model
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
	

	public  $auth_user_id;

	public function __construct()
	{
		parent::__construct();
		$this->session = getSession();
		$this->helper = $this->db->table($this->table);
		$this->fields = new \App\Libraries\Sys\Fields();
		getFormData();

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
			$this->registerLastError("Error fetching total rows: ");
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
		if($this->fields_map['status']){
			$this->where['status'] = 'ativo';
		}
		return $this->search(1)[0];
	}

	public function getAtivos($select = null, $limit = 0)
	{
		//Procurar o registro ativo

		if($select){
			$this->select = $select;
		}
		
		if($this->fields_map['status']){
			$this->where['status'] = 'ativo';
		}elseif($this->fields_map['ativo']){
			$this->where['ativo'] = '1';
		}
		return $this->search($limit);
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
			$this->registerLastError("Error fetching total rows: ");
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
		$this->helper->resetQuery();
        $this->helper->select('COUNT('.$this->table.'.id) AS total');
		$this->get_join();
        $this->get_where();
		// $this->get_order_by();
		try{
			$result = $this->helper->get();
			log_message('debug', (string)$this->db->getLastQuery());
			return $result->getResult()[0]->total;
		}catch(Exception $e){
			if ($this->db->error()['message']) {
				$this->registerLastError("Error fetching total rows: ");
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
			$c_where = "\nAND ";
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
	
	function search($limit = 0, $page = 0)
	{
		if(!$limit){
			$limit = 0;
		}
		
		$offset = ($page > 1) ? ($page - 1) * $limit : 0;
		$this->helper->resetQuery();
		$this->helper->select($this->select);
		$this->get_join();
		
		$this->get_where();
		$this->get_order_by();
		$this->get_group_by();
		try{
			$q = $this->helper->get($limit, $offset);
			log_message('debug', (string)$this->db->getLastQuery());
			if($q->resultID->num_rows > 0){
				return $q->getResult('array');
			}
			return array();
		}catch(Exception $e){
			if ($this->db->error()['message']) {
				$this->registerLastError("Error fetching total rows: ");
				return null;
			}
		}
	}
	
	public function before_save(string $operation = null)
	{
		//Nothing to do in base
		return true;
	}
	
	public function after_save(string $operation = null)
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
		if(!empty($fOld['id']) && !$this->new_with_id){
			$operationHook = 'update';
		}else{
			$operationHook = 'insert';
		}
		if($execute_logics){
			$returnBefore = $this->before_save($operationHook);
			if($returnBefore !== false){
				$fOld = $this->f;
				foreach($this->fields_map as $field => $options){
					if($options['dont_save'] || $options['nondb']){
						unset($fOld[$field]);
						continue;
					}
					$value = (isset($fOld[$field])) ? $fOld[$field] : null;
					if(isset($fOld[$field]) && in_array($options['type'], ['date', 'datetime']) && empty($value)){
						$fOld[$field] = null;
					}elseif(!is_null($value)){
						$fOld[$field] = $this->formatDBValues($options['type'], $value);
					}
				}
				if(!empty($fOld['id']) && !$this->new_with_id){
					$fOld['data_modificacao'] = date("Y-m-d H:i:s");
					$fOld['usuario_modificacao'] = $this->auth_user_id;
					$this->helper->where('id', $fOld['id']);
					$executed = $this->helper->update($fOld);
				}else{
					if(empty($fOld['id'])){
						if(empty($fOld['nome']) && $this->id_by_name){
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
							$fOld['nome'] = $codigo;
						}
						if(!$this->fields_map['id']['dont_generate']){
							$fOld['id'] = create_guid();
						}
					}
					if(!isset($fOld['data_criacao'])){
						$fOld['data_criacao'] = date("Y-m-d H:i:s");
					}
					if(!isset($fOld['usuario_criacao'])){
						$fOld['usuario_criacao'] = $this->auth_user_id;
					}
					
					if(!isset($fOld['data_modificacao'])){
						$fOld['data_modificacao'] = date("Y-m-d H:i:s");
					}
					
					if(!isset($fOld['usuario_modificacao'])){
						$fOld['usuario_modificacao'] = $this->auth_user_id;
					}
					$fOld['deletado'] = false;
					$executed = $this->helper->insert($fOld);
					
					//If ID it's an AUTO INCREMENT column, let's get the inserted ID
					if(empty($fOld['id']) && $this->fields_map['id']['dont_generate']){
						$fOld['id'] = $this->insertID();
					}
				}
				if($executed &&
				!$this->db->error()['message']){
					/* CHECK IF HAS FILES TO CREATE AND NEEDS TO EXECUTE AFTER_SAVE */

					$this->f = array_merge($this->f, $fOld);
					
					if($execute_logics){
						$this->checkUploadFiles();
						$this->after_save($operationHook);
					}

					return $this->f;
				}else{
					$this->registerLastError("Query failed: ");
				}
			}
		}

		return false;
	}
	
	public function checkUploadFiles()
	{
		global $requestForm;
		$update = [];
		foreach($this->fields_map as $field => $attrs){
			if($attrs['type'] == 'file'){
				$value = $requestForm->getFile($field);
				if($value && $value->isValid() && !$value->hasMoved()){
					if($this->table != 'arquivos'){
						$arquivos = new \App\Models\Arquivos\Arquivos();
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
	
	public function deleteRecord()
	{
		if(isset($this->f['id']) && !empty($this->f['id'])){
			$oldRecord = $this->get();

			$this->before_save('delete');
			$updateDeleted['deletado'] = true;
			$updateDeleted['data_modificacao'] = date("Y-m-d H:i:s");
			$updateDeleted['usuario_modificacao'] = $this->auth_user_id;
			$this->helper->where('id', $this->f['id']);

			$executed = $this->helper->update($updateDeleted);

			if($executed && !$this->db->error()['message']){
				if($oldRecord['arquivo']){
					unlink(ROOTPATH . 'public/uploads/'.$oldRecord['arquivo']);
				}

				$this->after_save('delete');
				return true;
			}else{
				$this->registerLastError("Query failed: ");
			}
		}
		return false;
	}
	
	public function registerLastError($msg, $log_error = true)
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
						$value = $this->fields->formatDateTime($value);
						break;
					case 'date':
						$value = $this->fields->formatDate($value);
						break;
					case 'time':
						$value = $this->fields->formatTime($value);
						break;
					case 'int':
						$value = $this->fields->formatInt($value);
						break;
					case 'float':
					case 'currency':
						$value = $this->fields->formatFloat($value, $options['parameter']['precision']);
						break;
					case 'bool':
						$value = ($value) ? true : false;
						break;
					case 'dropdown':
						$value = $this->fields->formatDropdown($options['parameter'], $value);
						break;
					case 'multidropdown':
						$value = $this->fields->formatMultidropdown($options['parameter'], $value);
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
							$arquivosmdl = new \App\Models\Arquivos\Arquivos();
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

	public function antiSqlInjection($val)
	{
		$val = str_replace("\\", "\\\\", $val);
		$val = str_replace("'", "\\'", $val);
		return $val;
	}

	public function getIdxTable($idxName)
	{
		try{
			$tablesList = $this->listTables();
            $tableExists = in_array($this->table, $tablesList);
			if($tableExists){
				$q = $this->db->query("SHOW index FROM {$this->table} WHERE key_name = '{$idxName}'");
				if(is_object($q->resultID)){
					return $q->resultID->num_rows;
				}
			}
		}catch(Exception $e){
			
		}
		return 0;
	}

    public function getIdxSQL(int $key, $complete = false)
    {
		if(!isset($this->idx_table[$key])){
			return '';
		}
		$sqlRepair = "CREATE INDEX ";
		$idxSql = '';
		foreach($this->idx_table[$key] as $fieldIdx){
			$fieldIdx = str_replace('_', '', $fieldIdx);
			$idxSql .= ($idxSql) ? '_'.substr($fieldIdx, 0, 4) : substr($fieldIdx, 0, 4);
		}
		$idxSql .= $key;
		if(!$complete){
			if($this->getIdxTable($idxSql)){
				return '';
			}
		}
		$sqlRepair .= $idxSql;

		$sqlRepair .= " ON {$this->table} (";
		$idxSql = '';
		foreach($this->idx_table[$key] as $fieldIdx){
			$idxSql .= ($idxSql) ? ', '.$fieldIdx : $fieldIdx;
		}

		$sqlRepair .= $idxSql .' );';

		return $sqlRepair;
    }
}
?>