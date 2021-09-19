<?php
namespace App\Controllers;

class Karaoke_ajax extends BaseController
{
	public $module_name = 'MusicasFila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->ajax = new \App\Libraries\Sys\AjaxLib();
		$this->ajax->CheckIncoming();
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

	public function k_set_thread()
	{

		if(empty($this->ajax->body['action'])){
			$this->ajax->setError('1x001', 'action not found');
		}
		$data_encode = [
			'action' => $this->ajax->body['action'],
			'valueTo' => (int)$this->ajax->body['valueTo'],
		];

		$encoded = json_encode($data_encode);
		file_put_contents(ROOTPATH . 'public/thread.json', $encoded);

		
		$data_copy = [
			'volume' => null,
		];
		if($data_encode['action'] == 'volume'){
			$data_copy = [
				'volume' => (int)$data_encode['valueTo'],
			];
			file_put_contents(ROOTPATH . 'public/threadCopy.json', json_encode($data_copy));
		}
		$this->ajax->setSuccess($data_encode);
	}
}
