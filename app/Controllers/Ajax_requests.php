<?php
namespace App\Controllers;
//asdasdasd
class Ajax_requests extends BaseController
{
	public $module_name = null;
	public $data = array();
	public $dummy_controller = true;
	
	public $body = array();
	
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->AjaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$this->AjaxLib->CheckIncoming();
		
		$this->body = $this->AjaxLib->GetData();
	}
	
	public function index($offset = 0)
	{
		echo 'not implemented';exit;
	}
	
	public function get_related()
	{
		$required = array(
			'model',
		);
		$this->AjaxLib->CheckRequired($required);
		
		$this->model_name = str_replace('/', '\\', $this->body['model']);
		
		$ns_mdl = "\\App\\Models\\".$this->model_name;
		$mdl = new $ns_mdl();
		
		$mdl->select = "id, nome";
		
		if(!empty($this->body['custom_where'])){
			$mdl->where = $this->body['custom_where'];
		}else{
			$mdl->where = array(
				'nome' => $this->body['search_param'],
			);
		}
		$results = $mdl->search(5);
		
		$return = array();
		
		if($results){
			$results = $mdl->formatRecordsView($results, true);
		
			foreach($results as $key => $fields){
				$return[$key] = array(
					'value' => $fields['nome'],
					'label' => $fields['nome'],
					'id' => $fields['id'],
				);
			}
		}
		
		$this->AjaxLib->SetSuccess($return);
	}
	
	public function pagination_ajax()
	{
		$required = array(
			'id',
			'model',
			'fields_return',
			'location_to',
		);
		$this->AjaxLib->CheckRequired($required);
		
		if(!empty($this->body['per_page'])){
			$this->pager_cfg['per_page'] = $this->body['per_page'];
		}
		$this->pager_cfg['template'] = 'template_ajax';
		
		$this->model_name = str_replace('/', '\\', $this->body['model']);
		
		$ns_mdl = "\\App\\Models\\".$this->model_name;
		if(!class_exists($ns_mdl)){
			$this->AjaxLib->setError('1x002', 'class nao encontrado!');
		}
		$this->mdl = new $ns_mdl();
		
		$this->SetView();
		$this->SetLayout();
		
		$page = ($this->body['page']) ? $this->body['page'] : 0;
		
		$c_select = '';
		$this->mdl->select = '';
		$list_columns = array();
		foreach($this->body['fields_return'] as $field => $ext){
			$this->mdl->select .= $c_select.$field;
			$c_select = ', ';
			$list_columns[$field] = $ext;
		}
		if(!isset($this->body['fields_return']['id'])){
			$this->mdl->select .= $c_select.'id';
			$c_select = ', ';
		}
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/admin/home/index',
			'generic_filter' => array(),
			'id_filter' => $this->body['id'],
			'template_name' => 'template/Filter_template_ajax',
			'page' => $page,
		);
		
		$initial_order_by = array();
		if(!empty($this->body['initial_order_by'])){
			$initial_order_by = $this->body['initial_order_by'];
			
		}
		$initial_filter = array();
		if(!empty($this->body['initial_filter'])){
			$initial_filter = $this->body['initial_filter'];
			
		}
		$this->PopulateFiltroPost($initial_filter, $initial_order_by);
		
		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $page, $this->body['id']);
		
		$results = $this->mdl->search($this->pager_cfg['per_page'], $page);
		
		
		$results = $this->mdl->formatRecordsView($results);
		
		/* LISTAGEM DE REGISTROS GENERICAS COM BASE NO LAYOUT LIB TEMPLATE */
		
		$this->data['layout_list'] = $this->layout->GetGenericListaAjax($this->body['id'], $this->body['location_to'], $list_columns, $results);
		
		return $this->view->setData($this->data)->view('template/Lista_Registros_Ajax');
	}

	public function toogle_dark_mode()
	{
		$mdl = new \App\Models\Usuarios\Usuarios();


		$which_key = 'auth_user';
		if($this->session->get('auth_user')['id']){
			$mdl->f['id'] = $this->session->get('auth_user')['id'];
		}elseif($this->session->get('auth_user_admin')['id']){
			$which_key = 'auth_user_admin';
			$mdl->f['id'] = $this->session->get('auth_user_admin')['id'];
		}else{
			$this->AjaxLib->setError('1x001', 'Usuário não identificado');
		}

		$mdl->f['dark_mode'] = $this->body['dark_mode'];
		$mdl->saveRecord();
		$AuthUser = $mdl->get();
	
		$this->session->set($which_key, $AuthUser);
		$this->AjaxLib->setSuccess(true);
	}
}
