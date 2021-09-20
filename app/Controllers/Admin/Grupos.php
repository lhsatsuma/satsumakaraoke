<?php
namespace App\Controllers\Admin;

class Grupos extends AdminBaseController
{
	public $module_name = 'Grupos';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/grupos/editar">Novo +</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(2, 'r', true);
		$this->data['title'] = 'Lista de Grupos';
		
		$initial_filter = array(
			'nome' => '',
			'ativo' => '1',
		);
		$initial_order_by = array(
			'field' => 'data_criacao',
			'order' => 'DESC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/grupos/index',
			'generic_filter' => array(
				'nome',
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (count($result)) ? true : false;
		
		return $this->displayNew('pages/Admin/Grupos/index');
	}
	
	public function detalhes($id = null)
	{
		hasPermission(2, 'r', true);

		$this->data['title'] = 'Detalhes do Grupo';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/grupos/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(2);
		
		return $this->displayNew('pages/Admin/Grupos/detalhes');
	}
	
	public function editar($id = null)
	{
		hasPermission(2, 'w', true);

		$this->data['title'] = ($id) ? 'Editar Grupo' : 'Criar Grupo';
		
		$result = array();
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);

		$this->setPermData(2);
		
		return $this->displayNew('pages/Admin/Grupos/editar');
	}
	
	public function salvar()
	{
		hasPermission(2, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(2, 'd', true);

				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/grupos/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/grupos/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/grupos/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
		}
	}
}