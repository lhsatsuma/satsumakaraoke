<?php
namespace App\Models\OAuthTokens;

use App\Models\Basic\Basic;

class OAuthTokens extends Basic
{
	public $db;
	public $table = 'oauth_access_tokens';
	public array $f = [];
	public array $fields_map = [
		'access_token' => [
			'lbl' => 'LBL_ACCESS_TOKEN',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 10,
			'max_length' => 80,
        ],
		'client_id' => [
			'lbl' => 'LBL_CLIENT_ID',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 10,
			'max_length' => 80,
        ],
		'user_id' => [
			'lbl' => 'LBL_USER_ID',
			'type' => 'related',
			'required' => true,
			'table' => 'usuarios',
			'parameter' => [
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
            ],
        ],
		'expires' => [
			'lbl' => 'LBL_EXPIRES',
			'type' => 'datetime',
        ],
		'scope' => [
			'lbl' => 'LBL_SCOPE',
			'type' => 'dropdown',
			'required' => true,
			'default' => 'api',
			'parameter' => 'scope_list',
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
    ];
	public $idx_table = [
		['client_id', 'deleted'],
	];
}