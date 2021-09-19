<?php
namespace App\Controllers\Admin;

class Permissao_grupo extends AdminBaseController
{
	public $module_name = 'PermissaoGrupo';
	
	public function ExtButtonsGenericFilters()
	{
		$this->ext_buttons['new'] = '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/grupos/editar">Novo +</a>';

		return parent::ExtButtonsGenericFilters();
	}

	public function index($offset = 0)
	{
		$this->data['title'] = 'Lista de Permissão por Grupo';
	
		$grupos = new \App\Models\Grupos\Grupos();
		$gruposAtivos = $grupos->getAtivos('id, nome');

		foreach($gruposAtivos as $key => $val){
			unset($gruposAtivos[$key]);
			$gruposAtivos[$val['id']] = $val['nome'];
		}
		$DropdownLib = new \App\Libraries\Sys\DropdownLib();
		$DropdownLib->values['gruposAtivos'] = $gruposAtivos;

		$this->data['gruposAtivos'] = $DropdownLib->GetDropdownHTML('gruposAtivos');
		
		return $this->displayNew('pages/Admin/PermissaoGrupo/index');
	}

	public function procurarPermissoes()
	{
		$permissao = new \App\Models\Permissao\Permissao();
		$permissao->getAllPermissao('1');
		
	}
	
	public function salvar()
	{
		$this->PopulatePost();
		
		if($this->mdl->f['deletado']){
			if(!empty($this->mdl->f['id'])){
				$deleted = $this->mdl->deleteRecord();
				if($deleted){
					rdct('/admin/grupos/index');
				}
				$this->validation_errors = array(
					'generic_error' => 'Não foi possível deletar o registro, tente novamente.',
				);
				$this->SetErrorValidatedForm(false);
				rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
			}
			rdct('/admin/grupos/editar');
		}
		
		if(!$this->ValidateFormPost()){
			$this->SetErrorValidatedForm();
			rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
		}

		$saved = $this->mdl->saveRecord();
		if($saved){
			rdct('/admin/grupos/detalhes/'.$this->mdl->f['id']);
		}else{
			$this->validation_errors = array(
				'generic_error' => $this->mdl->last_error,
			);
			$this->SetErrorValidatedForm();
			rdct('/admin/grupos/editar/'.$this->mdl->f['id']);
		}
	}
}