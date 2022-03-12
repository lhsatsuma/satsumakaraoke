<?php
namespace App\Controllers\Admin;

class Karaoke_ajax extends AdminBaseController
{
	public $module_name = 'MusicasFila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		hasPermission(1002, 'r', true);
		
		$this->ajax = new \App\Libraries\Sys\Ajax();
		$this->ajax->CheckIncoming();
		
		$this->mdl = new \App\Models\MusicasFila\MusicasFila();
	}

	public function index()
	{
		$this->ajax->setError(0, 'root not allowed');
	}

	public function k_reset_thread()
	{
		unlink(WRITEPATH . 'cache/thread.json');
		$this->ajax->setSuccess([]);
	}

	public function k_get_thread()
	{
		$thread = json_decode(file_get_contents(WRITEPATH . 'cache/thread.json'), true);

		if($this->ajax->body['search']){
			if($this->ajax->body['reset']){
				$this->mdl->createJSON();
			}
			$encoded_line = json_decode(file_get_contents(WRITEPATH . 'cache/line_music.json'), true);
		}else{
			$encoded_line = [];
		}

		$encoded = [
			'th' => ($thread) ? $thread : null,
			't' => ($encoded_line['t']) ? $encoded_line['t'] : 0,
			's' => ($encoded_line['s']) ? $encoded_line['s'] : [],
		];
		$this->ajax->setSuccess($encoded);
	}

	public function k_musics_list()
	{

	}

	public function k_ended_video()
	{
		$this->mdl->f['id'] = $this->ajax->body['id'];
		$result = $this->mdl->get();
		if(!$result){
			$this->ajax->setError('1x001', 'id not found');
		}

		$this->mdl->f['id'] = $result['id'];
		$this->mdl->f['status'] = 'encerrado';
		$this->mdl->saveRecord();
		$this->ajax->setSuccess();
	}

	public function k_get_body()
	{
		$this->data['karaokeURL'] = getValorParametro('karaoke_url_videos');
		$this->data['host_fila'] = getValorParametro('karaoke_url_host');
		$this->ajax->setSuccess($this->displayNew('pages/Admin/Karaoke/k_body_'.$this->ajax->body['id'], false));
	}

	public function k_search_music()
	{
		$mdl = new \App\Models\Musicas\Musicas();
		$mdl->where['codigo'] = $this->ajax->body['code'];
		$result = $mdl->search(1)[0];
		if($result){
			$this->ajax->setSuccess($result);
		}else{
			$this->ajax->setError('0x001', 'Não foi possível encontrar a música pelo código '.$this->ajax->body['code'].'!');
		}
	}
}