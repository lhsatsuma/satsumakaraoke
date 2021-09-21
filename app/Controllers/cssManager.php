<?php
namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class CssManager extends BaseController
{
	public $data = array();
	public $session;
	public $parser;
	public $module_name = 'CssManager';
	public $dummy_controller = true;
	
	public function get(...$fileEx)
	{
		if($fileEx){
			//Get's all info of file and check if allowed access
			$fileInfo = $this->getFile($fileEx);

			if($fileInfo){
                $this->response->setHeader('Content-Type', $fileInfo['mimetype'])->appendHeader('Content-Length', filesize($fileInfo['path']));
                $this->response->setHeader('Last-Modified', date("D, d M Y H:i:s", filemtime($fileInfo['path'])).' GMT');
				$this->response->setHeader('Expires', date("D, d M Y H:i:s", filemtime($fileInfo['path'])+2592000).' GMT');
                $this->response->setHeader('Cache-Control', 'max-age=2592000');
                $this->response->setHeader('Pragma', 'public');
				return readfile($fileInfo['path']);
			}
		}
		
		//If file doesn't exists, throw 404
		throw PageNotFoundException::forPageNotFound();
	}
	
	private function getFile($fileEx)
	{
		global $AppVersion;
		$this->response->removeHeader('Location');

		/* Check access for file */
        if(strtolower($fileEx[0]) == 'admin'){
            $this->checkAccess('admin');
        }elseif(strtolower($fileEx[0]) == 'private'){
            $this->checkAccess('private');
        }


		$ch_ver = GetCacheVersion();
        $cachePath = WRITEPATH . 'cache/css_min_'.md5(implode('_', $fileEx));
        $file_name = APPPATH.'Views/css/'.implode('/', $fileEx);
		if(substr($file_name, -4) == '.map'){
			return false;
		}

		if($AppVersion->compress_output){
			//If dont exists cache file or original has been modified
			if(!file_exists($cachePath)
			|| (filemtime($cachePath) < filemtime($file_name))){
				if(strstr($file_name, '.min.')){
					$minifiedCode = file_get_contents($file_name);
				}else{
					$minifiedCode = file_get_contents($file_name);
				}
				file_put_contents($cachePath, "/*{$ch_ver}*/\n".$minifiedCode);
			}
		}else{
			/* If dont compress, just return original file */
			$cachePath = $file_name;
		}

		/* Return to controller action */
        if(file_exists($cachePath)){
            return ['nome' => basename($file_name), 'mimetype' => 'text/javascript', 'path' => $cachePath];
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