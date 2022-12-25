<?php
namespace App\Controllers\Admin;

class Home extends AdminBaseController
{
	public $data = array();
	public $session;
	public $parser;
	protected $module_name = 'Home';
    protected $dummy_controller = true;
	
	public function index()
	{
		rdct('/admin/users/index');
	}
}
