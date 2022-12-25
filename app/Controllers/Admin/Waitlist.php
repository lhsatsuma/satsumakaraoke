<?php
namespace App\Controllers\Admin;

class Waitlist extends AdminBaseController
{
	protected $module_name = 'Waitlist';

	public function ExtButtonsGenericFilters()
	{
		return [
			'clearWaitList' => '<button class="btn btn-outline-warning btn-rounded" type="button" id="clearWaitList"><i class="fas fa-times-circle"></i> Limpar todas as músicas na fila</button>'
		];
	}

	public function index($offset=0)
	{

		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/waitlist/index',
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
		$this->mdl->select = "musicas_fila.id,
		musicas_fila.name,
		musicas_fila.status,
		musicas_fila.date_created,
		usuarios.name as user_created_name,
		musicas.name as musica_id_name";
		$this->mdl->join["LEFTJOIN_usuarios"] = "musicas_fila.user_created = usuarios.id";
		$this->mdl->join["LEFTJOIN_musicas"] = "musicas_fila.musica_id = musicas.id";
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		$this->data['records'] = $result;
		
		return $this->displayNew('pages/Admin/waitlist/index');
	}
	
	public function cancelar_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\Ajax(['id']);
		
		$musicas_mdl = new \App\Models\Musics\Musics();
		
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
		
		$this->mdl = new \App\Models\Waitlist\Waitlist();
		
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
