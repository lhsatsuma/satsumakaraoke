<?php
namespace App\Models\MenuLanguages;

class MenuLanguages extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'menu_languages';
	public array $f = [];
	public array $fields_map = array(
		'id' => array(
			'lbl' => 'LBL_ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
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
		'menu_id' => array(
			'lbl' => 'LBL_MENU_ID',
			'type' => 'related',
			'table' => 'menus',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Menus/Menus',
				'link_detail' => 'admin/menus/detail/',
			),
		),
		'language' => array(
			'lbl' => 'LBL_LANGUAGE',
			'type' => 'dropdown',
			'parameter' => 'languages',
			'required' => true,
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['menu_id', 'language', 'deleted'],
	];
}
?>