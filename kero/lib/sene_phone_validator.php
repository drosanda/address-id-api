<?php 
class SENE_Phone_Validator {
	var $lolos = false;
	public function validate($email,$debug=""){
		//check at
		$v = $this->ca($email);
		if($v){
			$this->lolos=true;
		}
		return $this->lolos;
	}
	private function ca($s){
		$s = $this->s($s,"@");
		if($this->c($s)==2){
			if(($this->l($s[0])>0) && ($this->l($s[1])>2)){
				$v = $this->caa($s[1]);
				if($v){
					return true;
				}
			}
		}
		return false;
	}
	private function caa($s){
		$s = $this->s($s,".");
		if($this->c($s)>1){
			if(($this->l($s[0])>0) && ($this->l($s[1])>0)){
				return true;
			}
		}
		return false;
	}
	private function f($mystring, $findme){
		$pos = strpos($mystring, $findme);
		if ($pos !== false) return 1;
		return 0;
	}
	private function l($mystring){
		return strlen($mystring);
	}
	private function s($mystring,$dl){
		return explode($dl,$mystring);
	}
	private function c($myarr){
		return count($myarr);
	}
	public function validatex($email,$debug=""){
		//**************************//
		//***email validator @drosanda***//
		//**************************//
		
		$lolos = 0;
		$mystring = $email;
		$mystring = trim($mystring);
		$findme = " ";
		$pos = strpos($mystring, $findme);
		if($debug) echo 'Cek spasi... ';
		if ($pos !== false) {
			$lolos = 0;
			if($debug) echo 'Ada Spasi.<br />';
		}else{
			$lolos = 1;
			if($debug) echo 'Tidak ada Spasi. OK<br />';
		}
		if($lolos){
			if($debug) echo 'Cek karakter @... ';
			$mystring = $email;
			$findme   = '@';
			$pos = strpos($mystring, $findme);
			if ($pos !== false) {
				// $lolos = 1;
				if($debug) echo 'Ditemukan ada char @ . OK <br />';
				$mystr = explode("@",$mystring);
				if($debug) echo 'Jumlah kata yang dipecah setelah @ harus ada 2... ';
				$max = count($mystr);
				if($max==2){
					if($debug) echo 'Ada '.$max.' kata setelah @. OK <br />';
					$mystring1 = $mystr[0];
					$mystring2 = $mystr[1];
					if($debug) echo 'Panjang karakter pada kata pertama minimal harus ada 1 ... ';
					$max = strlen($mystring1);
					if($max>1){
						if($debug) echo 'Ada karakter pada kata pertama dan berjumlah '.$max.'. OK <br />';
						
						if($debug) echo 'Pemeriksaan karakter titik pada kata kedua ... ';
						$findme   = '.';
						$pos = strpos($mystring2, $findme);
						if ($pos !== false) {
							if($debug) echo 'Titik pada karakter kedua ditemukan. OK <br />';
							
							if($debug) echo 'Jumlah titik pada karakter kedua harus mempunyai minimal 1 titik ... ';
							$mystr2 = explode(".",$mystring2);
							$max = count($mystr2);
							if($max>=2){
								if($debug) echo 'Ada kata yang dipecah setelah titik dan berjumlah '.$max.'. OK <br />';
								for($i=0;$i<$max;$i++){
									if($debug) echo ($i+1).'. Pengecekan untuk "'.$mystr2[$i].'"... ';
									$jumlah  = strlen($mystr2[$i]);
									if($debug) echo 'ditemukan jumlah karakter sebanyak '.$jumlah;
									if(($jumlah)<1){
										$lolos = 0;
										break;
									}
									if($debug) echo '. OK <br />';
								}
								if($debug) echo '<br />Pengecekan selesai dengan hasil '.$lolos.'. <br />';
								
								if($debug) echo 'Memulai pengecekan karater terakhir...<br />';
								if($lolos){
									$last = end($mystr2);
									if($debug) echo 'Pengecekan kata "'.$last.'" terakhir setelah titik. Harus memiliki 2 karakter atau lebih ';
									
									if(strlen($last)>0){
										$lolos = 1;
										if($debug) echo 'Ditemukan 1 karakter atau lebih setelah titik terakhir. Pengecekan selesai. OK<br />';
									}else{
										if($debug) echo 'Tidak ditemukan 1 karakter atau lebih setelah titik terakhir. Pengecekan selesai.<br />';
										$lolos = 0;
									}
								}else{
									if($debug) echo 'jumlah karakter sesudah @ dan "." kurang dari 2. Harusnya daeng@daeng.com. ini berarti daeng@daeng. atau daeng@.co <br />';
									$lolos = 0;
								}
							}else{
								if($debug) echo 'jumlah karakter sesudah @ dan "." kurang dari 2. Harusnya daeng@daeng.com. ini berarti daeng@daeng. atau daeng@.co <br />';
								$lolos = 0;
							}
						}else{
							if($debug) echo 'Titik pada karakter tidak ditemukan. OK <br />';
							$lolos = 0;
						}
					}else{
						if($debug) echo 'Hanya ada '.$max.' kata setelah @. <br />';
						$lolos = 0;
					}
				}else{
					if($debug) echo 'Jumlah @ ada '.$max.'.<br />';
					$lolos = 0;
				}
			}else{
				if($debug) echo 'Tidak ditemukan @.<br />';
				$lolos = 0;
			}
			
			if(strlen($email) <7){
				$lolos = 0;
			}
			
		}
		if($debug) echo 'Akhir dari pengecekan dengan hasil : '.$lolos;
		return $lolos;
	}
}