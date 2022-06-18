<?php
namespace App\Controllers;

class Waitlist extends BaseController
{
	public $module_name = 'Waitlist';

	public function index($offset=0)
	{
		
		$initial_filter = array(
			'user_created' => '',
			'status' => 'pendente',
		);
		$initial_order_by = array(
			'field' => 'date_created',
			'order' => 'DESC',
		);
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);

		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);
		
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		
		$icon_search = '<i class="far fa-times-circle"></i>';
		if($this->filter['user_created']['value']){
			$this->data['color_user_created'] = 'warning';
			$this->data['icon_user_created'] = $icon_search;
		}else{
			$this->data['color_user_created'] = 'success';
			$this->data['icon_user_created'] = '';
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
		
		return $this->displayNew('pages/Waitlist/index');
	}

	public function topMusics()
	{		
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
		
		
		return $this->displayNew('pages/Waitlist/topMusics');
	}
}
