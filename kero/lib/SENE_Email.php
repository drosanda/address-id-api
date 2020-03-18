<?php 
class SENE_Email{
/*
* Cara penggunaan
* ====================================
* $this->load("SENE_Email","lib");
* 
* Identitas pengirim
* $this->SENE_Email->from("nama","email");
*
* Menambah judul email.
* $this->SENE_Email->subject("test kirim email");
*
* Menambah penerima, atau banyak penerima cukup diulang saja.
* $this->SENE_Email->to("email@domain.com");
*
* Menambahkan isi email
* 1. HTML
*   $this->SENE_Email->html("<h1>Test</h1>");
* ATAU
* 2. Teks Biasa
*   $this->SENE_Email->text("ini teks biasa");
*
* Mengirimkan email.
* $this->SENE_Email->send();
*
* 
*/

	var $log = "";
	var $to = array();
	var $cc = array();
	var $bcc = array();
	var $attachment = array();
	var $boundary = "";
	var $header = "";
	var $subject = "";
	var $body = "";

	public function from($name,$mail){
		$this->boundary = md5(uniqid(time()));
		$this->header .= "From: $name <$mail>\r\n";
		$this->log .= "adding From $name <$mail> \r\n";
	}
	public function replyto($name,$mail){
		$this->header .= "Reply-To: $name <$mail>\r\n";
		$this->log .= "adding Reply-To $name <$mail> \r\n";
	}
	public function to($mail){
		$this->to[] = $mail;
		$this->log .= "adding To $mail \r\n";
	}

	public function cc($mail){
		$this->cc[] = $mail;
		$this->log .= "adding cc $mail \r\n";
	}

	public function bcc($mail){
		$this->bcc[] = $mail;
		$this->log .= "adding bcc $mail \r\n";
	}

	public function attachment($file){
		$this->attachment[] = $file;
		$this->log .= "adding attachment \r\n";
	}

	public function subject($subject){
		$this->subject = $subject;
		$this->log .= "adding subject $subject \r\n";
	}

	public function text($text){
		$this->body = "Content-Type: text/plain; charset=ISO-8859-1\n";
		$this->body .= "Content-Transfer-Encoding: 8bit\n\n";
		$this->body .= $text."\n";
		$this->log .= "adding text content \r\n";
	}

	public function html($html,$type="windows"){
		if($type=="windows"){
			$this->body = "Content-Type: text/html; charset=ISO-8859-1\n";
		}else{
			$this->body = "Content-Type: text/html; charset=utf-8\n";
		}
		
		$this->body .= "Content-Transfer-Encoding: quoted-printable\n\n";
		/*$this->body .= "<html><body>\n".$html."\n</body></html>\n";*/
		$this->log .= "adding html content \r\n";
	}
	public function html2($html,$type="windows"){
		if($type=="windows"){
			$this->body = "Content-Type: text/html; charset=ISO-8859-1\n";
		}else{
			$this->body = "Content-Type: text/html; charset=utf-8\n";
		}
		$this->body .= "Content-Transfer-Encoding: quoted-printable\n\n";
		$this->body .= $html."\r\n";
		$this->log .= "adding html content \r\n";
	}
	public function send(){
		$max = count($this->cc);
		if($max>0){
			$this->header .= "Cc: ".$this->cc[0];
			for($i=1;$i<$max;$i++){
				$this->header .= ", ".$this->cc[$i];
			}
			$this->header .= "\n";
		}
		$max = count($this->bcc);
		if($max>0){
			$this->header .= "Bcc: ".$this->bcc[0];
			for($i=1;$i<$max;$i++){
					$this->header .= ", ".$this->bcc[$i];
			}
			$this->header .= "\n";
		}
		$this->header .= "MIME-Version: 1.0\n";
		$this->header .= "Content-Type: multipart/mixed; boundary=$this->boundary\n\n";
		$this->header .= "This is a multi-part message in MIME format\n";
		$this->header .= "--$this->boundary\n";
		$this->header .= $this->body;

		$max = count($this->attachment);
		if($max>0){
			for($i=0;$i<$max;$i++){
				$file = fread(fopen($this->attachment[$i], "r"), filesize($this->attachment[$i]));
				$this->header .= "--".$this->boundary."\n";
				$this->header .= "Content-Type: application/x-zip-compressed; name=".$this->attachment[$i]."\n";
				$this->header .= "Content-Transfer-Encoding: base64\n";
				$this->header .= "Content-Disposition: attachment; filename=".$this->attachment[$i]."\n\n";
				$this->header .= chunk_split(base64_encode($file))."\n";
				$file = "";
			}
		}
		$this->header .= "--".$this->boundary."--\n\n";
		
		
		$this->log .= "===== send email starting ======= \r\n";
		
		foreach($this->to as $mail){
			$res = mail($mail,$this->subject,"",$this->header);
			if($res){
				$this->log .= "sending to $mail success \r\n";
			}else{
				$this->log .= "sending to $mail failed \r\n";
			}
		}
	}
	public function getLog(){
		return $this->log;
	}
}
?>
