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
		'nome' => array(
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
	);
	public $idx_table = [
		['id', 'deletado'],
		['usuario_criacao', 'deletado'],
		['usuario_criacao', 'nome', 'deletado'],
	];

	public function getPref(string $nome, string $user = null)
	{
		$this->select = "id, nome, valor";
		$this->where['nome'] = $nome;
		$this->where['usuario_criacao'] = ($user) ? $user : $this->auth_user_id;

		if(empty($this->where['usuario_criacao']) || empty($nome)){
			return false;
		}
		return $this->search(1)[0];
	}

	public function getValor(string $nome, string $user = null)
	{
		return $this->getPref($nome, $user)['valor'];
	}

	public function setPref(string $nome, $valor, string $user = null)
	{
		$alreadySaved = $this->getPref($nome, $user);
		$this->f = [];
		if($alreadySaved['id']){
			$this->f['id'] = $alreadySaved['id'];
		}
		$this->f['nome'] = $nome;
		$this->f['valor'] = $valor;
		$this->f['usuario_criacao'] = ($user) ? $user : $this->auth_user_id;
		return $this->saveRecord();
	}

	public function delPref(string $nome=null, string $user = null)
	{
		$this->select = "id";
		if($nome){
			$this->where['nome'] = $nome;
		}
		$this->where['usuario_criacao'] = ($user) ? $user : $this->auth_user_id;

		foreach($this->search() as $result){
			$this->f = [];
			$this->f['id'] = $result['id'];
			$this->deleteRecord();
		}
	}
}
?>