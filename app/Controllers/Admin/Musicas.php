<?php
namespace App\Controllers\Admin;

class Musicas extends AdminBaseController
{
	public $module_name = 'Musicas';
	public $upload_path = ROOTPATH . 'public/uploads/VIDEOSKARAOKE/';
	
	public function __construct()
	{
		parent::__construct();
		
		/* The correct way was to create another controller, but i am lazy */
		if($this->uri[0] == 'listarAdmin'){
			$this->access_cfg['admin_only'] = true;
		}
	}
	
	public function index($offset=0)
	{
		
		$this->data['title'] = 'Músicas';
		
		$initial_order = array(
			'field' => 'nome',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/musicas/index',
			'generic_filter' => array(
				'nome',
				'codigo',
				'origem',
				'tipo'
			),
		);
		
		$this->PopulateFiltroPost(array(), $initial_order);
		
		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$this->mdl->select = "musicas.*, CAST(codigo AS DECIMAL(10,2)) AS codigo_cast";
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/Musicas/index');
	}
	
	public function detalhes($id)
	{
		$this->data['title'] = 'Detalhes da Música';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);
		
		return $this->displayNew('pages/Admin/Musicas/detalhes');
	}
	
	public function fixNomes($offset = 0)
	{
		$this->data['title'] = 'Arrumar Nomes de Músicas';
		
		$initial_order = array(
			'field' => 'nome',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/musicas/fixNomes',
			'generic_filter' => array(
				'nome',
				'codigo',
				'origem',
			),
		);
		
		$this->PopulateFiltroPost(array(), $initial_order);
		
		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$this->mdl->select = "musicas.*, CAST(codigo AS DECIMAL(10,2)) AS codigo_cast";
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/Musicas/fixNomes');
	}

	public function sanitanizeName()
	{
		$this->ajax = new \App\Libraries\Sys\AjaxLib();
		$this->ajax->CheckIncoming();
		$this->ajax->CheckRequired(['nome']);

		$ytLib = new \App\Libraries\YoutubeLib();
		$this->ajax->setSuccess($ytLib->__clear_title(urldecode($this->ajax->body['nome'])));
		
	}

	public function fixNomesSave()
	{
		$this->ajax = new \App\Libraries\Sys\AjaxLib(['id', 'newNome', 'tipo']);

		$this->mdl->f['id'] = $this->ajax->body['id'];
		$found = $this->mdl->get();
		if($found){
			$this->mdl->f['nome'] = $this->ajax->body['newNome'];
			$this->mdl->f['tipo'] = $this->ajax->body['tipo'];
			$savedRecord = $this->mdl->saveRecord();
			$this->ajax->setSuccess($savedRecord);
		}else{
			$this->ajax->setError('0x001', 'record not found');
		}
	}

	public function fixNomesSaveDel()
	{
		$this->ajax = new \App\Libraries\Sys\AjaxLib(['id']);

		$this->mdl->f['id'] = $this->ajax->body['id'];
		$found = $this->mdl->get();
		if($found){
			$savedRecord = $this->mdl->deleteRecord();
			$this->ajax->setSuccess($savedRecord);
		}else{
			$this->ajax->setError('0x001', 'record not found');
		}
	}

	public function import()
	{
		$this->data['title'] = 'Importar Músicas';
		
		return $this->displayNew('pages/Admin/Musicas/import');
	}

	public function karaoke()
	{
		$version = new \Config\AppVersion();

		$this->js_vars = array_merge($this->js_vars, [
			'karaokeURL' => ($version->VideosKaraokeURL) ? $version->VideosKaraokeURL : base_url().'/',
			'host_fila' => ($version->host_fila) ? $version->host_fila : base_url().'/'
		]);
		return $this->displayNew('pages/Admin/Musicas/karaoke', false);
	}
}