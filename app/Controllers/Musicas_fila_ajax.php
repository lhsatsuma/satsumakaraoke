<?php
namespace App\Controllers;

class Musicas_fila_ajax extends BaseController
{
	public $module_name = 'Musicas_fila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->ajax = new \App\Libraries\Sys\AjaxLib($this->request);
		$this->ajax->CheckIncoming();
		
		$this->body = $this->ajax->GetData();
		$this->mdl = new \App\Models\Musicas_fila\Musicas_filamodel();
	}

	public function index()
	{
		$this->ajax->setError(0, 'root not allowed');
	}

	public function k_get_thread_copy()
	{
		$encoded = file_get_contents(ROOTPATH . 'public/threadCopy.json');
		$this->ajax->setSuccess(json_decode($encoded, true));
	}
}
