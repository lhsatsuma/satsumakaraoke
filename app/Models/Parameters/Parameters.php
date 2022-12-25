<?php
namespace App\Models\Parameters;

use App\Models\Basic\Basic;

class Parameters extends Basic
{
	public $db;
	public $table = 'parametros';
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
		'codigo' => [
			'lbl' => 'LBL_CODE',
			'type' => 'varchar',
			'required' => true,
			'max_length' => 255,
        ],
		'descricao' => [
			'lbl' => 'LBL_DESCRIPTION',
			'type' => 'text',
			'rows' => 10,
        ],
		'valor' => [
			'lbl' => 'LBL_PARAMETER_VALUE',
			'type' => 'varchar',
			'max_length' => 255,
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['codigo', 'deleted']
	];

	public function getParameterValue(string $cod)
	{
		$this->select = 'valor';
		$this->where['codigo'] = $cod;
		return $this->search(1)[0];
	}

	public function after_save(?string $operation = null) : bool
	{
		if($this->f['codigo']){
			getSession()->remove('PARAM_CACHE_'.$this->f['codigo']);
		}
		return true;
	}
}