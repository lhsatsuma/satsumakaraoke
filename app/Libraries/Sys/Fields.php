<?php
namespace App\Libraries\Sys;

class Fields
{
	public function __construct()
	{
		$this->dropdown = new \App\Libraries\Dropdown();
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
				$value = encrypt_pass($value);
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
	
	public function formatFloat($value, $precision = 2)
	{
		return number_format($value, $precision, ',', '.');
	}
	
	public function formatDate($value)
	{
		if(strpos($value, '/') !== false){
			return $value;
		}
		return date("d/m/Y", strtotime($value));
	}
	
	public function formatDateTime($value)
	{
		if(strpos($value, '/') !== false){
			return $value;
		}
		return date("d/m/Y H:i", strtotime($value));
	}
	
	public function formatTime($value)
	{
		if(strpos($value, '/') !== false){
			return $value;
		}
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
}
?>