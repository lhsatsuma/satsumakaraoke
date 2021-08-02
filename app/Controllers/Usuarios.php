<?php
namespace App\Controllers;

class Usuarios extends BaseController
{
	public $module_name = 'Usuarios';
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		
		$data = array(
			'login_msg' => '',
		);
		if($this->uri[0] !== 'login' && $this->uri[1] !== 'MeusDados' && $this->uri[1] !== 'salvarMeusDados'){
			$this->access_cfg['admin_only'] = true;
		}
		$this->data = array_merge($data, $this->data);
	}
	
	public function index()
	{
		rdct('/usuarios/MeusDados');
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
		
		
		return $this->display_template($this->view->setData($this->data)->view('pages/Usuarios/meusDados'));
	}
	
	public function createUser()
	{
		$this->PopulatePost();
		$this->mdl->where['email'] = getFormData('email');
		$this->mdl->f['status'] = 'ativo';
		$founded = $this->mdl->search(1);

		if(getFormData('senha') !== getFormData('senha_repeat')
			|| $founded[0]){
			rdct('/login/criarConta');
		}

		$saved = $this->mdl->saveRecord();
		// $saved = false;
		if($saved){
			$this->session->setFlashdata('login_msg', 'Conta criado com sucesso!');
			rdct('/login');
		}else{
			$this->SetErrorValidatedForm();
			$this->session->setFlashdata('login_msg', 'Erro ao criar a conta! Tente novamente.');
			rdct('/login/criarConta');
		}
	}
	
	public function criarConta()
	{
		if($this->session->get('auth_user')){
			rdct('/home');
		}
		$this->data['title'] = 'Criar Conta';
		
		return $this->display($this->view->setData($this->data)->view('pages/Usuarios/criarConta'));
	}
	
	public function checkExistEmail()
	{
		if($this->session->get('auth_user')){
			rdct('/home');
		}
		$ajaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$ajaxLib->CheckIncoming();

		$body = $ajaxLib->GetData();

		$this->mdl->where['email'] = $body['chkEmail'];

		$founded = $this->mdl->search(1);

		if($founded[0]){
			$ajaxLib->setSuccess(['exists' => true]);
		}
		$ajaxLib->setSuccess(['exists' => false]);
	}
	
	public function salvarMeusDados()
	{
		$this->PopulatePost();
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/usuarios/MeusDados');
		}
		
		$AuthUser = $this->session->get('auth_user');
		
		$SenhaAtual = md5(getFormData('senha_atual'));
		
		if($AuthUser['senha'] !== $SenhaAtual){
			$this->validation_errors = array(
				'senha_atual' => 'A senha atual não confere.',
			);
			$this->SetErrorValidatedForm();
			rdct('/usuarios/MeusDados');
		}
		
		if(!empty(getFormData('senha_nova')) || !empty(getFormData('confirm_senha_nova'))){
			$senha_nova = getFormData('senha_nova');
			$confirm_senha_nova = getFormData('confirm_senha_nova');
			if($senha_nova !== $confirm_senha_nova){
				$this->validation_errors = array(
					'confirm_senha_nova' => 'As senhas não conferem',
				);
				$this->SetErrorValidatedForm();
				rdct('/usuarios/MeusDados');
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
			rdct('/usuarios/MeusDados');
		}
		$saved = $this->mdl->saveRecord();
		if($saved){
			$this->validation_errors = array(
				'generic_error' => 'Dados atualizados com sucesso!',
			);
			$this->SetErrorValidatedForm(false);
			rdct('/usuarios/MeusDados');
		}else{
			$this->validation_errors = array(
				'generic_error' => $this->mdl->last_error,
			);
			$this->SetErrorValidatedForm();
			rdct('/usuarios/MeusDados');
		}
	}
	
	public function login()
	{
		if($this->session->get('auth_user')){
			rdct('/home');
		}
		$this->data['login_msg'] = $this->session->getFlashdata('login_msg');
		return $this->display($this->view->setData($this->data)->view('pages/Usuarios/login'));
	}
	
	public function logout()
	{
		$this->session->destroy();
		rdct('/login');
	}
	
	
	public function auth()
	{
		$this->PopulatePost(true);
		
		if(empty($this->mdl->f['email']) || empty($this->mdl->f['senha'])){
			header('Location: /login');
			exit;
		}
		$exists = $this->mdl->SearchLogin();
		if(!$exists){
			$this->session->setFlashdata('login_msg', 'Login ou senha inválido(s)');
			rdct('/login');
		}
		
		$this->mdl->f['id'] = $exists['id'];
		$AuthUser = $this->mdl->get();

		$this->session->set('auth_user', $AuthUser);

		$this->mdl->f = [];
		$this->mdl->f['id'] = $exists['id'];
		$this->mdl->f['last_ip'] = $this->request->getIPAddress();
		$this->mdl->f['last_connected'] = date("Y-m-d H:i:s");
		$this->mdl->auth_user_id = $exists['id'];
		$this->mdl->saveRecord();
		
		rdct('/home');
	}
}
