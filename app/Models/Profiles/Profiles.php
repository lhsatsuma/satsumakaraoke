<?php
namespace App\Models\Profiles;

use App\Models\Basic\Basic;

class Profiles extends Basic
{
	public $db;
	public $table = 'profiles';
	public array $f = [];
	public array $fields_map = [
		'id' => [
			'lbl' => 'LBL_ID',
			'type' => 'int',
			'max_length' => 36,
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
		'ativo' => [
			'lbl' => 'LBL_ACTIVE',
			'type' => 'bool',
			'default' => '1',
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['ativo', 'deleted'],
	];
}