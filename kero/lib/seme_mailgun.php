<?php
	class Seme_Mailgun {
		var $apidomain = '';
		var $apikey = '';
		var $apiendpoint = 'https://api.mailgun.net/v3/';
		var $from = '';
		var $subject;
		var $to;
		var $template;
		var $result;
		var $replacer;
		public function __construct($apidomain="",$apikey="",$template=""){
			$this->to = array();
			if(strlen($apidomain)>5){
				$this->apidomain = trim($apidomain,"/");
			}
			if(strlen($apikey)>5){
				$this->apikey = $apikey;
			}
			if(strlen($template)>1){
				$this->template = $template;
			}
		}
		public function setApiKey($apikey){
			if(strlen($apikey)>5){
				$this->apikey = trim($apikey,"/");
			}
		}
		public function setApiDomain($apidomain){
			if(strlen($apidomain)>5){
				$this->apidomain = trim($apidomain,"/");
			}
		}
		public function template($template){
			$basetemp = SENELIB.'/seme_mailgun/';
			if(!is_dir($basetemp)) mkdir($bastemp);
			$basetemp = SENELIB.'/seme_mailgun/template/';
			if(!is_dir($basetemp)) mkdir($bastemp);
			$ftemp = $basetemp.$template.".php";
			if(!file_exists($ftemp)){
				trigger_error("SEME_MAILGUN: Template file not found in ".$ftemp);
				die();
			}
			$this->template = $ftemp;
		}
		public function to($to,$alias=""){
			if(is_array($to)){
				foreach($to as $t){
					$this->to[] = $t;
				}
			}else{
				if(strlen($alias)>1){
					$this->to[] = $alias.' <'.$to.'>';
				}else{
					$this->to[] = $to;
				}
			}
		}
		public function replacer($replacer,$val=""){
			if(is_array($replacer)){
				foreach($replacer as $key=>$val){
					if(!is_int($key)){
						$this->replacer[$key] = $val;
					}
				}
			}else if(!empty($val)){
				$this->replacer[$replacer] = $val;
			}
		}
		public function from($from){
			$this->from = $from;
		}
		public function subject($subject){
			if(strlen($subject)<=10){
				trigger_error("SEME_MAILGUN: Subject too short, please provide more");
				die();
			}
			$this->subject = $subject;
		}
		public function send($plain=""){
			$apikey = $this->apikey;
			if(strlen($apikey)<=5){
				trigger_error("SEME_MAILGUN: Invalid apikey, please specify API KEY by executing setApiKey()");
				die();
			}
			$apidomain = $this->apidomain;
			if(strlen($apidomain)<=5){
				trigger_error("SEME_MAILGUN: Invalid apidomain, please specify API DOMAIN by executing setApiDomain()");
				die();
			}
			$endpoint = rtrim($this->apiendpoint,"/").'/'.$apidomain.'/';
			
			$f = fopen($this->template, 'r');
			$message = fread($f, filesize($this->template));
			fclose($f);
			if(strlen($message)<2){
				trigger_error("SEME_MAILGUN: Message too short, please provide more");
				die();
			}
			if(strlen($this->subject)<=10){
				trigger_error("SEME_MAILGUN: Subject too short, please provide more");
				die();
			}
			$to = "";
			if(count($to)==1){
				$to = reset($this->to);
			}else if(count($to)>1){
				$to = implode(",",$this->to);
			}
			if(strlen($to)<=5){
				trigger_error("SEME_MAILGUN: No recipient existed, please add by execute to function ");
				die();
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$apikey);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			if(strlen($plain)<=5){
				if(count($this->replacer)>0){
					foreach($this->replacer as $key=>$val){
						$message = str_replace("{{".$key."}}",$val,$message);
					}
				}
				if(function_exists("br2nl")){
					$plain = strip_tags(br2nl($message));
				}else{
					$plain = strip_tags(str_replace("<br>","\r\n",$message));
					$plain = strip_tags(str_replace("<br/>","\r\n",$plain));
					$plain = strip_tags(str_replace("<br />","\r\n",$plain));
				}
			}else{
				$message = $plain;
			}

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, $endpoint.'messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, 
				array(
					'from' => $this->from,
					'to' => $to,
					'subject' => $this->subject,
					'html' => $message,
					'text' => $plain
				)
			);

			$j = curl_exec($ch);

			$info = curl_getinfo($ch);

			curl_close($ch);
			if($info['http_code'] != 200){
				error_log("SEME_MAILGUN: http return code: ".$info['http_code']);
				return false;
			}else{
				$res = json_decode($j);
				if(is_null($res)){
					error_log("SEME_MAILGUN: result ".$j);
					$this->result = $j;
					return false;
				}else{
					$this->result = $res;
					return true;
				}
			}
		}
		public function result(){
			return $this->result;
		}
	}