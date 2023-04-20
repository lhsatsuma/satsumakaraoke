<?php
namespace App\Controllers\Api\V1;
use OAuth2\Request;
use App\Models\Waitlist\Waitlist;

class Thread extends ApiController
{
	protected $module_name = 'Waitlist';

	public function get()
	{
        if(!$this->checkMethod('GET')){
            return $this->fail('Method not supported', 405);
        }

        $this->mdl = new Waitlist();

		$thread = json_decode(file_get_contents(WRITEPATH . 'utils/thread.json'), true);

		if($this->body['search']){
			if($this->body['reset']){
				$this->mdl->createJSON();
			}
			$encoded_line = json_decode(file_get_contents(WRITEPATH . 'utils/line_music.json'), true);
		}else{
			$encoded_line = [];
		}

		$encoded = [
			'th' => ($thread) ? $thread : null,
			't' => ($encoded_line['t']) ? $encoded_line['t'] : 0,
			's' => ($encoded_line['s']) ? $encoded_line['s'] : [],
		];

		return $this->respond($encoded, 200);
	}

	public function reset()
	{
		if(!$this->checkMethod('DELETE')){
            return $this->fail('Method not supported', 405);
        }
		
		unlink(WRITEPATH . 'utils/thread.json');

		return $this->respondDeleted();
	}

	public function getCopy()
	{
		if(!$this->checkMethod('GET')){
            return $this->fail('Method not supported', 405);
        }
		$encoded = file_get_contents(WRITEPATH . 'utils/threadCopy.json');
		return $this->respond(['status' => 1, 'data' => json_decode($encoded, true)], 200);
	}

	public function set()
	{
		if(!$this->checkMethod('PUT')){
            return $this->fail('Method not supported', 405);
        }

		if(empty($this->body['copy_only'])){
			$data_encode = [
				'action' => $this->body['action'],
				'valueTo' => (int)$this->body['valueTo'],
			];

			$encoded = json_encode($data_encode);
			file_put_contents(WRITEPATH . 'utils/thread.json', $encoded);
		}
		
		if($this->body['action'] == 'volume'){
			$data_copy = [
				'volume' => (int)$this->body['valueTo'],
			];
			file_put_contents(WRITEPATH . 'utils/threadCopy.json', json_encode($data_copy));
		}
        return $this->respond(['status' => 1, 'data' => 'ok'], 200);
	}
}
