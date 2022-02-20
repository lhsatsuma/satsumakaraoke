<?php
namespace App\Models\MusicasFila;

class MusicasFila extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'musicas_fila';
	public $f = array();
	public $id_by_name = true;
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'name' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuarios',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
		),
		'musica_id' => array(
			'lbl' => 'Música ID',
			'type' => 'related',
			'required' => true,
			'table' => 'musicas',
			'validations' => 'required',
		),
		'status' => array(
			'lbl' => 'Origem',
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

	public function after_save(?string $operation = null)
	{
		$this->createJSON();
	}

	public function createJSON()
	{
		log_message('debug', 'Creating JSON musics in line...');
		$this->select = "musicas_fila.id,
		usuarios.name as cantor,
		musicas.codigo,
		musicas.name as name_musica,
		musicas.md5";
		$this->where["status"] = "pendente";
		$this->join["musicas"] = "musicas.id = musicas_fila.musica_id";
		$this->join["usuarios"] = "usuarios.id = musicas_fila.user_created";
		$this->order_by["musicas_fila.date_created"] = "ASC";

		$total_rows = $this->total_rows();
		$this->page_as_offset = true;
		$result = $this->search(10);
		if(is_null($result)){
			return false;
		}
		foreach($result as $key => $fila){
			if((int) $this->ajax->body['sh'] == 1){
				$fila['cantor'] = explode(' ', $fila['cantor'])[0];
				if(strlen($fila['cantor']) > 13){
					$result[$key]['cantor'] = mb_substr($fila['cantor'], 0, 11) . '...';
				}
				if(strlen($fila['name_musica']) > 29){
					$result[$key]['name_musica'] = mb_substr($fila['name_musica'], 0, 26) . '...';
				}
			}elseif((int)20 > 1){
				$total_len = $fila['cantor'].$fila['name_musica'];
				if(strlen($total_len) > 20 - 3){
					$result[$key]['name_musica'] = mb_substr($fila['name_musica'], 0, 32 - strlen($fila['cantor'])) . '...';
				}
			}
			$result[$key]['codigo'] = (int)$result[$key]['codigo'];
			$result[$key] = array_values($result[$key]);
		}
		$result = array_values($result);
		log_message('debug', 'Creating JSON musics in line... DONE');
		return file_put_contents(WRITEPATH . 'cache/line_music.json', json_encode(['t' => $total_rows, 's' => $result]));
	}
}
?>