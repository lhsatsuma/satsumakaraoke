<?php
namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use JShrink\Minifier;

class JsManager extends BaseController
{
	public $data = [];
	public $session;
    protected $dummy_controller = true;
	
	public function get(...$fileEx)
	{
		if($fileEx){
			//Gets all info of file and check if allowed access
			$fileInfo = $this->getFile($fileEx);

			if($fileInfo){
                $this->response->setHeader('Content-Type', $fileInfo['mimetype'])->appendHeader('Content-Length', filesize($fileInfo['path']));
                $this->response->setHeader('Last-Modified', date('D, d M Y H:i:s', filemtime($fileInfo['path'])).' GMT');
				$this->response->setHeader('Expires', date('D, d M Y H:i:s', filemtime($fileInfo['path'])+2592000).' GMT');
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
		if(!in_array('public', $fileEx)){
			$this->session = getSession();

			/* Check access for file */
			if(strtolower($fileEx[0]) == 'admin'){
				$this->checkAccess('admin');
			}elseif(strtolower($fileEx[0]) == 'private'){
				$this->checkAccess('private');
			}
		}


		$ch_ver = GetCacheVersion();
		if(!str_contains(implode('/', $fileEx), 'Languages')){
			$cachePath = WRITEPATH . 'cache/js/js_min_'.md5(implode('_', $fileEx));
			$file_name = APPPATH.'Views/js/'.implode('/', $fileEx);
			if(str_ends_with($file_name, '.map') || !file_exists($file_name)){
				return false;
			}

            if($AppVersion->compress_output){
				//If dont exists cache file or original has been modified
				if(!file_exists($cachePath)
				|| filemtime($cachePath) < filemtime($file_name)){
					if(str_contains($file_name, '.min.')){
						$minifiedCode = file_get_contents($file_name);
					}else{
						$minifiedCode = Minifier::minify(file_get_contents($file_name));
					}
					if(!is_dir(WRITEPATH . 'cache/js/')){
						mkdir(WRITEPATH . 'cache/js/');
					}
					file_put_contents($cachePath, "/*{$ch_ver}*/\n".$minifiedCode);
				}
			}else{
				/* If dont compress, just return original file */
				$cachePath = $file_name;
			}
		}else{
			$cachePath = WRITEPATH . 'cache/'.implode('/', $fileEx);

			if(!file_exists($cachePath)){
				$locale = service('request')->getLocale();
				if(!getRecursiveLanguages($locale, APPPATH . 'Language/', APPPATH . 'Language/'.$locale.'/')){
					return false;
				}
			}
		}

		/* Return to controller action */
        if(file_exists($cachePath)){
            return ['name' => basename($cachePath), 'mimetype' => 'text/javascript', 'path' => $cachePath];
        }
		return false;
	}
	
	private function checkAccess($tipo)
	{
		if($tipo == 'admin'){
			$this->access_cfg = [
				'needs_login' => true,
				'admin_only' => true,
            ];
		}elseif($tipo == 'private'){
			$this->access_cfg = [
				'needs_login' => true,
				'admin_only' => false,
            ];
		}
		$this->SetSys(); //Checking session if necessary
	}
}