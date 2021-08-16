<?php
namespace App\Libraries\Sys;

class InitAppLib
{
	private $url;
	private $session;
	public $vardef;
	public $module;
	public $action;
	
	public function __construct($need_login, $module_name)
	{
		$this->session = getSession();
		$this->url = current_url(true)->getSegments();
		array_shift($this->uri);
		$this->module = $module_name;
	}
	
	public function CheckSession()
	{
		if(!empty($this->session->get('auth_user'))){
			return $this->session->get('auth_user');
		}
		return false;
	}
	
	public function CheckAccessAdmin()
	{
		if(!empty($this->session->get('auth_user'))){
			return $this->session->get('auth_user')['tipo'] == 99;
		}
		return false;
	}
}
?>