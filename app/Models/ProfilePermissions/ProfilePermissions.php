<?php
namespace App\Models\ProfilePermissions;

class ProfilePermissions extends \App\Models\Basic\Basic
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
			'required' => true,
			'dont_load_layout' => true,
		),
		'grupo' => array(
			'lbl' => 'Grupo',
			'type' => 'related',
			'table' => 'grupos',
			'required' => true,
			'dont_load_layout' => true,
		),
		'nivel' => array(
			'lbl' => 'Nível',
			'type' => 'int',
			'required' => true,
			'dont_load_layout' => true,
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
				'link_detail' => 'admin/users/detail/',
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
				'link_detail' => 'admin/users/detail/',
			]
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['permissao', 'grupo', 'deleted'],
		['grupo', 'deleted'],
	];

	public function hasPermission(int $cod, int $grupo)
	{
		$this->select = "permissao_grupo.id, permissao_grupo.nivel";
		$this->join['permissao'] = 'permissao.id = permissao_grupo.permissao';
		$this->where['permissao_grupo.grupo'] = $grupo;
		$this->where['permissao.id'] = $cod;
		return $this->search(1)[0];
	}
}
?>