<?php
class SENE_JSON_Engine{
	private $data;
	
	public function out($data,$allowed="*"){
		$this->data = $data;
		header("Access-Control-Allow-Origin: ".$allowed);
		header("Content-Type: application/json");
		echo json_encode($this->data);
	}
}