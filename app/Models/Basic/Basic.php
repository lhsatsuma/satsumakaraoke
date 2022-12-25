<?php
namespace App\Models\Basic;

use CodeIgniter\Model;
use Exception;
use App\Libraries\Sys\Fields;
use CodeIgniter\Database\BaseBuilder;
use App\Models\Files\Files;

class Basic extends Model
{
	/*
	Table name for this model
	*/
	public $table = null;
	public array | string $where = [];
	public bool $force_deleted = false;
	public array $order_by = [];
	public string $select = '*';
	public array $join = [];
	public string $group_by = '';
	public string $last_error = '';
	public bool $id_by_name = false;
	public bool $page_as_offset = false;
	
	//If you have ID and you know that it doesn't exist on database, set's this with true
	public bool $new_with_id = false;
	
	/*
	Field map for this model, with definitions 
	@Example
	'id' => [
		'lbl' => 'ID', //label for view
		'type' => 'varchar', //type of field
		'min_length' => 2, //min length value
		'max_length' => 255, //max length value
		'required' => true, //is required
		'validations' => 'callback_test', //Extras validations forms
		'dont_load_layout' => true, //If isset, LayoutLib won't load for Smarty
	],
	
	ALL BASE FIELDS!!! MUST HAVE IN EVERY TABLE OF DATABASE
	*/
	public array $fields_map = [
		'id' => [
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		],
		'name' => [
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		],
		'deleted' => [
			'lbl' => 'deleted',
			'type' => 'bool',
			'dont_load_layout' => true,
		],
		'date_created' => [
			'lbl' => 'Data Criação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		],
		'user_created' => [
			'lbl' => 'Usuário Criação',
			'type' => 'related',
			'table' => 'caminhadas_usuarios',
			'dont_load_layout' => true,
		],
		'date_modified' => [
			'lbl' => 'Data Modificação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		],
		'user_modified' => [
			'lbl' => 'Usuário Modificação',
			'type' => 'related',
			'table' => 'caminhadas_usuarios',
			'dont_load_layout' => true,
		],
	];
	
	/*
	Fields ($this->f) defined for database porpourse
	*/
	public array $f = [];
	
	
	/*
	ToDo: Fetched row and use always $this->name, $this->date_created for setting fields
	*/
	public array $fetched = [];
	

	public string | null $auth_user_id;
    public BaseBuilder $helper;
    public mixed $session;
    public Fields $fields;

    public function __construct()
	{
		parent::__construct();
		$this->session = getSession();
		$this->helper = $this->db->table($this->table);
		$this->fields = new Fields();
		getFormData();

		if($this->session->get('auth_user')){
			$this->auth_user_id = $this->session->get('auth_user')['id'];
		}elseif($this->session->get('auth_user_admin')){
			$this->auth_user_id = $this->session->get('auth_user_admin')['id'];
		}
	}

	public function reset()
	{
		$this->where = [];
		$this->force_deleted = false;
		$this->order_by = [];
		$this->select = '*';
		$this->join = [];
		$this->group_by = '';
	}
	
	/*
	Get record from DataBase with $this->f['id']
	*/
	public function get()
	{
		$this->helper->select($this->select);
		$this->helper->where('id', $this->f['id']);
		if(!$this->force_deleted) {
            $this->helper->where('deleted', '0');
        }

		try{
			$query = $this->helper->get(1);
			log_message('debug', (string)$this->db->getLastQuery());
			if($query->resultID->num_rows > 0) return $query->getResult('array')[0];
		}catch(Exception $e){
			$this->registerLastError('Error fetching total rows: '.$e->getMessage());
			return null;
		}
		return false;
	}

	public function getActive()
	{
		//Procurar o registro ativo
		$this->where['id'] = ['EQUAL', $this->f['id']];
		if($this->fields_map['status']){
			$this->where['status'] = 'ativo';
		}
		return $this->search(1)[0];
	}

	public function getActives($select = null, $limit = 0)
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
			if($this->fields_map[$field] && !in_array($field, ['date_created', 'user_created', 'date_modified', 'user_modified'])){
				$this->f[$field] = $val;
			}
		}
	}
	
