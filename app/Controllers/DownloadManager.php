<?php
namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class DownloadManager extends BaseController
{
	public $data = array();
	public $session;
	public $parser;
	public $module_name = 'DownloadManager';
	public $dummy_controller = true;
	
	public function download($id = null)
	{
		if($id){
			
			//Get's all info of file and check if allowed access
			$fileName = $this->getFile($id);
			if($fileName){
				return $this->response->download($fileName['nome'], file_get_contents($fileName['path']));
			}
		}
		
		//If file doesn't exists, throw 404
		throw PageNotFoundException::forPageNotFound();
	}
	
	public function preview($id = null)
	{	
		if($id){
			//Get's all info of file and check if allowed access
			$fileName = $this->getFile($id);
			if($fileName){
				
				//Check if mimetype it's an image or pdf, otherwise let's download the file
				if(strpos($fileName['mimetype'], 'image') === false && $fileName['mimetype'] !== 'application/pdf'){
					return $this->response->download($fileName['nome'], file_get_contents($fileName['path']));
				}
				
				//Setting all headers necessarys and returning binary file
				$this->response->setHeader('Content-Type', $fileName['mimetype'])->appendHeader('Content-Length', filesize($fileName['path']))->appendHeader('Content-Disposition', 'inline; filename='.$fileName['nome'].'');
				return readfile($fileName['path']);
			}
		}
		//If file doesn't exists, throw 404
		throw PageNotFoundException::forPageNotFound();
	}
	
	private function getFile($id)
	{
		$this->response->setHeader('Cache-Control', 'no-cache')->appendHeader('Cache-Control', 'must-revalidate');
		$this->response->removeHeader('Location');
		
		$arquivos = new \App\Models\Arquivos\Arquivos();
		$arquivos->f['id'] = $id;
		$result = $arquivos->get();
		if($result){
			$this->checkAccess($result['tipo']);
			$file_name = ROOTPATH.'public/uploads/'.$result['arquivo'];
			if(file_exists($file_name)){
				return ['nome' => $result['nome'], 'mimetype' => $result['mimetype'], 'path' => $file_name];
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
				'role_access' => array(),
			);
		}elseif($tipo == 'private'){
			$this->access_cfg = array(
				'needs_login' => true,
				'admin_only' => false,
				'role_access' => array(),
			);
		}
		$this->SetSys(); //Checking session if necessary
	}
}
