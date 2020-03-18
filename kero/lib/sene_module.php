<?php 
class sene_module{
	function check($module_name,$module_lists,$force=0){
		if(in_array($module_name,$module_lists)){
			return 1;
		}else{
			if($force) die("Access Denied");
			return 0;
		}
	}
}