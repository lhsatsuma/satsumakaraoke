<?php
namespace App\Filters;

use App\Models\Users\Users;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\Sys\OAuth;
use Config\Services;
use OAuth2\Request;
use OAuth2\Response;
use CodeIgniter\API\ResponseTrait;

class OAuthFilter implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('Sys_helper');
        if($request->uri->getSegment(3) != 'token'){
            $oauth = new OAuth();
            $requestOAuth = Request::createFromGlobals();
            $responseOauth = new Response();
            if(!$oauth->server->verifyResourceRequest($requestOAuth)){
                $oauth->server->getResponse()->send();
                die();
            }
            $access_token = $oauth->server->getAccessTokenData($requestOAuth);
            $result_token = $oauth->getRecord($access_token['access_token']);
            $mdl_user = new Users();
            $mdl_user->f['id'] = $result_token['user_id'];
            $AuthUser = $mdl_user->get();

            getSession()->set('auth_user', $AuthUser);

            $this->body = $request->getBody();
            if(!empty($this->body)){
                $this->body = json_decode($this->body, true);
                if(is_null($this->body)){
                    $this->response = Services::response();
		            $this->request = Services::request();
                    $this->fail('Unable to decode body JSON');
                    $this->response->send();
                    die();
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}