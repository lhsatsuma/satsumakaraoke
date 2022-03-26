<?php
namespace App\Controllers;

class Curiosidades extends BaseController
{
	public $data = array();
	public $session;
	public $parser;
	public $module_name = 'Curiosidades';
	
	public function index()
	{
		$this->data['title'] = '<i class="fas fa-glasses"></i> Curiosidades sobre o Sistema';
		$this->data['total'] = json_decode(file_get_contents(WRITEPATH . 'cache/total_musics.json'), true);
		return $this->displayNew('pages/Curiosidades/index');
	}
}
