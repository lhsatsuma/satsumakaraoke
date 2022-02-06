<?php
namespace App\Models\Musicas;

class Musicas extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'musicas';
	public $f = array();
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
		'link' => array(
			'lbl' => 'Link',
			'type' => 'link',
			'required' => true,
			'max_length' => 255,
			'validations' => 'required',
		),
		'origem' => array(
			'lbl' => 'Origem',
			'type' => 'dropdown',
			'parameter' => 'origem_musica_list',
			'required' => true,
			'max_length' => 255,
		),
		'codigo' => array(
			'lbl' => 'Código',
			'type' => 'varchar',
			'max_length' => 6,
			'required' => true,
		),
		'md5' => array(
			'lbl' => 'MD5 ID',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'tipo' => array(
			'lbl' => 'Idioma',
			'type' => 'dropdown',
			'parameter' => 'tipo_musica',
			'required' => true,
		),
		'fvt' => array(
			'nondb' => true,
			'lbl' => 'Meus Favoritos',
			'type' => 'bool',
		)
	);
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['tipo', 'deleted']
	];
	
	function get_order_by()
	{
		$comp = '';
		$order_by = '';
		foreach($this->order_by as $field => $value){
			if($field == 'codigo'){
				$order_by .= $comp.'codigo_cast '.$value;
			}else{
				$order_by .= $comp.$field.' '.$value;
			}
			$comp = ', ';
		}
		$this->helper->orderBy($order_by);
        return true;
	}

	public function before_save(string $operation = null)
	{
		if($this->f['name']){
			$this->f['name'] = trim($this->f['name']);
		}
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
			$this->select = "MAX(CAST(codigo as UNSIGNED))+1 as codigo_ult";
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
		$return_data['saved_record'] = $this->SaveRecord();
		if($return_data['saved_record'] && !$return_data['exists']){
			$arquivos = new \App\Models\Arquivos\Arquivos();
			$arquivos->new_with_id = true;
			$arquivos->f['id'] = $md5;
			$arquivos->f['name'] = $title.'.mp4';
			$arquivos->f['arquivo'] = $md5;
			$arquivos->f['mimetype'] = 'video/mp4';
			$arquivos->f['tipo'] = 'private';
			$arquivos->f['registro'] = $this->f['id'];
			$arquivos->f['tabela'] = 'musicas';
			$arquivos->f['campo'] = 'arquivo_id';
			$arquivos->saveRecord();
		}
		$return_data['saved'] = true;
		return $return_data;
	}
}
?>