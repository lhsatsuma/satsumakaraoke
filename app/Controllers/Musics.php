<?php
namespace App\Controllers;

use App\Libraries\Sys\Ajax;
use App\Libraries\Youtube;
use App\Models\MusicsFavorites\MusicsFavorites;
use App\Models\UserPreferences\UserPreferences;

class Musics extends BaseController
{
	protected $module_name = 'Musics';

	public function __construct()
	{
		parent::__construct();
		$this->upload_path = getParameterValue('path_video_karaoke');
	}
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		$extBtns['helpSongs'] = '<button class="btn btn-outline-info btn-rounded" type="button" onclick="showPopupWizard()"><i class="fas fa-question"></i></button>';
		if(hasPermission(1003, 'r')){
			$extBtns['import'] = '<button class="btn btn-outline-info btn-rounded" type="button" data-toggle="modal" data-target="#ImportModal"><i class="fas fa-upload"></i></button>';
		}

		return $extBtns;
	}
	
	public function index($offset=0)
	{
		$this->data['title'] = 'Músicas';

		$this->pager_cfg['per_page'] = 100;

		$initial_filter = [
			'codigo' => '',
			'name' => '',
			'tipo' => '',
			'fvt' => '',
        ];
		
		$initial_order = [
			'field' => 'name',
			'order' => 'ASC',
        ];
		
		$this->filterLib_cfg = [
			'use' => true,
			'action' => base_url().'/musics/index',
			'generic_filter' => [
				'name',
				'codigo',
				'origem',
				'tipo'
            ],
        ];
		
		$this->PopulateFiltroPost($initial_filter, $initial_order);
		$key_join = $this->filter['fvt']['value'] ? 'musicas_favorites' : 'LEFTJOIN_musicas_favorites';

		$this->mdl->select = 'musicas.id, musicas.name, musicas.codigo,musicas.tipo, IF(musicas_favorites.id IS NOT NULL, 2, 1) as favorite';
		$this->mdl->join[$key_join] = "musicas.id = musicas_favorites.musica_id
		AND musicas_favorites.user_created = '{$this->session->get('auth_user')['id']}'
		AND musicas_favorites.deleted = 0";

		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		$result = $this->mdl->search($this->pager_cfg['per_page'], $offset);

		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;

		$hideInfoPopup = UserPreferences::getPreference('hideInfoPopup');
		$this->data['showPopupWizard'] = !$hideInfoPopup;
		
		return $this->displayNew('pages/Musics/index');
	}
	
	public function CheckImportVideo()
	{
		hasPermission(1003, 'r', true);
		$AjaxLib = new Ajax(['link']);
		
		$this->mdl = new \App\Models\Musics\Musics();


		$ytLib = new Youtube();
		$AjaxLib->body['link'] = trim($AjaxLib->body['link']);


		if(str_contains($AjaxLib->body['link'], 'https://youtu.be/')){
			$AjaxLib->body['link'] = 'https://www.youtube.com/watch?v='.str_replace('https://youtu.be/', '', $AjaxLib->body['link']);
		}


		$videoID = explode('?v=', $AjaxLib->body['link'])[1];
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
		
		$AjaxLib = new Ajax(['link','md5','title']);
		
		$return_data = [
			'exists' => false,
			'saved' => false,
			'downloaded' => false,
			'saved_record' => [],
			'auto_fila' => false,
			'saved_fila' => [],
			'len_link' => $AjaxLib->body['len_link']
        ];
		
		$ytLib = new Youtube();

		$downloaded = $ytLib->importUrl($AjaxLib->body['link'], $AjaxLib->body['md5']);

		if(!$downloaded){
			$AjaxLib->setError('3x002', 'Não foi possível realizar o download do vídeo! Entre em contato com o administrador!', $return_data);
		}
		
		$return_data = $this->mdl->force_save($AjaxLib->body['link'], $AjaxLib->body['md5'], $AjaxLib->body['title'], $AjaxLib->body['tipo']);
		
		$return_data['downloaded'] = $downloaded;

		if($AjaxLib->body['auto_fila']){
			$return_data['auto_fila'] = true;
			
			$musicas_fila_mdl = new \App\Models\Waitlist\Waitlist();
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
		
		$AjaxLib = new Ajax(['id']);
		
		$musicas_fila_mdl = new \App\Models\Waitlist\Waitlist();
		
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
		
		$AjaxLib = new Ajax(['id']);
		
		$mdl = new MusicsFavorites();
		
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
		$hideInfoPopup = UserPreferences::getPreference('hideInfoPopup');
		$this->data['showPopupWizard'] = !$hideInfoPopup;

		return $this->displayNew('pages/Musics/popupWizard');
	}
	
	public function hidePopupWizard()
	{
		$AjaxLib = new Ajax();
		
		$mdl = new UserPreferences();
		$AjaxLib->setSuccess($mdl->setPref('hideInfoPopup', '1'));
	}
}