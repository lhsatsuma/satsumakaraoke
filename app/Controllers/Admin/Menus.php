<?php
namespace App\Controllers\Admin;

use App\Models\MenuLanguages\MenuLanguages;

class Menus extends AdminBaseController
{
	protected $module_name = 'Menus';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/menus/edit">'.translate('LBL_NEW_RECORD').'</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(9, 'r', true);
		
		$initial_filter = [
			'name' => '',
			'tipo' => '',
			'ativo' => '1',
        ];
		$initial_order_by = [
			'field' => 'tipo',
			'order' => 'ASC',
        ];
		
		$this->filterLib_cfg = [
			'use' => true,
			'action' => base_url().'/admin/menus/index',
			'generic_filter' => [
				'name',
            ],
        ];
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (bool)count((array)$result);
		
		return $this->displayNew('pages/Admin/Menus/index');
	}
	
	public function detail($id = null)
	{
		hasPermission(9, 'r', true);
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/menus/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(9);
		
		return $this->displayNew('pages/Admin/Menus/detail');
	}
	
	public function edit($id = null)
	{
		hasPermission(9, 'w', true);

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

		$this->setPermData(9);

		$this->data['saved_languages'] = $this->mdl->getLanguagesMenu();
		
		return $this->displayNew('pages/Admin/Menus/edit');
	}
	
	public function salvar()
	{
		hasPermission(9, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(9, 'd', true);

				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/menus/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/menus/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/menus/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/menus/edit/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){

			//Saving menu languages

			foreach(getFormData('menu_languages') as $id => $values){
				$menuLang = new MenuLanguages();
				$menuLang->fillF($values);
				$menuLang->f['menu_id'] = $this->mdl->f['id'];
				if(!$menuLang->saveRecord()){
					$this->setMsgData('error', $menuLang->last_error);
					rdct('/admin/menus/edit/'.$this->mdl->f['id']);
				}
			}
			rdct('/admin/menus/detail/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/menus/edit/'.$this->mdl->f['id']);
		}
	}
	
	public function reordenar()
	{
		hasPermission(9, 'w', true);
		return $this->displayNew('pages/Admin/Menus/reordenar');
	}
}