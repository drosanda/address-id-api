<?php
class SMS_Alaska {
	var $url = "http://alaska.zenziva.com/apps/smsapi.php?";
	var $userkey = '';
	var $passkey = "";
	var $tipe = "reguler";
	var $nohp = "085861264300";
	var $pesan = "SMS BTW OK";
	public function __construct($reset="",$userkey="", $passkey=""){
		if(!empty($reset)) $this->url = "http://alaska.zenziva.com/apps/smsapi.php?";
		if(!empty($userkey)) $this->userkey = $userkey;
		if(!empty($passkey)) $this->passkey = $passkey;
		$this->url = $this->url."userkey=".$this->userkey."&passkey=".$this->passkey."&tipe=".$this->tipe;
	}
	public function sendSms($nomor,$pesan){
		if(!empty($nomor)) $this->nomor = $nomor;
		if(!empty($pesan)) $this->pesan = $pesan;
		$url = $this->url."&nohp=".urlencode($this->nomor)."&pesan=".urlencode($this->pesan);
		//die($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_USERAGENT, $this->ua);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}
}
