<?php
namespace App\Controllers;

class Curiosidades extends BaseController
{
	public $data = [];
	public $session;
	public $parser;
	protected $module_name = 'Curiosidades';
	
	public function index()
	{
		$this->data['title'] = '<i class="fas fa-glasses"></i> Curiosidades sobre o Sistema';
		$this->data['total'] = json_decode(file_get_contents(WRITEPATH . 'utils/total_musics.json'), true);
		$this->data['last_date_version'] = date("Y-m-d", filemtime(APPPATH . 'Config/AppVersion.php'));
		return $this->displayNew('pages/Curiosidades/index');
	}
}
