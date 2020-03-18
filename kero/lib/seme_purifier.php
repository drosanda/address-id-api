<?php
class Seme_Purifier {
	var $restricted;
	var $replacer;
	var $richtext;
	
	
	public function __construct(){
		$this->replacer = ' ';
		
		$this->restricted = array();
		$this->restricted[] = '<?';
		$this->restricted[] = '<?php';
		$this->restricted[] = '<script';
		$this->restricted[] = '</script>';
		$this->restricted[] = '?>';
		
		$this->richtext[] = '<p>';
		$this->richtext[] = '<br>';
		$this->richtext[] = '<ul>';
		$this->richtext[] = '<ol>';
		$this->richtext[] = '<li>';
		$this->richtext[] = '<b>';
		$this->richtext[] = '<i>';
		$this->richtext[] = '<u>';
		$this->richtext[] = '<em>';
		$this->richtext[] = '<strong>';
		$this->richtext[] = '<div>';
		$this->richtext[] = '<h1>';
		$this->richtext[] = '<h2>';
		$this->richtext[] = '<h3>';
		$this->richtext[] = '<h4>';
		$this->richtext[] = '<h5>';
		$this->richtext[] = '<h6>';
		$this->richtext[] = '<h7>';
		$this->richtext[] = '<hr>';
		$this->richtext[] = '<sup>';
		$this->richtext[] = '<sub>';
		$this->richtext[] = '<quote>';
		$this->richtext[] = '<video>';
		$this->richtext[] = '<audio>';
		$this->richtext[] = '<source>';
		$this->richtext[] = '<a>';
		$this->richtext[] = '<span>';
	}
	private function createPatterns(){
		foreach($this->restricted as &$v){
			$v = '/'.$v.'/i';
		}
		unset($v);
	}
	public function exec($str){
		$this->createPatterns();
		//preg_replace($this->restricted, $this->replacer, $str); 
		preg_replace_callback("/(&#[0-9]+;)/", function($str) { return mb_convert_encoding($str[1], "UTF-8", "HTML-ENTITIES"); }, $str);
		//print_r($this->restricted);
		//die();
		return $str;
	}
	public function quoteEscape($data, $addSlashes = false){
		if ($addSlashes === true) {
			$data = addslashes($data);
		}
		return htmlspecialchars($data, ENT_QUOTES, null, false);
	}
	public function escapeHtml($data, $allowedTags = null){
		if (is_array($data)) {
			$result = array();
			foreach ($data as $item) {
				$result[] = $this->escapeHtml($item,$allowedTags);
			}
		} else {
			// process single item
			if (strlen($data)) {
				if (is_array($allowedTags) and !empty($allowedTags)) {
					$allowed = implode('|', $allowedTags);
					$result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
					$result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
				} else {
					$result = htmlspecialchars($data, ENT_COMPAT, 'UTF-8', false);
				}
			} else {
				$result = $data;
			}
		}
		return $result;
	}
	public function richtext($text){
		return $this->escapeHtml($text,$this->richtext);
	}
}