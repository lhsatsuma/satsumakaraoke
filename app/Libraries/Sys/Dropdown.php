<?php
namespace App\Libraries\Sys;

class Dropdown
{
	/*
	ARRAY OF DROPDOWN VALUES
	*/
	
	public $values = array();
	public function __construct()
	{
		//Nothing to do for now
		$locale = service('request')->getLocale();
		$locale_search = [$locale, 'en']; //Find first locale of system

		foreach($locale_search as $lang){
			$file_language = APPPATH . "Language/{$lang}/Dropdown.php";
			if(file_exists($file_language)){

				$this->values = include($file_language);

				//Search for extensions of dropdown for system
				$file_language = APPPATH . "Language/{$lang}/Dropdown_ext.php";
				
				if(file_exists($file_language)){
					$new_values = include($file_language);
					if(is_array($new_values)){
						$this->values = array_merge($this->values, $new_values);
					}
				}
				break;
			}
		}		
	}
	
	public function GetDropdown($what)
	{
		return $this->values[$what];
	}
	
	public function GetMultiDropdownHTML($what, $selected_val = null)
	{
		$html = '';

		if($what == 'timezones_availables' && empty($this->values[$what])){
			$this->values[$what] = $this->generate_timezone_list();
		}
		
		if(is_null($selected_val) || $selected_val == ''){
			$selected_blank = (is_null($selected_val) || empty($selected_val)) ? 'selected="selected"' : '';
		}
		$html .= "<option value='' {$selected_blank}>Selecione</option>";
		foreach($this->values[$what] as $key => $lbl){
			if($key == ''){
				continue;
			}
			$selected = (in_array($key, $selected_val)) ? 'selected="selected"' : '';
			
			$html .= "<option value='{$key}' {$selected}>{$lbl}</option>";
		}
		return $html;
	}
	
	public function GetDropdownHTML($what, $selected_val = null)
	{
		if($what == 'timezones_availables' && empty($this->values[$what])){
			$this->values[$what] = $this->generate_timezone_list();
		}
		$html = '';
		if(is_null($selected_val) || $selected_val == ''){
			$selected_blank = (is_null($selected_val) || $selected_val == '') ? 'selected="selected"' : '';
		}
		$html .= "<option value='' {$selected_blank}>Selecione</option>";
		foreach($this->values[$what] as $key => $lbl){
			if($key == ''){
				continue;
			}
			$selected = (!is_null($selected_val) && $selected_val !== '' && $selected_val == $key) ? 'selected="selected"' : '';
			$html .= "<option value='{$key}' {$selected}>{$lbl}</option>";
			
		}
		return $html;
	}
	
	public function GetMultiDropdownHTMLSelected($what, $selected = null)
	{
		$html = '';
		foreach($this->values[$what] as $key => $lbl){
			if($key == ''){
				continue;
			}
			if(in_array($key, $selected)){
				$html .= "<option value='{$key}'>{$lbl}</option>";
			}
		}
		return $html;
	}
	
	public function multitranslate($what, $value)
	{
		$return = array();
		foreach($value as $key => $select){
			if(isset($this->values[$what][$select])){
				$return[$key] = $this->values[$what][$select];
			}
			
		}
		return $return;
	}
	
	public function translate($what, $value)
	{
		if(isset($this->values[$what][$value])){
			return $this->values[$what][$value];
		}else{
			return $value;
		}
	}

	private function generate_timezone_list()
	{
		$cache_file = WRITEPATH . 'utils/timezone_list.json';
		if(file_exists($cache_file)){
			return json_decode(file_get_contents($cache_file), true);
		}

		static $regions = array(
			\DateTimeZone::AMERICA,
		);
	
		$timezones = array();
		foreach( $regions as $region )
		{
			$timezones = array_merge( $timezones, \DateTimeZone::listIdentifiers( $region ) );
		}
	
		$timezone_offsets = array();
		foreach( $timezones as $timezone )
		{
			$tz = new \DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
		}
	
		// sort timezone by offset
		asort($timezone_offsets);
	
		$timezone_list = array();
		foreach( $timezone_offsets as $timezone => $offset )
		{
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );
	
			$pretty_offset = "UTC${offset_prefix}${offset_formatted}";
	
			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}
		file_put_contents($cache_file, json_encode($timezone_list));
		return $timezone_list;
	}
	
}
?>