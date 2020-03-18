<?php
class SENE_Uploader {
	public function upload($loc,$ident,$id=""){
		if($_FILES[$ident]['error'] > 0){
			return 0;
		}else{
			$file_name = $_FILES[$ident]['name'];
			$extension = explode(".",$file_name);
			$extension = end($extension);
			$extension = strtolower($extension);
			
			if(empty($id)) $id = strtolower($file_name);
			
			$img_name = strtolower($id.'.'.$extension);
			$path = realpath($loc);
			$path = $path.DIRECTORY_SEPARATOR.$img_name;
			if (file_exists($path)){
				$res = unlink($path);
			}
			$res = move_uploaded_file($_FILES[$ident]["tmp_name"],$path);
			return strtolower($img_name);
		}
	}
}