<?php
namespace App\Models\PermissaoGrupo;

class PermissaoGrupo extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'permissao_grupo';
	public $f = array();
	public $id_by_name = true;
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
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
		'nome' => array(
			'lbl' => 'Nome do Arquivo',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
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
		['nome', 'deletado'],
	];

	public function hasPermission($grupo, $cod)
	{
		$this->select = "id, permissao.cod_permissao, permissao.nome";
		$this->join['permissao'] = 'permissao.id = permissao_grupo.permissao';
		$this->where['grupo'] = $grupo;

		return $this->search(1)[0];
	}
}
?>