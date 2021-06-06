<?php
/**
 * SeneEngineTest is a known like a 'bootstrap' for another Framework
 * @codeCoverageIgnore
 */

if (!defined('SEME_VERSION')) {
  define('SEME_VERSION', '4.0.1');
}
if (!defined('SEMEROOT')) {
  define('SEMEROOT', __DIR__.'/../..');
}

if (!defined('SENEVIEW')) {
  define('SENEVIEW',SEMEROOT.'/app/view');
}
if (!defined('TEM_ERR')) {
  define('TEM_ERR', 'Error');
}

/** global objects */
$GLOBALS['SEMEDIR'] = new stdClass();

/**
* Register a directory
* @param  string $directory directory name
* @param  string $name      alias
*/
$regdir = function ($directory, $name, $constant='') {
  $directory = SEMEROOT.'/'.$directory;
  if (realpath($directory) !== false) {
    $directory = realpath($directory).'/';
  }
  if (!is_dir($directory)) {
    trigger_error('Missing '.$directory.'');
  }
  $directory = rtrim($directory, '/').'/';
  if (!is_dir($directory)) {
    trigger_error('Missing '.$directory.'');
  }
  $GLOBALS['SEMEDIR']->$name = $directory;
  if (strlen($constant)>0) {
    if (!defined(strtoupper($constant))) {
      define(strtoupper($constant), $directory);
    }
  }
};


// directory register
$regdir('app', 'app');
$regdir('app/cache', 'app_cache', 'SENECACHE');
$regdir('app/config', 'app_config');
$regdir('app/controller', 'app_controller');
$regdir('app/core', 'app_core');
$regdir('app/model', 'app_model');
$regdir('app/view', 'app_view');
$regdir('kero', 'kero');
$regdir('kero/lib', 'kero_lib');
$regdir('kero/sine', 'kero_sine');
$regdir('kero/bin', 'kero_bin');

// remove function
unset($regdir);

//cli validation
if (!isset($_SERVER)) {
  $_SERVER = array();
}
if (!isset($_SERVER['HTTP_HOST'])) {
  $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SERVER['REQUEST_URI'])) {
  $_SERVER['REQUEST_URI'] = '/';
}
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
  $_SERVER['DOCUMENT_ROOT'] = __DIR__;
}
if (!isset($_SERVER['HTTP_HOST'])) {
  $_SERVER['HTTP_HOST'] = 'localhost';
}

// find configuration files and loaded it
$config_values = array();
$config_file_found = 0;
$config_file_array = array('development.php','staging.php','production.php');
$semevar = array();
$routes = array();
foreach ($config_file_array as $cfa) {
  if (file_exists($GLOBALS['SEMEDIR']->app_config.$cfa) && is_readable($GLOBALS['SEMEDIR']->app_config.$cfa)) {
    $config_file_found++;
    $config_values['file'] = $GLOBALS['SEMEDIR']->app_config.$cfa;
    $config_values['environment'] = rtrim($cfa, '.php');
    require_once($GLOBALS['SEMEDIR']->app_config.$cfa);
  }
}
if (empty($config_file_found)) {
  die('No settings file found in : '.$GLOBALS['SEMEDIR']->app_config);
}

$db = array();
$db['host']  = 'localhost';
$db['user']  = 'root';
$db['pass']  = '';
$db['name']  = 'seme_framework';
$db['port'] = '3306';
$db['charset'] = 'latin1';
$db['engine'] = 'mysqli';
$db['enckey'] = '';

$core_prefix = '';
$core_controller = '';
$core_model = '';
$controller_main = 'home';
$controller_404 = 'notfound';
$routes = array();
$semevar = array();

