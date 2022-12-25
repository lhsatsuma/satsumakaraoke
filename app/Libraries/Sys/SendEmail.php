<?php
namespace App\Libraries\Sys;

use Config\Services;
use PHPMailer\PHPMailer\PHPMailer;

class SendEmail{
	
	public $view; //Smarty View for Email Template
	public $subject;
	public $body;
	public $toAddr;
	private $mailCI;
	public $mailer;
	public function __construct(Array $override = [])
	{
		$this->mailCI = Services::email();
		if(!empty($override)){
			foreach($override as $k => $v){
				$this->mailCI->$k = $v;
			}
		}
		
		$this->mailer = new PHPMailer();
		$this->mailer->isSMTP();
		$this->mailer->Host = $this->mailCI->SMTPHost;
		$this->mailer->Port = $this->mailCI->SMTPPort;
		$this->mailer->SMTPAuth = true;
		$this->mailer->SMTPSecure = $this->mailCI->SMTPCrypto;
		$this->mailer->Username = $this->mailCI->SMTPUser;
		$this->mailer->Password = $this->mailCI->SMTPPass;
		$this->mailer->From = $this->mailCI->fromEmail;
		$this->mailer->FromName = $this->mailCI->fromName;
		$this->mailer->SMTPDebug = 2;
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->Debugoutput = function($str, $level) {
			log_message('debug', "$level: $str\n");
		};
		$this->view = new SmartyCI();
		
		$data = [
			'app_url' => base_url().'/',
			'ch_ver' => GetCacheVersion(),
        ];
		$this->view->setData($data);
		$this->mailCI->setFrom($this->mailCI->fromEmail, $this->mailCI->fromName);
		
	}
	
	public function send()
	{
		$this->mailer->addAddress($this->toAddr);
		$this->mailer->isHTML(); // Define que o e-mail será enviado como HTML
		$this->mailer->Subject  = $this->subject; // Assunto da mensagem
		$this->mailer->Body = $this->body;

		$sended = $this->mailer->send();
		if(!$sended){
			log_message('error', 'Error sending email: '.$this->mailer->ErrorInfo);
		}
		return $sended;
	}
	
	public function setBodyTemplate($name, Array $data = [])
	{
		$this->mailCI->setMailType('html');
		$this->body = $this->view->setData($data)->view('template/emails/'.$name);
	}
}