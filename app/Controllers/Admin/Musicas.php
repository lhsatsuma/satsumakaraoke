<?php
namespace App\Controllers\Admin;

class Musicas extends AdminBaseController
{
	public $module_name = 'Musicas';
	
	public function __construct()
	{
		parent::__construct();
		$this->upload_path = getValorParametro('path_video_karaoke');
	}
	
	public function index($offset=0)
	{
		
		$this->data['title'] = 'Músicas';
		
		$initial_order = array(
			'field' => 'name',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/musicas/index',
			'generic_filter' => array(
				'name',
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
	
	public function fixnames($offset = 0)
	{
		hasPermission(1003, 'w', true);
		$this->data['title'] = 'Arrumar names de Músicas';
		
		$initial_order = array(
			'field' => 'name',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/musicas/fixnames',
			'generic_filter' => array(
				'name',
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
		
		return $this->displayNew('pages/Admin/Musicas/fixnames');
	}

	public function sanitanizeName()
	{
		$this->ajax = new \App\Libraries\Sys\Ajax();
		$this->ajax->CheckIncoming();
		$this->ajax->CheckRequired(['name']);

		$ytLib = new \App\Libraries\Youtube();
		$this->ajax->setSuccess($ytLib->__clear_title(urldecode($this->ajax->body['name'])));
		
	}

	public function fixnamesSave()
	{
		hasPermission(1003, 'w', true);
		$this->ajax = new \App\Libraries\Sys\Ajax(['id', 'newname', 'tipo']);

		$this->mdl->f['id'] = $this->ajax->body['id'];
		$found = $this->mdl->get();
		if($found){
			$this->mdl->f['name'] = $this->ajax->body['newname'];
			$this->mdl->f['tipo'] = $this->ajax->body['tipo'];
			$savedRecord = $this->mdl->saveRecord();
			$this->ajax->setSuccess($savedRecord);
		}else{
			$this->ajax->setError('0x001', 'record not found');
		}
	}

	public function fixnamesSaveDel()
	{
		hasPermission(1003, 'w', true);
		$this->ajax = new \App\Libraries\Sys\Ajax(['id']);

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
		hasPermission(1003, 'w', true);
		$this->data['title'] = 'Importar Músicas';
		
		return $this->displayNew('pages/Admin/Musicas/import');
	}

	public function karaoke()
	{
		hasPermission(1002, 'r', true);

		$videosKaraoke = getValorParametro('karaoke_url_videos');
		$hostFila = getValorParametro('karaoke_url_host');


		$this->js_vars = array_merge($this->js_vars, [
			'karaokeURL' => ($videosKaraoke) ? $videosKaraoke : base_url().'/',
			'host_fila' => ($hostFila) ? $hostFila : base_url().'/'
		]);
		return $this->displayNew('pages/Admin/Musicas/karaoke', false);
	}
}