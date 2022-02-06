<?php
namespace App\Controllers\Admin;

class Musicas_fila extends AdminBaseController
{
	public $module_name = 'MusicasFila';

	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas na Fila';
		
		$initial_filter = array(
			'user_created' => '',
			'status' => 'pendente',
		);
		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		foreach($result as $key => $fields){
			$result[$key]['ordem'] = $key + 1;
		}
		$this->data['records'] = $result;
		
		
		$icon_search = '<i class="far fa-times-circle"></i>';
		if($this->filter['user_created']['value']){
			$this->data['color_user_created'] = 'warning';
			$this->data['icon_user_created'] = $icon_search;
		}else{
			$this->data['color_user_created'] = 'success';
			$this->data['icon_user_created'] = '';
		}
		
		$this->data['color_status_pendente'] = 'success';
		$this->data['icon_status_pendente'] = '';
		$this->data['color_status_encerrado'] = 'success';
		$this->data['icon_status_encerrado'] = '';
		
		if($this->filter['status']['value'] == 'pendente'){
			$this->data['color_status_pendente'] = 'warning';
			$this->data['icon_status_pendente'] = $icon_search;
		}elseif($this->filter['status']['value'] == 'encerrado'){
			$this->data['color_status_encerrado'] = 'warning';
			$this->data['icon_status_encerrado'] = $icon_search;
		}
		
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
}
