<?php
Class Conumtext {
	private $key='';
	//private $alpha='YUGieAND1d43ngaWCBLhMubcfjklmopqrstvwxyz2567890EFHIJKOPQRSTVXZ';
	//private $alpha='6yug13andir0z4m89cefhjklopqstuvwx27b5';
	//private $alpha='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	private $alpha='QX2B3C4D5E6F7G8H9I0JKLMNOP1RWAYZSTUV';
	private $beta='';
	private $charlie;
	private $str;

	public function __construct($key=""){
		$this->key=$key;
	}
	private function moveElement(&$array, $a, $b){
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
	}
	private function init(){
		$this->beta = str_split($this->alpha,1);
	}
	public function debug(){
		$this->init();
		echo strlen($this->alpha)."<br />";
		echo count($this->beta)."<br />";

	}
	public function encode($num){
		$b = strlen($this->alpha);
		$r = $num  % $b ;
		$res = $this->alpha[$r];
		$q = floor($num/$b);
		while ($q) {
			$r = $q % $b;
			$q = floor($q/$b);
			$res = $this->alpha[$r].$res;
		}
		return $res;
	}
	public function decode($num){
		$b = strlen($this->alpha);
		$limit=strlen($num);
		$r = $num  % $b ;
		$res=strpos($this->alpha,$num[0]);
		for($i=1;$i<$limit;$i++) {
			$res = $b * $res + strpos($this->alpha,$num[$i]);
		}
		return $res;
	}
	public function genRand($type="int",$min=1,$max=9){
		$str = '';
		if($type=="int"){
			$min=0;
			if(empty($max)) $max=9;
			for($i=0;$i<=$min;$i++){
				$x = rand($min,($max-1));
				$str .= $x;
			}
		}else{
			$alpha = $this->alpha;
			if(empty($max)) $max = strlen($alpha);

			for($i=0;$i<=$min;$i++){
				$x = rand(0,($max-1));
				$str .= $alpha{$x};
			}
		}
		return $str;
	}
}
