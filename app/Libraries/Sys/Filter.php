<?php
namespace App\Libraries\Sys;

class Filter
{
	
	/* Filter fields and propertys */
	private $filters = [];
	
	/* Extra buttons like New Record */
	public $ext_buttons = [];
	
	/* Action of form submit */
	public $action;
	
	/* Template folder */
	public $template = 'template';
	
	/* Template file */
	public $template_name = 'Filter_template';
	
	/* Has generic filter search */
	public $generic_filter = false;
	
	/* Request Interface CI */
	public $request;

	protected string $file_language;
	
	public function __construct($request, Array $filters, string $class_name)
	{
		$this->smarty = new \App\Libraries\Sys\SmartyCI(true);
		$this->filters = $filters;
		$this->request = $request;
		$this->file_language = $class_name;
	}
	
	public function SetExtBtn($name, $button){
		$this->ext_buttons[$name] = $button;
	}
	
	public function display()
	{
		if(empty($this->action)){
			throw new \Exception('action filter is undefined');
		}
		$tpl = (($this->template) ? $this->template.'/' : '').$this->template_name;
		
		$this->smarty->clearInputs($tpl);
		
		$this->has_icon_advanced_filter = false;
		$data = [];
		$data['filters'] = $this->filters;
		$data['id_filter'] = $this->id_filter;
		$data['page'] = $this->page;
		$data['generic_filter'] = $this->generic_filter;
		$data['search_generic_filter'] = getFormData('search_generic_filter');
		$data['order_by'] = $this->order_by;
		
		$data['ext_buttons'] = $this->ext_buttons;
		$data['action'] = $this->action;
		$data['modal_filter_advanced'] = $this->GenerateFilterAdvanced();
		$data['icon_filter_advanced'] = $this->has_icon_advanced_filter;
		return $this->smarty->setData($data)->view($tpl);
	}
	
	public function GenerateFilterAdvanced()
	{
		$fields_map = [];
		$record = [];
		foreach($this->filters as $field => $attrs){
			if($attrs['options']['type'] == 'related'){
				$fields_map['search_'.$field] = $attrs['options'];
				$record['search_'.$field] = $attrs['value'];
				$record['search_'.$field.'_name'] = getFormData('search_'.$field.'_name');
			}
			$fields_map['search_'.$field] = $attrs['options'];
			$record['search_'.$field] = $attrs['value'];
			if($record['search_'.$field]){
				$this->has_icon_advanced_filter = true;
			}
		}
		$this->layout = new \App\Libraries\Sys\Layout($fields_map, $this->file_language);
		
		$types_layout = $this->layout->GetAllFieldsFilter($record);
		$html = '';
		
		foreach($types_layout as $fields){
			foreach($fields as $html_field){
				$html .= '<div class="form-group">';
				$html .= $html_field;
				$html .= '</div>';
			}
		}
		
		return $html;
	}
}
?>