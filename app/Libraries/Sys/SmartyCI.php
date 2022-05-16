<?php
namespace App\Libraries\Sys;

class SmartyCI extends \Smarty
{
	protected $config;
	
	public $isCaching;
	
	
	public function __construct($isCaching=true) 
	{
		parent::__construct();
		
		if ($this->config !== $this->config instanceof \Config\Smarty)
		{
			$this->config = new \Config\Smarty();
		}
		$this->template_dir = $this->config->templateDir;		
		$this->compile_dir = $this->config->compileDir;		
		$this->config_dir = $this->config->configDir;
		$this->cache_dir = $this->config->cacheDir;
		
		$this->assign( 'APPPATH', APPPATH );
		$this->assign( 'BASEPATH', ROOTPATH );

		$this->isCaching = $isCaching;
		$this->force_compile = true;
		if($this->isCaching){
			$this->caching = \Smarty::CACHING_LIFETIME_CURRENT;
			$this->cache_lifetime = 520;
		}
		
	}
	public function clearInputs($view)
	{
		$this->clearAllAssign();
		$this->clearCache($view, null, null);
	}
	public function view(string $view, array $options = null) 
	{
		/* Legacy compatibility for view render
		parser->view('view_template');
		to:
		parser->view('view_template.php');
		*/
		if(strtolower(substr($view, strlen($view) -4, 4)) !== '.tpl'){
			$view .= '.tpl';
		}elseif(strtolower(substr($view, strlen($view) -4, 4)) == 'php'){
			$view = substr($view, -4).'.tpl';
		}
		
		if(!$this->isCaching){
			$this->smarty->clearCache($view, null, null);
		}
			
		return $this->fetch($view);
	}
	
	public function setData(array $data = [])
	{
		if(!$this->isCaching){
			
			$this->smarty->clearAllAssign();
		}
		foreach ($data as $key => $value)
		{
			if($key == 'JS_VARS'){
				$this->assign($key, 'JSON.parse(\''.json_encode($value).'\')');
			}else{
				$this->assign($key, $value);
			}
		}
		
		return $this;
	}
}