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
		'name' => array(
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
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['ativo', 'deleted'],
	];
}
?>