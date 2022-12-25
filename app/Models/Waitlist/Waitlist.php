<?php
namespace App\Models\Waitlist;

class Waitlist extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'musicas_fila';
	public array $f = [];
	public  bool $id_by_name = true;
	public array $fields_map = array(
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
		'musica_id' => array(
			'lbl' => 'LBL_MUSIC_ID',
			'type' => 'related',
			'required' => true,
			'table' => 'musicas',
			'validations' => 'required',
		),
		'status' => array(
			'lbl' => 'LBL_STATUS',
			'type' => 'dropdown',
			'len' => 255,
			'parameter' => 'status_musicas_fila_list',
			'required' => true,
			'validations' => 'required|max_length[255]',		
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['status', 'deleted'],
	];

	public function after_save(?string $operation = null) : bool
	{
		return $this->createJSON();
	}

	public function createJSON()
	{
		log_message('debug', 'Creating JSON musics in line...');
		$this->select = "musicas_fila.id,
		usuarios.name as cantor,
		musicas.codigo,
		musicas.name as name_musica,
		musicas.md5,
		musicas_fila.name as numero_fila,
		musicas.duration";
		$this->where["status"] = "pendente";
		$this->join["musicas"] = "musicas.id = musicas_fila.musica_id";
		$this->join["usuarios"] = "usuarios.id = musicas_fila.user_created";
		$this->order_by["musicas_fila.date_created"] = "ASC";

		$total_rows = $this->total_rows();
		$this->page_as_offset = true;
		$result = $this->search();
		if(is_null($result)){
			return false;
		}
		foreach($result as $key => $fila){
			$result[$key]['cantor'] = explode(' ', $result[$key]['cantor'], 3);
			$result[$key]['cantor'] = $result[$key]['cantor'][0] . ' '.$result[$key]['cantor'][1]; 
			if(strlen($result[$key]['cantor']) > 11){
				$result[$key]['cantor'] = mb_substr(trim($result[$key]['cantor']), 0, 11) . '...';
			}
			if(strlen($fila['name_musica']) > 32){
				$result[$key]['name_musica'] = mb_substr($fila['name_musica'], 0, 32) . '...';
			}
			$result[$key]['codigo'] = (int)$result[$key]['codigo'];
			$result[$key] = array_values($result[$key]);
		}
		$result = array_values($result);
		log_message('debug', 'Creating JSON musics in line... DONE');
		return file_put_contents(WRITEPATH . 'utils/line_music.json', json_encode(['t' => $total_rows, 's' => $result]));
	}
}
?>