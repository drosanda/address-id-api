<?php
class Sene_Upload_Wide_Image {
	public function upload($loc,$ident,$id){
		require_once (SENELIB.'/wideimage/WideImage.php');
		if(isset($_FILES[$ident])){
			if($_FILES[$ident]['error'] > 0){
				//exit('File error');
				return 0;
			}else{
				
				$filename = $_FILES[$ident]['name'];
				$extension = explode(".",$filename);
				$extension = end($extension);
				$img_name = strtolower($id.'.'.$extension);
				
				if ($_FILES[$ident]["error"] > 0){
					//exit("Error : " . $_FILES["thumb"]["error"] . "<br>");
					return 0;
				}else{
					
					$path = $loc."/".$img_name;
					if (file_exists($path)){
						$res = unlink($path);
						//var_dump($res);
						//die('kedelete bos');
					}
					
					move_uploaded_file($_FILES[$ident]["tmp_name"],$path);
					
					if(strtolower($extension) != "jpg"){
						
						$base = WideImage::load($path);
						$img_name = $id.'.jpg';
						
						$base->saveToFile($loc."/".$img_name);
						
					}else{
						//die('extension jpg g usah didelet');
					}
					return strtolower($img_name);
				}
			}
		}else{
			return 0;
		}
	}
	public function product($loc,$ident,$id=""){
		if(isset($_FILES[$ident])){
			if($_FILES[$ident]['error'] > 0){
				//exit('File error');
				return 0;
			}else{
				$fn_first = "6";
				$fn_second = "6";
				$filename = $_FILES[$ident]['name'];
				if(strlen($filename)>2){
					$fn_first = $filename{0};
					$fn_second = $filename{1};
				}else{
					$filename = "66".$filename;
				}
				$extension = explode(".",$filename);
				$extension = strtolower(end($extension));
				$img_name = rtrim($filename,$extension);
				$exts = array("png","jpg","gif");
				if(in_array($extension,$exts)){
					if ($_FILES[$ident]["error"] > 0){
						//exit("Error : " . $_FILES["thumb"]["error"] . "<br>");
						return 0;
					}else{
						if(empty($id)) $id = date("mYdHis");
						$dir = $loc."/".$fn_first;
						if (!is_dir($dir)) {
							mkdir($dir,0777); 
						}
						$dir = $loc."/".$fn_first."/".$fn_second;
						if (!is_dir($dir)) {
							mkdir($dir,0777); 
						}
						$path = $dir."/".$img_name;
						if (file_exists($path.".".$extension)){
							$img_name = $img_name."".$id;
							$path = $dir."/".$img_name;
						}
						move_uploaded_file($_FILES[$ident]["tmp_name"],$path.".".$extension);
						if($extension != "jpg"){
							require_once (SENELIB.'/wideimage/WideImage.php');
							$base = WideImage::load($path.".".$extension);
							$img_name = $img_name.'.jpg';
							$base->saveToFile($dir."/".$img_name);
							return $img_name;
						}else{
							//die('extension jpg g usah didelet');
							return $img_name.".".$extension;
						}
					}
				}
			}
			return 0;
		}else{
			return 0;
		}
	}
}