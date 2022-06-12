<?php
namespace App\Models\Files;

class Files extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'arquivos';
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
		'arquivo' => array(
			'lbl' => 'LBL_FILE',
			'type' => 'file',
			'parameter' => array(
				'max_size' => 40960,
			),
		),
		'mimetype' => array(
			'lbl' => 'LBL_MIMETYPE',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'tipo' => array(
			'lbl' => 'LBL_TYPE_ACCESS',
			'type' => 'dropdown',
			'parameter' => 'tipo_acesso',
		),
		'registro' => array(
			'lbl' => 'LBL_RELATED_TO',
			'type' => 'related',
		),
		'tabela' => array(
			'lbl' => 'LBL_RELATED_TABLE',
			'type' => 'varchar',
		),
		'campo' => array(
			'lbl' => 'LBL_RELATED_FIELD',
			'type' => 'varchar',
			'max_length' => 255,
		),
	);
	public $idx_table = [
		['id', 'deleted'],
		['name', 'deleted'],
		['tipo', 'deleted'],
	];
	
	public function get()
	{
		$this->helper->select($this->select);
		$this->helper->where('id', $this->f['id']);
		$query = $this->helper->get(1);
		
		if ($this->db->error()['message']) {
			$this->RegisterLastError("Error fetching total rows: ");
			return null;
        }
		
		if($query->resultID->num_rows > 0){
			$result = $query->getResult('array')[0];
			$this->fields_map['registro']['table'] = $result['tabela'];
			$this->fields_map['registro']['parameter'] = [];
			
			switch($this->fields_map['registro']['table']){
				case 'musicas':
					$this->fields_map['registro']['parameter'] = [
						'url' => null,
						'model' => 'Musicas/Musicas',
						'link_detail' => 'admin/musicas/detalhes/',
					];
					break;
				default:
					$this->fields_map['registro']['parameter'] = [
						'url' => null,
						'model' => 'files/Arquivos',
						'link_detail' => 'admin/files/detail/',
					];
					break;
			}
			return $result;
		}
		return false;
	}
}
?>