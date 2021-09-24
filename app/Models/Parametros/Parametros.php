<?php
namespace App\Models\Parametros;

class Parametros extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'parametros';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
		),
		'nome' => array(
			'lbl' => 'Nome do Parâmetro',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
		),
		'codigo' => array(
			'lbl' => 'Código',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'valor' => array(
			'lbl' => 'Valor Parâmetro',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'deletado' => array(
			'lbl' => 'Deletado',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'data_criacao' => array(
			'lbl' => 'Data Criação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_criacao' => array(
			'lbl' => 'Usuário Criação',
			'type' => 'related',
			'table' => 'usuarios',
			'dont_load_layout' => true,
		),
		'data_modificacao' => array(
			'lbl' => 'Data Modificação',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'usuario_modificacao' => array(
			'lbl' => 'Usuário Modificação',
			'type' => 'related',
			'table' => 'usuarios',
			'dont_load_layout' => true,
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['nome', 'deletado']
	];

	public function getAllPermissao(string $grupo)
	{
		if($grupo){
			$this->force_deletado = true;
			$this->select = "permissao.id, permissao.nome, permissao_grupo.id as permissao_grupo_id, permissao_grupo.nivel";
			$this->join['LEFTJOIN_permissao_grupo'] = "permissao.id = permissao_grupo.permissao
			AND (permissao_grupo.deletado = '0' OR permissao_grupo.deletado IS NULL)
			AND permissao_grupo.grupo = '{$grupo}'";
			$this->where['permissao.deletado'] = '0';
			$this->order_by['permissao.id'] = 'ASC';
			$results = $this->search();
			$this->force_deletado = true;

			foreach($results as $key => $result){
				$results[$key]['id'] = (int)$result['id'];
				$results[$key]['permissao_grupo_id'] = (int)$result['permissao_grupo_id'];
				$results[$key]['nivel'] = (int)$result['nivel'];
			}
			return $results;
			
		}
		return [];
	}
}
?>