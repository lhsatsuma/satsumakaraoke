<?php
namespace App\Controllers\Api\v1;
use CodeIgniter\Exceptions\PageNotFoundException;
use \OAuth2\Request;

class DownloadManager extends ApiController
{
    public function download($id)
    {
        if($id){
            //Get all info of file and check if allowed access
            $fileName = $this->getFile($id);
            if($fileName){
                return $this->response->download($fileName['name'], file_get_contents($fileName['path']));
            }
        }

        //If file doesn't exist, throw 404
        throw PageNotFoundException::forPageNotFound();

        return false;
    }
    private function getFile($id)
    {
        $this->response->setHeader('Cache-Control', 'no-cache')->appendHeader('Cache-Control', 'must-revalidate');
        $this->response->removeHeader('Location');

        $file = new \App\Models\Files\Files();
        $file->f['id'] = $id;
        $result = $file->get();
        if($result){
            $this->checkAccess($result['tipo']);
            $file_name = ROOTPATH.'public/uploads/'.$result['arquivo'];
            if(file_exists($file_name)){
                return ['name' => $result['name'], 'mimetype' => $result['mimetype'], 'path' => $file_name];
            }
        }
        return false;
    }

    private function checkAccess($tipo)
    {
        if($tipo == 'admin'){
            $this->access_cfg = array(
                'needs_login' => true,
                'admin_only' => true,
            );
        }elseif($tipo == 'private'){
            $this->access_cfg = array(
                'needs_login' => true,
                'admin_only' => false,
            );
        }
        $this->SetSys(); //Checking session if necessary
    }
}
