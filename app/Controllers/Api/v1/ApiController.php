<?php
namespace App\Controllers\Api\v1;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\Sys\OAuth;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ApiController extends BaseController
{
    use ResponseTrait;
    protected $dummy_controller = true;
    protected $token;
    protected OAuth $oauth;
    protected $method;

    public function __construct()
    {
        parent::__construct();
        $this->oauth = new OAuth();
        
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function checkMethod(mixed $methods = null)
    {
        if(!empty($methods)){
            if(is_string($methods)){
                $methods = [$methods];
            }
            if(!in_array(strtoupper($_SERVER['REQUEST_METHOD']), $methods)){
                return false;
            }
        }

        return true;
    }

	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
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