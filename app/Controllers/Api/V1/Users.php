<?php
namespace App\Controllers\Api\V1;

class Users extends ApiController
{
    protected $module_name = 'Users';

    public function auth()
    {
        if(!$this->checkMethod('POST')){
            return $this->fail('Method not supported', 405);
        }
        if(empty($this->body['email']) || empty($this->body['password'])){
            return $this->fail('Missing required parameters');
        }

        $this->mdl->f['email'] = $this->body['email'];
        $this->mdl->f['senha'] = base64_decode($this->body['password']);
        $exists = $this->mdl->SearchLogin();
        if(!$exists){
            $this->setMsgData('error', translate('LBL_LOGIN_INVALID'));
            $this->respond('User not found', 404);
        }

        $this->mdl->f['id'] = $exists['id'];
        $this->mdl->select = 'usuarios.id, usuarios.name, usuarios.email, usuarios.telefone, usuarios.celular';
        $AuthUser = $this->mdl->get();

        $this->mdl->f = [];
        $this->mdl->f['id'] = $exists['id'];
        $this->mdl->f['last_ip'] = $this->request->getIPAddress();
        $this->mdl->f['last_connected'] = date('Y-m-d H:i:s');
        $this->mdl->auth_user_id = $exists['id'];
        $this->mdl->saveRecord();

        return $this->respond(['status' => 1, 'data' => $AuthUser], 200);
    }
}