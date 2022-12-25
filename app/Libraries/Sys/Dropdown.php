<?php
namespace App\Libraries\Sys;

class Dropdown
{
	/*
	ARRAY OF DROPDOWN VALUES
	*/
	
	public $values = [];
	public function __construct()
	{
		$locale = service('request')->getLocale();
		$get_file = 'Dropdown_ext';
		if(!file_exists(APPPATH . "Language/{$locale}/Public/{$get_file}.php")){
			$get_file = 'Dropdown';
		}

		$this->values = translate('', 'Public.'.$get_file);
	}
	
	public function GetDropdown($what)
	{
		return $this->values[$what];
	}
	
	public function GetMultiDropdownHTML($what, $selected_val = null)
	{
		$html = '';

		if($what == 'timezones_availables' && empty($this->values[$what])){
			$this->values[$what] = Dropdown::generate_timezone_list();
		}
        $selected_blank = empty($selected_val) ? 'selected="selected"' : '';

		$html .= "<option value='' {$selected_blank}>Selecione</option>";
		foreach($this->values[$what] as $key => $lbl){
			if($key == ''){
				continue;
			}
			$selected = in_array($key, $selected_val) ? 'selected="selected"' : '';
			
			$html .= "<option value='{$key}' {$selected}>{$lbl}</option>";
		}
		return $html;
	}
	
	public function GetDropdownHTML($what, $selected_val = null)
	{
		if($what == 'timezones_availables' && empty($this->values[$what])){
			$this->values[$what] = Dropdown::generate_timezone_list();
		}
		$html = '';
        $selected_blank = empty($selected_val) ? 'selected="selected"' : '';

		$html .= "<option value='' {$selected_blank}>Selecione</option>";
		foreach($this->values[$what] as $key => $lbl){
			if($key == ''){
				continue;
			}
			$selected = !is_null($selected_val) && $selected_val !== '' && $selected_val == $key ? 'selected="selected"' : '';
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
		$return = [];
		foreach($value as $key => $select){
			if(isset($this->values[$what][$select])){
				$return[$key] = $this->values[$what][$select];
			}
			
		}
		return $return;
	}
	
	public function translate($what, $value)
	{
        return $this->values[$what][$value] ?? $value;
	}

    private static function generate_timezone_list()
    {
        $cache_file = WRITEPATH . 'utils/timezone_list.json';
        if(file_exists($cache_file)){
            return json_decode(file_get_contents($cache_file), true);
        }

        static $regions = [
            DateTimeZone::AMERICA,
];

        $timezones = [];
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
        }

        $timezone_offsets = [];
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime());
        }

        // sort timezone by offset
        asort($timezone_offsets);

        $timezone_list = [];
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