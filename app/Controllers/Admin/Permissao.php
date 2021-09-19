<?php
namespace App\Controllers\Admin;

class Permissao extends AdminBaseController
{
	public $module_name = 'Permissao';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/permissao/editar">Novo +</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		$this->data['title'] = 'Lista de Permissões';
		
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
			'action' => base_url().'/admin/permissao/index',
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
		
		return $this->displayNew('pages/admin/Permissao/index');
	}
	
	public function detalhes($id = null)
	{
		$this->data['title'] = 'Detalhes da Permissão';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/permissao/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);
		
		return $this->displayNew('pages/admin/Permissao/detalhes');
	}
	
	public function editar($id = null)
	{
		$this->data['title'] = ($id) ? 'Editar Permissão' : 'Criar Permissão';
		
		$result = array();
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);
		
		return $this->displayNew('pages/admin/Permissao/editar');
	}
	
	public function salvar()
	{
		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/permissao/index');
				}
				$this->validation_errors = array(
					'generic_error' => 'Não foi possível deletar o registro, tente novamente.',
				);
				$this->SetErrorValidatedForm(false);
				rdct('/admin/permissao/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/permissao/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/permissao/editar/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/permissao/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->validation_errors = array(
				'generic_error' => $this->mdl->last_error,
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/permissao/editar/'.$this->mdl->f['id']);
		}
	}
}