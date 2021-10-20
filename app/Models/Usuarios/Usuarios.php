<?php
namespace App\Models\Usuarios;

class Usuarios extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'usuarios';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'nome' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		),
		'deletado' => array(
			'lbl' => 'Deletado',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'data_criacao' => array(
			'lbl' => 'Data Criação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_criacao' => array(
			'lbl' => 'Usuário Criação',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
		),
		'data_modificacao' => array(
			'lbl' => 'Data Modificação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_modificacao' => array(
			'lbl' => 'Usuário Modificação',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
		),
		'email' => array(
			'lbl' => 'Email',
			'type' => 'email',
			'len' => 255,
			'required' => true,
			'min_length' => 10,
			'max_length' => 255,
		),
		'senha' => array(
			'lbl' => 'Senha',
			'type' => 'password',
		),
		'status' => array(
			'lbl' => 'Status',
			'type' => 'dropdown',
			'parameter' => 'status_usuario_list',
			'default' => 'ativo',
			'required' => true,
		),
		'tipo' => array(
			'lbl' => 'Grupo',
			'type' => 'related',
			'table' => 'grupos',
			'required' => true,
			'skipRequired' => true,
			'parameter' => array(
				'model' => 'Grupos/Grupos',
				'link_detail' => 'admin/grupos/detalhes/',
			),
		),
		'last_ip' => array(
			'lbl' => 'Último IP',
			'type' => 'varchar',
		),
		'last_connected' => array(
			'lbl' => 'Última vez conectado',
			'type' => 'datetime',
		),
		'hash_esqueci_senha' => array(
			'lbl' => 'Chave Esqueci Senha',
			'type' => 'varchar',
		),
		'ultima_troca_senha' => array(
			'lbl' => 'Data Última Troca de Senha',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'dark_mode' => array(
			'lbl' => 'Modo Escuro',
			'type' => 'bool',
		),
		'timezone' => array(
			'nondb' => true,
			'lbl' => 'Fuso Horário',
			'type' => 'dropdown',
			'parameter' => 'timezones_availables',
		),
		'telefone' => array(
			'lbl' => 'Telefone',
			'type' => 'telephone',
		),
		'celular' => array(
			'lbl' => 'Celular',
			'type' => 'celphone',
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['tipo','deletado'],
	];

	public function __construct()
	{
		parent::__construct();
		$this->preference = new \App\Models\PreferenciasUsuario\PreferenciasUsuario();
	}
	
	public $template_forget_pass = 'EsqueciMinhaSenha';

	public function after_save(string $operation = null)
	{
		if($operation == 'delete'){
			$this->preference->delPref(null, $this->f['id']);
		}elseif($this->f['timezone'] || $operation == 'insert'){
			$this->preference->setPref('timezone_user', (($this->f['timezone']) ? $this->f['timezone'] : date_default_timezone_get()), $this->f['id']);
		}
	}
	
	public function SearchLogin(){
		$this->helper->select('id, senha');
		$this->helper->where('email', $this->f['email']);
		$query = $this->helper->get(1);
		log_message('debug', (string)$this->db->getLastQuery());
		if($query){
			if($query->resultID->num_rows > 0){
				$resultDB = $query->getResult('array')[0];
				if(verify_pass($this->f['senha'], $resultDB['senha'])){
					return $resultDB;
				}
			}		
		}else{
			$this->RegisterLastError("Query search login failed: ");	
		}
		return false;
	}
	public function generateHashForget()
	{
		$this->f['hash_esqueci_senha'] = str_replace('=', '00XX00', base64_encode(date("Y-m-d H:i:s")));
	}
	public function decryptHashForget()
	{
		return base64_decode(str_replace('00XX00', '=', $this->f['hash_esqueci_senha']));
	}
	public function sendForgetPass()
	{
		if(empty($this->f['email'])){
			return null;
		}
		$this->generateHashForget();
		$mail = new \App\Libraries\Sys\SendEmail();
		$mail->mailer->AddAddress($this->f['email']);
		
		$mail->subject = '['.GetTitle().'] Esqueci Minha Senha';
		$mail->setBodyTemplate($this->template_forget_pass, $this->f);
		
		if($mail->send()){
			$this->helper->where('id', $this->f['id']);
			$this->helper->update(['hash_esqueci_senha' => $this->f['hash_esqueci_senha']]);
			return true;
		}else{
			return false;
		}
	}
	public function validateHash(string $hash)
	{
		if($this->f['hash_esqueci_senha'] == $hash){
			$decrypted = $this->decryptHashForget();
			$date1 = new \DateTime($decrypted);
			$date2 = new \DateTime();
			$diff = $date2->getTimestamp() - $date1->getTimestamp();
			
			if($diff < 86400){ //Max 24 horas para utilizar
				return true;
			}
		}
		return false;
	}
	
	public function changePass($new)
	{
		$encrypted = encrypt_pass($new);
		$this->helper->where('id', $this->f['id']);
		$this->helper->update(['senha' => $encrypted, 'hash_esqueci_senha' => null, 'ultima_troca_senha' => date("Y-m-d H:i:s")]);
		return true;
	}
}
?>