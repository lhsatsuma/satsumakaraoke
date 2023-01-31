<?php
namespace App\Models\Musics;

use App\Models\Basic\Basic;
use App\Models\Files\Files;
use getID3;
class Musics extends Basic
{
	public $db;
	public $table = 'musicas';
	public array $f = [];
	public array $fields_map = [
		'id' => [
			'lbl' => 'LBL_ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
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
		'link' => [
			'lbl' => 'LBL_LINK',
			'type' => 'link',
			'required' => true,
			'max_length' => 255,
			'validations' => 'required',
        ],
		'origem' => [
			'lbl' => 'LBL_ORIGIN',
			'type' => 'dropdown',
			'parameter' => 'origem_musica_list',
			'required' => true,
			'max_length' => 255,
        ],
		'codigo' => [
			'lbl' => 'LBL_CODE',
			'type' => 'int',
			'required' => true,
        ],
		'md5' => [
			'lbl' => 'LBL_MD5',
			'type' => 'varchar',
			'max_length' => 255,
        ],
		'tipo' => [
			'lbl' => 'LBL_LANGUAGE',
			'type' => 'dropdown',
			'parameter' => 'tipo_musica',
			'required' => true,
        ],
		'duration' => [
			'lbl' => 'LBL_DURATION',
			'type' => 'int',
			'default' => 0,
			'required' => true,
        ],
		'fvt' => [
			'nondb' => true,
			'lbl' => 'LBL_MY_FAVORITES',
			'type' => 'bool',
        ]
    ];
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
	
	public function force_save(string $link, string $md5, string $title, string $tipo)
	{
        $return_data = [];
        $this->force_deleted = true;
		$this->where = [
			'md5' => $md5,
        ];
		$result = $this->search(1);
		if($result[0]){
			$return_data['exists'] = true;
			$this->f['id'] = $result[0]['id'];
			$this->f['codigo'] = $result[0]['codigo'];
		}
		if(empty($this->f['codigo'])){
			$this->where = [];
			$this->select = 'MAX(codigo)+1 as codigo_ult';
			$number = $this->search(1);
			$this->f['codigo'] = $number[0]['codigo_ult'];
			if(is_null($this->f['codigo'])){
				$this->f['codigo'] = 1;
			}
		}
        $this->force_deleted = false;
		$this->f['name'] = $title;
		$this->f['md5'] = $md5;
		$this->f['link'] = $link;
		$this->f['tipo'] = $tipo;
		$this->f['origem'] = 'UserImport';
        $this->f['deleted'] = 0;

		$file_path = FCPATH . 'uploads/'.$md5;
		$getID3 = new getID3();
		$analized = $getID3->analyze($file_path);
		$this->f['duration'] = (int)$analized['playtime_seconds'];

		$return_data['saved_record'] = $this->saveRecord();
		if($return_data['saved_record']){
			$file = new Files();
            $file->force_deleted = true;
            //Check if already has File Record
            $file->select = 'id';
            $file->where['id'] = $md5;
            $file_exists = $this->search(1);

            //If exists, just update
            $file->new_with_id = !$file_exists[0]['id'];

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