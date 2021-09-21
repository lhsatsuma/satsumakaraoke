<?php
namespace App\Libraries;

if(!function_exists('remove_emoji')){
	function remove_emoji($text)
	{
        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }

    function remove_non_utf8($str)
    {
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
return preg_replace($regex, '$1', $str);

    }
}
class Youtube
{
    public $upload_path = ROOTPATH . 'public/uploads/VIDEOSKARAOKE/';

    public function __clear_title($title, $decode = true)
    {   
        if($decode){
            $new_title = trim(remove_emoji(str_replace("&nbsp;", " ", $title)));
        }
        $title = $new_title;

        $title = str_replace([
            ' [KARAOKE VERSION | OFF VOCAL/LINKED HORIZON]',
            ' [Sing with Us - Instrumental] [Karaoke]',
            ' [Sing with Us - Instrumental] [Karaoke]',
            ' with lyrics (no lead vocal)',
            ' | Karaoke with Lyrics',
            ' / VERSÃO KARAOKÊ',
            ' with lyrics (with lead vocal)',
            ' (no lead vocal)',
            ' (KARAOKE VERSION)',
            ' (Karaoke Version)',
            ' (Karaokê Version)',
            ' | Karaoke Version | KaraFun',
            ' | Version | KaraFun',
            ' (KARAOKE COMPLETO)',
            ' with lyrics (with lead vocal)',
            ' (Radio Version)',
            ' (Backing Track)',
            ' ( Karaoke)',
            ' (Karaoke)',
            ' (karaoke)',
            ' ( KARAOKE )',
            ' - KARAOKÊ',
            ' - Karaokê',
            ' - Karaoke',
            ' (English version)',
            ' [Karaoke]',
            '[Karaoke] ',
            '[KARAOKE] ',
            ' (Live)',
            'Karaoke ',
            ' Karaoke',
            'Karaokê ',
            ' KARAOKE',
            ' (off vocals)',
            ' (HD)',
            ' *',
            '"',
        ],
        '',
        $title);

        $title = str_replace([
            ' in the Style of ',
            ' : ',
        ],
        [
            ' - ',
            ' - ',
        ],
        $title);
        $title = (string) trim(remove_non_utf8($title));
        
        return $title;
    }
    public function getInfo($videoID)
    {	
        $callUrl = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$videoID.'&key=AIzaSyAjrrM41ewtN5bJWzhi6oQtP5A6H6piyL0';
        $page = file_get_contents($callUrl);
        $decoded = json_decode($page, true);

        $data = [
            'title' => $this->__clear_title($decoded['items'][0]['snippet']['title']),
            'videoID' => $decoded['items'][0]['id'],
            'url' => 'https://www.youtube.com/watch?v='.$decoded['items'][0]['id'],
            'md5' => md5($decoded['items'][0]['id']),
        ];
		return $data;
    }

    public function importUrl($url, $md5, $title)
    {
        log_message('info', "DOWNLOADING VIDEO: ".$url);
        if(file_exists($this->upload_path . $md5.'.mp4')){
			unlink($this->upload_path . $md5.'.mp4');
		}
		
		$string = ("cd {$this->upload_path} && youtube-dl.exe " . escapeshellarg($url) . ' --cookies cookies.txt -f 18 --newline --no-cache-dir -o ' .
                  escapeshellarg($md5.".mp4"));
        log_message('info', "COMMAND: ".$string);
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin
		   1 => array("pipe", "w"),  // stdout
		   2 => array("pipe", "w"),  // stderr
		);
		$process = proc_open($string, $descriptorspec, $pipes);
		$stdout = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        if(strpos($stderr, 'HTTP Error 429') !== false){
            log_message('critical', "FATAL ERROR DOWNLOAD VIDEO: ".$url);
            var_dump($stderr);exit;
        }
		fclose($pipes[2]);
        $ret = proc_close($process);
        
		if(strpos($stdout, '[download] 100% of') !== false){
            log_message('info', "DOWNLOAD COMPLETED: ".$url);
			return true;
		}else{
            if(function_exists('log_message')){
                log_message('critical', "ERROR DOWNLOADING VIDEO: ".$url);
                log_message('critical', print_r($stdout, true));
            }else{
                echo "\nERROR DOWNLOADING VIDEO: ".$url;
            }
            return false;
        }

    }
}
?>