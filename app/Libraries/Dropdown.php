<?php
namespace App\Libraries;


class Dropdown extends \App\Libraries\Sys\Dropdown{
	/*
	ARRAY OF DROPDOWN VALUES
	*/
	public function __construct()
	{
		parent::__construct();
		$new_values = array(
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
			),
			'timezones_availables' => array(),
		);
		$this->values = array_merge($this->values, $new_values);
	}
}
?>