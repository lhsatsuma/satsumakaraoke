<?php
namespace App\Models\Musics;

class Musics extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'musicas';
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
		'link' => array(
			'lbl' => 'LBL_LINK',
			'type' => 'link',
			'required' => true,
			'max_length' => 255,
			'validations' => 'required',
		),
		'origem' => array(
			'lbl' => 'LBL_ORIGIN',
			'type' => 'dropdown',
			'parameter' => 'origem_musica_list',
			'required' => true,
			'max_length' => 255,
		),
		'codigo' => array(
			'lbl' => 'LBL_CODE',
			'type' => 'int',
			'required' => true,
		),
		'md5' => array(
			'lbl' => 'LBL_MD5',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'tipo' => array(
			'lbl' => 'LBL_LANGUAGE',
			'type' => 'dropdown',
			'parameter' => 'tipo_musica',
			'required' => true,
		),
		'duration' => array(
			'lbl' => 'LBL_DURATION',
			'type' => 'int',
			'default' => 0,
			'required' => true,
		),
		'fvt' => array(
			'nondb' => true,
			'lbl' => 'LBL_MY_FAVORITES',
			'type' => 'bool',
		)
	);
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['tipo', 'deleted']
	];

	public function before_save(string $operation = null) : bool
	{
		if($this->f['name']){
			$this->f['name'] = trim($this->f['name']);
		}
		return true;
	}
	
	function force_save(string $link, string $md5, string $title, string $tipo)
	{
		$this->where = array(
			'md5' => $md5,
		);
		$result = $this->search(1);
		if($result[0]){
			$return_data['exists'] = true;
			$this->f['id'] = $result[0]['id'];
			$this->f['codigo'] = $result[0]['codigo'];
		}
		if(empty($this->f['codigo'])){
			$this->force_deleted = true;
			$this->where = array();
			$this->select = "MAX(codigo)+1 as codigo_ult";
			$number = $this->search(1);
			$this->f['codigo'] = $number[0]['codigo_ult'];
			if(is_null($this->f['codigo'])){
				$this->f['codigo'] = 1;
			}
			$this->force_deleted = false;
		}
		$this->f['name'] = $title;
		$this->f['md5'] = $md5;
		$this->f['link'] = $link;
		$this->f['tipo'] = $tipo;
		$this->f['origem'] = 'UserImport';

		$file_path = FCPATH . 'uploads/'.$md5;
		$getID3 = new \getID3();
		$analized = $getID3->analyze($file_path);
		$this->f['duration'] = (int)$analized['playtime_seconds'];

		$return_data['saved_record'] = $this->saveRecord();
		if($return_data['saved_record'] && !$return_data['exists']){
			$file = new \App\Models\Files\Files();
			$file->new_with_id = true;
			$file->f['id'] = $md5;
			$file->f['name'] = $title.'.mp4';
			$file->f['arquivo'] = $md5;
			$file->f['mimetype'] = 'video/mp4';
			$file->f['tipo'] = 'private';
			$file->f['registro'] = $this->f['id'];
			$file->f['tabela'] = $this->table;
			$file->f['campo'] = 'arquivo_id';
			$file->saveRecord();
		}
		$return_data['saved'] = true;
		return $return_data;
	}
}
?>