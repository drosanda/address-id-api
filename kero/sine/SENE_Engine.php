<?php
DEFINE('SENE_VERSION','3.2');

class SENE_Engine{
  protected static $__instance;

	var $admin_url="";
	var $notfound=NOTFOUND_CONTROLLER;
	var $default=DEFAULT_CONTROLLER;
	var $core_prefix = 'SM_';
	var $core_controller = '';
	var $core_model = '';
	var $routes;
	public function __construct(){
		require_once SENEKEROSINE."/SENE_Controller.php";
		require_once SENEKEROSINE."/SENE_Model.php";
		if(isset($GLOBALS['core_prefix'])) if(!empty($GLOBALS['core_prefix'])) $this->core_prefix = $GLOBALS['core_prefix'];
		if($GLOBALS['core_controller']) if(!empty($GLOBALS['core_controller'])) $this->core_controller = $GLOBALS['core_controller'];
		if($GLOBALS['core_model']) if(!empty($GLOBALS['core_model'])) $this->core_model = $GLOBALS['core_model'];

		$this->admin_url=ADMIN_URL;
		self::$__instance = $this;

		if(!isset($GLOBALS['routes'])) $GLOBALS['routes'] = array();
		$routes = $GLOBALS['routes'];
		$rs = array();
		foreach($routes as $key=>$val){
			$key = trim($key,"/");
			$val = trim($val,"/");
			$rs[$key] = $val;
		}
		$this->routes = $rs;

		$sene_method = $GLOBALS['sene_method'];
		if(isset($_SERVER['argv'])){
			if(count($_SERVER['argv'])>1){
				$i=0;
				$_SERVER[$sene_method] = '';
				foreach($_SERVER['argv'] as $argv){
					$i++;
					if($i==1) continue;
					$_SERVER[$sene_method] .= '/'.$argv;
				}
				unset($i);
				unset($argv);
			}
		}
		unset($sene_method);
	}
  public static function getInstance(){
    return self::$_instance;
  }
	public function SENE_Engine(){
		$core_controller_file = SENECORE.$this->core_prefix.$this->core_controller.'.php';
		$core_model_file = SENECORE.$this->core_prefix.$this->core_model.'.php';

		if(!empty($this->core_controller) && !empty($this->core_prefix)){
			if(file_exists($core_controller_file)){
				require_once($core_controller_file);
			}else{
				$error_msg = 'unable to load core controller on '.$core_controller_file;
				$core_controller_file = SENECORE.$this->core_prefix.$this->core_controller.'.php';
				error_log($error_msg);
				trigger_error($error_msg);
			}
		}
		if(!empty($this->core_model) && !empty($this->core_prefix)){
			if(file_exists($core_model_file)){
				require_once($core_model_file);
			}else{
				$error_msg = 'unable to load core model on '.$core_controller_file;
				$core_controller_file = SENECORE.$this->core_prefix.$this->core_controller.'.php';
				error_log($error_msg);
				trigger_error($error_msg);
			}
		}

		$this->newRouteFolder();
		//$this->newRouteFolder2();
	}
	private function defaultController(){
		$cname = $this->default."";
		require_once SENECONTROLLER.$this->default.".php";
		$cname = new $cname();
		$cname->index();
	}
	private function notFound($newpath=""){
		$cname = $this->notfound."";
		if(file_exists($newpath.$this->notfound.".php")){
			require_once($newpath.$this->notfound.".php");
		}else{
			require_once(SENECONTROLLER.$this->notfound.".php");
		}
		$cname = new $cname();
		$cname->index();
	}
	private function globalHandlerCMSPage($path=array()){
		if(count($path)>1){
			$slug_parent = $path[1];
			$slug_child = "";
			if(isset($path[2])) $slug_child = $path[2];
			$filename = realpath(SENECONTROLLER."cms_handler.php");
			//die($filename);
			if(is_file($filename) && !empty($slug_parent)){
				require_once $filename;
				$cname = basename($filename, ".php");
				$cname = str_replace('-','_',$cname);
				if(class_exists($cname)){
					$cname = new $cname();
					$func = "slugParent";
					$reflection = new ReflectionMethod($cname, $func);
					$args = array();
					$args[0] = $slug_parent;
					$args[1] = $slug_child;
					$reflection->invokeArgs($cname,$args);
				}
			}
		}
	}
	private function ovrRoutes($paths=array()){
		$path = strtolower(implode('/', $paths));
		$path = trim($path,'/');
		$target = '';
		$routes = $this->routes;
		foreach($routes as $key=>$val){
			$key = strtolower(trim($key,"/"));
			$val = strtolower(trim($val,"/"));
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
			if(preg_match('#^'.$key.'$#', $path, $matches)){
				$target = '/'.preg_replace('#^'.$key.'$#', $val, $path);
			}
		}
		if(!empty($target)) return explode("/",$target);
		return $paths;
	}
	private function newRouteFolder(){
		$found=0;
		$sene_method = $GLOBALS['sene_method'];
		if(isset($_SERVER[$sene_method])){
			$path = $_SERVER[$sene_method];
      $path = str_replace("//","/",$path);
			$path = explode("/",str_replace("//","/",$path));
			$i=0;
			foreach($path as $p){
				if(strlen($p)>0){
					$pos = strpos($p, '?');
					if ($pos !== false) {
						//echo "pos: ".$pos;
						unset($path[$i]);
					}
				}
				$i++;
			}
			unset($p);
			$path = $this->ovrRoutes($path);
			$path[1] = str_replace('-','_',$path[1]);
			if((!empty($path[1]))){
				if($path[1] == "admin" && $this->admin_url !="admin"){
					$newpath = realpath(SENECONTROLLER.$path[1]);
					$this->notFound($newpath);
					die();
				}

				if($this->admin_url==$path[1]){
					$path[1]="admin";
				}else{
					$this->globalHandlerCMSPage($path);
				}

				$newpath = realpath(SENECONTROLLER.$path[1]);

				if(is_dir($newpath)){
					$newpath = rtrim($newpath,'/');
					$newpath = $newpath.'/';
					if(empty($path[2])) $path[2] = 'home';
					$dirn = $newpath.'/'.$path[2];
					$filename = realpath($newpath.''.$path[2].".php");
					//var_dump($filename);
					if(is_dir($dirn)){
						$dirn = rtrim($dirn,'/');
						$dirn = $dirn.'/';
						if(!isset($path[3])) $path[3] = '';
						if(empty($path[3])) $path[3] = 'home';
						$filename = realpath($dirn.''.$path[3].".php");
						if(is_file($filename)){
							require_once $filename;
							$cname = basename($filename, ".php");
							$cname = str_replace('-','_',$cname);
							if (!class_exists($cname, false)) {
								trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".SENECONTROLLER.$path[2].'/'.$path[3].".php", E_USER_ERROR);
								die();
							}
							$cname = new $cname();
							$func = "index";
							if(isset($path[4])){
								if(empty($path[4])){
									$func = "index";
								}else{
									$func = $path[4];
								}
							}
							$func = str_replace('-','_',$func);
							if(method_exists($cname,$func)){
								$reflection = new ReflectionMethod($cname, $func);
								if (!$reflection->isPublic()){
									$this->notFound();
								}
								$args=array();
								$num = $reflection->getNumberOfParameters();
								if($num>0){
									for($j=0;$j<$num;$j++){
										if(isset($path[(5+$j)])){
											$args[]=$path[(5+$j)];
										}else{
											$args[]=NULL;
										}
									}
								}
                $_SERVER['SEME_CONTROLLER_CLASS'] = $cname;
								$reflection->invokeArgs($cname,$args);
							}else{
								$this->notFound($newpath);
							}
						}else{
							$this->notFound($newpath);
						}
					}else	if(is_file($filename)){
						require_once $filename;
						$cname = basename($filename, ".php");
						$cname = str_replace('-','_',$cname);
						if (!class_exists($cname, false)) {
							trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".SENECONTROLLER." triggered ", E_USER_ERROR);
							die();
						}
						$cname = new $cname();
						$func = "index";
						if(isset($path[3])){
							if(empty($path[3])){
								$func = "index";
							}else{
								$func = $path[3];
							}
						}
						$func = str_replace('-','_',$func);
						if(method_exists($cname,$func)){
							$reflection = new ReflectionMethod($cname, $func);
							if (!$reflection->isPublic()){
								$this->notFound();
							}
							$args=array();
							$num = $reflection->getNumberOfParameters();
							if($num>0){
								for($j=0;$j<$num;$j++){
									if(isset($path[(4+$j)])){
										$args[]=$path[(4+$j)];
									}else{
										$args[]=NULL;
									}
								}
							}
              $_SERVER['SEME_CONTROLLER_CLASS'] = $cname;
							$reflection->invokeArgs($cname,$args);
						}else{
							$this->notFound($newpath);
						}
					}else{
					//die("wdnlknl");
						$this->notFound($newpath);
					}
				}else{
					$filename = realpath(SENECONTROLLER.$path[1].".php");
					if(is_file($filename)){
						include $filename;
						$cname = basename($filename, ".php");
						$cname = str_replace('-','_',$cname);
						if(class_exists($cname)){
							$cname = new $cname();
							$func = "index";
							if(isset($path[2])){
								if(empty($path[2])){
									$func = "index";
								}else{
									$func = $path[2];
								}
							}
							$func = str_replace('-','_',$func);
							if(method_exists($cname,$func)){
								$reflection = new ReflectionMethod($cname, $func);
								if (!$reflection->isPublic()){
									$this->notFound();
								}
								$args=array();
								$num = $reflection->getNumberOfParameters();
								if($num>0){
									for($j=0;$j<$num;$j++){
										if(isset($path[(3+$j)])){
											$args[]=$path[(3+$j)];
										}else{
											$args[]=NULL;
										}
									}
								}
                $_SERVER['SEME_CONTROLLER_CLASS'] = $cname;
								$reflection->invokeArgs($cname,$args);
							}else{

								$this->notFound();
							}
						}else{
							//echo 'controller not found';
							$this->notFound();
						}
					}else{
						$this->notFound();
					}
				}
			}else{
				$this->defaultController();
			}
		}else{
			$this->defaultController();
		}
	}
	private function newRouteFolder2(){
		$found=0;
		$sene_method = $GLOBALS['sene_method'];
		if(isset($_SERVER[$sene_method])){
			$path=$_SERVER[$sene_method];
			$path=explode("/",$path);
			$i=0;
			foreach($path as $p){
				if(!empty($p)){
					$pos = strpos($p, '?');
					if ($pos !== false) {
						//echo "pos: ".$pos;
						unset($path[$i]);
					}
				}
				$i++;
			}
			unset($p);
			$path[1] = str_replace('-','_',$path[1]);
			if((!empty($path[1]))){
				if($path[1] == "admin" && $this->admin_url !="admin"){
					$newpath = realpath(SENECONTROLLER.$path[1]);
					$this->notFound($newpath);
					die();
				}

				if($this->admin_url==$path[1]){
					$path[1]="admin";
				}else{
					$this->globalHandlerCMSPage($path);
				}

				$newpath = realpath(SENECONTROLLER.$path[1]);

				if(is_dir($newpath)){
					$newpath = rtrim($newpath,'/');
					$newpath = $newpath.'/';
					if(empty($path[2])) $path[2] = 'home';
					$dirn = $newpath.'/'.$path[2];
					$filename = realpath($newpath.''.$path[2].".php");
					//var_dump($filename);
					if(is_dir($dirn)){
						$dirn = rtrim($dirn,'/');
						$dirn = $dirn.'/';
						if(!isset($path[3])) $path[3] = '';
						if(empty($path[3])) $path[3] = 'home';
						$filename = realpath($dirn.''.$path[3].".php");
						if(is_file($filename)){
							require_once $filename;
							$cname = basename($filename, ".php");
							$cname = str_replace('-','_',$cname);
							if (!class_exists($cname, false)) {
								trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".SENECONTROLLER." triggered ", E_USER_ERROR);
								die();
							}
							$cname = new $cname();
							$func = "index";
							if(isset($path[4])){
								if(empty($path[4])){
									$func = "index";
								}else{
									$func = $path[4];
								}
							}
							$func = str_replace('-','_',$func);
							if(method_exists($cname,$func)){
								$reflection = new ReflectionMethod($cname, $func);
								if (!$reflection->isPublic()){
									$this->notFound();
								}
								$args=array();
								$num = $reflection->getNumberOfParameters();
								if($num>0){
									for($j=0;$j<$num;$j++){
										if(isset($path[(5+$j)])){
											$args[]=$path[(5+$j)];
										}else{
											$args[]=NULL;
										}
									}
								}
								$reflection->invokeArgs($cname,$args);
							}else{
								$this->notFound($newpath);
							}
						}else{
							$this->notFound($newpath);
						}
					}else	if(is_file($filename)){
						require_once $filename;
						$cname = basename($filename, ".php");
						$cname = str_replace('-','_',$cname);
						if (!class_exists($cname, false)) {
							trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".SENECONTROLLER." triggered ", E_USER_ERROR);
							die();
						}
						$cname = new $cname();
						$func = "index";
						if(isset($path[3])){
							if(empty($path[3])){
								$func = "index";
							}else{
								$func = $path[3];
							}
						}
						$func = str_replace('-','_',$func);
						if(method_exists($cname,$func)){
							$reflection = new ReflectionMethod($cname, $func);
							if (!$reflection->isPublic()){
								$this->notFound();
							}
							$args=array();
							$num = $reflection->getNumberOfParameters();
							if($num>0){
								for($j=0;$j<$num;$j++){
									if(isset($path[(4+$j)])){
										$args[]=$path[(4+$j)];
									}else{
										$args[]=NULL;
									}
								}
							}
							$reflection->invokeArgs($cname,$args);
						}else{
							$this->notFound($newpath);
						}
					}else{
					//die("wdnlknl");
						$this->notFound($newpath);
					}
				}else{
					$filename = realpath(SENECONTROLLER.$path[1].".php");
					if(is_file($filename)){
						include $filename;
						$cname = basename($filename, ".php");
						$cname = str_replace('-','_',$cname);
						if(class_exists($cname)){
							$cname = new $cname();
							$func = "index";
							if(isset($path[2])){
								if(empty($path[2])){
									$func = "index";
								}else{
									$func = $path[2];
								}
							}
							$func = str_replace('-','_',$func);
							if(method_exists($cname,$func)){
								$reflection = new ReflectionMethod($cname, $func);
								if (!$reflection->isPublic()){
									$this->notFound();
								}
								$args=array();
								$num = $reflection->getNumberOfParameters();
								if($num>0){
									for($j=0;$j<$num;$j++){
										if(isset($path[(3+$j)])){
											$args[]=$path[(3+$j)];
										}else{
											$args[]=NULL;
										}
									}
								}
								$reflection->invokeArgs($cname,$args);
							}else{

								$this->notFound();
							}
						}else{
							//echo 'controller not found';
							$this->notFound();
						}
					}else{
						$this->notFound();
					}
				}
			}else{
				$this->defaultController();
			}
		}else{
			$this->defaultController();
		}
	}
	private function newRoute(){
		$found=0;
		$sene_method = $GLOBALS['sene_method'];
		if(isset($_SERVER[$sene_method])){
			$path=$_SERVER[$sene_method];
			$path=explode("/",$path);
			$path[1] = str_replace('-','_',$path[1]);
			if((!empty($path[1]))){
				$filename = realpath(SENECONTROLLER.$path[1].".php");
				if(is_file($filename)){
					include $filename;
					$cname = basename($filename, ".php");
					$cname = str_replace('-','_',$cname);
					$cname = new $cname();
					$func = "index";
					if(isset($path[2])){
						if(empty($path[2])){
							$func = "index";
						}else{
							$func = $path[2];
						}
					}

					if(method_exists($cname,$func)){
						$reflection = new ReflectionMethod($cname, $func);
						if (!$reflection->isPublic()){
							$this->notFound();
						}
						$args=array();
						$num = $reflection->getNumberOfParameters();
						if($num>0){
							for($j=0;$j<$num;$j++){
								if(isset($path[(3+$j)])){
									$args[]=$path[(3+$j)];
								}else{
									$args[]=NULL;
								}
							}
						}
						$reflection->invokeArgs($cname,$args);
					}else{
						$this->notFound();
					}
				}else{
					$this->notFound();
				}
			}else{
				$this->defaultController();
			}
		}else{
			$this->defaultController();
		}
	}
	private function newRoute2(){
		$found=0;
		$sene_method = $GLOBALS['sene_method'];
		if(isset($_SERVER[$sene_method])){
			$path=$_SERVER[$sene_method];
			$path=explode("/",$path);
			foreach (glob(SENECONTROLLER."*.php") as $filename){
				if(isset($_SERVER['PATH_INFO'])){
					$path[1] = str_replace('-','_',$path[1]);
					if((!empty($path[1])) && (basename($filename, ".php")==$path[1])){
						if(is_file($filename)){
							include $filename;
							$cname = basename($filename, ".php");
							$cname = str_replace('-','_',$cname);
							$cname = new $cname();
							$func = "index";
							if(isset($path[2])){
								if(empty($path[2])){
									$func = "index";
								}else{
									$func = $path[2];
								}
							}

							if(method_exists($cname,$func)){
								$reflection = new ReflectionMethod($cname, $func);
								if (!$reflection->isPublic()){
									$found=0;
									break;
								}
								$args=array();
								$num = $reflection->getNumberOfParameters();
								if($num>0){
									for($j=0;$j<$num;$j++){
										if(isset($path[(3+$j)])){
											$args[]=$path[(3+$j)];
										}else{
											$args[]="";
										}
									}
									$reflection->invokeArgs($cname,$args);
									$found=1;
									break;
								}else{
									$reflection->invokeArgs($cname,$args);
									$found=1;
									break;
								}

							}else{
								$found=0;
								break;
							}
						}else{
							$found=0;
							break;
						}
					}else{
						$found=0;
						//continue to next file
					}
				}else{
					$found=0;
					break;
				}
			}
			if($found == 0){
				$this->notFound();
			}
		}else{
			$cname = $this->default."";
			include SENECONTROLLER.$this->default.".php";
			$cname = new $cname();
			$cname->index();
		}
	}
	private function aktif($route=1){
		if($route){
			$this->newRoute();
		}else{
			foreach (glob(SENECONTROLLER."*.php") as $filename){
				if(isset($_GET[basename($filename, ".php")])){
					if(is_file($filename)){
						include $filename;
						foreach (glob(SENEMODEL."*.php") as $fmodel){
							include $fmodel;
						}
						$content = new Content();
						$found = 1;
						break;
					}else{
						include SENEVIEW.$this->notfound.".php";
						content();
						///echo 'not found';
						$found = 1;
						break;
					}
				}else{
					//print_r($_GET);
					$found = 0;
					//break;
				}
			}
			if($found == 0){
				include SENECONTROLLER.$this->notfound.".php";
				$content = new Content();
				//print_r($_GET);
			}
		}
	}

