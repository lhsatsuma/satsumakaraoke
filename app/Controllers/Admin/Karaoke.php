<?php
namespace App\Controllers\Admin;

class Karaoke extends AdminBaseController
{
	protected $module_name = 'Karaoke';
	
	public function __construct()
	{
		parent::__construct();
		$this->upload_path = getParameterValue('path_video_karaoke');
	}

	public function index()
	{
		hasPermission(1002, 'r', true);

		$videosKaraoke = getParameterValue('karaoke_url_videos');
		$hostFila = getParameterValue('karaoke_url_host');
		
		$this->js_vars = array_merge($this->js_vars, [
			'karaokeURL' => ($videosKaraoke) ? $videosKaraoke : base_url().'/',
			'host_fila' => ($hostFila) ? $hostFila : base_url().'/'
		]);
		return $this->displayNew('pages/Admin/Karaoke/index', false);
	}
}