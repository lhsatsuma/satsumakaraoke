<?php
namespace App\Controllers\Api\v1;

use CodeIgniter\API\ResponseTrait;
use \App\Libraries\Sys\OAuth;
use \Oauth2\Request;
use CodeIgniter\HTTP\Message;

class ApiController extends \App\Controllers\BaseController
{
    use ResponseTrait;
    public $dummy_controller = true;
    protected $token;
    protected OAuth $oauth;
    protected $method;

    public function __construct()
    {
        parent::__construct();
        $this->oauth = new OAuth();
        
        $this->method = $this->request->getMethod(true);
    }

    public function checkMethod(mixed $methods = [])
    {
        if(!empty($methods)){
            if(is_string($methods)){
                $methods = [$methods];
            }
            if(!in_array($this->request->getMethod(true), $methods)){
                return false;
            }
        }

        return true;
    }

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		
		$this->mdl = new \App\Models\Waitlist\Waitlist();
        $this->getBody();
	}

    public function getBody()
    {
        $this->body = $this->request->getBody();
        if(!empty($this->body)){
            $this->body = json_decode($this->body, true);
            if(is_null($this->body)){
                return false;
            }
        }
        return $this->body;
    }
}