<?php
namespace App\Models\Menus;

use App\Models\Basic\Basic;
use App\Models\MenuLanguages\MenuLanguages;

class Menus extends Basic
{
	public $db;
	public $table = 'menus';
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
		'ativo' => [
			'lbl' => 'LBL_ACTIVE',
			'type' => 'bool',
			'default' => '1',
			'required' => true,
        ],
		'ordem' => [
			'lbl' => 'LBL_ORDER',
			'type' => 'int',
			'default' => '1',
			'required' => true,
        ],
		'tipo' => [
			'lbl' => 'LBL_TYPE',
			'type' => 'dropdown',
			'default' => '1',
			'parameter' => 'tipo_menu_list',
			'required' => true,
        ],
		'menu_pai' => [
			'lbl' => 'LBL_PARENT_MENU',
			'type' => 'related',
			'table' => 'menus',
			'parameter' => [
				'model' => 'Menus/Menus',
				'link_detail' => 'admin/menus/detalhes/',
			]
        ],
		'url_base' => [
			'lbl' => 'LBL_URL_BASE',
			'type' => 'varchar',
			'default' => '',
        ],
		'icon' => [
			'lbl' => 'LBL_ICON',
			'type' => 'varchar',
			'default' => 'fas fa-list',
			'max_length' => 30,
        ],
		'perm' => [
			'lbl' => 'LBL_PERMISSION',
			'type' => 'related',
			'table' => 'permissao',
			'parameter' => [
				'model' => 'Permissions/Permissions',
				'link_detail' => 'admin/permissions/detail/',
			]
        ],
    ];
	public $idx_table = [
		['id', 'deleted'],
		['name', 'ativo', 'tipo', 'deleted'],
		['ativo', 'tipo', 'deleted'],
	];

	public function after_save(string $operation = null) : bool
	{
		$this->session->remove('arr_menu_template');
		$this->session->remove('arr_menu_template_admin');
        return true;
	}

	public function mountArrayMenus($type = 'public')
	{
		$this->reset();
		$locale = service('request')->getLocale();
		$this->select = 'menus.id, menus.tipo, menus.ordem, menus.url_base, menus.icon, menus.perm, menus.menu_pai as parent_menu, menu_languages.name as label';
		$this->join['LEFTJOIN_menu_languages'] = "menu_languages.menu_id = menus.id AND menu_languages.language = '{$locale}'";
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
					'lbl' => $menu_result['label'] ?: 'LBL_ERR_MENU_LANGUAGE_NOT_FOUND',
					'perm' => $menu_result['perm'],
				];
			}else{
				$menus[$menu_result['parent_menu']]['subs'][$menu_result['id']] = [
					'url' => $menu_result['url_base'],
					'icon' => $menu_result['icon'],
					'lbl' => $menu_result['label'] ?: 'LBL_ERR_MENU_LANGUAGE_NOT_FOUND',
					'perm' => $menu_result['perm'],
				];
			}
		}

		//Fixing index array of sub menus
		foreach($menus as $key_parent => $parent_menu){
			if($parent_menu['subs']){
				$menus[$key_parent]['subs'] = array_values($parent_menu['subs']);
			}
		}

		//Fixing index array of menus
        return array_values($menus);
	}

	public function getLanguagesMenu()
	{
		if($this->f['id']){
			$menu_languages = new MenuLanguages();
			$menu_languages->select = 'id,name,language';
			$menu_languages->where['menu_id'] = $this->f['id'];
			return $menu_languages->search();
		}
		return [];
	}
}