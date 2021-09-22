<?php
namespace App\Models\PermissaoGrupo;

class PermissaoGrupo extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'permissao_grupo';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
		),
		'permissao' => array(
			'lbl' => 'Permissão',
			'type' => 'related',
			'table' => 'permissao',
			'dont_load_layout' => true,
		),
		'grupo' => array(
			'lbl' => 'Grupo',
			'type' => 'related',
			'table' => 'grupos',
			'dont_load_layout' => true,
		),
		'nivel' => array(
			'lbl' => 'Nível',
			'type' => 'int',
			'required' => true,
			'dont_load_layout' => true,
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
			'dont_load_layout' => true,
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['permissao', 'grupo', 'deletado'],
		['grupo', 'deletado'],
	];

	public function hasPermission($cod, $grupo)
	{
		$this->select = "permissao_grupo.id, permissao_grupo.nivel";
		$this->join['permissao'] = 'permissao.id = permissao_grupo.permissao';
		$this->where['permissao_grupo.grupo'] = $grupo;
		$this->where['permissao.id'] = $cod;
		return $this->search(1)[0];
	}
}
?>