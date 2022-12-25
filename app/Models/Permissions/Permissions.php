<?php
namespace App\Models\Permissions;

use App\Models\Basic\Basic;

class Permissions extends Basic
{
	public $db;
	public $table = 'permissao';
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
    ];
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted']
	];

	public function getAllPermissions(string $grupo)
	{
		if($grupo){
			$this->force_deleted = true;
			$this->select = 'permissao.id, permissao.name, permissao_grupo.id as permissao_grupo_id, permissao_grupo.nivel';
			$this->join['LEFTJOIN_permissao_grupo'] = "permissao.id = permissao_grupo.permissao
			AND (permissao_grupo.deleted = '0' OR permissao_grupo.deleted IS NULL)
			AND permissao_grupo.grupo = '{$grupo}'";
			$this->where['permissao.deleted'] = '0';
			$this->order_by['permissao.id'] = 'ASC';
			$results = $this->search();
			$this->force_deleted = true;

			foreach($results as $key => $result){
				$results[$key]['id'] = (int)$result['id'];
				$results[$key]['permissao_grupo_id'] = (int)$result['permissao_grupo_id'];
				$results[$key]['nivel'] = $grupo == 1 ? 7 : (int)$result['nivel'];
			}
			return $results;
			
		}
		return [];
	}
}
?>