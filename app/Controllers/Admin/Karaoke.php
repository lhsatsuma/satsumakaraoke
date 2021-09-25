<?php
namespace App\Controllers\Admin;

class Karaoke extends AdminBaseController
{
	public $module_name = 'Karaoke';
	public $upload_path = ROOTPATH . 'public/uploads/VIDEOSKARAOKE/';
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		hasPermission(1002, 'r', true);

		$videosKaraoke = getValorParametro('karaoke_url_videos');
		$hostFila = getValorParametro('karaoke_url_host');
		
		$this->js_vars = array_merge($this->js_vars, [
			'karaokeURL' => ($videosKaraoke) ? $videosKaraoke : base_url().'/',
			'host_fila' => ($hostFila) ? $hostFila : base_url().'/'
		]);
		return $this->displayNew('pages/Admin/Karaoke/index', false);
	}
}