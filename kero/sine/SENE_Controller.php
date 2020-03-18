<?php
abstract class SENE_Controller{
  protected static $__instance;
	var $admin_url = ADMIN_URL;
	var $input;
	var $db;
	var $lang = 'en';
	var $title = 'SEME Framework';
	var $content_language = 'id';
	var $canonical = 'id';
	var $pretitle = 'SEME Framework';
	var $posttitle = 'SEME Framework';
	var $robots = 'INDEX,FOLLOW';
	var $description = 'Created By Seme Framework. The light weight framework that fit your needs with automation generated model.';
	var $keyword = 'lightweight, framework, php, api, generator';
	var $author = 'SEME Framework';
	var $icon = 'favicon.png';
	var $shortcut_icon = 'favicon.png';
	var $content_type = 'text/html; charset=utf-8';
	var $additional = array();
	var $additionalBefore = array();
	var $additionalAfter = array();
	var $theme = 'front/';
	var $js_footer = array();
	var $js_ready = "";
	var $__content = ""; //loads by view
	var $__themeContent = ""; //use by putThemeContent
	var $__themeRightContent = ""; //use by putRightThemeContent
	var $__themeLeftContent = ""; //use by putLeftThemeContent
	var $__themeRightMenu = ""; //use by putLeftThemeContent
  var $__bodyBefore = "";

