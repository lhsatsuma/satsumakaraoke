<?php
namespace App\Controllers\Admin;

class Files extends AdminBaseController
{
    protected $module_name = 'Files';
    public $data = [];
    public $generic_filter = true;
    public $pager_config = [
        'per_page' => 10,
        'segment' => 3,
        'template' => 'template_basic',
    ];
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		if(hasPermission(7, 'w')){
			$extBtns['new'] = '<a class="btn btn-outline-success" href="'.$this->base_url.'admin/files/edit">'.translate('LBL_NEW_RECORD').'</a>';
		}

		return $extBtns;
	}

    public function index($offset = 0)
    {
		hasPermission(7, 'r', true);
        
        $initial_filter = [
            'name' => '',
        ];
        $initial_order_by = [
            'field' => 'name',
            'order' => 'ASC',
        ];

        $this->filterLib_cfg = [
            'use' => true,
            'action' => base_url().'/admin/files/index',
            'generic_filter' => [
                'name',

            ],
        ];

        $this->PopulateFiltroPost($initial_filter, $initial_order_by);

        $total_row = $this->mdl->total_rows();
        $this->data['pagination'] = $this->GetPagination($total_row, $offset);

        $result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
        $result = $this->mdl->formatRecordsView($result);
        $this->data['records'] = $result;
        $this->data['records_count'] = (bool)count((array)$result);

        return $this->displayNew('pages/Admin/Files/index');
    }

    public function detail($id)
    {
		hasPermission(7, 'r', true);

        $this->mdl->f['id'] = $id;
        $result = $this->mdl->get();
		
		$this->SetLayout(); //Sets layout again for update vars of related record
        $result = $this->mdl->formatRecordsView($result);
        $this->data['record'] = $result;

        $this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(7);

        return $this->displayNew('pages/Admin/Files/detail');
    }
	
	
	public function edit($id = null)
	{
		hasPermission(7, 'w', true);

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

		$this->setPermData(7);
		
		return $this->displayNew('pages/Admin/Files/edit');
	}
	
	public function salvar()
	{
		hasPermission(7, 'w', true);
		
		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				$deleted = $this->mdl->DeleteRecord();
				if($deleted){
					rdct('/admin/files/index');
				}
				$this->validation_errors = [
					'generic_error' => 'Não foi possível deletar o registro, tente novamente.',
                ];
				$this->SetErrorValidatedForm(false);
				rdct('/admin/files/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/files/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/files/edit/'.$this->request->getPost('id'));
		}
		
		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/files/detail/'.$this->mdl->f['id']);
		}else{
			$this->validation_errors = [
				'generic_error' => $this->mdl->last_error,
            ];
			$this->SetErrorValidatedForm();
			rdct('/admin/files/edit/'.$this->request->getPost('id'));
		}
	}
}