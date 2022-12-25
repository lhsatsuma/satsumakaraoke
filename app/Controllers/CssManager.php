<?php
namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class CssManager extends BaseController
{
	public $data = [];
	public $session;
	protected $module_name = 'CssManager';
    protected $dummy_controller = true;
	
	public function get(...$fileEx)
	{
		if($fileEx){
			//Get all info of file and check if allowed access
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

		$ch_ver = GetCacheVersion();
        $cachePath = WRITEPATH . 'cache/css/css_min_'.md5(implode('_', $fileEx));
        $file_name = APPPATH.'Views/css/'.implode('/', $fileEx);
		if(str_ends_with($file_name, '.map')){
			return false;
		}

		if($AppVersion->compress_output){
			//If don't exists cache file or original has been modified
			if(!file_exists($cachePath)
			|| filemtime($cachePath) < filemtime($file_name)){
				if(str_contains($file_name, '.min.')){
					$minifiedCode = file_get_contents($file_name);
				}else{
					$minifiedCode = file_get_contents($file_name);
					//replace all new lines and spaces
					$minifiedCode = str_replace("\r\n", ' ', $minifiedCode);
					$minifiedCode = str_replace("\t", ' ', $minifiedCode);
					$minifiedCode = preg_replace('/(\d*px(?!;))/', '$1 ', $minifiedCode);
				}
				if(!is_dir(WRITEPATH . 'cache/css/')){
					mkdir(WRITEPATH . 'cache/css/');
				}
				file_put_contents($cachePath, "/*{$ch_ver}*/\n".$minifiedCode);
			}
		}else{
			/* If you don't compress, just return original file */
			$cachePath = $file_name;
		}

		/* Return to controller action */
        if(file_exists($cachePath)){
            return ['name' => basename($file_name), 'mimetype' => 'text/css', 'path' => $cachePath];
        }
		return false;
	}
}