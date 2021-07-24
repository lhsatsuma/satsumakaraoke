<?php
namespace App\Controllers\Admin;

class Musicas_fila extends AdminBaseController
{
	public $module_name = 'Musicas_fila';

	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas na Fila';
		
		$initial_filter = array(
			'usuario_cantar' => '',
			'status' => 'pendente',
		);
		$initial_order_by = array(
			'field' => 'data_criacao',
			'order' => 'DESC',
		);
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		$result = $this->mdl->search(20, $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		foreach($result as $key => $fields){
			$result[$key]['ordem'] = $key + 1;
		}
		$this->data['records'] = $result;
		
		
		$icon_search = '<i class="far fa-times-circle"></i>';
		if($this->filter['usuario_cantar']['value']){
			$this->data['color_usuario_cantar'] = 'warning';
			$this->data['icon_usuario_cantar'] = $icon_search;
		}else{
			$this->data['color_usuario_cantar'] = 'success';
			$this->data['icon_usuario_cantar'] = '';
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
		
		return $this->display_template($this->view->setData($this->data)->view('pages/Musicas_fila/index'));
	}
	
	public function cancelar_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$AjaxLib->CheckIncoming();
		
		
		$required = array(
			'id',
		);
		$AjaxLib->CheckRequired($required);
		unset($required);
		
		$body_post = $AjaxLib->GetData();
		
		$musicas_mdl = new \App\Models\Musicas\Musicasmodel();
		
		$musicas_mdl->f['id'] = $body_post['id'];
		$result = $musicas_mdl->get();
		if(!$result){
			$AjaxLib->setError('2x001', 'registro não encontrado');
		}
		
		
		$this->mdl->f['nome'] = $result['nome'];
		$this->mdl->f['usuario_cantar'] = $this->session->get('auth_user')['id'];
		$this->mdl->f['musica_id'] = $result['id'];
		$this->mdl->f['status'] = 'pendente';
		$saved_record = $this->mdl->saveRecord();
		$AjaxLib->setSuccess($saved_record);
		
	}
}
