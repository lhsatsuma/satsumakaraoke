<?php
/*

THIS IS A LIB FOR AJAX CONTROLLER
FOR CURL REQUESTS USE CURL LIB


ERRORS CODE:
0x--- = Request error (headers)
1x--- = Data error (body)
2x--- = Record error (mdl)
3x--- = Specific error from controller
*/
namespace App\Libraries\Sys;

class AjaxLib{
	public $data = array();
	public $request;
	public $body;
	
	public function __construct($req)
	{
		$this->request = $req;
		$this->body = $this->GetData();
	}
	public function CheckIncoming()
	{	
		if(!$this->request->isAJAX()){
			$this->setError('0x001', 'XMLHttpRequest não setado');
		}
		if($this->request->getMethod(false) !== 'post'){
			$this->setError('0x002', 'Método inválido');
		}
	}
	public function CheckRequired(Array $fields = array())
	{
		$body_data = $this->GetData();
		foreach($fields as $field){
			if(empty($body_data[$field])){
				$this->setError('1x001', $field.' não identificado');
			}
		}
	}
	public function GetData()
	{
		return json_decode($this->request->getBody(), true);		
	}
	public function setSuccess($data = array())
	{
		$this->data['status'] = 1;
		$this->data['detail'] = $data;
		$this->setAjax();
		
	}
	public function setError($code, $msg)
	{
		$this->data['status'] = 0;
		$this->data['error_code'] = $code;
		$this->data['detail'] = $msg;
		$this->setAjax();
	}
	public function setAjax()
	{
		global $AppVersion;
		$encoded = json_encode($this->data, ((!$AppVersion->compress_output) ? JSON_PRETTY_PRINT : null));
		header('Content-Type: application/json');
		header('Content-Length: '.strlen($encoded));
		echo $encoded;
		exit;
	}
	
	
}
?>