<?php
namespace App\Controllers\Admin;

class Parameters extends AdminBaseController
{
	protected $module_name = 'Parameters';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/parameters/edit">'.translate('LBL_NEW_RECORD').'</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(8, 'r', true);
		
		$initial_filter = [
			'name' => '',
			'codigo' => '',
        ];
		$initial_order_by = [
			'field' => 'date_created',
			'order' => 'DESC',
        ];
		
		$this->filterLib_cfg = [
			'use' => true,
			'action' => base_url().'/admin/parameters/index',
			'generic_filter' => [
				'name',
				'codigo',
            ],
        ];
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (bool)count((array)$result);
		
		return $this->displayNew('pages/Admin/Parameters/index');
	}
	
	public function detail($id = null)
	{
		hasPermission(8, 'r', true);
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/parameters/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(8);
		
		return $this->displayNew('pages/Admin/Parameters/detail');
	}
	
	public function edit($id = null)
	{
		hasPermission(8, 'w', true);

		$this->data['title'] = translate($id ? 'LBL_ACTION_CTRL_EDIT' : 'LBL_ACTION_CTRL_NEW');
		
		$result = [];
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);

		$this->setPermData(8);
		
		return $this->displayNew('pages/Admin/Parameters/edit');
	}
	
	public function salvar()
	{
		hasPermission(8, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(8, 'd', true);

				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/parameters/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/parameters/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/parameters/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/parameters/edit/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/parameters/detail/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/parameters/edit/'.$this->mdl->f['id']);
		}
	}
}