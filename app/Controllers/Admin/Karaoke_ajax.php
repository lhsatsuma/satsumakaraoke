<?php
namespace App\Controllers\Admin;

class Karaoke_ajax extends AdminBaseController
{
	public $module_name = 'MusicasFila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->ajax = new \App\Libraries\Sys\AjaxLib();
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
		usuarios.nome as cantor,
		musicas.codigo,
		musicas.nome as nome_musica,
		musicas.md5";
		$this->mdl->where["status"] = "pendente";
		$this->mdl->join["musicas"] = "musicas.id = musicas_fila.musica_id";
		$this->mdl->join["usuarios"] = "usuarios.id = musicas_fila.usuario_criacao";
		$this->mdl->order_by["musicas_fila.data_criacao"] = "ASC";

		$total_rows = $this->mdl->total_rows();
		$result = $this->mdl->search(($this->ajax->body['ct']) ? $this->ajax->body['ct'] : 10);
		if(is_null($result)){
			$this->ajax->setError('0x001', 'Error retrieving list of musics');
		}
		foreach($result as $key => $fila){
			if($this->ajax->body['sh']){
				if(strlen($fila['cantor']) > 13){
					$result[$key]['cantor'] = substr($fila['cantor'], 0, 11) . '...';
				}
				if($key > 0 && strlen($fila['nome_musica']) > 29){
					$result[$key]['nome_musica'] = substr($fila['nome_musica'], 0, 26) . '...';
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
	}
}