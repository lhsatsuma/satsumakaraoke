<?php namespace Config;

class AppVersion extends \CodeIgniter\Config\BaseConfig
{
	/*
	@var string
	 Sets App Version
	 Dummy var for now, gonna use this in the future
	 */
	public $app = '1.5';
	
	/*
	@var string
	 Sets cache version for jsManager/CSS/IMG files
	 */
	public $css = '20210822_v8';
	
	/*
	@var bool
	 MD5 with $last_version
	 if sets false its gonna be like ?v=20200912_v1
	 */
	public $enc_md5 = true;
	
	
	/*
	@var bool
	Compress HTML to output
	*/
	public $compress_output = true;

	public $VideosKaraokeURL = '';

	public $host_fila = '';

	public $ajax_pagination = true;
	
	public function __construct()
	{
		parent::__construct();
		ini_set('default_charset', 'UTF-8');
		date_default_timezone_set('America/Recife');
		// setlocale(LC_ALL, 'pt_BR.utf8');
	}
}
