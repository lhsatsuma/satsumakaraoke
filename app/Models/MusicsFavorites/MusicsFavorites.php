<?php
namespace App\Models\MusicsFavorites;

use App\Models\Basic\Basic;

class MusicsFavorites extends Basic
{
	public $db;
	public $table = 'musicas_favorites';
	public array $f = [];
	public  bool $id_by_name = true;
	public array $fields_map = [
		'id' => [
			'lbl' => 'LBL_ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
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
		'musica_id' => [
			'lbl' => 'LBL_MUSIC_ID',
			'type' => 'related',
			'required' => true,
			'table' => 'musicas',
			'validations' => 'required',
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['user_created', 'deleted']
	];
}