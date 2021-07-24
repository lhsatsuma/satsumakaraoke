<?php namespace Config;

class Smarty
{
	/**
	 *---------------------------------------------------------------
	 * TEMPLATE DIRECTORY
	 *---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "template directory" folder.
	 *
	 * @var string
	 */
	public $templateDir = APPPATH . 'Views';
	
	/**
	 *---------------------------------------------------------------
	 * COMPILE DIRECTORY
	 *---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "compile directory" folder.
	 *
	 * @var string
	 */
	public $compileDir = WRITEPATH . 'cache';
	
	/**
	 *---------------------------------------------------------------
	 * CONFIG DIRECTORY
	 *---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "config directory" folder.
	 *
	 * @var string
	 */
	public $configDir = APPPATH . 'Config';
	
	/**
	 *---------------------------------------------------------------
	 * CACHE DIRECTORY
	 *---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "cache directory" folder.
	 *
	 * @var string
	 */
	public $cacheDir = WRITEPATH . 'cache';
}
