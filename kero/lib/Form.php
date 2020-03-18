<?php
class Form{
	private $fname;
	private $action;
	private $enctype="multipart/form-data";
	private $type="post";
	private $content=array();
	public function __construct(){
		
	}
	public function init($name,$action,$enctype="multipart/form-data"){
		$this->name=$name;
		$this->action=$action;
		$this->enctype=$enctype;
	}
	public function clear(){
		$this->content=array();
	}
	public function add($type,$display,$name,$value){
		array_push($this->content,$this->formContent($type,$display,$name,$value));
	}
	private function formContent($type,$display,$name,$value){
		$str = '<div id="row">';
		if($type=="file"){
			$str = $str.'<label class="input-control" for="i'.$name.'">';
			$str = $str.'<span class="helper">'.$display.'</span>';
			$str = $str.'<input type="file" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'" />';
		}elseif($type=="password"){
			$str = $str.'<label class="input-control" for="i'.$name.'">';
			$str = $str.'<span class="helper">'.$display.'</span>';
			$str = $str.'<input type="passowrd" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'" />';
		}elseif($type=="submit"){
			$str = $str.'<label class="input-control" for="i'.$name.'">';
			$str = $str.'<span class="helper">&nbsp;</span>';
			$str = $str.'<input type="submit" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'" />';
		}else{
			$str = $str.'<label class="input-control text" for="i'.$name.'">';
			$str = $str.'<span class="helper">'.$display.'</span>';
			$str = $str.'<input type="text" id="i'.$name.'" name="'.$name.'" placeholder="'.$name.'" value="'.$value.'"';
			$str = $str.' required="required" ';
			$str = $str.' />';
		}
		$str = $str.'</label>&nbsp;</div>';
		return $str;
	}
	public function show(){
		echo '<form id="i'.$this->name.'" name="'.$this->name.'" method="'.$this->type.'" enctype="'.$this->enctype.'" action="'.$this->action.'">';
		foreach($this->content as $el){
			echo $el;
		}
		echo '</form>';
	}
}
?>