<?php
namespace App\Models\Files;

use App\Models\Basic\Basic;

class Files extends Basic
{
	public $db;
	public $table = 'arquivos';
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
		'arquivo' => [
			'lbl' => 'LBL_FILE',
			'type' => 'file',
			'parameter' => [
				'max_size' => 40960,
            ],
        ],
		'mimetype' => [
			'lbl' => 'LBL_MIMETYPE',
			'type' => 'varchar',
			'max_length' => 255,
        ],
		'tipo' => [
			'lbl' => 'LBL_TYPE_ACCESS',
			'type' => 'dropdown',
			'parameter' => 'tipo_acesso',
        ],
		'registro' => [
			'lbl' => 'LBL_RELATED_TO',
			'type' => 'related',
        ],
		'tabela' => [
			'lbl' => 'LBL_RELATED_TABLE',
			'type' => 'varchar',
        ],
		'campo' => [
			'lbl' => 'LBL_RELATED_FIELD',
			'type' => 'varchar',
			'max_length' => 255,
        ],
    ];
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
			$this->registerLastError('Error fetching total rows: ');
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
						'link_detail' => 'admin/musics/detalhes/',
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