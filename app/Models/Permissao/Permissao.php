<?php
namespace App\Models\Permissao;

class Permissao extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'permissao';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
		),
		'name' => array(
			'lbl' => 'name da Permissão',
			'type' => 'varchar',
			'max_length' => 255,
			'required' => true,
			'link_record' => true,
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
		['name', 'deleted']
	];

	public function getAllPermissao(string $grupo)
	{
		if($grupo){
			$this->force_deleted = true;
			$this->select = "permissao.id, permissao.name, permissao_grupo.id as permissao_grupo_id, permissao_grupo.nivel";
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
				$results[$key]['nivel'] = ($grupo == 1) ? 7 : (int)$result['nivel'];
			}
			return $results;
			
		}
		return [];
	}
}
?>