<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Response;
use Psr\Log\LoggerInterface;

class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Instance of the main Response object.
     *
     * @var ResponseInterface|Response
     */
    public $response;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
	
	/*
	@var array
	An array for pass data in view parser
	*/
	public $data = [];
	
	/*
	@var string
	String to store base_url of app, just in case
	*/
	public $base_url;
	
	/*
	@var array
	An array of filter
	*/
	public $filter = array();
	
	/*
	Session core service of CI
	*/
	public $session;
	
	/*
	@class of Smarty
	Smarty it's a TPL Engine for PHP
	Because the Parser of CI4 it's limited for functions and vars display
	*/
	public $view;
	
	/*
	Initial lib for system
	Not sure what to do here yet
	*/
	public $sysLib;
	
	/*
	Lib for layout HTML for generic inputs/select/textarea etc...
	*/
	public $layout;
	
	/*
	@var string
	Generic template for views
	*/
	public $template = 'template';
	
	/*
	@var string
	Generic template for views
	*/
	public $template_file = 'template';
	
	/*
	Dummy controller is a variable to set if has to call all initials vars and libs
	*/
	
	public $dummy_controller = false;
	
	/*
	Check if this controller is only for admin
	*/
	
	public $access_cfg = array(
		'needs_login' => true, //For access all pages, needs to be logged in
		'admin_only' => false,
	);
	
	/*
	@array
	Sets var for pagination config
	*/
	public $pager_cfg = array(
		'per_page' => 20,
		'segment' => 3,
		'template' => 'template_basic',
	);
	
	/*
	@namespace
	Sets namespace for call model
	*/
	public $ns_model;
	
	/*
	@Array
	Sets if it's gonna use the Generic Filter
	*/
	public $filterLib_cfg = array(
		'use' => false,
		'action' => '',
		'generic_filter' => array(
			'name',
		),
	);

	protected $is_mobile = false;

	public $js_vars = [];

	protected $ext_buttons = [];

	public string $lang_file;
	
	public function __construct()
	{
		$this->lang_file = str_replace(['App\\Controllers\\', '\\'], ['', '.'], get_class($this));
		$GLOBALS['lang_file'] = $this->lang_file;
		helper('Sys_helper');
		if(strpos(strtolower(get_class($this)), 'cssmanager') === false
		&& strpos(strtolower(get_class($this)), 'jsmanager') === false){
			$this->session = getSession();
			$this->uri = current_url(true)->getSegments();
			array_shift($this->uri);
			$this->routes = \Config\Services::router();
			$this->request = \Config\Services::request();

			if(isMobile()){
				$this->is_mobile = true;
			}
			$this->setPermData([5, 1001, 1003]);
		}
		global $AppVersion;
		$this->template = $AppVersion->template;
		$this->template_file = $AppVersion->template_file;
	}
	
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		$this->base_url = base_url().'/';
		$this->js_vars['app_url'] = $this->base_url;
		
		//Check dummy controller
		if($this->dummy_controller === false){
			
			$this->SetSys();
			$this->SetMdl();
			$this->SetView();
			$this->SetLayout();
			$this->SetInitialData();
			translate('', 'app');
		}
	}
	
	public function SetView()
	{
		$this->view = new \App\Libraries\Sys\SmartyCI();
	}
	
	public function SetMdl()
	{
		//Let's call for MDL (model) for short code in Controllers
		$namespace_call = ($this->ns_model) ? $this->ns_model : '\\App\\Models\\'.$this->module_name.'\\'.$this->module_name;
		if(class_exists($namespace_call)){
			$this->mdl = new $namespace_call();
		}
	}
	
	public function SetSys()
	{
		//Initialize all system vars for models and session data
		$this->sysLib = new \App\Libraries\Sys\InitApp($this->module_name);
		$this->CheckSysAccess();
	}
	
	public function rdctLogin()
	{
		$current_url = str_replace(['index.php/', 'index.php'], '', current_url());
		$this->session->setFlashdata('rdct_url', urlencode($current_url));
		rdct('/login');
	}
	
	public function CheckSysAccess()
	{
		$HasAccess = $this->sysLib->CheckSession();
		if($this->access_cfg['needs_login'] &&
		!$HasAccess &&
		(!isset($this->uri[0]) || $this->uri[0] !== 'login')){
			$this->rdctLogin();
		}
		if($this->access_cfg['admin_only']){
			$HasAccess = $this->sysLib->CheckAccessAdmin();
			if(!$HasAccess){
				header('HTTP/1.0 403 Forbbiden');
				echo '<p>Acesso Negado!</p>';
				echo '<p><a href="'.$this->base_url.'">Voltar para Página Inicial</a></p>';
				exit;
			}
		}
	}
	
	public function SetLayout()
	{
		$this->layout = new \App\Libraries\Sys\Layout($this->mdl->fields_map, $this->lang_file);
		$this->layout->template = $this->template;
	}
	public function SetBreadCrumbArr()
	{
		/*
		2020-12-02 FINALLY I'VE GOT TO DO THIS!!!!!!!!!!!!!!!!!!!
		*/
		$breadcrumb = array();
		
		$controllerName = strtolower(str_replace('App\\Controllers\\', '', get_class($this)));
		$methodName = strtolower($this->routes->methodName());
		if(count($this->uri) == 0){
			//For cases like app_url/
			$breadcrumb[$controllerName][$methodName] = 1;
		}elseif(count($this->uri) == 1){
			if($this->uri[0] == 'admin'){
				//For cases like app_url/admin/
				$breadcrumb['admin'][$methodName] = 1;
			}else{
				//For cases like app_url/home
				$breadcrumb[$controllerName][$methodName] = 1;
			}
		}else{
			//For cases like app_url/home/index/2/test
			$temp = &$breadcrumb;
			foreach($this->uri as $key) {
				$key = strtolower($key);
				$temp = &$temp[$key];
			}
			if($temp === null){
				$temp = 1;
			}
		}
		$this->js_vars['_CTRL_NAME'] = explode('\\', $controllerName);
		$this->js_vars['_ACTION_NAME'] = $methodName;
		return $breadcrumb;
	}

	public function getBreadcrumb()
	{
		$breadcrumb = [];
		$controllerName = strtolower(str_replace('App\\Controllers\\', '', get_class($this)));
		$methodName = strtolower($this->routes->methodName());
		if(count($this->uri) == 0){
			//For cases like app_url/
			$breadcrumb[] = $controllerName;
			$breadcrumb[] = $methodName;
		}elseif(count($this->uri) == 1){
			if($this->uri[0] == 'admin'){
				//For cases like app_url/admin/
				$breadcrumb[] = 'admin';
				$breadcrumb[] = $methodName;
			}else{
				//For cases like app_url/home
				$breadcrumb[] = $controllerName;
				$breadcrumb[] = $methodName;
			}
		}else{
			$breadcrumb = $this->uri;
		}
		return $breadcrumb;
	}
	
	public function SetInitialData()
	{
		global $AppVersion;
		//Initial data for view, assuming this it's gonna be used in all pages
		$msg_type = '';
		if($this->session->getFlashdata('msg_type') == 'success'){
			$msg_type = 'success';
		}elseif($this->session->getFlashdata('msg_type') == 'error'){
			$msg_type = 'error';
		}
		$this->js_vars['ajax_pagination'] = ($AppVersion->ajax_pagination ? true : false);
		$this->js_vars['app_version'] = $AppVersion->version;
		$this->js_vars['ch_ver'] = GetCacheVersion();
		$this->js_vars['dark_mode'] = $this->session->get('auth_user')['dark_mode'];
		$this->js_vars['ctrl_language'] = translate('', $this->lang_file);


		$locale = service('request')->getLocale();
		$get_file = 'Dropdown_ext';
		if(!file_exists(APPPATH . "Language/{$locale}/Public/{$get_file}.php")){
			$get_file = 'Dropdown';
		}

		$dataNew = array(
			'app_url' => base_url().'/',
			'ch_ver' => GetCacheVersion(),
			'ch_ver_org' => $AppVersion->version,
			'is_mobile' => $this->is_mobile,
			'msg' => $this->session->getFlashdata('msg'),
			'msg_type' => $msg_type,
			'title' => translate('LBL_ACTION_CTRL_'. strtoupper($this->routes->methodName())),
			'sys_title' => getTitle(),
			'save_data_errors' => $this->session->getFlashdata('save_data_errors'),
			'auth_user' => $this->session->get('auth_user'),
			'auth_user_admin' => $this->session->get('auth_user_admin'),
			'breadcrumb' => $this->SetBreadCrumbArr(),
			'auto_redirect_after_to' => getFormData('auto_redirect_after_to'),
			'bdOnly' => ($this->request->getGet('bdOnly') ? true : false),
			'rdct_url' => $this->session->getFlashdata('rdct_url'),
			'default_lang_file' => $this->lang_file,
			'languages' => [
				'app' => json_encode(translate('','app')),
				$this->lang_file => json_encode(translate('',$this->lang_file)),
				'Dropdown' => json_encode(translate('', 'Public.'.$get_file)),
			]
		);
		if($this->data){
			$this->data = array_merge($this->data, $dataNew);
		}else{
			$this->data = $dataNew;
		}
	}
	
	public function PopulatePost(bool $encode = false)
	{
		//Just a generic function for populate all mdl->f with incoming post
		foreach($this->mdl->fields_map as $field => $options){
			if($options['type'] == 'file'){
				//Old value for model update
				$this->mdl->f[$field] = getFormData($field);
			}else{
				$value = getFormData($field);
				if(!is_null($value)){
					if($encode){
						$value = $this->mdl->formatDBValues($options['type'], $value);
					}
					$this->mdl->f[$field] = $value;
				}
			}
		}
	}
	
	public function PopulateFiltroPost($initial_filter=array(), $initial_order=array())
	{
		//Just a generic function for populate all mdl->f with incoming post
		foreach($this->mdl->fields_map as $field => $options){
			$value = (!is_null(getFormData('search_'.$field))) ? getFormData('search_'.$field) : null;
			if(is_null($value) &&
			isset($initial_filter[$field]) &&
			!empty($initial_filter[$field])){
				$value = $initial_filter[$field];
			}
			$old_value = '';
			if(!is_null($value)){
				//So, there's an value to make a condition
				$old_value = $value;


				//Let's format value to DB
				if($value == '|ASSIGNED_ONLY|'){
					$value = $this->session->get('auth_user')['id'];
				}else{
					$value = $this->mdl->formatDBValues($options['type'], $value);
				}

				//Default condition it's LIKE for filter
				$condition = 'LIKE';

				if(getFormData('search_'.$field.'_condition')){
					//Condition came with POST
					$condition = getFormData('search_'.$field.'_condition');
				}elseif(in_array($options['type'], ['related','dropdown','bool'])){
					
					//Force condition EQUAL for this types of fields
					$condition = 'EQUAL';
				}
				
				if(!$options['nondb']){
					if(is_array($value)){
						$this->mdl->where[$field] = ['IN', $value];
					}elseif($value){
						if($condition == 'LIKE'){
							$value = '%'.$value.'%';
						}
						$this->mdl->where[$field] = [$condition, $value];
					}
				}
				$this->data['search_'.$field] = $old_value;
				$this->filter[$field] = array(
					'options' => $options,
					'value' => $old_value,
				);
			}
			if(isset($initial_filter[$field])){
				$this->filter[$field] = array(
					'options' => $options,
					'value' => $old_value,
				);
			}
		}
		if($this->filterLib_cfg['generic_filter'] && !is_null(getFormData('search_generic_filter'))){
			$value = getFormData('search_generic_filter');
			if($value){
				foreach($this->filterLib_cfg['generic_filter'] as $key => $field){
					if($this->fields_map[$field]['nondb']){
						continue;
					}
					$key_where = "";
					if(count($this->filterLib_cfg['generic_filter']) > 1){
						if($key == 0){
							$key_where = 'BEGINORWHERE_';
						}elseif($key == count($this->filterLib_cfg['generic_filter']) - 1){
							$key_where = 'ENDORWHERE_';
						}else{
							$key_where = 'MIDORWHERE_';
						}
					}else{
						$key_where = 'BEGINENDORWHERE_';
					}
					$this->mdl->where[$key_where.$this->mdl->table.'.'.$field] = ['LIKE', '%'.$value.'%'];
				}
			}
		}
		if(!empty($this->body['order_by_field'])){
			$order_by_field = $this->body['order_by_field'];
		}else{
			$order_by_field = getFormData('order_by_field');
		}
		if(!empty($this->body['order_by_order'])){
			$order_by_order = $this->body['order_by_order'];
		}else{
			$order_by_order = getFormData('order_by_order');
		}
		if(!empty($order_by_field) && !empty($order_by_order)){
			$this->mdl->order_by[$order_by_field] = $order_by_order;
		}elseif(!empty($initial_order)){
			$order_by_field = $initial_order['field'];
			$order_by_order = $initial_order['order'];
			$this->mdl->order_by[$order_by_field] = $order_by_order;
			
		}
		$this->data['order_by_field'] = $order_by_field;
		$this->data['order_by_order'] = $order_by_order;
		
		if($this->filterLib_cfg['use']
			&& !$this->request->getGet('bdOnly')){
			$this->data['filter_template'] = $this->GenerateGenericFilter();
			
		}
	}
	
	public function GenerateGenericFilter()
	{
		$this->filterLib = new \App\Libraries\Sys\Filter($this->request, $this->filter, $this->lang_file);
		$this->filterLib->action = $this->filterLib_cfg['action'];
		$this->filterLib->generic_filter = $this->filterLib_cfg['generic_filter'];
		$this->filterLib->id_filter = $this->filterLib_cfg['id_filter'];
		if($this->filterLib_cfg['template_name']){
			$this->filterLib->template_name = $this->filterLib_cfg['template_name'];
			$this->filterLib->template = '';
		}else{
			$this->filterLib->template = $this->template;
		}
		$this->filterLib->ext_buttons = $this->ExtButtonsGenericFilters();
		$this->filterLib->order_by = array('field'=>$this->data['order_by_field'], 'order'=>$this->data['order_by_order']);
		return $this->filterLib->display();
	}
	
	public function ExtButtonsGenericFilters()
	{
		return $this->ext_buttons;
	}
	
	public function GetPagination($total, $offset=0, $group = 'default')
	{
		
		$pager = \Config\Services::pagerext();
		$page = ($offset > 1) ? ($offset) : 1;
		return $pager->makeLinks($page, $this->pager_cfg['per_page'], $total, $this->pager_cfg['template'], $this->pager_cfg['segment'], $group);
		
	}
	
	private function display_template($content)
	{
		$dataMenu = $this->layout->mountLayoutMenu($this->template_file, $this->getBreadcrumb());
		if($dataMenu['perms']){
			$this->data['perms'] = array_merge($this->data['perms'], $dataMenu['perms']);
		}
		$this->data['menu_arr'] = $dataMenu['menu_arr'];

		$this->view->setData([
			'perms' => $this->data['perms'],
			'menu_arr' => $this->data['menu_arr'],
		]);

		return $this->display($this->view->setData(array('content'=>$content))->view($this->template.'/'.$this->template_file));
	}

	public function setDataTPL()
	{
		$this->data['JS_VARS'] = $this->js_vars;
		return $this->view->setData($this->data);
	}
	
	private function display($content)
	{
		global $AppVersion;
		
		/*
		Compressing HTML to output to consume less memory
		*/
		if($AppVersion->compress_output){
			$content = str_replace(array("    ", "\t", "\n", "\r"), "", $content);
		}
		if($this->request->getGet('bdOnly')){
			$Ajax = new \App\Libraries\Sys\Ajax();
			$Ajax->setSuccess($content);
		}else{
			return $content;
		}
        return false;
	}
	
	public function displayNew($tpl, $template = true)
	{
		$this->setDataTPL();
		if($template){
			return $this->display_template($this->view->view($tpl));
		}else{
			return $this->display($this->view->view($tpl));
		}
	}
	
	public function PopulateFromSaveData($result)
	{
		
		$save_data = $this->session->getFlashdata('save_data');
		if(is_null($save_data)){
			///Let's try from POST params
			foreach($this->mdl->fields_map as $field => $attrs){
				$value = getFormData('save_data_'.$field);
				if(!empty($value)){
					if($attrs['type'] == 'related'){
						$save_data[$field.'_name'] = getFormData('save_data_'.$field);
						$save_data[$field] = getFormData('save_data_'.$field.'_name');
					}elseif($attrs['type'] == 'dropdown'){
						$save_data['raw'][$field] = $value;
					}else{
						$save_data[$field] = $value;
					}
				}else{
					$value = $this->request->getGet('save_data_'.$field);
					if(!empty($value)){
						if($attrs['type'] == 'related'){
							$save_data[$field.'_name'] = $value;
							$save_data[$field] = $this->request->getGet('save_data_'.$field.'_name');
						}elseif($attrs['type'] == 'dropdown'){
							$save_data['raw'][$field] = $value;
						}else{
							$save_data[$field] = $value;
						}
					}
				}
			}
		}
		if(!is_null($save_data)){
			$result = array_merge($result, $save_data);
		}
		return $result;
	}
	
	public function ValidateFormPost()
	{
		
		$this->validation = \Config\Services::validation();
		foreach($this->mdl->fields_map as $field => $attrs){
			if($field == 'id'){
				continue;
			}
			$validation_str = '';
			$c_validation_str = '';
			if($attrs['type'] == 'file'){
				$value = $this->request->getFile($field);
				if($value){
					if($attrs['required']){
						$validation_str .= $c_validation_str.'uploaded['.$field.']';
						$c_validation_str = '|';
					}
					if($attrs['parameter']['max_size']){
						$validation_str .= $c_validation_str.'max_size['.$field.','.$attrs['parameter']['max_size'].']';
					}
				}
			}else{
				if($attrs['required'] && isset($_POST[$field]) ||
				(isset($attrs['skipRequired']) && !$attrs['skipRequired'])){
					$validation_str .= $c_validation_str.'required';
					$c_validation_str = '|';
				}
				if(isset($attrs['min_length'])){
					$validation_str .= $c_validation_str.'min_length['.$attrs['min_length'].']';
					$c_validation_str = '|';
				}
				if(isset($attrs['max_length'])){
					$validation_str .= $c_validation_str.'max_length['.$attrs['max_length'].']';
					$c_validation_str = '|';
				}
				if($attrs['type'] == 'email' && $attrs['required']){
					$validation_str .= $c_validation_str.'valid_email';
					$c_validation_str = '|';
				}
				if(!empty($attrs['validations'])){
					$validation_str .= $c_validation_str.$attrs['validations'];
				}
			}
			if(!empty($validation_str)){
				$this->validation->setRule($field, $attrs['lbl'], $validation_str);
			}
		}
		if(!$this->validation->withRequest($this->request)->run()){
			$this->validation_errors = $this->validation->getErrors();
			return false;
		}
		return true;
	}
	
	public function SetErrorValidatedForm($set_save_data = true)
	{
		if(!isset($this->validation_errors['generic_error']) && $this->validation_errors){
			$this->validation_errors['generic_error'] = implode("\n", $this->validation_errors);
		}
		$this->session->setFlashdata('save_data_errors', $this->validation_errors);
		if($set_save_data){
			$this->session->setFlashdata('save_data', getFormData());
		}
	}

	public function setMsgData($type, $msg){
		$this->session->setFlashdata('msg_type', $type);
		$this->session->setFlashdata('msg', $msg);
	}

	public function setPermData($cods)
	{
		if(is_array($cods)){
			foreach($cods as $cod){
				$this->data['perms']['cod_'.$cod] = hasPermission($cod);
			}
		}elseif(is_int($cods)){
			$this->data['perms']['cod_'.$cods] = hasPermission($cods);
		}
		return true;
	}
}