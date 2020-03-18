<?php
class Sene_Product_Media {
	public function get($ft,$fm,$fn){
		if($ft == "internal"){
			$ff = $fn{0};
			$fs = $fn{1};
			return $fm.'/'.$ff.'/'.$fs.'/'.$fn;
		}
	}
}
