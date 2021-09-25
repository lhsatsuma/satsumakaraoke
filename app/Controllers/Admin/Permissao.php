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
		hasPermission(3, 'r', true);

		$this->data['title'] = 'Lista de Permissões';
		
		$initial_filter = array(
			'nome' => '',
		);
		$initial_order_by = array(
			'field' => 'id',
			'order' => 'ASC',
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
		
		return $this->displayNew('pages/Admin/Permissao/index');
	}
	
	public function detalhes($id = null)
	{
		hasPermission(3, 'r', true);

		$this->data['title'] = 'Detalhes da Permissão';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/permissao/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(3);
		
		return $this->displayNew('pages/Admin/Permissao/detalhes');
	}
	
	public function editar($id = null)
	{
		hasPermission(3, 'w', true);

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

		$this->setPermData(3);
		
		return $this->displayNew('pages/Admin/Permissao/editar');
	}
	
	public function salvar()
	{
		hasPermission(3, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(3, 'd', true);
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/permissao/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
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
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/permissao/editar/'.$this->mdl->f['id']);
		}
	}
}