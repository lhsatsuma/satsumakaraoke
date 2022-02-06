<?php
namespace App\Models\MusicasFila;

class MusicasFila extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'musicas_fila';
	public $f = array();
	public $id_by_name = true;
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
		),
		'musica_id' => array(
			'lbl' => 'Música ID',
			'type' => 'related',
			'required' => true,
			'table' => 'musicas',
			'validations' => 'required',
		),
		'status' => array(
			'lbl' => 'Origem',
			'type' => 'dropdown',
			'len' => 255,
			'parameter' => 'status_musicas_fila_list',
			'required' => true,
			'validations' => 'required|max_length[255]',		
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['status', 'deleted'],
	];
}
?>