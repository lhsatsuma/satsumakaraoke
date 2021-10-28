<?php
namespace App\Models\Menus;

class Menus extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'menus';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'int',
			'dont_load_layout' => true,
			'dont_generate' => true,
		),
		'nome' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
			'required' => true,
		),
		'ativo' => array(
			'lbl' => 'Ativo',
			'type' => 'bool',
			'default' => '1',
			'required' => true,
		),
		'ordem' => array(
			'lbl' => 'Ordem',
			'type' => 'int',
			'default' => '1',
			'required' => true,
		),
		'tipo' => array(
			'lbl' => 'Tipo',
			'type' => 'dropdown',
			'default' => '1',
			'parameter' => 'tipo_menu_list',
			'required' => true,
		),
		'menu_pai' => array(
			'lbl' => 'Menu Pai',
			'type' => 'related',
			'table' => 'menus',
			'parameter' => [
				'model' => 'Menus/Menus',
				'link_detail' => 'admin/menus/detalhes/',
			]
		),
		'url_base' => array(
			'lbl' => 'URL Base',
			'type' => 'varchar',
			'default' => '',
		),
		'icon' => array(
			'lbl' => 'Ícone',
			'type' => 'varchar',
			'default' => 'fas fa-list',
			'max_length' => 30,
		),
		'label' => array(
			'lbl' => 'Label',
			'type' => 'varchar',
			'max_length' => 30,
			'required' => true,
		),
		'perm' => array(
			'lbl' => 'Cód. Permissão',
			'type' => 'related',
			'table' => 'permissao',
			'parameter' => [
				'model' => 'Permissao/Permissao',
				'link_detail' => 'admin/permissao/detalhes/',
			]
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
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
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
			'parameter' => [
				'link_detail' => 'admin/usuarios/detalhes/',
			]
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['nome', 'ativo', 'tipo', 'deletado'],
		['ativo', 'tipo', 'deletado'],
	];

	public function mountArrayMenus($type = 'public')
	{
		$this->reset();
		$this->select = 'id, tipo, ordem, url_base, icon, label, perm, menu_pai';
		$this->where['ativo'] = '1';
		if($type == 'public'){
			$this->where['tipo'] = ['IN', ['1', '2']];
		}else{
			$this->where['tipo'] = ['IN', ['3', '4']];

		}
		$this->order_by['tipo'] = 'ASC';
		$this->order_by['ordem'] = 'ASC';


		$menus = [];
		foreach($this->search() as $menu_result){
			if($menu_result['tipo'] == '1' || $menu_result['tipo'] == '3'){
				$menus[$menu_result['id']] = [
					'url' => $menu_result['url_base'],
					'icon' => $menu_result['icon'],
					'lbl' => $menu_result['label'],
					'perm' => $menu_result['perm'],
				];
			}else{
				$menus[$menu_result['menu_pai']]['subs'][$menu_result['id']] = [
					'url' => $menu_result['url_base'],
					'icon' => $menu_result['icon'],
					'lbl' => $menu_result['label'],
					'perm' => $menu_result['perm'],
				];
			}
		}

		//Fixing index array of sub menus
		foreach($menus as $key_pai => $menu_pai){
			if($menus[$key_pai]['subs']){
				$menus[$key_pai]['subs'] = array_values($menus[$key_pai]['subs']);
			}
		}

		//Fixing index array of menus
		$menus = array_values($menus);

		return $menus;
	}
}
?>