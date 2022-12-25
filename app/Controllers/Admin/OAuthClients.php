<?php
namespace App\Controllers\Admin;

class OAuthClients extends AdminBaseController
{
    protected $module_name = 'OAuthClients';
    public $data = [];
    public $generic_filter = true;
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		if(hasPermission(10, 'w')){
			$extBtns['new'] = '<a class="btn btn-outline-success" href="'.$this->base_url.'admin/OAuthClients/edit">'.translate('LBL_NEW_RECORD').'</a>';
		}

		return $extBtns;
	}

    public function index($offset = 0)
    {
		hasPermission(10, 'r', true);
        
        $initial_filter = array(
            'name' => '',
        );
        $initial_order_by = array(
            'field' => 'name',
            'order' => 'ASC',
        );

        $this->filterLib_cfg = array(
            'use' => true,
            'action' => base_url().'/admin/OAuthClients/index',
            'generic_filter' => array(
                'name',
            ),
        );

        $this->PopulateFiltroPost($initial_filter, $initial_order_by);

        $total_row = $this->mdl->total_rows();
        $this->data['pagination'] = $this->GetPagination($total_row, $offset);

        $result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
        $result = $this->mdl->formatRecordsView($result);
        $this->data['records'] = $result;
        $this->data['records_count'] = (count((array)$result)) ? true : false;

        return $this->displayNew('pages/Admin/OAuthClients/index');
    }

    public function detail($id)
    {
		hasPermission(10, 'r', true);

        $this->mdl->f['id'] = $id;
        $result = $this->mdl->get();
		
		$this->SetLayout(); //Sets layout again for update vars of related record
        $result = $this->mdl->formatRecordsView($result);
        $this->data['record'] = $result;

        $this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(10);

        return $this->displayNew('pages/Admin/OAuthClients/detail');
    }
	
	
	public function edit($id = null)
	{
		hasPermission(10, 'w', true);

		$this->data['title'] = translate(($id) ? 'LBL_ACTION_CTRL_EDIT' : 'LBL_ACTION_CTRL_NEW');
		
		$result = [];
		if($id){
			$this->mdl->f['id'] = $id;
			$result = $this->mdl->get();
			$result = $this->mdl->formatRecordsView($result);
		}
		
		$result = $this->PopulateFromSaveData($result);
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);

		$this->setPermData(10);
		
		return $this->displayNew('pages/Admin/OAuthClients/edit');
	}
	
	public function salvar()
	{
		hasPermission(10, 'w', true);
		
		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				$deleted = $this->mdl->DeleteRecord();
				if($deleted){
					rdct('/admin/OAuthClients/index');
				}
				$this->validation_errors = array(
					'generic_error' => 'Não foi possível deletar o registro, tente novamente.',
				);
				$this->SetErrorValidatedForm(false);
				rdct('/admin/OAuthClients/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/OAuthClients/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/OAuthClients/edit/'.$this->request->getPost('id'));
		}
		
		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/OAuthClients/detail/'.$this->mdl->f['id']);
		}else{
			$this->validation_errors = array(
				'generic_error' => $this->mdl->last_error,
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/OAuthClients/edit/'.$this->request->getPost('id'));
		}
	}

	public function check_client_id()
	{
		$ajax = new \App\Libraries\Sys\Ajax(['client_id']);
		
		$this->mdl->f['client_id'] = $ajax->body['client_id'];
		$this->mdl->f['id'] = $ajax->body['id'];
		if($this->mdl->checkExists()){
			$ajax->setSuccess(['exists' => true, 'lbl' => 'ALREADY_EXISTS']);
		}
		$ajax->setSuccess(['exists' => false]);
	}
}