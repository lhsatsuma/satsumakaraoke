<?php
/*
Curl Class v1.0
Ultima Modificacao: 04/08/2020
Por: Luis Henrique Minoru Satsuma
Otimizado para PHP 7.1
*/
namespace App\Libraries\Sys;
class Soap
{
	private $headers = [];
	private $data = [];
	public $auth = [
		'user' => '',
		'pass' => ''
    ];
	private $client;	
	private $options = [
		'location' => '',
		'uri' => '',
		'trace' => true,
    ];
	public function __construct(){
		//Nothing to do for now
	}
	public function DestroyClient(){
		unset($this->client);
	}
	public function SetClient($url=null){
		if($this->client){
			return $this->client;
		}
		$dataClient = [];
        $headers = [];

		$key_array = [
			'connection_timeout',
			'keep_alive',
        ];
		foreach($key_array as $key){
			$key_val = $this->GetOption($key);
			if(isset($key_val)){
				$dataClient[$key] = $key_val;
			}
		}
		
		if($url){
			$urlEffective = $url;
		}else{
			$urlEffective = $this->base_url;
		}
		$dataClient['uri'] = $urlEffective;
		$dataClient['location'] = $urlEffective;
		$dataClient['trace'] = 1;
		
		$this->client = new SoapClient($urlEffective, $dataClient);
		
		if(!empty($this->headers)){
			$headers[] = new SoapHeader('SoapNamespace', $this->headers);
			$this->client->__setSoapHeaders($headers);
		}
		
		
		return $this->client;
	}
	public function SetOption($option, $value){
		// if(isset($this->options[$option])){
			$this->options[$option] = $value;
			return true;
		// }
		// return false;
	}
	public function GetOption($option){
		if($option === true){
			return $this->options;
		}else{
			return $this->options[$option];
		}
	}
	public function SetHeader($type, $content){
		if(!array_key_exists($type, $this->headers)){
			$this->headers[$type] = $content;
			return true;
		}
		return false;
	}
	private function CheckInitial($urlEffective=null){
		if(empty($this->base_url) && empty($urlEffective)){
			return $this->set_data(0, 'Base URL not set!');
		}
		$this->SetClient($urlEffective);
		return true;
	}
	public function call($ext, $method, $data=null){
		
		$urlEffective = $this->base_url.$ext.'?wsdl';
		
		// $this->base_url .= $ext;
		//Reset var for the request
		$this->data = [];
		
		//Validate All initial Variables to init curl
		$validated = $this->CheckInitial($urlEffective);
		if($validated === true){
			$valid_soap = true;
			try{
				$result = $this->client->__call($method, $data);
			}catch(SoapFault $fault){
				$valid_soap = false;
				$this->data['request_body'] = $this->client->__getLastRequest();
				$this->data['request_headers'] = $this->client->__getLastRequestHeaders();
				
				return $this->set_data($valid_soap, $fault->faultstring);
			}
			$this->data['response_body'] = $result;
			$this->data['response_header'] = $this->client->__getLastResponseHeaders();
			
			$soap_msg = $valid_soap ? 'SOAP call successfully' : 'Error in SOAP call';
			
			return $this->set_data($valid_soap, $soap_msg);
		}
		
		return $validated;
	}
	private function set_data($error_code, $msg){
		
		$status = (bool)$error_code;
		
		if(!$status){
			$this->data = [
				'status' => $status,
				'msg' => $msg,
				'request' => [
					'uri' => $this->GetOption('uri'),
                ],
            ];
		}else{
			$this->data = [
				'status' => $status,
				'msg' => $msg,
				'request' => [
					'uri' => $this->GetOption('uri'),
                ],
				'response' => [
					'header' => $this->data['response_header'],
					'body' => $this->data['response_body'],
                ],
            ];
		}
		return $this->data;
	}
	public function getLast($option = null){
		if(empty($option) || !isset($this->client)){
			return null;
		}
		switch($option){
			case 'request_headers':
				return $this->client->__getLastRequestHeaders();
			case 'request_body':
				return $this->client->__getLastRequest();
			case 'response_headers':
				return $this->client->__getLastResponseHeaders();
			default:
				return null;
		}
	}
}