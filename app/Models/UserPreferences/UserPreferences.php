<?php
namespace App\Models\UserPreferences;

use Exception;

class UserPreferences extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'preferencias_usuario';
	public array $f = [];
	public array $fields_map = array(
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
		'valor' => array(
			'lbl' => 'LBL_VALUE',
			'type' => 'text',
			'required' => true,
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['user_created', 'deleted'],
		['user_created', 'name', 'deleted'],
	];

	public static function getPreference(string $name, string $user = null)
	{
		$class = new self();
		return $class->getPref($name, $user);
	}
	public function getPref(string $name, string $user = null)
	{
		$this->select = "id, name, valor";
		$this->where['name'] = $name;
		$this->where['user_created'] = ($user) ? $user : $this->auth_user_id;

		if(empty($this->where['user_created']) || empty($name)){
			return false;
		}
		return $this->search(1)[0];
	}

	public function getValue(string $name, string $user = null)
	{
		return $this->getPref($name, $user)['valor'];
	}

	public function setPref(string $name, $valor, string $user = null)
	{
		$alreadySaved = $this->getPref($name, $user);
		$this->f = [];
		if($alreadySaved['id']){
			$this->f['id'] = $alreadySaved['id'];
		}
		$this->f['name'] = $name;
		$this->f['valor'] = $valor;
		$this->f['user_created'] = ($user) ? $user : $this->auth_user_id;
		return $this->saveRecord();
	}

	public function delPref(string $name=null, string $user = null, $reset = false)
	{
		$this->select = "id";
		if($reset){
			$this->where['name'] = ['DIFF', 'timezone_user'];
		}elseif($name){
			$this->where['name'] = $name;
		}
		$this->where['user_created'] = ($user) ? $user : $this->auth_user_id;
		$success = true;
		foreach($this->search() as $result){
			$this->f = [];
			$this->f['id'] = $result['id'];
			if(!$this->deleteRecord()){
				$success = false;
			}
		}
		return $success;
	}
}
?>