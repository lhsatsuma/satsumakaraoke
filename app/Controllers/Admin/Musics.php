<?php
namespace App\Controllers\Admin;

class Musics extends AdminBaseController
{
	protected $module_name = 'Musics';
	
	public function __construct()
	{
		parent::__construct();
		$this->upload_path = getParameterValue('path_video_karaoke');
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
			'action' => base_url().'/admin/musics/index',
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
		
		$this->mdl->select = "musicas.*";
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/Musics/index');
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
		
		return $this->displayNew('pages/Admin/Musics/import');
	}

	public function karaoke()
	{
		hasPermission(1002, 'r', true);

		$videosKaraoke = getParameterValue('karaoke_url_videos');
		$hostFila = getParameterValue('karaoke_url_host');


		$this->js_vars = array_merge($this->js_vars, [
			'karaokeURL' => ($videosKaraoke) ? $videosKaraoke : base_url().'/',
			'host_fila' => ($hostFila) ? $hostFila : base_url().'/'
		]);
		return $this->displayNew('pages/Admin/Musics/karaoke', false);
	}
}