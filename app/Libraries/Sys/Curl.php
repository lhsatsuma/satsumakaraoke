<?php
/*
Curl Class v2.0
Ultima Modificacao: 04/08/2020
Por: Luis Henrique Minoru Satsuma
Otimizado para PHP 7.1
*/
namespace App\Libraries\Sys;
class Curl{
	public $base_url = '';
	private $headers = [];
	private $data = [];
	public $auth = array(
		'user' => '',
		'pass' => ''
	);
	private $ch;	
	private $options = array(
		'ssl' => false,
		'ssl_version' => 3,
		'timeout' => 10,
		'header' => false,
		'connect_timeout' => 10,
		'http_code_accept' => array(
			200, //OK
			201, //CREATED
			202, //ACCEPTED
			204, //No Content
		),
		'methods_allowed' => array(
			'post',
			'get',
			'put',
			'delete',
		),
		'response_json' => false,
	);
	public function __construct(){
		//Nothing to do for now
	}
	public function SetOption($option, $value){
		if(isset($this->options[$option])){
			$this->options[$option] = $value;
			return true;
		}
		return false;
	}
	public function GetOption($option){
		if($option === true){
			return $this->options;
		}else{
			return $this->options[$option];
		}
	}
	public function SetHeader($type, $content){
		$this->headers[$type] = $content;
		return true;
	}
	public function GetHeaders(){
		$return = [];
		if(!empty($this->headers)){
			foreach($this->headers as $type => $content){
				$return[] = $type.': '.$content;
			}
		}
		return $return;
	}
	public function ResetHeaders()
	{
		$this->headers = [];
	}
	public function call(string $method, $data=null, $ext_url=null){
		//Reset var for the request
		$this->data = [];
		
		//Checking if method its allowed
		if(!in_array(strtolower($method), $this->GetOption('methods_allowed'))){
			return $this->set_data(0, 'Method '.$method.' not supported');
		}
		
		//Validate All initial Variables to init curl
		$validated = $this->CheckInitial();
		if($validated === true){
			$url = $this->base_url;
			if($ext_url){
				$url .= $ext_url;
			}
			if(strtolower($method) == 'get'){
				if(!empty($data)){
					$url .= (strpos($ext_url, '?') === false) ? '?'.http_build_query($data) : http_build_query($data);
				}
				curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");
				$this->InitCurl($url);
			}else{
				$this->InitCurl($url);
				if(strtolower($method) == 'post'){
					curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
				}else{
					curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
				}
				if(!empty($data)){
					if($this->headers['Content-Type'] == 'application/x-www-form-urlencoded'){
						$data = http_build_query($data);
					}
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
				}
				log_message('debug', '[CURL]['.$method.'][DATA] '.var_export($data, true));
			}
			return $this->ExecuteCurl();
		}else{
			return $validated;
		}
	}
	private function InitCurl($url){
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, $this->GetOption('header'));
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->GetHeaders());
		curl_setopt($this->ch,CURLOPT_TIMEOUT, $this->GetOption('timeout'));
		/* Check SSL of URL called*/
		if($this->GetOption('ssl')){
			curl_setopt($this->ch, CURLOPT_SSLVERSION, $this->GetOption('ssl_version'));
		}else{
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		$this->Auth();
		log_message('debug', '[CURL][URL] '.$url);
		log_message('debug', '[CURL][HEADERS] '.var_export($this->GetHeaders(), true));
		return true;
	}
	private function ExecuteCurl(){
		$result = curl_exec($this->ch);
		$http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		$url_effective = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
		$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
		$this->data['http_code'] = $http_code;
		$this->data['url_effective'] = $url_effective;
		$this->data['header_size'] = $header_size;
		$this->data['response'] = $result;
		curl_close($this->ch);
		log_message('debug', '[CURL][RESPONSE] ['.$http_code.'] '.var_export($result, true));
		return $this->set_data($http_code, 'cURL executed successfully');
	}
	private function Auth(){
		if(!empty($this->auth['user'])){
			curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($this->ch, CURLOPT_USERPWD, $this->auth['user'].':'.$this->auth['pass']);
		}
	}
	private function CheckInitial(){
		if(empty($this->base_url)){
			return $this->set_data(0, 'Base URL not set!');
		}
		return true;
	}
	private function set_data($error_code, $msg){
		if($error_code == 0){
			$this->data = array(
				'status' => false,
				'msg' => $msg,
			);
		}else{
			if($this->GetOption('header')){
				$header = substr($this->data['response'], 0, $this->data['header_size']);
				$body = substr($this->data['response'], $this->data['header_size']);
			}else{
				$header = null;
				$body = $this->data['response'];
			}
			if($this->GetOption['response_json'] === true){
				$body = json_decode($body, true);
			}
			if(!in_array($error_code, $this->GetOption('http_code_accept'))){
				$status = false;
			}else{
				$status = true;
			}
			$this->data = array(
				'status' => $status,
				'msg' => $msg,
				'response' => array(
					'url' => $this->data['url_effective'],
					'code' => $this->data['http_code'],
					'header' => $header,
					'body' => $body,
				),
			);
		}
		return $this->data;
	}
}
?>