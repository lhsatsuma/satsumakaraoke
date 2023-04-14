<?php
/* ALL FUNCTIONS WITH NO CLASS GOES HERE */

use App\Controllers\BaseController;
use App\Models\Parameters\Parameters;
use App\Models\ProfilePermissions\ProfilePermissions;
use Config\AppVersion;
use Config\Services;

if(!isset($GLOBALS['AppVersion'])){
	$GLOBALS['AppVersion'] = new AppVersion();
	function GetCacheVersion(){
		global $AppVersion;
		return $AppVersion->enc_md5 ? md5($AppVersion->version) : $AppVersion->version;
	}
	function getTitle(){
		global $AppVersion;
		return $AppVersion->title;
	}
	function rdct($to){
		$request = getFormData();
		if($request['force_redirect_whatever']){
			$to = $request['force_redirect_whatever'];
		}
		header('Location: '.$to);
		exit;
	}
	function scan_dir($path){
		$scan = scandir($path);
		$key_1 = array_search('.', $scan);
		$key_2 = array_search('..', $scan);
		unset($scan[$key_1]);
		unset($scan[$key_2]);
		return $scan;
	}
	function Mask($mask,$str)
	{

		$str = str_replace(' ', '',$str);

		for($i=0;$i<strlen($str);$i++){
			$mask[strpos($mask, '#')] = $str[$i];
		}

		return $mask;

	}
	function MaskCPF($str)
	{
		return Mask('###.###.###-##', $str);
	}

	function round_up($number, $precision = 2)
	{
		$fig = (int) str_pad('1', $precision, '0');
		return ceil($number * $fig) / $fig;
	}
	function round_down($number, $precision = 2)
	{
		$fig = (int) str_pad('1', $precision, '0');
		return floor($number * $fig) / $fig;
	}
	function create_guid($legacy = false){
        if(!$legacy){
            return create_guid_v4();
        }
		$microTime = microtime();
		list($a_dec, $a_sec) = explode(' ', $microTime);
		$dec_hex = dechex($a_dec * 1000000);
		$sec_hex = dechex($a_sec);
		ensure_length($dec_hex, 5);
		ensure_length($sec_hex, 6);
		$guid = $dec_hex;
		$guid .= create_guid_section(3);
		$guid .= '-';
		$guid .= create_guid_section(4);
		$guid .= '-';
		$guid .= create_guid_section(4);
		$guid .= '-';
		$guid .= create_guid_section(4);
		$guid .= '-';
		$guid .= $sec_hex;
		$guid .= create_guid_section(6);
		return $guid;
	}
	function create_guid_section($characters){
		$return = '';
		for ($i = 0; $i < $characters; ++$i) {
			$return .= dechex(mt_rand(0, 15));
		}
		return $return;
	}
	function ensure_length(&$string, $length){
		$strlen = strlen($string);
		if ($strlen < $length) {
			$string = str_pad($string, $length, '0');
		} elseif ($strlen > $length) {
			$string = substr($string, 0, $length);
		}
	}
	function microtime_diff($a, $b){
		list($a_dec, $a_sec) = explode(' ', $a);
		list($b_dec, $b_sec) = explode(' ', $b);
		return $b_sec - $a_sec + $b_dec - $a_dec;
	}
	function encrypt_pass($toEncrypt, string $type = 'default')
	{
		if(empty($toEncrypt)){
			return null;
		}
		if($type === 'md5'){
			return md5($toEncrypt);
		}
		return password_hash($toEncrypt, PASSWORD_BCRYPT, ['cost' => 12]);
	}
	function verify_pass($toEncrypt, $encrypted)
	{
		$validPass = false;
		if(preg_match('/^[a-f0-9]{32}$/', $encrypted)){
			$validPass = md5($toEncrypt) === $encrypted;
		}

		if(!$validPass){
			$validPass = password_verify($toEncrypt, $encrypted);
		}
		return $validPass;
	}
	function create_slug($text)
	{
		// iconv_set_encoding("internal_encoding", "UTF-8");
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = removeAccents($text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim
		$text = trim($text, '-');
	
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
	
		// lowercase
		$text = strtolower($text);
	
		if (empty($text)) {
		return 'n-a';
		}
	
		return $text;
	}
	function validaCPF($cpf = null) {

		// Verifica se um número foi informado
		if(empty($cpf)) {
			return false;
		}

		// Elimina possivel mascara
		$cpf = preg_replace('/[^0-9]/', '', $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
		
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
			return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		if ($cpf == '00000000000' ||
			$cpf == '11111111111' || 
			$cpf == '22222222222' || 
			$cpf == '33333333333' || 
			$cpf == '44444444444' || 
			$cpf == '55555555555' || 
			$cpf == '66666666666' || 
			$cpf == '77777777777' || 
			$cpf == '88888888888' || 
			$cpf == '99999999999') {
			return false;
		// Calcula os digitos verificadores para verificar se o
		// CPF é válido
		} else {   
			
			for ($t = 9; $t < 11; $t++) {
				
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf[$c] * ($t + 1 - $c);
				}
				$d = 10 * $d % 11 % 10;
				if ($cpf[$c] != $d) {
					return false;
				}
			}

			return true;
		}
	}

	function validaCNPJ($cnpj){$cnpj=preg_replace('/[^0-9]/','',(string) $cnpj);if(strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj))return false;
        for($i=0, $j=5, $soma=0; $i<12; $i++){$soma+=$cnpj[$i]*$j;$j= $j==2 ?9:$j-1;}$resto=$soma%11;if($cnpj[12]!=($resto<2?0:11-$resto))return false;for($i=0, $j=6, $soma=0; $i<13; $i++){$soma+=$cnpj[$i]*$j;$j= $j==2 ?9:$j-1;}$resto=$soma%11;return $cnpj[13]==($resto<2?0:11-$resto);}

	function convertSize($size){
		$base = log($size) / log(1024);
		$suffix = ['', 'KB', 'MB', 'GB', 'TB'];
		$f_base = floor($base);
		$math = round(1024 ** ($base - floor($base)), 1) . $suffix[$f_base];
		
		return str_replace('.', ',', $math);
	}
	function removeAccents($string){
		return preg_replace(['/ç|Ç/', '/(á|à|ã|â|ä)/', '/(Á|À|Ã|Â|Ä)/', '/(é|è|ê|ë)/', '/(É|È|Ê|Ë)/', '/(í|ì|î|ï)/', '/(Í|Ì|Î|Ï)/', '/(ó|ò|õ|ô|ö)/', '/(Ó|Ò|Õ|Ô|Ö)/', '/(ú|ù|û|ü)/', '/(Ú|Ù|Û|Ü)/', '/(ñ)/', '/(Ñ)/'],explode(' ', 'c a A e E i I o O u U n N'),$string);
	}
	function sliceString($string, $int, $capitalize = false, $clean = false){
		$result = implode(' ', array_slice(explode(' ', $string), 0, $int));
		$result = removeAccents($result);
		$result = str_replace('-','', $result);
		$result = str_replace(':','', $result);
		if(!$capitalize){
			$result = str_replace(' ', '_', $result);
			$result = strtoupper($result);
		}
		if($clean){
			$result = str_replace('_', '', $result);
		}
		return ucfirst($result);
	}
	
	function sliceDate($string, $inicio, $fim){
		return implode(' ', array_slice(explode(' ', $string), $inicio, $fim));
	}

	function sanitizeYt($link){
		$link = str_replace(['https://youtu.be/', 'https://www.youtube.com/embed/', 'https://www.youtube.com/watch?v='], '', $link);
		return explode('&ab_channel=', $link)[0];
	}
		
	function xmlToArray($string)
	{
		$xml = simplexml_load_string($string);
		$json = json_encode($xml);
		return json_decode($json,TRUE);
	}

	function getModules()
	{
		$return = [];
		$models = scan_dir(APPPATH . 'Models/');
		foreach($models as $dir){

			/* Check if dir has an model and it's not basicmodel */
			if(is_dir(APPPATH . 'Models/'.$dir)
			&& $dir !== 'Basic'
			&& file_exists(APPPATH . 'Models/'.$dir.'/'.$dir.'.php')){

				$modelCall = '\\App\\Models\\'.$dir.'\\'.$dir;
				$ns = new $modelCall();
				$return[$modelCall] = $ns->model_name;
			}

		}
		return $return;
	}
	function getFormData($key=null)
	{
		global $requestForm;
		if(!$requestForm){
			$requestForm = Services::request();
		}
		if($requestForm->isAJAX()){
			$rawInput = $requestForm->getBody();
			$decoded = json_decode($rawInput, true);
			if($key === null){
				return $decoded;
			}elseif(isset($decoded[$key])){
				return $decoded[$key];
			}
		}
		if($key === null){
			return $requestForm->getPostGet();
		}
		return $requestForm->getPostGet($key);
	}
	function getSession()
	{
		global $sessionCI;
		if(!$sessionCI){
			$sessionCI = session();
		}
		return $sessionCI;
	}
	function hasPermission(int $cod, string $nivel_need = null, bool $rdct = false, int $profile = null)
	{
		global $permissao;

		if(is_null($profile)){
			$profile = (int)getSession()->get('auth_user')['profile'];
		}

		if(is_null($profile)){
			$profile = (int)getSession()->get('auth_user_admin')['profile'];
		}

		if($profile == 1){
			$permissions = [
				'r' => 1,
				'd' => 1,
				'w' => 1,
			];
		}else{
			$permissions = getSession()->get('PRM_'.$cod.'_'.$profile);
			if(is_null($permissions)){
				if(!$permissao){
					$permissao = new ProfilePermissions();
				}
				$levelPermission = $permissao->hasPermission($cod, $profile)['nivel'];

				$level = $levelPermission;
				$permissions = [
					'r' => 0,
					'd' => 0,
					'w' => 0,
				];
				$level -= 4;
				if($level < 0){
					$level = $levelPermission;
				}else{
					$levelPermission -= 4;
					$permissions['r'] = 1;
				}

				$level -= 2;
				if($level < 0){
					$level = $levelPermission;
				}else{
					$levelPermission -= 2;
					$permissions['w'] = 1;
				}
				--$level;
				if($level > 0){
					$permissions['d'] = 1;
				}

				getSession()->set('PRM_'.$cod.'_'.$profile, $permissions);
			}
		}
		if(is_null($nivel_need)){
			return $permissions;
		}
		if(!$permissions[$nivel_need] && $rdct){
			rdctForbbiden();
		}

		return $permissions[$nivel_need];
	}
	function rdctForbbiden()
	{
		$focus = new BaseController();
		$focus->SetSys();
		$focus->SetView();
		$focus->SetLayout();
		$focus->SetInitialData();
		echo $focus->displayNew('403', false);exit;
	}
	function getParameterValue(string $cod)
	{
		global $parametros;
		$parametro_valor = getSession()->get('PARAM_CACHE_'.$cod);
		if(is_null($parametro_valor)){
			if(!$parametros){
				$parametros = new Parameters();
			}
			$parametro_valor = $parametros->getParameterValue($cod)['valor'];
			getSession()->set('PARAM_CACHE_'.$cod, $parametro_valor);
		}
		return $parametro_valor;
	}
	function checkRdct($rdct_url)
	{
		return $rdct_url
			&& !str_contains($rdct_url, 'downloadManager')
			&& !str_contains($rdct_url, 'cssManager')
			&& !str_contains($rdct_url, 'jsManager')
			&& !str_contains($rdct_url, 'Ajax_requests')
			&& !str_contains($rdct_url, '_ajax');
	}

	function isMobile()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
	}

	function translate(string $label = '', string $module = ''){
		global $locale;
		if(empty($module)){
			if(empty($GLOBALS['lang_file'])){
				return '';
			}
			$module = $GLOBALS['lang_file'];
		}
		if($module === 'app'){
			$module = 'Public.App';
		}elseif(!str_contains($module, 'Public.')){
			$module = 'Controllers.'.$module;
		}
		
		if(!isset($GLOBALS['translates'][$module])){
			$file = explode('.', $module);
			if(!$locale){
				$locale = service('request')->getLocale();
			}
			$file = APPPATH . 'Language/'.$locale.'/'.implode('/', $file).'.php';
			if(file_exists($file)){
				log_message('debug', 'Calling language: '.$module);
				$GLOBALS['translates'][$module] = require $file;
			}else{
				log_message('error', 'Language file dont exist: '.$module);
			}
		}
		$actual_language = $GLOBALS['translates'][$module];
		if($label === ''){
			return $actual_language;
		}elseif($actual_language[$label]){
			return $actual_language[$label];
		}elseif($GLOBALS['translates']['Public.App'][$label]){
			return $GLOBALS['translates']['Public.App'][$label];
		}else{
			return $label;
		}
	}
	function getRecursiveLanguages($lang, $remove, $folder)
    {
        $files_return = [];
        if(!is_dir($folder)){
            return false;
        }
        $files = scan_dir($folder);
        $count = 0;
        foreach($files as $file){
        $check_folder = $folder.$file;

            if(is_dir($check_folder)){
                getRecursiveLanguages($lang, $remove, $check_folder.'/');
            }elseif(str_contains($file, '.php')
                && !str_contains($file, 'Validation')){
                $file = str_replace('.php', '', $file);
                $key_array = str_replace([$remove.$lang.'/','Controllers/','/'], ['','', '.'], $folder.$file);
                $content_json = json_encode(require $check_folder);
                $files_return[$key_array] = "/* Language for {$key_array} in {$lang} */\n";
                $files_return[$key_array] .= "translate.add('{$key_array}', {$content_json});\n";
                $count++;
            }
        }
        $filename_cache = 'compressed_lang.js';
        $cache_path = WRITEPATH . 'cache/Languages/'.str_replace($remove, '', $folder);
        if(!is_dir($cache_path)){
            mkdir($cache_path, 0777, true);
        }
        file_put_contents($cache_path.$filename_cache, $files_return);
        return $count;
    }


    function create_guid_v4()
    {
        $data = random_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}