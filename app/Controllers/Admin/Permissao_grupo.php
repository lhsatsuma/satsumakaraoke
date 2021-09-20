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
		hasPermission(16, true);

		$this->data['title'] = 'Lista de Permissão por Grupo';
	
		$grupos = new \App\Models\Grupos\Grupos();
		$gruposAtivos = $grupos->getAtivos('id, nome');

		$gruposArr = [];
		foreach($gruposAtivos as $key => $val){
			unset($gruposAtivos[$key]);
			$gruposArr[$val['id']] = $val['nome'];
		}
		unset($gruposAtivos);
		$DropdownLib = new \App\Libraries\Sys\DropdownLib();
		$DropdownLib->values['gruposAtivos'] = $gruposArr;

		$this->data['gruposAtivos'] = $DropdownLib->GetDropdownHTML('gruposAtivos');
		
		return $this->displayNew('pages/Admin/PermissaoGrupo/index');
	}

	public function procurarPermissoes()
	{
		hasPermission(16, true);
		
		$ajaxLib = new \App\Libraries\Sys\AjaxLib(['grupo_id']);
		$permissao = new \App\Models\Permissao\Permissao();
		$permissoes = $permissao->getAllPermissao($ajaxLib->body['grupo_id']);
		
		$ajaxLib->setSuccess($permissoes);
	}
	
	public function salvar()
	{
		hasPermission(16, true);
		
		$postdata = getFormData();
		foreach($postdata['permissao'] as $perm_id => $saved_id){
			$this->mdl->f = [];
			if($saved_id){
				$this->mdl->f['id'] = $saved_id;
			}
			$this->mdl->f['grupo'] = $postdata['grupo'];
			$this->mdl->f['permissao'] = $perm_id;

			if(!$postdata['permissao_checked'][$perm_id] && $saved_id){
				$this->mdl->deleteRecord();
			}elseif($postdata['permissao_checked'][$perm_id] && !$saved_id){
				$this->mdl->saveRecord();
			}
		}
		$this->session->setFlashdata('msg', 'Permissões salvas com sucesso');
		rdct('/admin/permissao_grupo/index/');
	}
}