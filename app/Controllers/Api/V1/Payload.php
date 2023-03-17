<?php
namespace App\Controllers\Api\V1;
use App\Controllers\Admin\PayloadController;

class Payload extends ApiController
{
    protected $module_name = 'Payload';

    public function __construct()
    {
        parent::__construct();
    }
    public function last()
    {
        if(!$this->checkMethod('GET')){
            return $this->fail('Method not supported', 405);
        }

        return $this->respond(PayloadController::getStatusPayload(), 200);
    }

    public function get()
    {
        if(!$this->checkMethod('GET')){
            return $this->fail('Method not supported', 405);
        }

        if(empty($this->body['hash'])){
            return $this->fail('Hash not found');
        }

        $payload_status = PayloadController::getStatusPayload();

        if(!$payload_status['status'] || $payload_status['data']['hash'] !== $this->body['hash']){
            return $this->fail('Payload not available', 409);
        }
        $return_get = [
            'status' => 1,
            'data' => PayloadController::getPayload(),
        ];

        //Forces return with header and json_encode
        //For minify
        return $this->respond($return_get);
    }
}
