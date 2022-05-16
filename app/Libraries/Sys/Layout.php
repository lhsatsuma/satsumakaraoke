<?php
namespace App\Libraries\Sys;

class Layout
{
	//Sets fields maps because i can get from another controller or model
	public $fields_map = array();
	
	public $template = 'template';

	public $disabled_all = false;
	
	public function __construct($fields_map)
	{
		//Nothing to do for now
		$this->fields_map = $fields_map;
		$this->dropdown = new \App\Libraries\Sys\Dropdown();
        $this->dropdown->values['modules_list'] = getModules();
		$this->smarty = new \App\Libraries\Sys\SmartyCI(true);
		$this->session = getSession();
		$this->request = getFormData();
		
		$this->base_url = base_url().'/';
	}
	
	public function GetAllFields($record = array())
	{
		$this->its_filter = false;
		$return = array();
		foreach($this->fields_map as $field => $attrs){
			if(isset($attrs['dont_load_layout'])){
				continue;
			}
			$return[$attrs['type']][$field] = $this->GetField($field, $attrs, $record);
		}
		return $return;
	}
	
	public function GetAllFieldsFilter($record = array())
	{
		$this->its_filter = true;
		$return = array();
		foreach($this->fields_map as $field => $attrs){
			if((isset($attrs['dont_load_layout']) && !$this->disabled_all) || $attrs['force_dont_load_layout']){
				continue;
			}
			$return[$attrs['type']][$field] = $this->GetField($field, $attrs, $record);
		}
		return $return;
	}
	
	public function GetAllFieldsDetails($record = array())
	{
		$this->its_filter = false;
		$this->disabled_all = true;
		$return = array();
		foreach($this->fields_map as $field => $attrs){
			if((isset($attrs['dont_load_layout']) && !$this->disabled_all) || $attrs['force_dont_load_layout']){
				continue;
			}
			$return[$attrs['type']][$field] = $this->GetField($field, $attrs, $record);
		}
		return $return;
	}
	public function GetField($field, $attrs, $record)
	{
		$return = null;
		switch($attrs['type']){
			case 'file':
				$return= $this->GetFile($field, $record[$field], $attrs['parameter']);
				break;
			case 'bool':
				if(!is_null($record['raw'][$field])){
					$selected = $record['raw'][$field];
				}elseif(isset($attrs['default']) && !$this->its_filter){
					$selected = $attrs['default'];
				}elseif($this->its_filter && $record[$field]){
					$selected = 1;
				}else{
					$selected = '';
				}
				$return = $this->GetCheckbox($field, $selected);
				break;
			case 'currency':
				$return = $this->GetCurrency($field, $record[$field]);
				break;
			case 'cep':
				$return = $this->GetCep($field, $record[$field], $attrs['parameter']);
				break;
			case 'html':
				$return= $this->getHtml($field, $record[$field], $attrs['parameter']);
				break;
			case 'dropdown':
				if(!is_null($record['raw'][$field])){
					$selected = $record['raw'][$field];
				}elseif(isset($attrs['default']) && !$this->its_filter){
					$selected = $attrs['default'];
				}else{
					$selected = '';
				}
				if(!$selected && $record[$field]){
					$selected = $record[$field];
				}
				$return = $this->GetSelect($field, $selected);
				break;
			case 'multidropdown':
				if(!is_null($record['raw'][$field])){
					$selected = explode('|^|',$record['raw'][$field]);
				}elseif(isset($attrs['default'])){
					$selected = $attrs['default'];
				}else{
					$selected = array();
				}
				$return = $this->GetMultiselect($field, $selected);
				break;
			case 'related':
				$return = $this->GetRelated($field, $record, $attrs['parameter']);
				break;
			default:
				$return = $this->getGeneric(ucfirst($attrs['type']), $field, $record[$field]);
				break;
		}
		return $return;
	}
	
