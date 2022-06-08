<?php
namespace App\Controllers\Admin;

class Users extends AdminBaseController
{
	public $module_name = 'Users';
	public $data = array();
	public $generic_filter = true;
	
	public function ExtButtonsGenericFilters()
	{
		return array(
			'new' => '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/users/edit">'.translate('app', 'LBL_NEW_RECORD').'</a>',
		);
	}
	
	
	public function index($offset = 0)
	{
		hasPermission(1, 'r', true);
		
		$initial_filter = array(
			'name' => '',
		);
		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/users/index',
			'generic_filter' => array(
				'name',
				'email',
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		
		$total_row = $this->mdl->total_rows();
		$this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		$this->data['records_count'] = (count($result)) ? true : false;
		
		return $this->displayNew('pages/Admin/Users/index');
	}
	
	public function detail($id)
	{
		hasPermission(1, 'r', true);
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		$result['timezone'] = $this->mdl->preference->getValor('timezone_user', $this->mdl->f['id']);

		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(1);
		
		return $this->displayNew('pages/Admin/Users/detail');
	}
	
	public function edit($id = null)
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

		$this->setPermData(1);
		
		return $this->displayNew('pages/Admin/Users/edit');
	}
	
	public function salvar()
	{
		hasPermission(1, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deleted']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(2, 'd', true);
				
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/users/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/users/edit/'.$this->mdl->f['id']);
			}
			rdct('/admin/users/edit');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/users/edit/'.getFormData('id'));
		}
		
		if(empty($this->mdl->f['id']) && empty(getFormData('senha_nova')) && empty(getFormData('confirm_senha_nova'))){
			$this->validation_errors = array(
				'senha_nova' => 'É necessário digitar uma senha para novos usuários.',
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/users/edit/');
		}
		
		if(!empty(getFormData('senha_nova')) || !empty(getFormData('confirm_senha_nova'))){
			$senha_nova = getFormData('senha_nova');
			$confirm_senha_nova = getFormData('confirm_senha_nova');
			if($senha_nova !== $confirm_senha_nova){
				$this->validation_errors = array(
					'confirm_senha_nova' => 'As senhas não conferem',
				);
				$this->SetErrorValidatedForm();
				rdct('/admin/users/edit/'.getFormData('id'));
			}
			$this->mdl->f['senha'] = $confirm_senha_nova;
		}
		
		$this->mdl->where = "email = '{$this->mdl->f['email']}' AND id <> '{$this->mdl->f['id']}' AND status NOT IN ('inativo')";
		
		//Verify's if the email already exists
		$search_email = $this->mdl->search(1);
		if($search_email){
			$this->validation_errors = array(
				'email' => 'Já existe um usuário com este email.',
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/users/edit/'.getFormData('id'));
		}
		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/users/detail/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/users/edit/'.getFormData('id'));
		}
	}
	
	public function login()
	{
		if($this->session->get('auth_user')){
			rdct('/admin/users/index');
		}
		$this->data['login_msg'] = $this->session->getFlashdata('login_msg');
		return $this->displayNew('pages/Admin/Users/login', false);
	}
	
	public function logout()
	{
		$this->session->destroy();
		rdct('/admin/login');
	}
	
	public function auth()
	{
		$this->PopulatePost(false);
		
		if(empty($this->mdl->f['email']) || empty($this->mdl->f['senha'])){
			header('Location: /admin/login');
			exit;
		}
		$exists = $this->mdl->SearchLogin();
		if(!$exists){
			$this->setMsgData('error', 'Login ou senha inválido(s)');
			rdct('/admin/login');
		}
		
		$this->mdl->f['id'] = $exists['id'];
		$AuthUser = $this->mdl->get();
		
		if(!hasPermission(5, 'r', false, $AuthUser['profile'])){
			$this->setMsgData('error', 'Accesso negado! Entre em contato com o administrador.');
			rdct('/admin/login');
		}
		
		$this->session->set('auth_user', $AuthUser);

		$this->mdl->f = [];
		$this->mdl->f['id'] = $exists['id'];
		$this->mdl->f['last_ip'] = $this->request->getIPAddress();
		$this->mdl->f['last_connected'] = date("Y-m-d H:i:s");
		$this->mdl->auth_user_id = $exists['id'];
		$this->mdl->saveRecord();
		
		rdct('/admin/users/index');
	}
}
