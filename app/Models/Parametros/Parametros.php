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
		'name' => array(
			'lbl' => 'name do Parâmetro',
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
		'descricao' => array(
			'lbl' => 'Descrição',
			'type' => 'text',
			'rows' => 10,
		),
		'valor' => array(
			'lbl' => 'Valor Parâmetro',
			'type' => 'varchar',
			'max_length' => 255,
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
		['codigo', 'deleted']
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