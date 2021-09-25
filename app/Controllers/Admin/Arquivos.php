<?php
namespace App\Controllers\Admin;

class Arquivos extends AdminBaseController
{
    public $module_name = 'Arquivos';
    public $data = array();
    public $generic_filter = true;
    public $pager_config = array(
        'per_page' => 10,
        'segment' => 3,
        'template' => 'template_basic',
    );
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		if(hasPermission(7, 'w')){
			$extBtns['new'] = '<a class="btn btn-outline-success" href="'.$this->base_url.'admin/arquivos/editar">Novo +</a>';
		}

		return $extBtns;
	}

    public function index($offset = 0)
    {
		hasPermission(7, 'r', true);

        $this->data['title'] = 'Arquivos';
        
        $initial_filter = array(
            'nome' => '',
        );
        $initial_order_by = array(
            'field' => 'nome',
            'order' => 'ASC',
        );

        $this->filterLib_cfg = array(
            'use' => true,
            'action' => base_url().'/admin/arquivos/index',
            'generic_filter' => array(
                'nome',
                
            ),
        );

        $this->PopulateFiltroPost($initial_filter, $initial_order_by);

        $total_row = $this->mdl->total_rows();
        $this->data['pagination'] = $this->GetPagination($total_row, $offset);

        $result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
        $result = $this->mdl->formatRecordsView($result);
        $this->data['records'] = $result;
        $this->data['records_count'] = (count($result)) ? true : false;

        return $this->displayNew('pages/Admin/Arquivos/index');
    }

    public function detalhes($id)
    {
		hasPermission(7, 'r', true);

        $this->data['title'] = 'Detalhes do Arquivo';

        $this->mdl->f['id'] = $id;
        $result = $this->mdl->get();
		
		$this->SetLayout(); //Sets layout again for update vars of related record
        $result = $this->mdl->formatRecordsView($result);
        $this->data['record'] = $result;

        $this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(7);

        return $this->displayNew('pages/Admin/Arquivos/detalhes');
    }
	
	
	public function editar($id = null)
	{
		hasPermission(7, 'w', true);

		$this->data['title'] = ($id) ? 'Editar Arquivo' : 'Criar Arquivo';
		
		$result = array();
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);

		$this->setPermData(7);
		
		return $this->displayNew('pages/Admin/Arquivos/editar');
	}
	
	public function salvar()
	{
		hasPermission(7, 'w', true);
		
		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				$deleted = $this->mdl->DeleteRecord();
				if($deleted){
					rdct('/admin/arquivos/index');
				}
				$this->validation_errors = array(
					'generic_error' => 'Não foi possível deletar o registro, tente novamente.',
				);
				$this->SetErrorValidatedForm(false);
				rdct('/admin/arquivos/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/arquivos/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/arquivos/editar/'.$this->request->getPost('id'));
		}
		
		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/arquivos/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->validation_errors = array(
				'generic_error' => $this->mdl->last_error,
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/arquivos/editar/'.$this->request->getPost('id'));
		}
	}
}