	public function getGeneric($type, $field, $selected = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/'.$type : $this->template.'/fields/'.$type;
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['value'] = $selected;
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function getHtml($field, $selected, $parameter = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Html' : $this->template.'/fields/Html';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['value'] = $selected;

		$parameter['selector'] = 'textarea[name="'.$field.'"]';
		$parameter['language'] = 'pt_BR';
		if($this->session->get('auth_user')['dark_mode']){
			$parameter['skin'] = "oxide-dark";
			$parameter['content_css'] = "dark";
		}
		$data['parameter'] = json_encode($parameter, JSON_PRETTY_PRINT);

		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetCurrency($field, $selected)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Currency' : $this->template.'/fields/Currency';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['value'] = ($selected) ? $selected : '0,00';
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetRelated($field, $record, $autocomplete_ajax)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Related' : $this->template.'/fields/Related';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['name_id'] = $field.'_name';
		$data['value_id'] = $record[$field];
		$data['value'] = $record[$field.'_name'];
		$data['link_detail'] = $this->base_url.$autocomplete_ajax['link_detail'].$data['value_id'];
		$data['custom_where'] = ($autocomplete_ajax['custom_where']) ? json_encode($autocomplete_ajax['custom_where']) : '{}';
		$data['callback_select'] = $autocomplete_ajax['callback_select'];
		$data['autocomplete_model'] = $autocomplete_ajax['model'];
		if(!empty($autocomplete_ajax['url'])){
			$data['autocomplete_ajax'] = $autocomplete_ajax['url'];
			$data['autocomplete_is_custom'] = 'true';
		}else{
			$data['autocomplete_ajax'] = $autocomplete_ajax['model'];
			$data['autocomplete_is_custom'] = 'false';
		}
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetCep($field, $selected = null, $parameters = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Cep' : $this->template.'/fields/Cep';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['value'] = $selected;
		
		
		$data['autofill_fields'] = ($parameters['autofill_fields']) ? json_encode($parameters['autofill_fields']) : '{}';
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetFile($field, $selected = null, $parameter = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/File' : $this->template.'/fields/File';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		
		if(!empty($selected)){
			$arquivos = new \App\Models\Arquivos\Arquivos();
			$arquivos->where['arquivo'] = $selected;
			$result = $arquivos->search(1,0)[0];
			if($result){
				$data['value'] = $result['id'];
				$data['filename_field'] = $result['campo'];
				$data['file_mimetype'] = $result['mimetype'];
				$data['value_name'] = $result['name'];
			}
		}
		$data['accept'] = ($parameter['accept']) ? $parameter['accept'] : '';
		$data['max_size'] = (float)($parameter['max_size']) ? $parameter['max_size'] : '';
		
		//Verify if max_size of field in model have compatibility with upload_max_filesize of php.ini
		$max_size_ini = (float)str_replace('M', '', ini_get('upload_max_filesize'))*1024;
		if($max_size_ini < $data['max_size']){
			$data['max_size'] = $max_size_ini;
		}

		//Verify if max_size of field in model have compatibility with post_max_size of php.ini
		$post_max_size_ini = (float)str_replace('M', '', ini_get('post_max_size'))*1024;
		if($post_max_size_ini < $data['max_size']){
			$data['max_size'] = $max_size_ini;
		}

		$max_size_mb = $data['max_size']/1024;
		if(is_float($max_size_mb)){
			$max_size_mb = number_format($max_size_mb, 2, ',', '.');
		}
		$data['max_size_mb'] = $max_size_mb;
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetCheckbox($field, $selected = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Checkbox' : $this->template.'/fields/Checkbox';
		
		$this->smarty->clearInputs($tpl);
		
		
		$data = $this->MountDefaultData($field);
		$data['value'] = ($selected) ? 'checked' : '';
		$data['value_hidden'] = ($selected) ? '1' : '0';
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetSelect($field, $selected = null)
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Select' : $this->template.'/fields/Select';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		$data['org_value'] = $selected;
		$data['value'] = $this->dropdown->translate($this->fields_map[$field]['parameter'], $selected);
		$data['options'] = $this->dropdown->GetDropdownHTML($this->fields_map[$field]['parameter'], $selected);
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GetMultiselect($field, $selected = array())
	{
		$tpl = ($this->disabled_all) ? $this->template.'/fields/disabled/Multiselect' : $this->template.'/fields/Multiselect';
		
		$this->smarty->clearInputs($tpl);
		
		$data = $this->MountDefaultData($field);
		
		$data['value'] = $this->dropdown->multitranslate($this->fields_map[$field]['parameter'], $selected);
		
		
		if(!$this->disabled_all){
			$data['options'] = $this->dropdown->GetMultiDropdownHTML($this->fields_map[$field]['parameter'], $selected);
		}else{
			$data['options'] = $this->dropdown->GetMultiDropdownHTMLSelected($this->fields_map[$field]['parameter'], $selected);
		}
		
		return $this->smarty->setData($data)->view($tpl);
	}
	
	private function MountDefaultData($field)
	{
		$condition_filter_html = '';
		if($this->its_filter){
			$postVal = getFormData($field.'_condition');
			if(!$postVal){
				$postVal = 'LIKE';
			}
			$condition_filter_html = $this->dropdown->GetDropdownHTML('conditions_filter', $postVal);
		}
		return array(
			'label' => $this->fields_map[$field]['lbl'],
			'name' => $field,
			'ext_attrs' => $this->MountAttrs($this->fields_map[$field]),
			'required' => ($this->its_filter) ? false : $this->fields_map[$field]['required'],
			'error' => $this->session->getFlashdata('save_data_errors')[$field],
			'disabled' => $this->disabled_all,
			'mask' => $this->fields_map[$field]['mask'],
			'app_url' => $this->base_url,
			'its_filter' => $this->its_filter,
			'condition_filter_html' => $condition_filter_html,
		);
	}
	
	private function MountAttrs($attrs)
	{
		$return = '';
		$c_return = '';
		if($attrs['required'] && !$this->its_filter){
			$return .= $c_return.'required="true"';
			$c_return = ' ';
		}
		if(!is_null($attrs['max'])){
			$return .= $c_return.'max="'.$attrs['max'].'"';
			$return .= $c_return.'maxlength="'.strlen($attrs['max']).'"';
			$c_return = ' ';
		}
		if(!is_null($attrs['max_length'])){
			$return .= $c_return.'maxlength="'.$attrs['max_length'].'"';
			$c_return = ' ';
		}
		if(!is_null($attrs['min_length']) && !$this->its_filter){
			$return .= $c_return.'minlength="'.$attrs['min_length'].'"';
			$c_return = ' ';
		}
		if(!is_null($attrs['cols'])){
			$return .= $c_return.'cols="'.$attrs['cols'].'"';
			$c_return = ' ';
		}
		if(!is_null($attrs['rows'])){
			$return .= $c_return.'rows="'.$attrs['rows'].'"';
			$c_return = ' ';
		}
		if(!empty($attrs['ext_attrs'])){
			$return .= $c_return.$attrs['ext_attrs'];
		}
		return $return;
	}
	
	public function GetGenericLista(string $location, Array $fields, Array $records, $has_edit = true)
	{
		$return_data = array();
		
		$return_data['has_edit'] = $has_edit;
		
		foreach($fields as $field => $ext_class){
			$return_data['table_heads'][$field] = array(
				'label' => $this->fields_map[$field]['lbl'],
				'class' => $ext_class,
			);
		}
			
		$return_data['table_tbody'] = array(
			'has_records' => true,
			'records' => array(),
		);
		if(!empty($records)){
			foreach($records as $record){

				$return_data['table_tbody']['records'][$record['id']]['id_value'] = $record['id'];
				$return_data['table_tbody']['records'][$record['id']]['location_href'] = $this->base_url.$location.$record['id'];

				foreach($fields as $field => $ext_class){
					$link_record = '';
					$name = $field;
					$value = $record[$field];
					if($this->fields_map[$field]['type'] == 'related'){
						$name = $field.'_name';
						$value = $record[$field.'_name'];
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$this->fields_map[$field]['parameter']['link_detail'].$record[$field];
						}
					}else{
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$location.$record['id'];
						}
					}
					
					$return_data['table_tbody']['records'][$record['id']]['columns'][$field] = array(
						'name' => $name,
						'value' => $value,
						'link_record' => $link_record,
					);
				}
			}
		}else{
			$return_data['table_tbody']['has_records'] = false;
		}
		
		return $return_data;
	}
	
	public function GetGenericListaAjax(string $subpanel_id, string $location, Array $fields, Array $records, $has_edit = false)
	{
		$return_data = array();

		$return_data['has_edit'] = $has_edit;
		$return_data['table_id'] = $subpanel_id;
		
		foreach($fields as $field => $ext_class){
			$return_data['table_heads'][$field] = array(
				'label' => $this->fields_map[$field]['lbl'],
				'class' => $ext_class,
			);
		}
			
		$return_data['table_tbody'] = array(
			'has_records' => true,
			'records' => array(),
		);
		if(!empty($records)){
			foreach($records as $record){
				$return_data['table_tbody']['records'][$record['id']]['id_value'] = $record['id'];
				$return_data['table_tbody']['records'][$record['id']]['location_href'] = $this->base_url.$location.$record['id'];
				
				foreach($fields as $field => $ext_class){
					
					$link_record = '';
					$name = $field;
					$value = $record[$field];
					if($this->fields_map[$field]['type'] == 'related'){
						$name = $field.'_name';
						$value = $record[$field.'_name'];
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$this->fields_map[$field]['parameter']['link_detail'].$record[$field];
						}
					}else{
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$location.$record['id'];
						}
					}
					$return_data['table_tbody']['records'][$record['id']]['columns'][$field] = array(
						'name' => $name,
						'value' => (is_bool($value) ? (($value) ? 'Sim' : 'Não') : $value),
						'link_record' => $link_record,
					);
				}
			}
		}else{
			$return_data['table_tbody']['has_records'] = false;
		}
		
		return $return_data;
	}
	public function mountLayoutMenu(string $type = 'template', array $breadcrumb = [])
	{
		$this->breadcrumbString = $breadcrumb;

		$dataLayout = [
			'perms' => [],
			'menu_arr' => [],
		];


		if($this->session->get('arr_menu_'.$type)){
			$menusSalvos = $this->session->get('arr_menu_'.$type);
		}else{
			$menus = new \App\Models\Menus\Menus();
			$menusSalvos = $menus->mountArrayMenus((($type == 'template') ? 'public' : 'admin'));

			//Get JSON for menu fixed of framework
			$file_json = $type. '_menu';
			$json_menus = json_decode(file_get_contents(APPPATH . 'Views/template/'.$file_json.'.json'), true);

			$menusSalvos = array_merge($menusSalvos, $json_menus);
		}


		foreach($menusSalvos as $key => $parent_menu){
			$parent_menu['id'] = 'menu_'.sliceString($parent_menu['lbl'], 10);
			//If has sub menus, consider the menu dont need permission
			if($parent_menu['subs']){
				$parent_menu_clone = $parent_menu;
				unset($parent_menu_clone['subs']);
				$dataLayout['menu_arr'][$key] = $parent_menu_clone;
				foreach($parent_menu['subs'] as $key_filho => $menu_filho){
					$menu_filho['id'] = 'submenu_'.$parent_menu['id'].'_'.sliceString($menu_filho['lbl'], 10);
					//Checking if sub menu needs permission
					$menu_filho['class_active'] = $this->checkBreadcrumb($menu_filho['url']);
					if($menu_filho['class_active']){
						$dataLayout['menu_arr'][$key]['class_active'] = 1;
					}

					if($menu_filho['perm']){
						$dataLayout['perms']['cod_'.$menu_filho['perm']] = hasPermission($menu_filho['perm']);
						if($dataLayout['perms']['cod_'.$menu_filho['perm']]['r']){
							$dataLayout['menu_arr'][$key]['subs'][$key_filho] = $menu_filho;
						}
					}else{
						$dataLayout['menu_arr'][$key]['subs'][$key_filho] = $menu_filho;
					}
				}
			}else{
				//Checking if menu needs permission
				$parent_menu['class_active'] = $this->checkBreadcrumb($parent_menu['url']);
				if($parent_menu['perm']){
					$dataLayout['perms']['cod_'.$parent_menu['perm']] = hasPermission($parent_menu['perm']);
					if($dataLayout['perms']['cod_'.$parent_menu['perm']]['r']){
						$dataLayout['menu_arr'][$key] = $parent_menu;
					}
				}else{
					$dataLayout['menu_arr'][$key] = $parent_menu;
				}
			}
		}
		
		//Fixing index array of sub menus
		foreach($dataLayout['menu_arr'] as $key_parent => $parent_menu){
			if($dataLayout['menu_arr'][$key_parent]['subs']){
				$dataLayout['menu_arr'][$key_parent]['subs'] = array_values($dataLayout['menu_arr'][$key_parent]['subs']);
			}elseif($menusSalvos[$key_parent]['subs']){
				//The menu dont have sub menus because of permission
				unset($dataLayout['menu_arr'][$key_parent]);
			}
		}

		//Fixing index array of menus
		$dataLayout['menu_arr'] = array_values($dataLayout['menu_arr']);

		$this->session->set('arr_menu_'.$type, $menusSalvos);

		return $dataLayout;
	}

	public function checkBreadcrumb(string $check)
	{
		$valid = 0;
		if(!empty($this->breadcrumbString)){
			//only 3 level (type, controller, method)
			$checkArr = explode('/', $check);
			if($checkArr[0] && $checkArr[0] == $this->breadcrumbString[0] &&
				$checkArr[1] && $checkArr[1] == $this->breadcrumbString[1]){
				$valid = 1;
			}
			
			//If check ends with "/", consider all methods
			if($checkArr[2] && $valid){
				if($checkArr[2] != $this->breadcrumbString[2]){
					$valid = 0;
				}
			}
		}

		return $valid;
	}
}
?>