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
			'required' => true,
			'link_record' => true,
		),
		'codigo' => array(
			'lbl' => 'Código',
			'type' => 'varchar',
			'required' => true,
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
		['codigo', 'deletado']
	];

	public function getValorParametro(string $cod)
	{
		$this->select = "valor";
		$this->where['codigo'] = $cod;
		return $this->search(1)[0];
	}

	public function after_save(?string $operation = null)
	{
		if($this->f['codigo']){
			getSession()->remove('PARAM_CACHE_'.$this->f['codigo']);
		}
	}
}
?>