// apply configuration
$config_values['baseurl'] = 'https://localhost/';
$config_values['method'] = 'PATH_INFO';
$config_values['baseurl_admin'] = 'admin';
$config_values['cdn_url'] = '';
$config_values['database'] = new stdClass();
$config_values['saltkey'] = '';
$config_values['core_prefix'] = $core_prefix;
$config_values['core_controller'] = $core_controller;
$config_values['core_model'] = $core_model;
$config_values['controller_main'] = $controller_main;
$config_values['controller_404'] = $controller_404;
$config_values['timezone'] = 'Asia/Jakarta';
$config_values['routes'] = array();
$config_values['semevar'] = array();

// DB config convert to object
foreach ($db as $k=>$v) {
  $config_values['database']->{$k} = $v;
}
foreach ($semevar as $k=>$v) {
  $config_values['semevar']->{$k} = $v;
}
unset($db,$k,$v,$routes,$semevar,$controller_404,$controller_main);

//convert to object
$cv = new stdClass();
foreach ($config_values as $k=>$v) {
  $cv->$k = $v;
}
unset($config_values,$k,$v);

//register config to globals
$GLOBALS['SEMECFG'] = $cv;
unset($cv,$semevar,$core_model,$admin_secret_url);


/** global functions */

/**
 * Get relatives base url
 * @param  string $url addtional url
 * @return string      full base url
 */
function base_url($url='')
{
    return rtrim($GLOBALS['SEMECFG']->baseurl.$url,'/').'/';
}
/**
 * Set admin secret base url
 * @param  string $url addtional url
 * @return string      full url
 */
function base_url_admin($url='')
{
    return rtrim($GLOBALS['SEMECFG']->baseurl.$GLOBALS['SEMECFG']->baseurl_admin.'/'.$url,'/').'/';
}

/**
 * Redirect to target url
 * @param  string  $url  target full qualified url
 * @param  integer $time delay time
 * @param  integer $type type of redirection, 1> html, 0 http header
 */
function redir($url, $time=0, $type=0)
{
    if ($type=="1" || $type==1) {
        if ($time) {
            echo '<meta http-equiv="refresh" content="'.$time.';URL=\''.$url.'\'" />';
        } else {
            echo '<meta http-equiv="refresh" content="1;URL=\''.$url.'\'" />';
        }
    } else {
        if ($time>1) {
            header('HTTP/1.1 301 Moved Permanently');
            header("Refresh:".$time."; url=".$url);
        } else {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
        }
    }
}

/** require_once core files */
//require_once $GLOBALS['SEMEDIR']->kero.'Functions.php';

/** loads kero/sine modules */
require_once $GLOBALS['SEMEDIR']->kero_sine.'SENE_Engine.php';
require_once $GLOBALS['SEMEDIR']->kero_sine.'SENE_Input.php';


use PHPUnit\Framework\TestCase;

Class SeneTestCase extends TestCase {
  public function __construct(){
    parent::__construct();
		if(!empty($GLOBALS['core_controller']) && !empty($GLOBALS['core_prefix'])){
      $core_controller_file = SENECORE.$GLOBALS['core_prefix'].$GLOBALS['core_controller'].'.php';
			if(file_exists($core_controller_file)){
				require_once($core_controller_file);
			}else{
				$error_msg = 'unable to load core controller on '.$core_controller_file;
				error_log($error_msg);
				trigger_error($error_msg);
			}
		}

		if(!empty($GLOBALS['core_model']) && !empty($GLOBALS['core_prefix'])){
      $core_model_file = SENECORE.$GLOBALS['core_prefix'].$GLOBALS['core_model'].'.php';
			if(file_exists($core_model_file)){
				require_once($core_model_file);
			}else{
				$error_msg = 'unable to load core model on '.$core_model_file;
				error_log($error_msg);
				trigger_error($error_msg);
			}
		}
  }
  /**
  * Call protected/private method of a class.
  *
  * @param object &$object    Instantiated object that we will run method on.
  * @param string $methodName Method name to call
  * @param array  $parameters Array of parameters to pass into method.
  *
  * @return mixed Method return.
  */
  public function invokeMethod(&$object, $methodName, array $parameters = array())
  {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);
    return $method->invokeArgs($object, $parameters);
  }
}