	/*
	Get related of field with ID
	This is why it's important to have "name" on each table
	*/
	public function getRelated($table, $id)
	{
		$helper2 = $this->db->table($table);
		$helper2->select('id, name');
		$helper2->where('id', $id);
		$query = $helper2->get(1);
		log_message('debug', (string)$this->db->getLastQuery());
		if ($this->db->error()['message']) {
			$this->registerLastError('Error fetching total rows: ');
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
	public function total_rows()
	{
		$this->helper->resetQuery();
        $this->helper->select('COUNT('.$this->table.'.id) AS total');
		$this->get_join();
        $this->get_where();

		try{
			$result = $this->helper->get();
			log_message('debug', (string)$this->db->getLastQuery());
			return $result->getResult()[0]->total;
		}catch(Exception $e){
			if ($this->db->error()['message']) {
				$this->registerLastError('Error fetching total rows: '.$e->getMessage());
				return false;
			}
		}
        return null;
    }
	
	
		
	/* Explain this it's a little too hard, so the main concept is:
	CodeIgniter database helper don't do conditions with parenthesys like:
	deleted = '0' OR (name = 'test' AND tipo = 'test')
	So i improvised the code of where condition with begin and end parenthesys according to the $field
	Beta test: Operator on condition $value[0] = OPERATOR, $value[1] = ACTUAL VALUE
	There's a hard work to optimize this
	*/
	public function get_where()
	{
		$return_string = '';
		$c_where = '';
		$c_where_or = '';
        $cond_value = '';
		if(!empty($this->where)){
			if(is_array($this->where)){
				foreach($this->where as $field => $value){
					$field = explode('RPLAFTER', $field)[0];
					if(is_array($value)){
						//VALUE ITS AN ARRAY, SO THERES OPERATOR
						switch($value[0]){
							case 'GREATHER_EQUAL':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$cond_value = " >= '{$value[1]}'";
								break;
							case 'IS_NULL':
								$cond_value = ' IS NULL';
								break;
							case 'NOT_NULL':
								$cond_value = ' IS NOT NULL';
								break;
							case 'LESS_EQUAL':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$cond_value = " <= '{$value[1]}'";
								break;
							case 'LESS':
								$cond_value = " < '{$value[1]}'";
								break;
							case 'GREATHER':
								$cond_value = " > '{$value[1]}'";
								break;
							case 'BETWEEN':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$value[2] = Basic::antiSqlInjection($value[2]);
								$cond_value = " BETWEEN '{$value[1]}' AND '{$value[2]}'";
								break;
							case 'NOT IN':
								foreach($value[1] as $kVal => $vVal){
									$value[1][$kVal] = Basic::antiSqlInjection($vVal);
								}
								$cond_value = " NOT IN ('".implode("','", $value[1])."')";
								break;
							case 'IN':
								foreach($value[1] as $kVal => $vVal){
									$value[1][$kVal] = Basic::antiSqlInjection($vVal);
								}
								$cond_value = " IN ('".implode("','", $value[1])."')";
								break;
							case 'LIKE':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$cond_value = " LIKE '{$value[1]}'";
								break;
							case 'EQUAL':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$cond_value = " = '{$value[1]}'";
								break;
							case 'DIFF':
								$value[1] = Basic::antiSqlInjection($value[1]);
								$cond_value = " <> '{$value[1]}'";
								break;
							default:
								//Operator it's not valid, throw error?
								break;
						}
					}else{
						$value = Basic::antiSqlInjection($value);
						$cond_value = " = '{$value}'"; //DEFAULT EQUAL
					}

					if($value !== ''){
						if(str_contains($field, 'BEGINENDORWHERE_')){
							$new_field = str_replace('BEGINENDORWHERE_', '', $field);
							$return_string .= $c_where. '(';
							$return_string .= $new_field.$cond_value;

							$return_string .= ')';
							$c_where_or = '';
							$c_where = ' AND ';

						}elseif(str_contains($field, 'BEGINORWHERE_')){
							$new_field = str_replace('BEGINORWHERE_', '', $field);

							$return_string .= $c_where. '(';
							$return_string .= $c_where_or.$new_field.$cond_value;
							$c_where_or = ' OR ';
							$c_where = ' AND ';

						}elseif(str_contains($field, 'MIDORWHERE_')){
							$new_field = str_replace('MIDORWHERE_', '', $field);

							$return_string .= $c_where_or.$new_field.$cond_value;
							$c_where_or = ' OR ';
							$c_where = ' AND ';
						}elseif(str_contains($field, 'ENDORWHERE_')){
							$new_field = str_replace('ENDORWHERE_', '', $field);

							$return_string .= $c_where_or.$new_field.$cond_value;

							$return_string .= ')';
							$c_where = ' AND ';
							$c_where_or = '';
						}else{
							if(!str_contains($field, '.')){
								$field = $this->table.'.'.$field;
							}
							$return_string .= $c_where.$field.$cond_value;
							$c_where = ' AND ';
						}
					}
				}
			}else{
				$return_string = $this->where;
				$c_where = ' AND ';
			}
		}
		if(!$this->force_deleted){
			$return_string .= $c_where."{$this->table}.deleted = '0'";
			$c_where = ' AND ';
			foreach($this->join as $table_name => $on){
				if(str_contains($table_name, ' AS ')){
					$new_table_name = explode(' AS ', $table_name);
				}else{
					$new_table_name = explode(' as ', $table_name);
				}
				$new_table_name = $new_table_name[count($new_table_name)-1];
				
				if(str_contains($table_name, 'LEFTJOIN_')
				|| str_contains($table_name, 'RIGHTJOIN_')){
					//If the join it's left or right, check deleted for null
					$new_table_name = str_replace(['LEFTJOIN_', 'RIGHTJOIN_'], '', $new_table_name);
					$return_string .= $c_where." ({$new_table_name}.deleted = '0' OR {$new_table_name}.deleted IS NULL)";
				}else{
					//If the join it's inner, check only deleted is 0
					$return_string .= $c_where."{$new_table_name}.deleted = '0'";
				}
			}
		}
		if(!empty($return_string)){
			$this->helper->where($return_string);
		}
        return $return_string;
    }
	
	public function get_order_by()
	{
		$comp = '';
		$return_string = '';
		foreach($this->order_by as $field => $value){
			if(!str_contains($field, '.') && !str_contains($field, '(*)')){
				$field = $this->table.'.'.$field;
			}
            $table_compare = $this->table.'.name';
			if($field == $table_compare && $this->id_by_name){
				$field = 'convert('.$field.', decimal)';
			}
			$return_string .= $comp.$field.' '.$value;
			$comp = ', ';
		}
		$this->helper->orderBy($return_string);
        return $return_string;
    }
	
	public function get_join()
	{
		foreach($this->join as $table_name => $on){
			if(str_contains($table_name, 'LEFTJOIN_')){
				$table_name = str_replace('LEFTJOIN_', '', $table_name);
				$this->helper->join($table_name, $on, 'left');
			}elseif(str_contains($table_name, 'RIGHTJOIN_')){
				$table_name = str_replace('RIGHTJOIN_', '', $table_name);
				$this->helper->join($table_name, $on, 'right');
			}else{
				$this->helper->join($table_name, $on);
			}
		}
	}
	
	public function get_group_by()
	{
		$this->helper->groupBy($this->group_by);
		return $this->group_by;
	}
	
	public function search(int $limit = 0, int $page = 0)
	{
		if(!$limit){
			$limit = 0;
		}
		if(!$this->page_as_offset){
			$offset = $page > 1 ? ($page - 1) * $limit : 0;
		}else{
			$offset = $page;
		}
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
			return [];
		}catch(Exception $e){
			log_message('debug', (string)$this->db->getLastQuery());
			if ($this->db->error()['message']) {
				$this->registerLastError('Error fetching total rows: '.$e->getMessage());
				return null;
			}
		}
        return null;
	}
	
	public function before_save(string $operation = null): bool
    {
		return true;
	}
	
	public function after_save(string $operation = null): bool
    {
		//Nothing to do in base
		return true;
	}

    public static function formatDBValues(string $type, $value)
    {
        switch($type){
            case 'date':
                //Sometimes value it's already in format
                if(str_contains($value, '/')){
                    $ex = explode('/', $value);
                    $value = $ex[2].'-'.$ex[1].'-'.$ex[0];
                }
                break;
            case 'datetime':
                //Sometimes value it's already in format
                if(str_contains($value, '/')){
                    $ex = explode(' ', $value);
                    $ex2 = explode('/', $ex[0]);
                    $value = $ex2[2].'-'.$ex2[1].'-'.$ex2[0].' '.$ex[1];
                }
                break;
            case 'password':
                //Well, let's assume the value it's not converted yet
                $value = encrypt_pass($value);
                break;
            case 'bool':
                $value = (bool) $value;
                break;
            case 'float':
            case 'currency':
                if(str_contains($value, ',')){
                    $value = (float)str_replace(',', '.', str_replace('.', '', $value));
                }
                break;
            case 'int':
                $value = (int)$value;
                break;
            case 'link':
                //Fields of type link ALWAYS needs to begin with http:// or https://
                if(!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')){
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
		$fOld = $this->f;
		if(!empty($fOld['id']) && !$this->new_with_id){
			$operationHook = 'update';
		}else{
			$operationHook = 'insert';
		}
		if($execute_logics){
			$returnBefore = $this->before_save($operationHook);
			if($returnBefore === true){
				foreach($this->fields_map as $field => $options){
					if($options['dont_save'] || $options['nondb']){
						unset($fOld[$field]);
						continue;
					}
					$value = $fOld[$field] ?? null;
					if(isset($fOld[$field]) && in_array($options['type'], ['date', 'datetime']) && empty($value)){
						$fOld[$field] = null;
					}elseif(!is_null($value)){
						$fOld[$field] = Basic::formatDBValues($options['type'], $value);
					}
				}
				if(!empty($fOld['id']) && !$this->new_with_id){
					$fOld['date_modified'] = date('Y-m-d H:i:s');
					$fOld['user_modified'] = $this->auth_user_id;
					$this->helper->where('id', $fOld['id']);
					$executed = $this->helper->update($fOld);
				}else{
					if(empty($fOld['id'])){
						if(empty($fOld['name']) && $this->id_by_name){
							/*
							Let's mount "name" field as an INT AUTO INCREMENT
							*/
							$this->where = [];
							$this->select = 'MAX(CAST(name as UNSIGNED))+1 as codigo_ult';
							$number = $this->search(1);
							$codigo = (int) $number[0]['codigo_ult'];
							if(empty($codigo)){
								$codigo = 1;
							}
							if(!empty($this->min_number_name) && $codigo < $this->min_number_name){
								$codigo = $this->min_number_name;
							}
							$fOld['name'] = $codigo;
						}
						if(!$this->fields_map['id']['dont_generate']){
							$fOld['id'] = create_guid();
						}
					}
					if(!isset($fOld['date_created'])){
						$fOld['date_created'] = date('Y-m-d H:i:s');
					}
					if(!isset($fOld['user_created'])){
						$fOld['user_created'] = $this->auth_user_id;
					}
					
					if(!isset($fOld['date_modified'])){
						$fOld['date_modified'] = date('Y-m-d H:i:s');
					}
					
					if(!isset($fOld['user_modified'])){
						$fOld['user_modified'] = $this->auth_user_id;
					}
					$fOld['deleted'] = false;
					$executed = $this->helper->insert($fOld);
					
					//If ID it's an AUTO INCREMENT column, let's get the inserted ID
					if(empty($fOld['id']) && $this->fields_map['id']['dont_generate']){
						$fOld['id'] = $this->db->insertID();
					}
				}
				if($executed &&
				!$this->db->error()['message']){
					/* CHECK IF IT HAS FILES TO CREATE AND NEEDS TO EXECUTE AFTER_SAVE */

					$this->f = array_merge($this->f, $fOld);
					
					if($execute_logics){
						$this->checkUploadFiles();
						if($this->after_save($operationHook) === false){
							log_message('critical', 'Unable to complete after_save...');
							return false;
						}
					}
					$this->new_with_id = false;
					return $this->f;
				}else{
					$this->registerLastError('Query failed: ');
				}
			}else{
				log_message('critical', 'Unable to complete before_save...');
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
						$file = new Files();
						if($this->f[$field]){
							$file->f['id'] = $this->f[$field];
							$result = $file->get();
							if($result){
								$file->fillF($result);
							}
						}
						$file->f['tabela'] = $this->table;
						$file->f['campo'] = $field;
						$file->f['mimetype'] = $value->getClientMimeType();
						$file->f['name'] = $value->getClientName();
						if(!$file->f['tipo']){
							$file->f['tipo'] = $attrs['parameter']['private'] ?? 'public';
						}
						$file->f['registro'] = $this->f['id'];
						$file->saveRecord();
						
						$file->helper->where('id', $file->f['id']);
						$file->helper->update(['arquivo' => $file->f['id']]);
						// $value->move(ROOTPATH.'public/uploads', $file->f['id']);
						$this->f[$field] = $file->f['id'];
						$update[$field] = $file->f['id'];
					}elseif($this->f['id']){
                        $update['id'] = $this->f['id'];
                        $update['mimetype'] = $value->getClientMimeType();
                        $update['name'] = $value->getClientName();
                        if(!$this->f['tipo']){
                            $update['tipo'] = $attrs['parameter']['private'] ?? 'public';
                        }
                        $update['arquivo'] = $this->f['id'];
                        $value->move(ROOTPATH.'public/uploads', $this->f['id']);
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
            $updateDeleted = [];
			$updateDeleted['deleted'] = true;
			$updateDeleted['date_modified'] = date('Y-m-d H:i:s');
			$updateDeleted['user_modified'] = $this->auth_user_id;
			$this->helper->where('id', $this->f['id']);

			$executed = $this->helper->update($updateDeleted);

			if($executed && !$this->db->error()['message']){
				if($oldRecord['arquivo']){
					unlink(ROOTPATH . 'public/uploads/'.$oldRecord['arquivo']);
				}

				$this->after_save('delete');
				return true;
			}else{
				$this->registerLastError('Query failed: ');
			}
		}
		return false;
	}
	
	public function registerLastError(string $msg, bool $log_error = true)
	{
		if($log_error){
			$msg .= $this->db->getLastQuery(). ' | '.$this->db->error()['message'];
		}
		log_message('critical', $msg);
		$this->last_error = $this->db->getLastQuery(). ' | '.$this->db->error()['message'];
	}
	
	public function formatRecordsView($result, bool $ignore_raw = false)
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
	public function formatSingleRecordView($record, bool $ignore_raw = false){
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
						$value = (bool)$value;
						break;
					case 'dropdown':
						$value = $this->fields->formatDropdown($options['parameter'], $value);
						break;
					case 'multidropdown':
						$value = $this->fields->formatMultidropdown($options['parameter'], $value);
						break;
					case 'related':
						if(isset($record[$field])){
							if($this->table == 'arquivos' && $field == 'registro'){
								$table_name = $record['tabela'];
							}else{
								$table_name = $options['table'];
							}
							if($value){
								$related = $this->getRelated($table_name, $value);
							}else{
								$related = null;
							}
							$field_name = $field.'_name';
							$record[$field_name] = $related ? $related['name'] : null;
						}
						break;
					case 'file':
						if($this->table !== 'arquivos' && $value){
							$filemdl = new Files();
							$filemdl->f['id'] = $value;
							$result = $filemdl->get();
							$field_name = $field.'_name';
							$record[$field_name] = $result ? $result['name'] : '';
						}
						break;
					default:
						break;
				}
				$record[$field] = $value;
			}elseif($options['type'] == 'bool'){
				$record[$field] = (bool)$value;
			}
		}
		return $record;
	}

    public static function antiSqlInjection($val)
    {
        $val = str_replace("\\", "\\\\", $val);
        return str_replace("'", "\\'", $val);
    }

	public function getIdxTable(string $idxName)
	{
		try{
			$tablesList = $this->db->listTables();
            $tableExists = in_array($this->table, $tablesList);
			if($tableExists){
				$q = $this->db->query("SHOW index FROM {$this->table} WHERE key_name = '{$idxName}'");
				if(is_object($q->resultID)){
					return $q->resultID->num_rows;
				}
			}
		}catch(Exception $e){
			log_message('critical', 'Error searching Indexes for '.$this->table.': '.$e->getMessage());
			return 0;
		}
		return 0;
	}

    public function getIdxSQL(int $key, $complete = false)
    {
		if(!isset($this->idx_table[$key])){
			return '';
		}
		$sqlRepair = 'CREATE INDEX ';
		$idxSql = '';
		foreach($this->idx_table[$key] as $fieldIdx){
			$fieldIdx = str_replace('_', '', $fieldIdx);
			$idxSql .= $idxSql ? '_'.substr($fieldIdx, 0, 4) : substr($fieldIdx, 0, 4);
		}
		$idxSql .= $key;
		if(!$complete && $this->getIdxTable($idxSql)){
			return '';
		}
		$sqlRepair .= $idxSql;

		$sqlRepair .= " ON {$this->table} (";
		$idxSql = '';
		foreach($this->idx_table[$key] as $fieldIdx){
			$idxSql .= $idxSql ? ', '.$fieldIdx : $fieldIdx;
		}

		$sqlRepair .= $idxSql .' );';

		return $sqlRepair;
    }
}