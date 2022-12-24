<?php
namespace App\Controllers\Api\v1;
use \OAuth2\Request;

class Token extends ApiController
{
	public function index()
	{
		$request = new Request();
		$respond = $this->oauth->server->handleTokenRequest($request->createFromGlobals());

		return $this->respond(json_decode($respond->getResponseBody()), $respond->getStatusCode());
	}
}
