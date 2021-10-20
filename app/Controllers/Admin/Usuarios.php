<?php
namespace App\Controllers\Admin;

class Usuarios extends AdminBaseController
{
	public $module_name = 'Usuarios';
	public $data = array();
	public $generic_filter = true;
	
	public function ExtButtonsGenericFilters()
	{
		return array(
			'new' => '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/usuarios/editar">Novo +</a>',
		);
	}
	
	
	public function index($offset = 0)
	{
		hasPermission(1, 'r', true);
		$this->data['title'] = 'Lista de Usuários';
		
		$initial_filter = array(
			'nome' => '',
		);
		$initial_order_by = array(
			'field' => 'data_criacao',
			'order' => 'DESC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/usuarios/index',
			'generic_filter' => array(
				'nome',
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
		
		return $this->displayNew('pages/Admin/Usuarios/index');
	}
	
	public function detalhes($id)
	{
		hasPermission(1, 'r', true);
		$this->data['title'] = 'Detalhes do Usuário';
		
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		$result['timezone'] = $this->mdl->preference->getValor('timezone_user', $this->mdl->f['id']);

		$result = $this->mdl->formatRecordsView($result);
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFieldsDetails($result);

		$this->setPermData(2);
		
		return $this->displayNew('pages/Admin/Usuarios/detalhes');
	}
	
	public function editar($id = null)
	{
		hasPermission(2, 'w', true);
		$this->data['title'] = ($id) ? 'Editar Usuário' : 'Criar Usuário';
		
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
		
		return $this->displayNew('pages/Admin/Usuarios/editar');
	}
	
	public function salvar()
	{
		hasPermission(2, 'w', true);

		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				hasPermission(2, 'd', true);
				
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/usuarios/index');
				}
				$this->setMsgData('error', 'Não foi possível deletar o registro, tente novamente.');
				rdct('/admin/usuarios/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/usuarios/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/usuarios/editar/'.getFormData('id'));
		}
		
		if(empty($this->mdl->f['id'])){
			if(empty(getFormData('senha_nova')) && empty(getFormData('confirm_senha_nova'))){
				$this->validation_errors = array(
					'senha_nova' => 'É necessário digitar uma senha para novos usuários.',
				);
				$this->SetErrorValidatedForm();
				rdct('/admin/usuarios/editar/');
			}
		}
		
		if(!empty(getFormData('senha_nova')) || !empty(getFormData('confirm_senha_nova'))){
			$senha_nova = getFormData('senha_nova');
			$confirm_senha_nova = getFormData('confirm_senha_nova');
			if($senha_nova !== $confirm_senha_nova){
				$this->validation_errors = array(
					'confirm_senha_nova' => 'As senhas não conferem',
				);
				$this->SetErrorValidatedForm();
				rdct('/admin/usuarios/editar/'.getFormData('id'));
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
			rdct('/admin/usuarios/editar/'.getFormData('id'));
		}
		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/usuarios/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->setMsgData('error', $this->mdl->last_error);
			rdct('/admin/usuarios/editar/'.getFormData('id'));
		}
	}
	public function MeusDados()
	{
		$this->data['title'] = 'Meus Dados';
		
		$this->mdl->f['id'] = $this->session->get('auth_user')['id'];
		
		$result = $this->mdl->get();
		$result = $this->mdl->formatRecordsView($result);
		
		$save_data = $this->session->getFlashdata('save_data');
		if(!is_null($save_data)){
			$result = array_merge($result, $save_data);
		}
		
		$this->data['record'] = $result;
		
		$this->data['layout'] = $this->layout->GetAllFields($result);
		
		
		return $this->displayNew('pages/Admin/Usuarios/meusDados');
	}
	
	public function salvarMeusDados()
	{
		$this->PopulatePost();
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/usuarios/MeusDados');
		}
		
		$AuthUser = $this->session->get('auth_user');
		
		if(!verify_pass(getFormData('senha_atual'), $AuthUser['senha'])){
			$this->validation_errors = array(
				'senha_atual' => 'A senha atual não confere.',
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/usuarios/MeusDados');
		}
		
		if(!empty(getFormData('senha_nova')) || !empty(getFormData('confirm_senha_nova'))){
			$senha_nova = getFormData('senha_nova');
			$confirm_senha_nova = getFormData('confirm_senha_nova');
			if($senha_nova !== $confirm_senha_nova){
				$this->validation_errors = array(
					'confirm_senha_nova' => 'As senhas não conferem',
				);
				$this->SetErrorValidatedForm();
				rdct('/admin/usuarios/MeusDados');
			}
			$this->mdl->f['senha'] = $confirm_senha_nova;
		}
		$this->mdl->f['id'] = $AuthUser['id'];
		
		
		$this->mdl->where = "email = '{$this->mdl->f['email']}' AND id <> '{$this->mdl->f['id']}' AND status NOT IN ('inativo')";
		
		//Verify's if the email already exists
		$search_email = $this->mdl->search(1);
		if($search_email){
			$this->validation_errors = array(
				'email' => 'Já existe um usuário com este email.',
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/usuarios/MeusDados');
		}
		$saved = $this->mdl->saveRecord();
		if($saved){
			$AuthUser['nome'] = $saved['nome'];
			$AuthUser['email'] = $saved['email'];
			$AuthUser['senha'] = $saved['senha'];
			$this->session->set('auth_user', $AuthUser);

			$this->setMsgData('sucess', 'Dados atualizados com sucesso!');
			rdct('/admin/usuarios/MeusDados');
		}else{

			$this->setMsgData('error', $this->mdl->last_error);
			
			rdct('/admin/admin/usuarios/MeusDados');
		}
	}
	
	public function login()
	{
		if($this->session->get('auth_user')){
			rdct('/admin/usuarios/index');
		}
		$this->data['login_msg'] = $this->session->getFlashdata('login_msg');
		return $this->displayNew('pages/Admin/Usuarios/login', false);
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
		
		if(!hasPermission(5, 'r', false, $AuthUser['tipo'])){
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
		
		rdct('/admin/usuarios/index');
	}
}
