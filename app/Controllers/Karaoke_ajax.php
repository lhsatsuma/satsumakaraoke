<?php
namespace App\Controllers;

class Karaoke_ajax extends BaseController
{
	public $module_name = 'MusicasFila';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		hasPermission(1001, 'r', true);

		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->ajax = new \App\Libraries\Sys\Ajax();
		$this->ajax->CheckIncoming();
	}

	public function index()
	{
		$this->ajax->setError(0, 'root not allowed');
	}

	public function k_get_thread_copy()
	{
		$encoded = file_get_contents(WRITEPATH . 'cache/threadCopy.json');
		$this->ajax->setSuccess(json_decode($encoded, true));
	}

	public function k_set_thread()
	{
		if(empty($this->ajax->body['action'])){
			$this->ajax->setError('1x001', 'action not found');
		}
		if(empty($this->ajax->body['copy_only'])){
			$data_encode = [
				'action' => $this->ajax->body['action'],
				'valueTo' => (int)$this->ajax->body['valueTo'],
			];

			$encoded = json_encode($data_encode);
			file_put_contents(WRITEPATH . 'cache/thread.json', $encoded);
		}
		
		$data_copy = [
			'volume' => null,
		];
		if($this->ajax->body['action'] == 'volume'){
			$data_copy = [
				'volume' => (int)$this->ajax->body['valueTo'],
			];
			file_put_contents(WRITEPATH . 'cache/threadCopy.json', json_encode($data_copy));
		}
		$this->ajax->setSuccess($data_encode);
	}
}
