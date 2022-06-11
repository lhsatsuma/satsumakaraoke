<?php
namespace App\Controllers\Admin;

class Profiles extends AdminBaseController
{
	public $module_name = 'Profiles';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/profiles/editar">'.translate('app', 'LBL_NEW_RECORD').'</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(2, 'r', true);
		
		$initial_filter = array(
			'name' => '',
			'ativo' => '1',
		);
		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/profiles/index',
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
		
		return $this->displayNew('pages/Admin/Profiles/index');
	}
	
	public function detail($id = null)
	{
		hasPermission(2, 'r', true);
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/profiles/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(2);
		
		return $this->displayNew('pages/Admin/Profiles/detail');
	}
	
	public function editar($id = null)
	{
		hasPermission(2, 'w', true);
		
		$this->data['title'] = translate($this->translate_file, ($id) ? 'LBL_ACTION_CTRL_EDIT' : 'LBL_ACTION_CTRL_NEW');
		
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
		
		return $this->displayNew('pages/Admin/Profiles/editar');
	}
	
	public function salvar()
	{
		hasPermission(2, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(2, 'd', true);

				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/profiles/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/profiles/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/profiles/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/profiles/edit/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/profiles/detail/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/profiles/edit/'.$this->mdl->f['id']);
		}
	}
}