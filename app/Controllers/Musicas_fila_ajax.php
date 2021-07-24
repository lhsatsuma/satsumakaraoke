<?php
namespace App\Controllers;

class Musicas_fila_ajax extends BaseController
{
	public $module_name = 'Musicas_fila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->ajax = new \App\Libraries\Sys\AjaxLib($this->request);
		$this->ajax->CheckIncoming();
		
		$this->body = $this->ajax->GetData();
		$this->mdl = new \App\Models\Musicas_fila\Musicas_filamodel();
	}

	public function index()
	{
		$this->ajax->setError(0, 'root not allowed');
	}

	public function k_set_thread()
	{

		if(empty($this->body['action'])){
			$this->ajax->setError('1x001', 'action not found');
		}
		$data_encode = [
			'action' => $this->body['action'],
			'valueTo' => (int)$this->body['valueTo'],
		];

		$encoded = json_encode($data_encode);
		file_put_contents(ROOTPATH . 'public/thread.json', $encoded);

		
		$data_copy = [
			'volume' => null,
		];
		if($data_encode['action'] == 'volume'){
			$data_copy = [
				'volume' => (int)$data_encode['valueTo'],
			];
			file_put_contents(ROOTPATH . 'public/threadCopy.json', json_encode($data_copy));
		}
		$this->ajax->setSuccess($data_encode);
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

	public function k_get_thread_copy()
	{
		$encoded = file_get_contents(ROOTPATH . 'public/threadCopy.json');
		$this->ajax->setSuccess(json_decode($encoded, true));
	}

	public function k_musics_list()
	{

		$this->mdl->select = "musicas_fila.id, usuarios.nome as cantor, musicas.nome as nome_musica, musicas.codigo, musicas.md5";
		$this->mdl->where["status"] = "pendente";
		$this->mdl->join["musicas"] = "musicas.id = musicas_fila.musica_id";
		$this->mdl->join["usuarios"] = "usuarios.id = musicas_fila.usuario_criacao";
		$this->mdl->order_by["musicas_fila.data_criacao"] = "ASC";
		$result = $this->mdl->search(11);
		if(is_null($result)){
			$this->ajax->setError('0x001', 'Error retrieving list of musics');
		}
		foreach($result as $key => $fila){
			if(strlen($fila['cantor']) > 13){
				$result[$key]['cantor'] = substr($fila['cantor'], 0, 10) . '...';
			}
		}
		$this->ajax->setSuccess($result);
	}

	public function k_ended_video()
	{
		$this->mdl->f['id'] = $this->body['id'];
		$result = $this->mdl->get();
		if(!$result){
			$this->ajax->setError('1x001', 'id not found');
		}

		$this->mdl->f['id'] = $result['id'];
		$this->mdl->f['status'] = 'encerrado';
		$this->mdl->saveRecord();
	}
}
