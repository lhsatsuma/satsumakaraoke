<?php
namespace App\Controllers\Admin;

/**
BASE Controller
AUTHOR: LUIS HENRIQUE MINORU SATSUMA
LAST UPDATE: 13/09/2020
 */

use CodeIgniter\Controller;

class AdminBaseController extends \App\Controllers\BaseController
{
	public $template_file = 'template_admin';
	public $access_cfg = array(
		'needs_login' => true, //For access all pages, needs to be logged in
		'admin_only' => true,
		'role_access' => array(), //ToDo, Access with roles
	);
	
	public $pager_cfg = array(
		'per_page' => 20,
		'segment' => 4,
		'template' => 'template_basic',
	);

	public function __construct()
	{
		parent::__construct();

		global $AppVersion;
		$this->template_file = $AppVersion->template_file_admin;
	}

	public function SetMdl()
	{
		//Admin Model
		$namespace_call = '\\App\\Models\\Admin\\'.$this->module_name.'\\'.$this->module_name;
		if(class_exists($namespace_call)){
			$this->mdl = new $namespace_call();
		}else{
			//If don't find Admin Model, let's try to call default of BaseController
			$namespace_call = ($this->ns_model) ? $this->ns_model : '\\App\\Models\\'.$this->module_name.'\\'.$this->module_name;
			if(class_exists($namespace_call)){
				$this->mdl = new $namespace_call();
			}
		}
	}

	public function CheckSysAccess()
	{
		$HasAccess = $this->sysLib->CheckSession();
		if($this->access_cfg['needs_login']){
			if(!$HasAccess &&
			(!isset($this->uri[1]) || $this->uri[1] !== 'login')){
				$this->session->setFlashdata('rdct_url', urlencode(current_url()));
				rdct('/admin/login');
			}
		}
		if($this->access_cfg['admin_only'] && $HasAccess){
			$HasAccessAdmin = $this->sysLib->CheckAccessAdmin();
			if(!$HasAccessAdmin){
				header('HTTP/1.0 403 Forbbiden');
				echo '<p>Acesso Negado!</p>';
				echo '<p><a href="'.base_url().'">Voltar para Página Inicial</a></p>';
				exit;
			}
		}
	}
}
