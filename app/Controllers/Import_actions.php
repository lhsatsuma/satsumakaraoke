<?php
namespace App\Controllers;

class Import_actions extends BaseController
{
	public $data = array();
	public $session;
	public $parser;
	public $module_name = 'Import_actions';
    public $dummy_controller = true;
    public $txts_path = ROOTPATH . 'public/uploads/TXTS/';
	
	public function index()
	{
		echo 'not implemented';exit;
    }
    
    public function correct_nomes_style_of()
    {
        $mdl = new \App\Models\Musicas\Musicasmodel();

        $yt = new \App\Libraries\YoutubeLib();
        $mdl->order_by['data_criacao'] = 'ASC';
        $mdl->where['nome'] =  'in the style of';
        $results = $mdl->search();
        $count_total = 0;
        foreach($results as $result){
            $mdl->f['id'] = $result['id'];
            $exploded = explode(' in the style of ', $result['nome']);
            if(count($exploded) < 2){
                continue;
            }
            $mdl->f['nome'] = $yt->__clear_title($exploded[1].' - '.$exploded[0]);
            $mdl->saveRecord();
            $count_total++;
        }
        echo "TOTAL: ".$count_total;
    }
	
	public function normalize_volume()
	{
        $mdl = new \App\Models\Musicas\Musicasmodel();

        $results = $mdl->search();
        foreach($results as $result){
            var_dump($result);exit;
            //E:\INETPUB\apps\satsumakaraoke\public\uploads\ffmpeg.exe -y -i b21effc4358a746e51af7c0cd90aa4de.mp4 -vcodec copy -af "volume=-3dB" b21effc4358a746e51af7c0cd90aa4de_copy.mp4 && del "b21effc4358a746e51af7c0cd90aa4de.mp4" && Ren "b21effc4358a746e51af7c0cd90aa4de_copy.mp4" "b21effc4358a746e51af7c0cd90aa4de.mp4"
        }
	}
	
	public function delete_arquivo_fisico()
	{
        $mdl = new \App\Models\Musicas\Musicasmodel();

        $results = $mdl->search();
        $missing = 0;
        foreach($results as $result){
            if(!file_exists(ROOTPATH . 'public/uploads/VIDEOSKARAOKE/'.$result['md5'].'.mp4')){
                $mdl->f['id'] = $result['id'];
                $mdl->DeleteRecord();
            }
        }
        var_dump($missing);exit;
	}
}
