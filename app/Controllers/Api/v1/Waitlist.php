<?php
namespace App\Controllers\Api\v1;

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
}
