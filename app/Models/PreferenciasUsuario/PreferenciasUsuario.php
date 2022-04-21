<?php
namespace App\Models\PreferenciasUsuario;

use Exception;

class PreferenciasUsuario extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'preferencias_usuario';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'name' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		),
		'valor' => array(
			'lbl' => 'Valor',
			'type' => 'text',
			'required' => true,
		),
		'deleted' => array(
			'lbl' => 'deleted',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'date_created' => array(
			'lbl' => 'Data Criação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_created' => array(
			'lbl' => 'Usuário Criação',
			'type' => 'related',
			'table' => 'usuarios',
			'dont_load_layout' => true,
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
		),
		'date_modified' => array(
			'lbl' => 'Data Modificação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_modified' => array(
			'lbl' => 'Usuário Modificação',
			'type' => 'related',
			'table' => 'usuarios',
			'dont_load_layout' => true,
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
		),
	);
	public $primary_keys = [
		'id'
	];
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

	public function getValor(string $name, string $user = null)
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