<?php
namespace App\Controllers;

class Musicas extends BaseController
{
	public $module_name = 'Musicas';

	public function __construct()
	{
		parent::__construct();
		$this->upload_path = getValorParametro('path_video_karaoke');
	}
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		$extBtns['helpSongs'] = '<button class="btn btn-outline-info btn-rounded" type="button" onclick="showPopupWizard()"><i class="fas fa-question"></i></button>';
		if(hasPermission(1003, 'r')){
			$extBtns['import'] = '<button class="btn btn-outline-info btn-rounded" type="button" data-toggle="modal" data-target="#ImportModal"><i class="fas fa-upload"></i> Importar</button>';
		}

		return $extBtns;
	}
	
	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas';

		$this->pager_cfg['per_page'] = 40;

		$initial_filter = array(
			'codigo' => '',
			'name' => '',
			'tipo' => '',
			'fvt' => '',
		);
		
		$initial_order = array(
			'field' => 'name',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/musicas/index',
			'generic_filter' => array(
				'name',
				'codigo',
				'origem',
				'tipo'
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order);
		$key_join = ($this->filter['fvt']['value']) ? "musicas_favorites" : "LEFTJOIN_musicas_favorites";

		$this->mdl->select = "musicas.*, IF(musicas_favorites.id IS NOT NULL, 2, 1) as favorite";
		$this->mdl->join[$key_join] = "musicas.id = musicas_favorites.musica_id
		AND musicas_favorites.user_created = '{$this->session->get('auth_user')['id']}'
		AND musicas_favorites.deleted = 0";

		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);

		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;

		$hideInfoPopup = \App\Models\PreferenciasUsuario\PreferenciasUsuario::getPreference('hideInfoPopup');
		$this->data['showPopupWizard'] = ($hideInfoPopup) ? false: true;
		
		return $this->displayNew('pages/Musicas/index');
	}
	
	public function CheckImportVideo()
	{
		hasPermission(1003, 'r', true);
		$AjaxLib = new \App\Libraries\Sys\Ajax(['link']);
		
		$this->mdl = new \App\Models\Musicas\Musicas();


		$ytLib = new \App\Libraries\Youtube();
		$AjaxLib->body['link'] = trim($AjaxLib->body['link']);


		if(strpos($AjaxLib->body['link'], 'https://youtu.be/') !== false){
			$AjaxLib->body['link'] = 'https://www.youtube.com/watch?v='.str_replace('https://youtu.be/', '', $AjaxLib->body['link']);
		}


		$videoID = explode("?v=", $AjaxLib->body['link'])[1];
		$dataInfo = $ytLib->getInfo($videoID);
		if(!$dataInfo['md5']){
			$AjaxLib->setError('0x001', 'Link inválido!');
		}
		if(isset($AjaxLib->body['len_link'])){
			$dataInfo['len_link'] = $AjaxLib->body['len_link'];
		}
		$AjaxLib->setSuccess($dataInfo);
	}
	
	public function ImportVideoUrl()
	{
		hasPermission(1003, 'r', true);
		
		$AjaxLib = new \App\Libraries\Sys\Ajax(['link','md5','title']);
		
		$return_data = array(
			'exists' => false,
			'saved' => false,
			'downloaded' => false,
			'saved_record' => array(),
			'auto_fila' => false,
			'saved_fila' => array(),
			'len_link' => $AjaxLib->body['len_link']
		);
		
		$ytLib = new \App\Libraries\Youtube();

		$downloaded = $ytLib->importUrl($AjaxLib->body['link'], $AjaxLib->body['md5'], $AjaxLib->body['title']);

		if(!$downloaded){
			$AjaxLib->setError('3x002', 'Não foi possível realizar o download do vídeo! Entre em contato com o administrador!', $return_data);
		}
		
		$return_data = $this->mdl->force_save($AjaxLib->body['link'], $AjaxLib->body['md5'], $AjaxLib->body['title'], $AjaxLib->body['tipo']);
		
		$return_data['downloaded'] = $downloaded;

		if($AjaxLib->body['auto_fila']){
			$return_data['auto_fila'] = true;
			
			$musicas_fila_mdl = new \App\Models\MusicasFila\MusicasFila();
			$musicas_fila_mdl->f['name'] = $return_data['saved_record']['name'];
			$musicas_fila_mdl->f['musica_id'] = $return_data['saved_record']['id'];
			$musicas_fila_mdl->f['status'] = 'pendente';
			$return_data['saved_fila'] = $musicas_fila_mdl->saveRecord();
		}

		if(isset($AjaxLib->body['len_link'])){
			$return_data['len_link'] = $AjaxLib->body['len_link'];
		}
		
		$AjaxLib->setSuccess($return_data);
	}
	
	public function insert_fila_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\Ajax(['id']);
		
		$musicas_fila_mdl = new \App\Models\MusicasFila\MusicasFila();
		
		$this->mdl->f['id'] = $AjaxLib->body['id'];
		$result = $this->mdl->get();
		if(!$result){
			$AjaxLib->setError('2x001', 'registro não encontrado');
		}
		$musicas_fila_mdl->f['musica_id'] = $result['id'];
		$musicas_fila_mdl->f['status'] = 'pendente';
		$saved_record = $musicas_fila_mdl->saveRecord();
		$AjaxLib->setSuccess($saved_record);
	}
	
	public function insert_favorite_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\Ajax(['id']);
		
		$mdl = new \App\Models\MusicasFavorites\MusicasFavorites();
		
		$this->mdl->f['id'] = $AjaxLib->body['id'];
		$result = $this->mdl->get();
		if(!$result){
			$AjaxLib->setError('2x001', 'registro não encontrado');
		}
		$mdl->f['musica_id'] = $result['id'];

		if($AjaxLib->body['rmv']){
			$mdl->select = 'id';
			$mdl->where['musica_id'] = $result['id'];
			$mdl->where['user_created'] = $this->session->get('auth_user')['id'];
			$results = $mdl->search(10);
			foreach($results as $result){
				$mdl->f = [];
				$mdl->f['id'] = $result['id'];
				$mdl->deleteRecord();
			}
			$saved_record = true;
		}else{
			$saved_record = $mdl->saveRecord();
		}
		$AjaxLib->setSuccess($saved_record);
	}

	public function showPopupWizard()
	{
		$hideInfoPopup = \App\Models\PreferenciasUsuario\PreferenciasUsuario::getPreference('hideInfoPopup');
		$this->data['showPopupWizard'] = ($hideInfoPopup) ? false: true;

		return $this->displayNew('pages/Musicas/popupWizard');
	}
	
	public function hidePopupWizard()
	{
		$AjaxLib = new \App\Libraries\Sys\Ajax();
		
		$mdl = new \App\Models\PreferenciasUsuario\PreferenciasUsuario();
		$AjaxLib->setSuccess($mdl->setPref('hideInfoPopup', '1'));
	}
}