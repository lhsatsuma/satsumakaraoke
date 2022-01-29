<?php
namespace App\Models\Arquivos;

class Arquivos extends \App\Models\Basic\Basic
{
	public $db;
	public $table = 'arquivos';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'nome' => array(
			'lbl' => 'Nome do Arquivo',
			'type' => 'varchar',
			'max_length' => 255,
			'link_record' => true,
		),
		'arquivo' => array(
			'lbl' => 'Arquivo',
			'type' => 'file',
			'parameter' => array(
				'max_size' => 40960,
			),
		),
		'mimetype' => array(
			'lbl' => 'Tipo do Arquivo',
			'type' => 'varchar',
			'max_length' => 255,
		),
		'tipo' => array(
			'lbl' => 'Tipo Acesso',
			'type' => 'dropdown',
			'parameter' => 'tipo_acesso',
		),
		'registro' => array(
			'lbl' => 'Relacionado a',
			'type' => 'related',
		),
		'tabela' => array(
			'lbl' => 'Tabela Relacionado',
			'type' => 'varchar',
		),
		'campo' => array(
			'lbl' => 'Campo Relacionado',
			'type' => 'varchar',
			'max_length' => 255,
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
		),
	);
	public $idx_table = [
		['id', 'deletado'],
		['nome', 'deletado'],
		['tipo', 'deletado'],
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
						'model' => 'Arquivos/Arquivos',
						'link_detail' => 'admin/arquivos/detalhes/',
					];
					break;
			}
			return $result;
		}
		return false;
	}
}
?>