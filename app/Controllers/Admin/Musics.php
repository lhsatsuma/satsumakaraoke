<?php
namespace App\Controllers\Admin;

use App\Libraries\Sys\Ajax;
use App\Libraries\Youtube;

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
		
		$initial_order = [
			'field' => 'name',
			'order' => 'ASC',
        ];
		
		$this->filterLib_cfg = [
			'use' => true,
			'action' => base_url().'/admin/musics/index',
			'generic_filter' => [
				'name',
				'codigo',
				'origem',
				'tipo'
            ],
        ];
		
		$this->PopulateFiltroPost([], $initial_order);
		
		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$this->mdl->select = 'musicas.*';
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/Musics/index');
	}

	public function sanitanizeName()
	{
		$this->ajax = new Ajax();
		$this->ajax->CheckIncoming();
		$this->ajax->CheckRequired(['name']);

		$ytLib = new Youtube();
		$this->ajax->setSuccess($ytLib->__clear_title(urldecode($this->ajax->body['name'])));
		
	}

	public function fixnamesSave()
	{
		hasPermission(1003, 'w', true);
		$this->ajax = new Ajax(['id', 'newname', 'tipo']);

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
		$this->ajax = new Ajax(['id']);

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
			'karaokeURL' => $videosKaraoke ?: base_url(),
			'host_fila' => $hostFila ?: base_url()
		]);
		return $this->displayNew('pages/Admin/Musics/karaoke', false);
	}
}