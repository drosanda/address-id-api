<?php
abstract class SENE_Model{
	protected $koneksi;
	private $fieldname = array();
	private $fieldvalue = array();
	
	function __construct(){
		$this->koneksi=koneksi_db();
	}
	public function last_id(){
		return mysql_insert_id($this->koneksi);
	}
	public function filter(&$str){
		$str=filter_var($str,FILTER_SANITIZE_SPECIAL_CHARS);
	}
	protected function exec($sql){
		$res = mysql_query($sql,$this->koneksi);
		if($res){
			return 1;
		}else{
			$this->fieldname[] = 'error';
			$this->fieldname[] = 'code';
			$this->fieldname[] = 'sql';
			$this->fieldvalue[] = mysql_error($this->koneksi);
			$this->fieldvalue[] = mysql_errno($this->koneksi);
			$this->fieldvalue[] = $sql;
			return 0;
		}
	}
	protected function select($sql){
		$res = mysql_query($sql,$this->koneksi);
		if($res){
			$dataz=array();
			while($data=mysql_fetch_assoc($res)){
				array_push($dataz,$data);
			}
			return $dataz;
		}else{
			$this->fieldname[] = 'error';
			$this->fieldname[] = 'code';
			$this->fieldname[] = 'sql';
			$this->fieldvalue[] = mysql_error($this->koneksi);
			$this->fieldvalue[] = mysql_errno($this->koneksi);
			$this->fieldvalue[] = $sql;
			return $this->fieldvalue;
		}
	}
	public function getStat(){
		return array("fieldname"=>$this->fieldname,"fieldvalue"=>$this->fieldvalue);
	}
	public function prettyName($name){
		$name=strtolower(trim($name));
		$names=explode("_", $name);
		$name='';
		foreach($names as $n){
			$name=$name.''.ucfirst($n).' ';
		}
		return $name;
	}
}
?>