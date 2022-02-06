<?php
namespace App\Controllers\Admin;

class Parametros extends AdminBaseController
{
	public $module_name = 'Parametros';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/parametros/editar">Novo +</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		hasPermission(8, 'r', true);
		$this->data['title'] = 'Lista de Parâmetros';
		
		$initial_filter = array(
			'name' => '',
			'codigo' => '',
		);
		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/parametros/index',
			'generic_filter' => array(
				'name',
				'codigo',
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (count($result)) ? true : false;
		
		return $this->displayNew('pages/Admin/Parametros/index');
	}
	
	public function detalhes($id = null)
	{
		hasPermission(8, 'r', true);

		$this->data['title'] = 'Detalhes do Parâmetro';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result['id']){
			rdct('/admin/parametros/index');
		}
		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(8);
		
		return $this->displayNew('pages/Admin/Parametros/detalhes');
	}
	
	public function editar($id = null)
	{
		hasPermission(8, 'w', true);

		$this->data['title'] = ($id) ? 'Editar Parâmetro' : 'Criar Parâmetro';
		
		$result = array();
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);

		$this->setPermData(8);
		
		return $this->displayNew('pages/Admin/Parametros/editar');
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
					rdct('/admin/parametros/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/parametros/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/parametros/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/parametros/editar/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/parametros/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/parametros/editar/'.$this->mdl->f['id']);
		}
	}
}