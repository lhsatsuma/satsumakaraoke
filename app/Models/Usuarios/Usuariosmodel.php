<?php
namespace App\Models\Usuarios;

class Usuariosmodel extends \App\Models\Basic\Basicmodel
{
	public $db;
	public $table = 'usuarios';
	public $f = array();
	public $fields_map = array(
		'id' => array(
			'lbl' => 'ID',
			'type' => 'varchar',
			'max_length' => 36,
			'dont_load_layout' => true,
		),
		'nome' => array(
			'lbl' => 'Nome',
			'type' => 'varchar',
			'required' => true,
			'min_length' => 2,
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuariosmodel',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
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
			'parameter' => array(
				'url' => null,
				'model' => 'Admin/Usuarios/Usuariosmodel',
				'link_detail' => 'admin/usuarios/detalhes/',
			),
			'dont_load_layout' => true,
		),
		'email' => array(
			'lbl' => 'Email',
			'type' => 'email',
			'len' => 255,
			'required' => true,
			'min_length' => 10,
			'max_length' => 255,
			'ext_attrs' => 'form_valid_email="true"',
		),
		'senha' => array(
			'lbl' => 'Senha',
			'type' => 'password',
		),
		'status' => array(
			'lbl' => 'Status',
			'type' => 'dropdown',
			'parameter' => 'status_usuario_list',
			'default' => 'ativo',
			'required' => true,
		),
		'tipo' => array(
			'lbl' => 'Tipo',
			'type' => 'dropdown',
			'parameter' => 'tipo_usuario',
			'default' => 1,
			'required' => true,
		),
		'last_ip' => array(
			'lbl' => 'Último IP',
			'type' => 'varchar',
		),
		'last_connected' => array(
			'lbl' => 'Última vez conectado',
			'type' => 'datetime',
		),
	);
	
	public function SearchLogin(){
		$this->helper->select('id');
		$this->helper->where('email', $this->f['email']);
		$this->helper->where('senha', $this->f['senha']);
		$query = $this->helper->get(1);
		if($query){
			if($query->resultID->num_rows > 0){
				return $query->getResult('array')[0];
			}		
		}else{
			$this->RegisterLastError("Query search login failed: ");	
		}
		return false;
	}
}
?>