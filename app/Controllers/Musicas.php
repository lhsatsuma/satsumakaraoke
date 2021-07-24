<?php
namespace App\Controllers;

class Musicas extends BaseController
{
	public $module_name = 'Musicas';
	public $upload_path = ROOTPATH . 'public/uploads/VIDEOSKARAOKE/';
	
	public function ExtButtonsGenericFilters()
	{
		$extBtns = [];
		$extBtns['helpSongs'] = '<button class="btn btn-outline-info btn-rounded" type="button" data-toggle="modal" data-target="#helpSongsModal"><i class="fas fa-info"></i></button>';
		if($this->session->get('auth_user')['tipo'] >= 80
		|| $this->session->get('auth_user_admin')['tipo'] >= 80){
			$extBtns['import'] = '<button class="btn btn-outline-info btn-rounded" type="button" data-toggle="modal" data-target="#ImportModal"><i class="fas fa-upload"></i> Importar</button>';
		}

		return $extBtns;
	}
	
	public function index($offset=0)
	{
		
		$this->data['title'] = 'Músicas';


		$initial_filter = array(
			'codigo' => '',
			'nome' => '',
			'tipo' => '',
		);
		
		$initial_order = array(
			'field' => 'nome',
			'order' => 'ASC',
		);
		
		$this->filterLib_cfg = array(
			'use' => true,
			'action' => base_url().'/musicas/index',
			'generic_filter' => array(
				'nome',
				'codigo',
				'origem',
				'tipo'
			),
		);
		
		$this->PopulateFiltroPost($initial_filter, $initial_order);
		
		$total_row = $this->mdl->total_rows();
		$this->data['pagination'] = $this->GetPagination($total_row, $offset);
		
		$this->mdl->select = "musicas.*, CAST(codigo AS DECIMAL(10,2)) AS codigo_cast";
		$result = $this->mdl->search(20, $offset);
		$result = $this->mdl->formatRecordsView($result);
		
		$this->data['records'] = $result;
		
		return $this->display_template($this->view->setData($this->data)->view('pages/Musicas/index'));
	}
	
	public function CheckImportVideo()
	{
		$AjaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$AjaxLib->CheckIncoming();
		
		$required = array(
			'link',
		);
		$AjaxLib->CheckRequired($required);
		unset($required);
		
		$body_post = $AjaxLib->GetData();
		
		$this->mdl = new \App\Models\Musicas\Musicasmodel();


		$ytLib = new \App\Libraries\YoutubeLib();
		$body_post['link'] = trim($body_post['link']);


		if(strpos($body_post['link'], 'https://youtu.be/') !== false){
			$body_post['link'] = 'https://www.youtube.com/watch?v='.str_replace('https://youtu.be/', '', $body_post['link']);
		}


		$videoID = explode("?v=", $body_post['link'])[1];
		$videoMD5 = md5(explode("?v=", $body_post['link'])[1]);
		$dataInfo = $ytLib->getInfo($videoID);
		if(!$dataInfo['md5']){
			$AjaxLib->setError('0x001', 'Link inválido!');
		}
		if(isset($body_post['len_link'])){
			$dataInfo['len_link'] = $body_post['len_link'];
		}
		$AjaxLib->setSuccess($dataInfo);
	}
	
	public function ImportVideoUrl()
	{
		
		$AjaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$AjaxLib->CheckIncoming();
		
		$required = array(
			'link',
			'md5',
			'title',
		);
		$AjaxLib->CheckRequired($required);
		unset($required);
		
		$body_post = $AjaxLib->GetData();
		
		$return_data = array(
			'exists' => false,
			'saved' => false,
			'downloaded' => false,
			'saved_record' => array(),
			'auto_fila' => false,
			'saved_fila' => array(),
			'len_link' => $body_post['len_link']
		);
		
		$ytLib = new \App\Libraries\YoutubeLib();

		$downloaded = $ytLib->importUrl($body_post['link'], $body_post['md5'], $body_post['title']);

		if(!$downloaded){
			$AjaxLib->setError('3x002', 'Não foi possível realizar o download do vídeo! Entre em contato com o administrador!');
		}
		
		$return_data = $this->mdl->force_save($body_post['link'], $body_post['md5'], $body_post['title'], $body_post['tipo']);
		
		$return_data['downloaded'] = $downloaded;

		if($body_post['auto_fila']){
			$return_data['auto_fila'] = true;
			
			$musicas_fila_mdl = new \App\Models\Musicas_fila\Musicas_filamodel();
			$musicas_fila_mdl->f['nome'] = $return_data['saved_record']['nome'];
			$musicas_fila_mdl->f['musica_id'] = $return_data['saved_record']['id'];
			$musicas_fila_mdl->f['status'] = 'pendente';
			$return_data['saved_fila'] = $musicas_fila_mdl->saveRecord();
		}

		if(isset($body_post['len_link'])){
			$return_data['len_link'] = $body_post['len_link'];
		}
		
		$AjaxLib->setSuccess($return_data);
	}
	
	public function insert_fila_ajax()
	{
		
		$AjaxLib = new \App\Libraries\Sys\AjaxLib($this->request);
		$AjaxLib->CheckIncoming();
		
		
		$required = array(
			'id',
		);
		$AjaxLib->CheckRequired($required);
		unset($required);
		
		$body_post = $AjaxLib->GetData();
		
		$musicas_fila_mdl = new \App\Models\Musicas_fila\Musicas_filamodel();
		
		$this->mdl->f['id'] = $body_post['id'];
		$result = $this->mdl->get();
		if(!$result){
			$AjaxLib->setError('2x001', 'registro não encontrado');
		}
		$musicas_fila_mdl->f['musica_id'] = $result['id'];
		$musicas_fila_mdl->f['status'] = 'pendente';
		$saved_record = $musicas_fila_mdl->saveRecord();
		$AjaxLib->setSuccess($saved_record);
		
	}

	public function karaoke()
	{
		$version = new \Config\AppVersion();

		$data = [
			'karaokeURL' => ($version->VideosKaraokeURL) ? $version->VideosKaraokeURL : base_url().'/',
			'host_fila' => ($version->host_fila) ? $version->host_fila : base_url().'/',
		];
		return $this->display($this->view->setData($data)->view('pages/Musicas/karaoke'));
	}
}