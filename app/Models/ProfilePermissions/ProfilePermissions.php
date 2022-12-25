<?php
namespace App\Models\ProfilePermissions;

use App\Models\Basic\Basic;

class ProfilePermissions extends Basic
{
	public $db;
	public $table = 'permissao_grupo';
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
		'permissao' => [
			'lbl' => 'LBL_PERMISSION',
			'type' => 'related',
			'table' => 'permissao',
			'required' => true,
			'dont_load_layout' => true,
        ],
		'grupo' => [
			'lbl' => 'LBL_PROFILE',
			'type' => 'related',
			'table' => 'grupos',
			'required' => true,
			'dont_load_layout' => true,
        ],
		'nivel' => [
			'lbl' => 'LBL_LEVEL',
			'type' => 'int',
			'required' => true,
			'dont_load_layout' => true,
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['permissao', 'grupo', 'deleted'],
		['grupo', 'deleted'],
	];

	public function hasPermission(int $cod, int $grupo)
	{
		$this->select = 'permissao_grupo.id, permissao_grupo.nivel';
		$this->join['permissao'] = 'permissao.id = permissao_grupo.permissao';
		$this->where['permissao_grupo.grupo'] = $grupo;
		$this->where['permissao.id'] = $cod;
		return $this->search(1)[0];
	}
}