	function __construct() {
		$this->input = new SENE_Input();
		$this->additional = $this->getThemeConfig();
		$this->js_footer = $this->getJsFooterBasic();
		$this->__content = '';
		$this->__themeContent = '';
		$this->__themeRightContent = '';
		$this->__themeLeftContent = '';
		$this->__jsContent = '';
    self::$__instance = $this;
	}
	public function loadLayout($u,$data=array()){
		if(empty($u)){
			trigger_error("Layout not found. Please check layout file at ".SENEVIEW.$this->getTheme()."page/ executed",E_USER_ERROR);
		}
		$this->view($this->getTheme()."page/".$u,$data);
	}
	public function resetThemeContent(){
		$this->__themeContent = '';
	}
	public function putThemeContent($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->__themeContent .= ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("unable to putThemeContent ".$v.".php");
			die();
		}
	}
	public function getRightMenuTitle(){
		return $this->__themeRightMenu;
	}
	public function setRightMenuTitle($title=""){
		$this->__themeRightMenu = $title;
	}
	public function putThemeRightContent($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->__themeRightContent = ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("unable to putThemeRightContent ".$v.".php");
			die();
		}
	}
	//sidemenuleft
	public function putThemeLeftContent($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->__themeLeftContent .= ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("unable to putThemeLeftContent ".$v.".php");
			die();
		}
	}
	public function putJsReady($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->js_ready .= ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("putJsReady unable to load  ".$v.".php");
			die();
		}
	}
  public function clearJsReady(){
  		$this->js_ready = '';
  }
	public function getThemeContent(){
		echo $this->__themeContent;
	}
	public function getThemeRightContent(){
		echo $this->__themeRightContent;
	}
	public function getThemeLeftContent(){
		echo $this->__themeLeftContent;
	}
	public function getJsReady(){
		echo $this->js_ready;
	}
	public function putJsContent($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->__jsContent .= ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("putJsContent unable to load  ".$v.".php");
			die();
		}
	}
	public function putBodyBefore($tc="",$__forward=array()){
		$v = SENEVIEW.$this->theme.'/'.$tc;
		//die($v);
		if(file_exists($v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once($v.".php");
			$this->__bodyBefore .= ob_get_contents();
			ob_end_clean();
			return 0;
		}else{
			$v = str_replace('\\','/',$v);
			$v = str_replace('//','/',$v);
			$v = str_replace(SENEROOT,'',$v);
			trigger_error("putBodyBefore unable to load ".$v.".php");
			die();
		}
	}
	public function getBodyBefore(){
		echo $this->__bodyBefore;
	}
	public function getJsContent(){
		echo $this->__jsContent;
	}
	public function getThemeConfig(){
		if(file_exists(SENEVIEW.'/'.$this->getTheme().'/theme.json')){
			return json_decode($this->fgc(SENEVIEW.$this->getTheme().'/theme.json'));
		}else{
			return array();
		}
	}
	public function getJsFooterBasic(){
		if(file_exists(SENEVIEW.'/'.$this->getTheme().'/script.json')){
			return json_decode($this->fgc(SENEVIEW.$this->getTheme().'/script.json'));
		}else{
			return array();
		}
	}
	public function fgc($path){
		$x = json_encode(array());
		if(file_exists($path)){
			$f = fopen($path, "r");
			if(filesize($path)>0){
				$x = fread($f, filesize($path));
			}
			fclose($f);
			unset($f);
		}
		return $x;
	}
	public function putJsFooter($stype,$is_external=0){
		if($is_external){
			$this->js_footer[] = '<script src="'.$stype.'"></script>';
		}else{
			$stype = rtrim($stype,'.js');
			$this->js_footer[] = '<script src="'.$stype.'.js"></script>';
		}
	}
	public function setCanonical($l=""){
		$this->canonical = $l;
	}
	public function getCanonical(){
		return $this->canonical;
	}
	public function setContentLanguage($l="en"){
		$this->content_language = $l;
	}
	public function getContentLanguage(){
		return $this->content_language;
	}
	public function setTheme($theme="front"){
		$theme = rtrim($theme,'/').'/';
		$this->theme = $theme;
		$this->additional = $this->getThemeConfig();
		$this->js_footer = $this->getJsFooterBasic();
	}
	public function setLang($lang="en"){
		$this->lang = $lang;
	}
	public function setTitle($title="SEME FRAMEWORK"){
		$this->title = $title;
	}
	public function setDescription($description="en"){
		$this->description = $description;
	}
	public function setKeyword($keyword="lightweight,framework,php,api,generator"){
		$this->keyword = $keyword;
	}
	public function setRobots($robots="INDEX,FOLLOW"){
		if($robots != "INDEX,FOLLOW") $robots='NOINDEX,NOFOLLOW';
		$this->robots = $robots;
	}
	public function setIcon($icon="favicon.png"){
		$this->icon = $icon;
	}
	public function setAuthor($author="SEME Framework"){
		$this->author = $author;
	}
	public function setShortcutIcon($shortcut_icon="favicon.png"){
		$this->shortcut_icon = $shortcut_icon;
	}
	public function setAdditional($val){
		end($this->additional); // move the internal pointer to the end of the array
		$key = (int)key($this->additional);
		$key = $key+1;
		$this->additional[$key] = $val;
	}
	public function setAdditionalBefore($val){
		if(!is_array($this->additionalBefore)) $this->additionalBefore = array();
		if(is_array($val)){
			foreach($val as $v){
				$this->additionalBefore[] = $v;
				$i++;
			}
		}elseif(is_string($val)){
			$this->additionalBefore[] = $val;
		}
	}
	public function setAdditionalAfter($val){
		if(!is_array($this->additionalAfter)) $this->additionalAfter = array();
		if(is_array($val)){
			foreach($val as $v){
				$this->additionalAfter[] = $v;
				$i++;
			}
		}elseif(is_string($val)){
			$this->additionalAfter[] = $val;
		}
	}
	public function loadCss($css_url,$utype="after"){
		if(strtolower($utype)=="after"){
			$this->setAdditionalAfter('<link rel="stylesheet" href="'.$css_url.'.css" />');
		}else{
			$this->setAdditionalBefore('<link rel="stylesheet" href="'.$css_url.'.css" />');
		}
	}
	public function putCssAfter($css_url,$utype="after"){
		$this->setAdditionalAfter('<link rel="stylesheet" href="'.$css_url.'.css" />');
	}
	public function putCssBefore($css_url){
		$this->setAdditionalBefore('<link rel="stylesheet" href="'.$css_url.'.css" />');
	}
	public function redirToHttps(){
		if(isset($_SERVER['HTTP_HOST'])){
			if($_SERVER['HTTP_HOST']!="localhost"){
				if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
					$b = ltrim(base_url(),'http://');
					$b = ltrim($b,'s://');
					$b = rtrim($b,'/');
					if($_SERVER['HTTP_X_FORWARDED_PROTO']!="https"){
						redir(base_url().ltrim($_SERVER['REQUEST_URI'],"/"));
						//die();
					}else if($_SERVER['HTTP_HOST']!=$b) {
						redir(base_url().ltrim($_SERVER['REQUEST_URI'],"/"));
						//die();
					}
				}
			}
		}
	}

	public function removeAdditional($key){
		unset($this->additional[$key]);
	}

	public function getLang(){
		return $this->lang;
	}
	public function getTitle(){
		return $this->title;
	}
	public function getAuthor(){
		return $this->author;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getKeyword(){
		return $this->keyword;
	}
	public function getRobots(){
		return $this->robots;
	}
	public function getIcon($icon="favicon.png"){
		return $this->icon;
	}
	public function getShortcutIcon($shortcut_icon="favicon.png"){
		return $this->shortcut_icon;
	}
	public function getAdditionalBefore(){
		foreach($this->additionalBefore as $key=>$a){
			if(is_string($a)){
				$a = str_replace ("{{base_url}}", base_url(), $a);
				$a = str_replace ("{{base_url_admin}}", base_url_admin(), $a);
				echo "\n\t".$a;
			}
		}
	}
	public function getAdditional(){
		if(count($this->additional)):
			foreach($this->additional as $key=>$a){
				if(is_string($a)){
					$a = str_replace ("{{base_url}}", base_url(), $a);
					$a = str_replace ("{{base_url_admin}}", base_url_admin(), $a);
					echo "\n\t".$a;
				}
			}
		endif;
	}
	public function getAdditionalAfter(){
		foreach($this->additionalAfter as $key=>$a){
			if(is_string($a)){
				$a = str_replace ("{{base_url}}", base_url(), $a);
				$a = str_replace ("{{base_url_admin}}", base_url_admin(), $a);
				echo "\n\t".$a;
			}
		}
	}
	public function getJsFooter(){
		foreach($this->js_footer as $key=>$a){
			if(is_string($a)){
				$a = str_replace ("{{base_url}}", base_url(), $a);
				$a = str_replace ("{{base_url_admin}}", base_url_admin(), $a);
				echo "\n\t".$a;
			}
		}
	}
	public function getContentType(){
		return $this->content_type;
	}
	public function getTheme(){
		return $this->theme;
	}
	public function getThemeElement($el="",$__forward=array(),$cacheable=0){
		if(!empty($el)){
			$this->view($this->theme.'/'.$el,$__forward);
			$this->render($cacheable);
		}
	}
	public function getThemeView($el="",$comp='page',$__forward=array(),$cacheable=0){
		if(!empty($el)){
			$this->view($this->theme.'/'.$comp.'/'.$el,$__forward);
		}
	}
	protected function load($item,$malias="",$type="model"){
		if($type=="model"){
			$mfile = SENEMODEL.$item.'.php';
			$cname = basename($mfile,'.php');
			if(empty($malias)) $malias = $cname;
			$malias = strtolower($malias);
			if(file_exists($mfile)){
				if(!class_exists($cname)){
					require_once $mfile;
				}
				$this->{$malias} = new $cname();
			}else{
				trigger_error('could not find model '.$item.'  on '.$mfile);
				//die();
			}
		}elseif($type=="lib"){
			$mfile = SENELIB.$item.'.php';
			if(empty($malias)) $malias = $cname;
			$malias = strtolower($malias);
			if(file_exists($mfile)){
				require_once $mfile;
				$this->$malias = new $malias();
			}else{
				trigger_error('could not find library '.$item.'  on '.$mfile);
			}
		}else{
			if(file_exists(SENELIB.$item.'.php')){
				require_once SENELIB.$item.'.php';
			}else{
				trigger_error('could not find require_once library '.$item.' on '.$mfile);
			}
		}
	}
	public function isLoggedIn($t="user"){
		$sess = $this->getKey();
		if(!is_object($sess)){
			$sess = new stdClass();
		}
		return isset($sess->$t->id);
	}
	public function __($d){
		echo htmlentities((string) $d, ENT_HTML5, 'UTF-8');
	}
	public function getLoggedIn($t="user"){
		$sess = $this->getKey();
		if(!is_object($sess)){
			$sess = new stdClass();
		}
		if(isset($sess->$t->id)){
			return $sess->$t;
		}else{
			return new stdClass();
		}
	}
  public static function getInstance(){
    return self::$_instance;
  }
	public abstract function index();

	protected function wrapper($data){
		return array("result"=>$data);
	}
	protected function data_wrapper($data){
		return array("data"=>$data);
	}
	protected function xml_out($data){
		$xml_engine = new XML_Engine($data);
		$xml_engine->parse();
	}
	protected function json_out($data){
		$json_engine = new JSON_Engine($data);
		$json_engine->parse();
	}
	protected function view($v,$__forward=array()){
		if(file_exists(SENEVIEW.$v.".php")){
			$keytemp=md5(date("h:i:s"));
			$_SESSION[$keytemp] = $__forward;
			//print_r($_SESSION);
			extract($_SESSION[$keytemp]);
			unset($_SESSION[$keytemp]);
			ob_start();
			require_once(SENEVIEW.$v.".php");
			$this->__content = ob_get_contents();
			ob_end_clean();
		}else{
			trigger_error("unable to load view ".SENEVIEW.$v.".php ",E_USER_ERROR);
			die("unable to load view ".SENEVIEW.$v.".php");
		}
	}
	protected function lib($data,$lalias="",$type="lib"){
		//die($type);
		if($type=='lib'){
			$lpath = str_replace("\\","/",SENELIB.$data.".php");
			if(file_exists(strtolower($lpath))){
				require_once(strtolower($lpath));
				$cname = basename($lpath,'.php');
				$method = new $cname();
				if(empty($lalias)) $lalias = $cname;
				$lalias = strtolower($lalias);
				$this->{$lalias} = $method;
			}else if(file_exists($lpath)){
				require_once($lpath);
				$cname = basename($lpath,'.php');
				$method = new $cname();
				if(empty($lalias)) $lalias = $cname;
				$lalias = strtolower($lalias);
				$this->{$lalias} = $method;
			}else{
				die("unable to load library on ".$lpath);
			}
		}else{
			if(file_exists(strtolower(SENELIB.$data.".php"))){
				require_once(strtolower(SENELIB.$data.".php"));
			}else if(file_exists(SENELIB.$data.".php")){
				require_once(SENELIB.$data.".php");
			}else{
				die("unable to load library on ".strtolower(SENELIB.$data.".php x"));
			}
		}
	}
	public function setKey($arr){
		$_SESSION[keyAdm()]=$arr;
	}
	public function getKey(){
		if(isset($_SESSION[keyAdm()])){
			return $_SESSION[keyAdm()];
		}else{
			return 0;
		}
	}
	public function delKey(){
		unset($_SESSION[keyAdm()]);
		session_destroy();
	}
	public function getcookie($var=""){
		if(empty($var)){
			return 0;
		}
		if(isset($_COOKIE[$var])){
			return $_COOKIE[$var];
		}else{
			return 0;
		}
	}
	public function setcookie($var="",$val="0"){
		$_COOKIE[$var] = $val;
	}
	public function debug($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
	public function render($cacheable=0){
    $cacheable = (int) $cacheable;
		if($cacheable){
			$cache_filename = md5($_SERVER['REQUEST_URI']);
			$cache_file = SENECACHE.'/'.$cache_filename;
			if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * $cacheable ))) {
				echo file_get_contents($cache_file);
			}else{

				$search = array(
					'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
					'/[^\S ]+\</s',     // strip whitespaces before tags, except space
					'/(\s)+/s',         // shorten multiple whitespace sequences
					'/<!--(.|\s)*?-->/' // Remove HTML comments
				);
				$replace = array(
					'>',
					'<',
					'\\1',
					''
				);
				$pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
				$buffer = preg_replace($pattern, '', $this->__content);
				$buffer = preg_replace($search, $replace, $buffer);
				file_put_contents($cache_file, $buffer, LOCK_EX);
				echo $this->__content;
			}
			// At this point $cache is either the retrieved cache or a fresh copy, so echo it

		}else{
			echo $this->__content;
		}
	}
}
class SENE_Loader{
	public function model($item,$alias=""){
		$mfile = MODEL_PATH.$item.'.php';
		if(empty($alias)) $nfile = basename($mfile);
		if(file_exists($fmodels)){
			require_once($fmodels);
			return new $nfile();
		}
	}
}
class SENE_Input{
	public function post($var){
		if(isset($_POST[$var])){
			return $_POST[$var];
		}else{
			return 0;
		}
	}
	public function get($var){
		if(isset($_GET[$var])){
			return $_GET[$var];
		}else{
			return 0;
		}
	}
	public function file($var){
		if(isset($_FILES[$var])){
			return $_FILES[$var];
		}else{
			return 0;
		}
	}
	public function debug(){
		return array("post_param"=>$_POST,"get_param"=>$_GET,"file_param"=>$_FILES);
	}
	public function request($var){
		if(isset($_REQUEST[$var])){
			return $_REQUEST[$var];
		}else{
			return 0;
		}
	}

}
