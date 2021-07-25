<?php
namespace App\Libraries\Sys;

class LayoutLib
{
	//Sets fields maps because i can get from another controller or model
	public $fields_map = array();
	
	public $template = 'template';

	public $disabled_all = false;
	
	public function __construct($fields_map)
	{
		//Nothing to do for now
		$this->fields_map = $fields_map;
		$this->dropdown = new \App\Libraries\DropdownLib();
        $this->dropdown->values['modules_list'] = getModules();
		$this->smarty = new \App\Libraries\Sys\SmartyCI(true);
		$this->session = \Config\Services::session();
		$this->request = \Config\Services::request();
		
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
				$return = $this->GetCheckbox($field, $record[$field]);
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
				}elseif(isset($attrs['default'])){
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
		$data['name_id'] = $field.'_nome';
		$data['value_id'] = $record[$field];
		$data['value'] = $record[$field.'_nome'];
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
			$arquivos = new \App\Models\Arquivos\Arquivosmodel();
			$arquivos->where['arquivo'] = $selected;
			$result = $arquivos->search(1,0)[0];
			if($result){
				$data['value'] = $result['id'];
				$data['filename_field'] = $result['campo'];
				$data['file_mimetype'] = $result['mimetype'];
				$data['value_nome'] = $result['nome'];
			}
		}
		$data['accept'] = ($parameter['accept']) ? $parameter['accept'] : '';
		$data['max_size'] = ($parameter['max_size']) ? $parameter['max_size'] : '';
		
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
		$data['value'] = ($selected) ? 'checked="true"' : '';
		
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
			'required' => $this->fields_map[$field]['required'],
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
		if(!is_null($attrs['min_length'])){
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
			foreach($records as $key => $record){
				$return_data['table_tbody']['records'][$record['id']]['id_value'] = $record['id'];
				$return_data['table_tbody']['records'][$record['id']]['location_href'] = $this->base_url.$location.$record['id'];
				$columns = array();
				foreach($fields as $field => $ext_class){
					$link_record = '';
					$name = $field;
					$value = $record[$field];
					if($this->fields_map[$field]['type'] == 'related'){
						$name = $field.'_nome';
						$value = $record[$field.'_nome'];
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$this->fields_map[$field]['parameter']['link_detail'].$record[$field];
						}
					}else{
						if($this->fields_map[$field]['link_record']){
							$link_record = $this->base_url.$location.$record['id'];
						}
					}
					// var_dump($field);
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
			foreach($records as $key => $record){
				$return_data['table_tbody']['records'][$record['id']]['id_value'] = $record['id'];
				$return_data['table_tbody']['records'][$record['id']]['location_href'] = $this->base_url.$location.$record['id'];
				$columns = array();
				foreach($fields as $field => $ext_class){
					
					$link_record = '';
					$name = $field;
					$value = $record[$field];
					if($this->fields_map[$field]['type'] == 'related'){
						$name = $field.'_nome';
						$value = $record[$field.'_nome'];
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
}
?>