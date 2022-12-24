<?php namespace Config;

class AppVersion extends \CodeIgniter\Config\BaseConfig
{
	/*
	@var string
	 Sets App Version
	 Dummy var for now, gonna use this in the future
	 x.y.zA
	 x = Core Version
	 y = System Version (includes all composer, vendor, major updates...)
	 z = App Version (Fixes for System Version or minor updates)
	 A = Indicates if its Alpha, Beta or Release
	 */
	public $version = '2.15.0b';
	
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

	public $charset = 'UTF-8';

	public $google_api_key = '';
	
	public function __construct()
	{
		parent::__construct();
		ini_set('default_charset', $this->charset);
	}
}
