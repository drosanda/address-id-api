<?php
abstract class SENE_Model {
	protected $db;
	public $field = array();

	public function __construct(){
		$db=$GLOBALS['db'];
		$this->loadEngine($db);
	}
	private function loadEngine($e){
		if($e=="mysql"){
			require_once(SENEKEROSINE."/SENE_MySQL_Engine.php");
			$this->db = new SENE_MySQLi_Engine();
		}elseif($e=="pdo"){
			require_once(SENEKEROSINE."/SENE_PDO_Engine.php");
			$this->db = new SENE_MySQLi_Engine();
		}else{
			require_once(SENEKEROSINE."/SENE_MySQLi_Engine.php");
			$this->db = new SENE_MySQLi_Engine();
		}
	}
	public function exec($sql){
		// $this->field = $this->engine->getField();
		return $this->db->exec($sql);
	}

	public function multiExec($sql){
		// $this->field = $this->engine->getField();
		$res = $this->db->multiExec($sql);
	}

	public function select($sql,$cache_engine=0,$flushcache=0,$tipe="object"){
	//die($tipe);
		return $this->db->select($sql,$cache_engine,$flushcache,$tipe);
	}
	public function lastId(){
		return $this->db->lastId();
	}
	public function esc($str){
		return $this->db->esc($str);
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

	public function filter(&$str){
		$str=filter_var($str,FILTER_SANITIZE_SPECIAL_CHARS);
	}
}
