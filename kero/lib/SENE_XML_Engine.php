<?php
class SENE_XML_Engine{
	private $data;
	public function XML_Engine($data){
		$this->data = $data;
	}
	private function parse_array_recursive($data=array()){
		foreach($data as $key=>$val){
			echo '<'.$key.'>';
			if(is_array($val)){
				$this->parse_array_recursive($val);
			}else{
				echo $val;
				
			}
			echo '</'.$key.'>';
		}
	}
	public function parse(){
		header("Content-Type:application/xml");
		$this->parse_array_recursive($this->data);
	}
}
?>