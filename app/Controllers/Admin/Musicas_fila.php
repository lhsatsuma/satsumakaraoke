<?php
namespace App\Controllers\Admin;

class Musicas_fila extends AdminBaseController
{
	public $module_name = 'MusicasFila';

	public function ExtButtonsGenericFilters()
	{
		return [
			'clearWaitList' => '<button class="btn btn-outline-warning btn-rounded" type="button" id="clearWaitList"><i class="fas fa-times-circle"></i> Limpar todas as músicas na fila</button>'
		];
	}

	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas na Fila';

		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/musicas_fila/index',
			'generic_filter' => array(
				'name',
				'status',
			),
		);

		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);

		$this->PopulateFiltroPost([], $initial_order_by);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/Musicas_fila/index');
	}
	
	public function cancelar_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\Ajax(['id']);
		
		$musicas_mdl = new \App\Models\Musicas\Musicas();
		
		$musicas_mdl->f['id'] = $AjaxLib->body['id'];
		$result = $musicas_mdl->get();
		if(!$result){
			$AjaxLib->setError('2x001', 'registro não encontrado');
		}
		
		
		$this->mdl->f['name'] = $result['name'];
		$this->mdl->f['user_created'] = $this->session->get('auth_user')['id'];
		$this->mdl->f['musica_id'] = $result['id'];
		$this->mdl->f['status'] = 'pendente';
		$saved_record = $this->mdl->saveRecord();
		$AjaxLib->setSuccess($saved_record);
		
	}
	
	public function clear_waitlist()
	{
		
		$AjaxLib = new \App\Libraries\Sys\Ajax();
		
		$this->mdl = new \App\Models\MusicasFila\MusicasFila();
		
		$this->mdl->select = "id";
		$count = 0;
		foreach($this->mdl->search() as $record){
			$this->select = "id";
			$this->mdl->f['id'] = $record['id'];
			$this->mdl->deleteRecord();
			$count++;
		}
		$AjaxLib->setSuccess($count);
		
	}
}
