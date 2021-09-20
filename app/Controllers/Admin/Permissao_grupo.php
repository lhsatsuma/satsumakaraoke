<?php
namespace App\Controllers\Admin;

class Permissao_grupo extends AdminBaseController
{
	public $module_name = 'PermissaoGrupo';

	public function index($offset = 0)
	{
		hasPermission(4, 'r', true);

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
		hasPermission(4, 'r', true);
		
		$ajaxLib = new \App\Libraries\Sys\AjaxLib(['grupo_id']);
		$permissao = new \App\Models\Permissao\Permissao();
		$permissoes = $permissao->getAllPermissao($ajaxLib->body['grupo_id']);
		
		$ajaxLib->setSuccess($permissoes);
	}
	
	public function salvar()
	{
		hasPermission(4, 'r', true);
		
		$postdata = getFormData();

		foreach($postdata['permissao'] as $perm_id => $saved_id){
			$this->mdl->f = [];
			if($saved_id){
				$this->mdl->f['id'] = $saved_id;
			}
			$this->mdl->f['grupo'] = $postdata['grupo'];
			$this->mdl->f['permissao'] = $perm_id;

			$nivel = 0;
			$nivel += ($postdata['permissao_checked'][$perm_id]['r']) ? 4 : 0;
			$nivel += ($postdata['permissao_checked'][$perm_id]['w']) ? 2 : 0;
			$nivel += ($postdata['permissao_checked'][$perm_id]['d']) ? 1 : 0;

			$this->mdl->f['nivel'] = $nivel;
			
			if($nivel == 0 && $saved_id){
				$this->mdl->deleteRecord();
			}else{
				$this->mdl->saveRecord();
			}
			$this->session->remove('PERM_CACHE_'.$perm_id.'_'.$this->mdl->f['grupo']);
		}
		$this->session->setFlashdata('msg', 'Permissões salvas com sucesso');
		rdct('/admin/permissao_grupo/index/');
	}
}