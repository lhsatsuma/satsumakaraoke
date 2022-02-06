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
		unlink(ROOTPATH . 'public/thread.json');
		$this->ajax->setSuccess([]);
	}

	public function k_get_thread()
	{
		$encoded = file_get_contents(ROOTPATH . 'public/thread.json');
		$this->ajax->setSuccess(json_decode($encoded, true));
	}

	public function k_musics_list()
	{

		$this->mdl->select = "musicas_fila.id,
		usuarios.name as cantor,
		musicas.codigo,
		musicas.name as name_musica,
		musicas.md5";
		$this->mdl->where["status"] = "pendente";
		$this->mdl->join["musicas"] = "musicas.id = musicas_fila.musica_id";
		$this->mdl->join["usuarios"] = "usuarios.id = musicas_fila.user_created";
		$this->mdl->order_by["musicas_fila.date_created"] = "ASC";

		$total_rows = $this->mdl->total_rows();
		$this->mdl->page_as_offset = true;
		$result = $this->mdl->search(($this->ajax->body['ct']) ? $this->ajax->body['ct'] : 10, ($this->ajax->body['of']) ? (int)$this->ajax->body['of'] : 0);
		if(is_null($result)){
			$this->ajax->setError('0x001', 'Error retrieving list of musics');
		}
		foreach($result as $key => $fila){
			if((int) $this->ajax->body['sh'] == 1){
				if(strlen($fila['cantor']) > 13){
					$result[$key]['cantor'] = mb_substr($fila['cantor'], 0, 11) . '...';
				}
				if(strlen($fila['name_musica']) > 29){
					$result[$key]['name_musica'] = mb_substr($fila['name_musica'], 0, 26) . '...';
				}
			}elseif((int)$this->ajax->body['sh'] > 1){
				$total_len = $fila['cantor'].$fila['name_musica'];
				if(strlen($total_len) > $this->ajax->body['sh'] - 3){
					$result[$key]['name_musica'] = mb_substr($fila['name_musica'], 0, $this->ajax->body['sh'] - strlen($fila['cantor'])) . '...';
				}
			}
			$result[$key]['codigo'] = (int)$result[$key]['codigo'];
			$result[$key] = array_values($result[$key]);
		}
		$this->ajax->setSuccess(['t' => (int)$total_rows, 's' => $result]);
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
}