<?php
namespace App\Controllers;

class Musicas_fila extends BaseController
{
	public $module_name = 'Musicas_fila';

	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas na Fila';
		
		$initial_filter = array(
			'usuario_criacao' => '',
			'status' => 'pendente',
		);
		$initial_order_by = array(
			'field' => 'data_criacao',
			'order' => 'DESC',
		);
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		$result = $this->mdl->search(20, $offset);
		
		$result = $this->mdl->formatRecordsView($result);

		// echo '<pre>';var_dump($result);exit;
		
		$this->data['records'] = $result;
		
		
		$icon_search = '<i class="far fa-times-circle"></i>';
		if($this->filter['usuario_criacao']['value']){
			$this->data['color_usuario_criacao'] = 'warning';
			$this->data['icon_usuario_criacao'] = $icon_search;
		}else{
			$this->data['color_usuario_criacao'] = 'success';
			$this->data['icon_usuario_criacao'] = '';
		}
		
		$this->data['color_status_pendente'] = 'success';
		$this->data['icon_status_pendente'] = '';
		
		if($this->filter['status']['value'] == 'pendente'){
			$this->data['color_status_pendente'] = 'warning';
			$this->data['icon_status_pendente'] = $icon_search;
		}elseif($this->filter['status']['value'] == 'encerrado'){
			$this->data['color_status_encerrado'] = 'warning';
			$this->data['icon_status_encerrado'] = $icon_search;
		}
		
		return $this->display_template($this->view->setData($this->data)->view('pages/Musicas_fila/index'));
	}
}
