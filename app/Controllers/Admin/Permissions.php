<?php
namespace App\Controllers\Admin;

class Permissions extends AdminBaseController
{
	public $module_name = 'Permissions';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/permissions/edit">'.translate('LBL_NEW_RECORD').'</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(3, 'r', true);

		$this->data['title'] = 'Lista de Permissões';
		
		$initial_filter = array(
			'name' => '',
		);
		$initial_order_by = array(
			'field' => 'id',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/permissions/index',
			'generic_filter' => array(
				'name',
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (count($result)) ? true : false;
		
		return $this->displayNew('pages/Admin/Permissions/index');
	}
	
	public function detail($id = null)
	{
		hasPermission(3, 'r', true);

		$this->data['title'] = 'Detalhes da Permissão';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/permissions/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(3);
		
		return $this->displayNew('pages/Admin/Permissions/detail');
	}
	
	public function edit($id = null)
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
		
		return $this->displayNew('pages/Admin/Permissions/edit');
	}
	
	public function salvar()
	{
		hasPermission(3, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(3, 'd', true);
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/permissions/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/permissions/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/permissions/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/permissions/edit/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/permissions/detail/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/permissions/edit/'.$this->mdl->f['id']);
		}
	}
}