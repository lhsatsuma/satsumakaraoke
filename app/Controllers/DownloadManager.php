<?php
namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\Files\Files;

class DownloadManager extends BaseController
{
	public $data = [];
	protected $module_name = 'DownloadManager';
    protected $dummy_controller = true;
	
	public function download($id = null)
	{
		if($id){
			
			//Get's all info of file and check if allowed access
			$fileName = $this->getFile($id);
			if($fileName){
				return $this->response->download($fileName['name'], file_get_contents($fileName['path']));
			}
		}
		
		//If file doesn't exists, throw 404
		throw PageNotFoundException::forPageNotFound();

        return false;
	}
	
	public function preview($id = null)
	{	
		if($id){
			//Get's all info of file and check if allowed access
			$fileName = $this->getFile($id);
			if($fileName){
				//Check if mimetype it's an image or pdf, otherwise let's download the file
				if($this->checkMimetypeDefault($fileName['mimetype'])
				){
					return $this->response->download($fileName['name'], file_get_contents($fileName['path']));
				}
				//Setting all headers necessarys and returning binary file
				$this->response->setHeader('Content-Type', $fileName['mimetype'])->appendHeader('Content-Length', filesize($fileName['path']))->appendHeader('Content-Disposition', 'inline; filename="'.$fileName['name'].'"');
				return readfile($fileName['path']);
			}
		}
		//If file doesn't exists, throw 404
		throw PageNotFoundException::forPageNotFound();
	}

	private function checkMimetypeDefault($mimetype)
	{
		if(!str_contains($mimetype, 'image')
		&& $mimetype !== 'application/pdf'
		&& !str_contains($mimetype, 'video/')){
			return true;
		}
		return false;
	}
	
	private function getFile($id)
	{
		$this->response->setHeader('Cache-Control', 'no-cache')->appendHeader('Cache-Control', 'must-revalidate');
		$this->response->removeHeader('Location');
		
		$file = new Files();
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
		}else{
            $this->access_cfg = array(
                'needs_login' => false,
                'admin_only' => false,
            );
        }
		$this->SetSys(); //Checking session if necessary
	}
}
