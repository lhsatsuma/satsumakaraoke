<?php namespace Config;

class AppVersion extends \CodeIgniter\Config\BaseConfig
{
	/*
	@var string
	 Sets App Version
	 Dummy var for now, gonna use this in the future
	 */
	public $version = '2.3.0.2b';
	
	/*
	@var bool
	 MD5 with $last_version
	 if sets false its gonna be like ?v=20200912_v1
	 */
	public $enc_md5 = true;

	public $title = 'Sistema';
	
	
	/*
	@var bool
	Compress HTML to output
	*/
	public $compress_output = true;

	public $ajax_pagination = true;

	public $template = 'template';
	public $template_file = 'template';
	public $template_file_admin = 'template_admin';
	
	public function __construct()
	{
		parent::__construct();
		ini_set('default_charset', 'UTF-8');
	}
}
