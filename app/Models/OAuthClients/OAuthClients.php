<?php
namespace App\Models\OAuthClients;

class OAuthClients extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'oauth_clients';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'LBL_ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'name' => array(
			'lbl' => 'LBL_NAME',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
			'max_length' => 255,
		),
		'client_id' => array(
			'lbl' => 'LBL_CLIENT_ID',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 10,
			'max_length' => 80,
		),
		'client_secret' => array(
			'lbl' => 'LBL_CLIENT_SECRET',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 10,
			'max_length' => 80,
		),
		'grant_types' => array(
			'lbl' => 'LBL_GRANT_TYPES',
			'type' => 'dropdown',
			'required' => true,
			'default' => 'password',
			'parameter' => 'grant_types_list',
		),
		'scope' => array(
			'lbl' => 'LBL_SCOPE',
			'type' => 'dropdown',
			'required' => true,
			'default' => 'api',
			'parameter' => 'scope_list',
		),
		'user_id' => array(
			'lbl' => 'LBL_USER_ID',
			'type' => 'related',
			'required' => true,
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
			),
		),
		'deleted' => array(
			'lbl' => 'LBL_DELETED',
			'type' => 'bool',
			'dont_load_layout' => true,
		),
		'date_created' => array(
			'lbl' => 'LBL_DATE_CREATED',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_created' => array(
			'lbl' => 'LBL_USER_CREATED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
			),
			'dont_load_layout' => true,
		),
		'date_modified' => array(
			'lbl' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'dont_load_layout' => true,
		),
		'user_modified' => array(
			'lbl' => 'LBL_USER_MODIFIED',
			'type' => 'related',
			'table' => 'usuarios',
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Users/Users',
				'link_detail' => 'admin/users/detail/',
			),
			'dont_load_layout' => true,
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['id','client_id', 'deleted'],
	];

	public function checkExists()
	{
		$this->where = [];
		$this->where['client_id'] = $this->f['client_id'];
		if($this->f['id']){
			$this->where['id'] = ['DIFF', $this->f['id']];
		}
		$this->select = "id";
		$number = $this->search(1);
		if($number[0]['id']){
			return true;
		}

		return false;
	}
}
?>