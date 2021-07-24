<?php
namespace App\Controllers\Admin;

class Home extends AdminBaseController
{
	public $data = array();
	public $session;
	public $parser;
	public $module_name = 'Home';
	public $dummy_controller = true;
	
	public function index()
	{
		rdct('/admin/usuarios/index');
	}
}
