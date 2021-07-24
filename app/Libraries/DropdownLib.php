<?php
namespace App\Libraries;


class DropdownLib extends \App\Libraries\Sys\DropdownLib{
	/*
	ARRAY OF DROPDOWN VALUES
	*/
	
	public $values = array(
		'ativo_inativo_list' => array(
			'ativo' => 'Ativo',
			'inativo' => 'Inativo',		
		),
		'tipo_usuario' => array(
			1 => 'Regular',
			80 => 'Colaborador',
			99 => 'Administrador',	
		),
		'status_usuario_list' => array(
			'ativo' => 'Ativo',
			'inativo' => 'Inativo',
			'bloqueado' => 'Bloqueado',	
		),
		'tipo_musica' => array(
			'N/A' => 'N/A',
			'INT' => 'INT',
			'BRL' => 'BRL',
			'ESP' => 'ESP',
			'JPN' => 'JPN',
			'OTR' => 'OTR',
		),
		'status_musicas_fila_list' => array(
			'pendente' => 'Pendente',
			'encerrado' => 'Encerrado',
		)
	);
}
?>