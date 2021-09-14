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

		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search(20, $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		
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
		
		return $this->displayNew('pages/Musicas_fila/index');
	}

	public function topMusicas()
	{
		$this->data['title'] = 'Top Músicas Mais Tocadas';
		
		$this->mdl->select = "count(*) as total, musica_id";
		$this->mdl->group_by = 'musica_id';
		$this->mdl->order_by['count(*)'] = 'DESC';
		$results = $this->mdl->search(10, 0);
		foreach($results as $key => $result){
			if($result['total'] < 2){
				unset($results[$key]);
			}
		}
		$results = $this->mdl->formatRecordsView($results);
		$this->data['records'] = $results;
		
		
		return $this->displayNew('pages/Musicas_fila/topMusicas');
	}
}
