<?php
namespace App\Controllers\Api\V1;

class Waitlist extends ApiController
{
	protected $module_name = 'Waitlist';

	public function ended($id)
	{
		$this->mdl->f['id'] = $id;
		$result = $this->mdl->get();
		if(!$result){
			return $this->fail('Waitlist not found', 404);
		}

		$this->mdl->f['id'] = $result['id'];
		$this->mdl->f['status'] = 'encerrado';
		$this->mdl->saveRecord();
		$this->ajax->setSuccess();
		return $this->respond('', 200);
	}

    public function get()
    {
        if(!$this->checkMethod('GET')){
            return $this->fail('Method not supported', 405);
        }
        $encoded_line = json_decode(file_get_contents(WRITEPATH . 'utils/line_music.json'), true);
        $encoded = [
            't' => $encoded_line['t'] ?? 0,
            's' => $encoded_line['s'] ?? [],
        ];
        return $this->respond([
            'status' => 1,
            'data' => $encoded
        ], 200);
    }
}
