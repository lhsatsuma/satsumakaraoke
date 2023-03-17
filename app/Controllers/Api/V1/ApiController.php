<?php
namespace App\Controllers\Api\V1;

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

        $this->getBody();
        $this->response = $response;
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

    /**
     * Overrides ResponseTrait function
     * For minify responses
     * @param $data
     * @param int|null $status
     * @param string $message
     * @return false|string
     */
    protected function respond($data = null, ?int $status = null, string $message = '')
    {
        $status = empty($status) ? 200 : $status;

        $this->response->setHeader('Content-Type', 'application/json')->setStatusCode($status);
        return json_encode($data);
    }
}