<?php
/* ALL FUNCTIONS WITH NO CLASS GOES HERE */

if(!isset($GLOBALS['AppVersion'])){
	$GLOBALS['AppVersion'] = new \Config\AppVersion();
	function GetCacheVersion(){
		global $AppVersion;
		return ($AppVersion->enc_md5) ? md5($AppVersion->version) : $AppVersion->version;
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

		$str = str_replace(" ","",$str);

		for($i=0;$i<strlen($str);$i++){
			$mask[strpos($mask,"#")] = $str[$i];
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
		return (ceil($number * $fig) / $fig);
	}
	function round_down($number, $precision = 2)
	{
		$fig = (int) str_pad('1', $precision, '0');
		return (floor($number * $fig) / $fig);
	}
	function create_guid(){
		$microTime = microtime();
		list($a_dec, $a_sec) = explode(' ', $microTime);
		$dec_hex = dechex($a_dec * 1000000);
		$sec_hex = dechex($a_sec);
		ensure_length($dec_hex, 5);
		ensure_length($sec_hex, 6);
		$guid = '';
		$guid .= $dec_hex;
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
		$cpf = preg_replace("/[^0-9]/", "", $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
		
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
			return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		else if ($cpf == '00000000000' || 
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
					$d += $cpf[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf[$c] != $d) {
					return false;
				}
			}

			return true;
		}
	}

	function validaCNPJ($cnpj){$cnpj=preg_replace('/[^0-9]/','',(string) $cnpj);if(strlen($cnpj)!=14)return false;if(preg_match('/(\d)\1{13}/',$cnpj))return false;for($i=0,$j=5,$soma=0;$i<12;$i++){$soma+=$cnpj[$i]*$j;$j=($j==2)?9:$j-1;}$resto=$soma%11;if($cnpj[12]!=($resto<2?0:11-$resto))return false;for($i=0,$j=6,$soma=0;$i<13;$i++){$soma+=$cnpj[$i]*$j;$j=($j==2)?9:$j-1;}$resto=$soma%11;return $cnpj[13]==($resto<2?0:11-$resto);}

	function convertSize($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		$math = round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
		
		$result = str_replace('.', ',', $math);
		return $result;
	}
	function removeAccents($string){
		return preg_replace(array("/ç|Ç/","/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","c a A e E i I o O u U n N"),$string);
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
		$result = ucfirst($result);
		return $result;
		function sliceDate($string, $inicio, $fim){
			$result = implode(' ', array_slice(explode(' ', $string), $inicio, $fim));
			return $result;
		}
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
	function getFormData($key=null, $raw = false)
	{
		global $requestForm;
		if(!$requestForm){
			$requestForm = \Config\Services::request();
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
	function hasPermission(int $cod, string $nivel_need = null, bool $rdct = false, int $grupo = null)
	{
		global $permissao;

		if(is_null($grupo)){
			$grupo = (int)getSession()->get('auth_user')['tipo'];
		}

		if(is_null($grupo)){
			$grupo = (int)getSession()->get('auth_user_admin')['tipo'];
		}

		if($grupo == 1){
			$permissions = [
				'r' => 1,
				'd' => 1,
				'w' => 1,
			];
		}else{
			$permissions = getSession()->get('PRM_'.$cod.'_'.$grupo);
			if(is_null($permissions)){
				if(!$permissao){
					$permissao = new \App\Models\PermissaoGrupo\PermissaoGrupo();
				}
				$levelPermission = $permissao->hasPermission($cod, $grupo)['nivel'];

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
				$level -= 1;
				if($level < 0){
					$level = $levelPermission;
				}else{
					$levelPermission -= 1;
					$permissions['d'] = 1;
				}

				getSession()->set('PRM_'.$cod.'_'.$grupo, $permissions);
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
		$focus = new \App\Controllers\BaseController();
		$focus->SetSys();
		$focus->SetView();
		$focus->SetLayout();
		$focus->SetInitialData();
		echo $focus->displayNew('403', false);exit;
	}
	function getValorParametro(string $cod)
	{
		global $parametros;
		$parametro_valor = getSession()->get('PARAM_CACHE_'.$cod);
		if(is_null($parametro_valor)){
			if(!$parametros){
				$parametros = new \App\Models\Parametros\Parametros();
			}
			$parametro_valor = $parametros->getValorParametro($cod)['valor'];
			getSession()->set('PARAM_CACHE_'.$cod, $parametro_valor);
		}
		return $parametro_valor;
	}
	function checkRdct($rdct_url)
	{
		if($rdct_url
		&& strpos($rdct_url, 'downloadManager') == false
		&& strpos($rdct_url, 'cssManager') == false
		&& strpos($rdct_url, 'jsManager') == false
		&& strpos($rdct_url, 'Ajax_requests') == false
		&& strpos($rdct_url, '_ajax') == false
		){
			return true;
		}else{
			return false;
		}
	}
}