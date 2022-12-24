<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\Sys\OAuth;
use \OAuth2\Request;
use \OAuth2\Response;
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
            
            $mdl_user = new \App\Models\Users\Users();
            $mdl_user->f['id'] = $exists['id'];
            $AuthUser = $mdl_user->get();

            getSession()->set('auth_user', $AuthUser);

            $this->body = $request->getBody();
            if(!empty($this->body)){
                $this->body = json_decode($this->body, true);
                if(is_null($this->body)){
                    $this->response = \Config\Services::response();
		            $this->request = \Config\Services::request();
                    $this->fail('Unable to decode body JSON', 400);
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