<?php
namespace App\Controllers;

/*

All comments of controller it's in BaseController
If you need any help, contact the author


Controller only for redirect use
*/

class Home extends BaseController
{
	public $data = [];
	public $session;
	protected $module_name = 'Home';
    protected $dummy_controller = true;
	
	public function index()
	{
		rdct('/musics/index');
	}
}
