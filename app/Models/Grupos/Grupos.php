<?php
namespace App\Models\Grupos;

class Grupos extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'grupos';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
		),
		'nome' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
			'required' => true,
		),
		'ativo' => array(
			'lbl' => 'Ativo',
			'type' => 'bool',
			'default' => '1',
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
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
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
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['nome', 'deletado'],
		['ativo', 'deletado'],
	];
}
?>