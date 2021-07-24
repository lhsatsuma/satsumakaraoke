<?php
/*
Log Class v3.0
Ultima modificacao: 31/07/2018
Por: Luis Henrique Minoru Satsuma
Adaptado para o CodeIgniter por: Luis Henrique Minoru Satsuma

Default Options:		
	level:
		1 - DEBUG
		2 - INFO
		3 - WARNING
		4 - FATAL
	die:
		dar um exit se o level for WARNING ou FATAL.
	echo:
		mostrar o log na tela.
		
*/
namespace App\Libraries\Sys;
class LogControl{
	var $path;
	var $general_log;
	var $log_file;
	var $error;
	var $max_log_files = 99; //Max arquivos de log
	var $max_file_size = 10485760; //10MB, colocar sempre em Bytes
	var $level_register = array(1,2,3,4);
	var $level_types = array(
		1 => 'DEBUG',
		2 => 'INFO',
		3 => 'WARNING',
		4 => 'FATAL',
	);
	var $default_options = array(
		'level' => 1,
		'die' => 0,
		'echo' => 0
	);
	var $pid_php;
	function __construct($config = array()){
		if(!is_writable($config['path'])){
			echo 'LogControl_v3: Não é possível gravar no diretorio '.$config['path'];
		}else{
			if(substr($config['path'], strlen($config['path'])-1, 1) !== '/'){
				$config['path'] .= '/';
			}
			$this->path = $config['path']; //Diretorio do arquivo de log
			$this->log_file = $config['log_file']; //Arquivo de Log com extensão
			if(!is_null($config['level_register'])){
				$this->level_register = $config['level_register'];
			}
			if(!is_null($config['default_op'])){
				$this->default_options = $config['default_op'];
			}
		}
		$this->pid_php = getmypid();
	}
	private function GetLogString($log_file = NULL){
		if(is_null($log_file)){
			$log_file = $this->log_file;
		}
		//Mudado de file_get_contents para is_file apenas para verificar se já existe o arquivo na quebra de linha
		return is_file($this->path.$log_file);
	}
	private function Add($str, $options_set = array()){
		$options = array();
		foreach($this->default_options as $key => $val){
			if(isset($options_set[$key])){
				$options[$key] = $options_set[$key];
			}else{
				$options[$key] = $val;
			}
		}
		$options['file'] = $this->log_file;
		if(!in_array($options['level'], $this->level_register)){
			return true; //Retorno true so para a consistencia se necessario
		}
		//Verifica o tamanho do arquivo de log setado
		if(filesize($this->path.$this->log_file) >= $this->max_file_size){
			$file_exploded = explode('.', $this->log_file);
			$name_file = $file_exploded[count($file_exploded) - 2];
			$extension = $file_exploded[count($file_exploded) - 1];
			for($i=1;$i<=$this->max_log_files;$i++){
				$new_file_log = $this->path.$name_file.'_'.$i.'.'.$extension;
				if(!file_exists($new_file_log) || filesize($new_file_log) < $this->max_file_size){
					$name_file = $name_file.'_'.$i;
					break;
				}
			}
			$this->log_file = $name_file.'.'.$extension;
			$pula_linha = $this->GetLogString($this->log_file) ? chr(10) : '';
			$str = $pula_linha.date("d/m/Y H:i:s").' ['.$this->pid_php.']['.$this->level_types[$options['level']].'] '.$str;
		}else{
			$pula_linha = $this->GetLogString($this->log_file) ? chr(10) : '';
			$str = $pula_linha.date("d/m/Y H:i:s").' ['.$this->pid_php.']['.$this->level_types[$options['level']].'] '.$str;
		}
		$return = file_put_contents($this->path.$this->log_file, "\xEF\xBB\xBF".  $str, FILE_APPEND);
		chmod($this->path.$this->log_file, 0777);
		if($options['echo']){
			echo $str;
		}
		if($options['die']){
			exit;
		}
		return $return;
	}
	public function Debug($msg = NULL, $die = NULL, $echo = NULL){
		$options = array();
		$options['level'] = 1;
		if($die){
			$options['die'] = 1;
		}
		if($echo){
			$options['echo'] = 1;
		}
		return $this->Add($msg, $options);
	}
	public function Info($msg = NULL, $die = NULL, $echo = NULL){
		$options = array();
		$options['level'] = 2;
		if($die){
			$options['die'] = 1;
		}
		if($echo){
			$options['echo'] = 1;
		}
		return $this->Add($msg, $options);
	}
	public function Warning($msg = NULL, $die = NULL, $echo = NULL){
		$options = array();
		$options['level'] = 3;
		if($die){
			$options['die'] = 1;
		}
		if($echo){
			$options['echo'] = 1;
		}
		return $this->Add($msg, $options);
	}
	public function Fatal($msg = NULL, $die = NULL, $echo = NULL){
		$options = array();
		$options['level'] = 4;
		if($die){
			$options['die'] = 1;
		}
		if($echo){
			$options['echo'] = 1;
		}
		return $this->Add($msg, $options);
	}
}
?>