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
				'senha_atual' => 'A senha atual n??o confere.',
			);
			$this->SetErrorValidatedForm();
			rdct('/usuarios/MeusDados');
		}
		
		if(!empty(getFormData('senha_nova')) || !empty(getFormData('confirm_senha_nova'))){
			$senha_nova = getFormData('senha_nova');
			$confirm_senha_nova = getFormData('confirm_senha_nova');
			if($senha_nova !== $confirm_senha_nova){
				$this->validation_errors = array(
					'confirm_senha_nova' => 'As senhas n??o conferem',
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
				'email' => 'J?? existe um usu??rio com este email.',
			);
			$this->SetErrorValidatedForm();
			rdct('/usuarios/MeusDados');
		}
		$saved = $this->mdl->saveRecord();
		if($saved){
			$this->validation_errors = array(
				'generic_error' => 'Dados atualizados com sucesso!',
			);
			$AuthUser = $this->mdl->get();
	
			$this->session->set('auth_user', $AuthUser);
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
			$this->session->setFlashdata('login_msg', 'Login ou senha inv??lido(s)');
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
	
	public function send_forget_pass()
	{
		$ajax = new \App\Libraries\Sys\AjaxLib($this->request);
		$ajax->CheckIncoming();
		$ajax->CheckRequired([
			'email',
		]);
		$this->mdl->where['email'] = $ajax->body['email'];
		$this->mdl->order_by['id'] = 'ASC';
		$result = $this->mdl->search(1)[0];
		if($result){
			$this->mdl->fillF($result);
			$sended = $this->mdl->sendForgetPass();
			if($sended){
				$ajax->setSuccess($this->mdl->f['email']);
			}else{
				$ajax->setError('1x001', 'N??o foi poss??vel enviar o email! Tente novamente.');
			}
		}else{
			$ajax->setError('0x001', 'usu??rio n??o encontrado');
		}
	}
	
	public function fgt_rcv($id, $hash)
	{
		if(empty($id) || empty($hash)){
			rdct('/home');
		}
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		$valid = false;
		if($result){
			$this->mdl->fillF($result);
			$valid = $this->mdl->validateHash($hash);
		}
		if($valid){
			$this->data['id'] = $id;
			$this->data['hash'] = $hash;
			$this->data['email'] = $this->mdl->f['email'];
			return $this->display($this->view->setData($this->data)->view('pages/Usuarios/resetSenha'));
		}else{
			//Caso deu algum erro, redirecionar para view de falha
			$this->session->setFlashdata('login_msg', 'A chave para recupera????o ?? inv??lida! Talvez a chave n??o existe ou expirou.');
			rdct('/login');
		}
	}
	
	public function save_fgt_pass()
	{
		$id = $this->request->getPost('id');
		$hash = $this->request->getPost('hash');
		
		if(empty($this->request->getPost('nova_senha'))
		|| empty($this->request->getPost('confirm_nova_senha'))
		|| empty($id)
		|| empty($hash)){
			rdct('/home');
		}
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		$valid = false;
		if($result){
			$this->mdl->fillF($result);
			$valid = $this->mdl->validateHash($hash);
		}
		if($valid){
			$nova = $this->request->getPost('nova_senha');
			$confirm = $this->request->getPost('confirm_nova_senha');
			$changedValid = true;
			if($nova === $confirm){
				
				$changed = $this->mdl->changePass($nova);
				if($changed){
					$this->session->setFlashdata('login_msg', 'Senha alterada com sucesso! Voc?? j?? pode fazer o login com a nova senha.');
					rdct('/login');
				}
				$this->data['login_msg'] = 'N??o foi poss??vel alterar a senha! Tente novamente.';
				$changedValid = false;
			}else{
				$this->data['login_msg'] = 'As senhas n??o conferem!';
				$changedValid = false;
			}
			
			if(!$changedValid){
				$this->data['id'] = $id;
				$this->data['hash'] = $hash;
				$this->data['usuario'] = $this->mdl->f['usuario'];
				return $this->display_template($this->view->setData($this->data)->view('pages/Usuarios/resetSenha'));
			}
		}else{
			//Caso deu algum erro, redirecionar para view de falha
			$this->session->setFlashdata('login_msg', 'A chave para recupera????o ?? inv??lida! Talvez a chave n??o existe ou expirou.');
			rdct('/login');
		}
	}
}