	private function inaktif(){
		include SENECONTROLLER.$this->default.".php";
		$content = new Content();
	}
}
function redir($url,$time=0,$type=1){
	if($type=="1" || $type==1){
		if($time){
			echo '<meta http-equiv="refresh" content="'.$time.';URL=\''.$url.'\'" />';
		}else{
			echo '<meta http-equiv="refresh" content="1;URL=\''.$url.'\'" />';
		}
	}else{
		if($time>1){
      header('HTTP/1.1 301 Moved Permanently');
			header("Refresh:".$time."; url=".$url);
		}else{
      header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $url);
		}
	}
}
function base_url($url=""){
	if(empty($url)) $url = "";
	//var_dump($url);
	//die();
	return BASEURL.$url;
}
function base_url_admin($url=""){
	return BASEURL.ADMIN_URL.'/'.$url;
}
function enkrip($str){
	return base64_encode(base64_encode($str));
}
function dekrip($str){
	return base64_decode(base64_decode($str));
}
function keyAdm(){
	return sha1(date("*W*Y-m").SALTKEY);
}
function get_caller_info() {
	$c = '';
	$file = '';
	$func = '';
	$class = '';
	$trace = debug_backtrace();
	if (isset($trace[2])) {
		$file = $trace[1]['file'];
		$func = $trace[2]['function'];
		if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
			$func = '';
		}
		} else if (isset($trace[1])) {
		$file = $trace[1]['file'];
		$func = '';
	}
	if (isset($trace[3]['class'])) {
		$class = $trace[3]['class'];
		$func = $trace[3]['function'];
		$file = $trace[2]['file'];
		} else if (isset($trace[2]['class'])) {
		$class = $trace[2]['class'];
		$func = $trace[2]['function'];
		$file = $trace[1]['file'];
	}
	if ($file != '') $file = basename($file);
	$c = $file . ": ";
	$c .= ($class != '') ? ":" . $class . "->" : "";
	$c .= ($func != '') ? $func . "(): " : "";
	return($c);
}
function seme_error_handling($errno, $errstr, $error_file,$error_line,$error_context){
  if(isset($_SERVER['argv'])){
    $backtraces = debug_backtrace();
  	$bct = array();
  	$fls = array('index.php','sene_controller.php','sene_model.php','sene_engine.php','sene_mysqli_engine.php','runner_controller.php');

  	$ef = explode('/',str_replace('\\','/',$error_file));
  	if(isset($ef[count($ef)-1])) $ef = $ef[count($ef)-1];
  	if(in_array(strtolower($ef),$fls)){
  		$error_file = '';
  		$error_line = '';
  	}
  	$i=0;
  	$bcts = array();
  	foreach($backtraces as $bts){
  		if(!isset($bts['file'])) continue;
  		$bcts[] = $bts;
  		$filename = explode('/',str_replace('\\','/',$bts['file']));
  		if(isset($filename[count($filename)-1])) $filename = $filename[count($filename)-1];
  		$bts['filename'] = $filename;
  		if(!in_array(strtolower($filename),$fls)){
  			if($i<=2 && (empty($error_file) || empty($error_line))){
  				$error_file = $bts['file'];
  				$error_line = $bts['line'];
  			}
  			$bct[]= $bts;
  		}
  		$i++;
  	}
  	if(empty($error_file) || empty($error_line)){
  		$error_file = $bcts[0]['file'];
  		$error_line = $bcts[0]['line'];
  	}
    $error_file = substr($error_file,strlen(SENEROOT));
    print '================= ERROR ===================='.PHP_EOL;
    print $error_file.''.PHP_EOL;
  	print 'Line: '.$error_line.PHP_EOL;
  	print 'Error: ['.$errno.'] '.$errstr.''.PHP_EOL;
    $error_file = substr($error_file,strlen(SENEROOT));
    print '--------------------------------------------'.PHP_EOL;
  	print 'Backtrace: ---------------------------------'.PHP_EOL;
  	$i=0;
  	foreach($bct as $e){
  		$i++;
  		if($i<=-1) continue;
  		if(!isset($e['file'])) continue;
      $e['file'] = substr($e['file'],strlen(SENEROOT));
  		print $i.'. File: '.$e['file'].PHP_EOL;
  		print 'Line: '.$e['line'].PHP_EOL;
  		if(isset($e['class'])){
  			print 'Class: '.$e['class'].PHP_EOL;
  			print 'Method: '.$e['function'].PHP_EOL;
  		}else{
  			print 'Function: '.$e['function'].PHP_EOL;
  		}
  	}
    print '=========== Seme Framework v'.SENE_VERSION.' ============'.PHP_EOL;
    die();
  }else{
  	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  	$backtraces = debug_backtrace();
  	$bct = array();
  	$fls = array('index.php','sene_controller.php','sene_model.php','sene_engine.php','sene_mysqli_engine.php');

  	$ef = explode('/',str_replace('\\','/',$error_file));
  	if(isset($ef[count($ef)-1])) $ef = $ef[count($ef)-1];
  	if(in_array(strtolower($ef),$fls)){
  		$error_file = '';
  		$error_line = '';
  	}
  	$i=0;
  	$bcts = array();
  	foreach($backtraces as $bts){
  		if(!isset($bts['file'])) continue;
  		$bcts[] = $bts;
  		$filename = explode('/',str_replace('\\','/',$bts['file']));
  		if(isset($filename[count($filename)-1])) $filename = $filename[count($filename)-1];
  		$bts['filename'] = $filename;
  		if(!in_array(strtolower($filename),$fls)){
  			if($i<=2 && (empty($error_file) || empty($error_line))){
  				$error_file = $bts['file'];
  				$error_line = $bts['line'];
  			}
  			$bct[]= $bts;
  		}
  		$i++;
  	}
  	if(empty($error_file) || empty($error_line)){
  		$error_file = $bcts[0]['file'];
  		$error_line = $bcts[0]['line'];
  	}

  	echo '<div style="padding: 10px; background-color: #ededed;">';
  	echo '<h2 style="color: #ef0000;">Error</h2>';
  	echo '<p>File: '.$error_file.'</p>';
  	echo '<p>Line: '.$error_line.'</p>';
  	echo "<p><b>Error:</b> [$errno] $errstr<br></p>";
  	echo '</div>';
  	echo '<div style="padding: 20px; border: 1px #dddddd solid; font-size: smaller;">';
  	echo "<h3>Backtrace</h3>";
  	echo '</div>';
  	echo '<div style="padding: 20px; border: 1px #dddddd solid; font-size: smaller;">';
  	$i=0;
  	foreach($bct as $e){
  		$i++;
  		if($i<=-1) continue;
  		if(!isset($e['file'])) continue;
  		echo '<p><b>File</b>: '.$e['file'].'</p>';
  		echo '<p><b>Line</b>: '.$e['line'].'</p>';
  		if(isset($e['class'])){
  			echo '<p><b>Class</b>: '.$e['class'].'</p>';
  			echo '<p><b>Method</b>: '.$e['function'].'</p>';
  		}else{
  			echo '<p><b>Function</b>: '.$e['function'].'</p>';
  		}

  		echo '<hr>';
  	}
  	echo '</div>';
    echo "<hr><p><small>Seme Framework v".SENE_VERSION." Error Handler</small></p>";
    die();
  }
}
if(!isset($_SERVER['SEME_ERR_BYPASS'])) set_error_handler("seme_error_handling");
