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
		'nome' => array(
			'lbl' => 'Nome da Permissão',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
		),
		'cod_permissao' => array(
			'lbl' => 'Código da Permissão',
			'type' => 'int',
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
		['nome', 'deletado'],
		['cod_permissao', 'deletado']
	];

	public function before_save()
	{
		if(empty($this->f['cod_permissao']) && empty($this->f['id'])){
			$this->force_deletado = true;
			$this->where = array();
			$this->select = "MAX(CAST(cod_permissao as UNSIGNED))+1 as codigo_ult";
			$number = $this->search(1);
			$this->f['cod_permissao'] = $number[0]['codigo_ult'];
			if(is_null($this->f['cod_permissao'])){
				$this->f['cod_permissao'] = 1;
			}
			$this->force_deletado = false;
		}
	}

	public function getAllPermissao(string $grupo)
	{
		if($grupo){
			$this->force_deletado = true;
			$this->select = "permissao.id, permissao.nome, permissao.cod_permissao, permissao_grupo.id as permissao_grupo_id";
			$this->join['LEFTJOIN_permissao_grupo'] = "permissao.id = permissao_grupo.permissao AND (permissao_grupo.deletado = '0' OR permissao_grupo.deletado IS NULL)";
			$this->where['permissao.deletado'] = '0';
			$this->where['permissao_grupo.grupo'] = $this->f['id'];
			$this->order_by['permissao.cod_permissao'] = 'ASC';
			$results = $this->search();
			$this->force_deletado = true;
			return $results;
			
		}


		return [];
	}
}
?>