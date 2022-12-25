<?php
namespace App\Models\MenuLanguages;

use App\Models\Basic\Basic;

class MenuLanguages extends Basic
{
	public $db;
	public $table = 'menu_languages';
	public array $f = [];
	public array $fields_map = [
		'id' => [
			'lbl' => 'LBL_ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
        ],
		'name' => [
			'lbl' => 'LBL_NAME',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
        ],
		'deleted' => [
			'lbl' => 'LBL_DELETED',
			'type' => 'bool',
			'dont_load_layout' => true,
        ],
		'date_created' => [
			'lbl' => 'LBL_DATE_CREATED',
			'type' => 'datetime',
			'dont_load_layout' => true,
        ],
		'user_created' => [
			'lbl' => 'LBL_USER_CREATED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => [
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
            ],
			'dont_load_layout' => true,
        ],
		'date_modified' => [
			'lbl' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'dont_load_layout' => true,
        ],
		'user_modified' => [
			'lbl' => 'LBL_USER_MODIFIED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => [
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
            ],
			'dont_load_layout' => true,
        ],
		'menu_id' => [
			'lbl' => 'LBL_MENU_ID',
			'type' => 'related',
			'table' => 'menus',
			'parameter' => [
				'url' => null,
				'model' => 'Admin/Menus/Menus',
				'link_detail' => 'admin/menus/detail/',
            ],
        ],
		'language' => [
			'lbl' => 'LBL_LANGUAGE',
			'type' => 'dropdown',
			'parameter' => 'languages',
			'required' => true,
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['menu_id', 'language', 'deleted'],
	];
}