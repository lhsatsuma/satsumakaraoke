<?php
namespace App\Libraries\Sys;

class InitApp
{
	private $uri;
	private $session;
	public $vardef;
	public $module;
	public $action;
	
	public function __construct($module_name)
	{
		$this->session = getSession();
		$this->uri = current_url(true)->getSegments();
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
			return hasPermission(4, 'r', false, $this->session->get('auth_user')['tipo']);
		}
		return false;
	}
}
?>