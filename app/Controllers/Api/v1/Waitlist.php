<?php
namespace App\Controllers\Api\v1;
use \OAuth2\Request;

class Waitlist extends ApiController
{
	public $module_name = 'Waitlist';

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		hasPermission(1002, 'r', true);
		
		$this->ajax = new \App\Libraries\Sys\Ajax();
		$this->ajax->CheckIncoming();
		
		$this->mdl = new \App\Models\Waitlist\Waitlist();
	}

	public function getVideo($id)
	{
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result){
			return $this->fail('Waitlist not found', 404);
		}

		$this->mdl->f['id'] = $result['id'];
		$this->mdl->f['status'] = 'encerrado';
		$this->mdl->saveRecord();
		$this->ajax->setSuccess();
		return $this->respond('', 200);
	}

	public function ended($id)
	{
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result){
			return $this->fail('Waitlist not found', 404);
		}

		$this->mdl->f['id'] = $result['id'];
		$this->mdl->f['status'] = 'encerrado';
		$this->mdl->saveRecord();
		$this->ajax->setSuccess();
		return $this->respond('', 200);
	}
}
