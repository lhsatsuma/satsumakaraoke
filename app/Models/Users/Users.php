<?php
namespace App\Models\Users;

class Users extends \App\Models\Basic\Basic
{
	public $table = 'usuarios';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'LBL_ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'name' => array(
			'lbl' => 'LBL_NAME',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		),
		'deleted' => array(
			'lbl' => 'LBL_DELETED',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'date_created' => array(
			'lbl' => 'LBL_DATE_CREATED',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_created' => array(
			'lbl' => 'LBL_USER_CREATED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
			),
			'dont_load_layout' => true,
		),
		'date_modified' => array(
			'lbl' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_modified' => array(
			'lbl' => 'LBL_USER_MODIFIED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
			),
			'dont_load_layout' => true,
		),
		'email' => array(
			'lbl' => 'LBL_EMAIL',
			'type' => 'email',
			'len' => 255,
			'required' => true,
			'min_length' => 10,
			'max_length' => 255,
		),
		'senha' => array(
			'lbl' => 'LBL_PASSWORD',
			'type' => 'password',
		),
		'status' => array(
			'lbl' => 'LBL_STATUS',
			'type' => 'dropdown',
			'parameter' => 'status_usuario_list',
			'default' => 'ativo',
			'required' => true,
		),
		'profile' => array(
			'lbl' => 'LBL_PROFILE',
			'type' => 'related',
			'table' => 'profiles',
			'required' => true,
			'skipRequired' => true,
			'parameter' => array(
				'model' => 'Profiles/Profiles',
				'link_detail' => 'admin/profiles/detail/',
			),
		),
		'last_ip' => array(
			'lbl' => 'LBL_LAST_IP',
			'type' => 'varchar',
		),
		'last_connected' => array(
			'lbl' => 'LBL_LAST_CONNECTED',
			'type' => 'datetime',
		),
		'hash_esqueci_senha' => array(
			'lbl' => 'LBL_HASH_ESQUECI_SENHA',
			'type' => 'varchar',
		),
		'ultima_troca_senha' => array(
			'lbl' => 'LBL_ULTIMA_TROCA_SENHA',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'dark_mode' => array(
			'lbl' => 'LBL_DARK_MODE',
			'type' => 'bool',
		),
		'timezone' => array(
			'nondb' => true,
			'lbl' => 'LBL_TIMEZONE',
			'type' => 'dropdown',
			'parameter' => 'timezones_availables',
		),
		'telefone' => array(
			'lbl' => 'LBL_TELEPHONE',
			'type' => 'telephone',
		),
		'celular' => array(
			'lbl' => 'LBL_CELPHONE',
			'type' => 'celphone',
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['profile','deleted'],
	];

	public function __construct()
	{
		parent::__construct();
		$this->preference = new \App\Models\UserPreferences\UserPreferences();
	}
	
	public $template_forget_pass = 'EsqueciMinhaSenha';

	public function after_save(string $operation = null) : bool
	{
		if($operation == 'delete'){
			$this->preference->delPref(null, $this->f['id']);
		}else{
			$this->preference->setPref('timezone_user', (($this->f['timezone']) ? $this->f['timezone'] : date_default_timezone_get()), $this->f['id']);
		}
		return true;
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
		
		$mail->subject = '['.removeAccents(getTitle()).'] Esqueci Minha Senha';
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