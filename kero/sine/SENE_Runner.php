<?php
/**
* Class Controller for runner
* @var integer
*/
$website_view_id = 1; // default
$admin_secret_url = 'mastermind';
$base_url = '';
$directory = dirname(__FILE__);
chdir("../../");


$apps_dir='app';
$assets_dir='assets';
$cache_dir='app/cache';
$ssys_dir='kero';
$kerosine_dir='kero/sine';
$library_dir='kero/lib';
$config_dir='app/config';
$model_dir='app/model';
$view_dir='app/view';
$controller_dir='app/controller';
$core_dir='app/core';


if (defined('STDIN')) {
  chdir(dirname(__FILE__));
}
if (realpath($apps_dir) !== false) {
  $apps_dir = realpath($apps_dir).'/';
}
if (realpath($assets_dir) !== false) {
  $assets_dir = realpath($assets_dir).'/';
}
if (realpath($ssys_dir) !== false) {
  $ssys_dir = realpath($ssys_dir).'/';
}
if (realpath($kerosine_dir) !== false) {
  $kerosine_dir = realpath($kerosine_dir).'/';
}
if (realpath($config_dir) !== false) {
  $config_dir = realpath($config_dir).'/';
}
if (realpath($cache_dir) !== false) {
  $cache_dir = realpath($cache_dir).'/';
}
if (realpath($library_dir) !== false) {
  $library_dir = realpath($library_dir).'/';
}
if (realpath($model_dir) !== false) {
  $model_dir = realpath($model_dir).'/';
}
if (realpath($view_dir) !== false) {
  $view_dir = realpath($view_dir).'/';
}
if (realpath($controller_dir) !== false) {
  $controller_dir = realpath($controller_dir).'/';
}
if (realpath($core_dir) !== false) {
  $core_dir = realpath($core_dir).'/';
}
if (!is_dir($apps_dir)) {
  die("missing apps dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($assets_dir)) {
  die("missing assets dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($ssys_dir)) {
  die("missing ssys dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($kerosine_dir)) {
  die("missing apps dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($library_dir)) {
  die("missing library dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($config_dir)) {
  die("missing config dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($cache_dir)) {
  die("missing cache dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($model_dir)) {
  die("missing nodel dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
} if (!is_dir($view_dir)) {
  die("missing view dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($controller_dir)) {
  die("missing controller dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($core_dir)) {
  die("missing core dir: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

$apps_dir = rtrim($apps_dir, '/').'/';
$ssys_dir = rtrim($ssys_dir, '/').'/';
$kerosine_dir = rtrim($kerosine_dir, '/').'/';
$library_dir = rtrim($library_dir, '/').'/';
$cache_dir = rtrim($cache_dir, '/').'/';
$config_dir = rtrim($config_dir, '/').'/';
$model_dir = rtrim($model_dir, '/').'/';
$view_dir = rtrim($view_dir, '/').'/';
$controller_dir = rtrim($controller_dir, '/').'/';
$core_dir = rtrim($core_dir, '/').'/';

if (!is_dir($apps_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($ssys_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($kerosine_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($library_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($cache_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($config_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($model_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($view_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($controller_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
if (!is_dir($core_dir)) {
  die("Seme framework directory missing : ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

if (!defined('SEMEROOT')) {
  define('SEMEROOT', str_replace("\\", "/", realpath("").'/'));
}
if (!defined('SENEAPP')) {
  define('SENEAPP', str_replace("\\", "/", $apps_dir));
}
if (!defined('SENEASSETS')) {
  define('SENEASSETS', $assets_dir);
}
if (!defined('SENESYS')) {
  define('SENESYS', $ssys_dir);
}
if (!defined('SENEKEROSINE')) {
  define('SENEKEROSINE', $kerosine_dir);
}
if (!defined('SENELIB')) {
  define('SENELIB', $library_dir);
}
if (!defined('SENECACHE')) {
  define('SENECACHE', $cache_dir);
}
if (!defined('SENECFG')) {
  define('SENECFG', $config_dir);
}
if (!defined('SENEMODEL')) {
  define('SENEMODEL', $model_dir);
}
if (!defined('SENEVIEW')) {
  define('SENEVIEW', $view_dir);
}
if (!defined('SENECONTROLLER')) {
  define('SENECONTROLLER', $controller_dir);
}
if (!defined('SENECORE')) {
  define('SENECORE', $core_dir);
}

if (!file_exists(SENECFG."/config.php")) {
  die('unable to load config file : config.php');
}
require_once(SENECFG."/config.php");

if (!file_exists(SENECFG."/controller.php")) {
  die('unable to load config file : controller.php');
}
require_once(SENECFG."/controller.php");

if (!file_exists(SENECFG."/timezone.php")) {
  die('unable to load config file : timezone.php');
}
require_once(SENECFG."/timezone.php");

if (!file_exists(SENECFG."/database.php")) {
  die('unable to load config file : database.php');
}
require_once(SENECFG."/database.php");

if (!file_exists(SENECFG."/session.php")) {
  die('unable to load config file : session.php');
}
require_once(SENECFG."/session.php");

if (!file_exists(SENECFG."/core.php")) {
  die('unable to load config file : core.php');
}
require_once(SENECFG."/core.php");

if (!isset($default_controller,$notfound_controller)) {
  $default_controller="welcome";
  $notfound_controller="notfound";
}
if (!defined('DEFAULT_CONTROLLER')) {
  define("DEFAULT_CONTROLLER", $default_controller);
}
if (!defined('NOTFOUND_CONTROLLER')) {
  define("NOTFOUND_CONTROLLER", $notfound_controller);
}

if (!isset($site)) {
  die('please fill site url / base url in : '.SENECFG.'config.php. Example: https://www.example.com/');
}
if (!defined('BASEURL')) {
  define("BASEURL", $site);
}
if (!isset($admin_url)) {
  $admin_url=$admin_secret_url;
}
if (!defined('ADMIN_URL')) {
  define("ADMIN_URL", $admin_url);
}
if (!defined('WEBSITE_VIEW_ID')) {
  define("WEBSITE_VIEW_ID", $website_view_id);
}

$routing = array();

require_once "app/config/config.php";
function base_url($url)
{
  return $GLOBALS['base_url'].$url;
}
require_once "kero/sine/SENE_Controller.php";
require_once "kero/sine/SENE_Model.php";
require_once "app/core/JI_Controller.php";
require_once "app/controller/api_mobile/apikey.php";

class SENE_Runner
{
  public $root = '';
  public $directory = '';
  public $controller = 'app/controller/api_mobile';
  public $directory_list = '';
  public function __construct()
  {
    $this->directory = dirname(__FILE__);
    $this->directory_list = array();
  }
  private function __getClass($file)
  {
    $fp = fopen($file, 'r');
    $class = $buffer = '';
    $i = 0;
    while (!$class) {
      if (feof($fp)) {
        break;
      }
      $buffer .= fread($fp, 512);
      $tokens = token_get_all($buffer);
      if (strpos($buffer, '{') === false) {
        continue;
      }
      for (;$i<count($tokens);$i++) {
        if ($tokens[$i][0] === T_CLASS) {
          for ($j=$i+1;$j<count($tokens);$j++) {
            if ($tokens[$j] === '{') {
              $class = $tokens[$i+2][1];
            }
          }
        }
      }
    }
    return $class;
  }
  public function scan()
  {
    chdir("../../");
    $this->root = getcwd();
    chdir($this->controller);
    $g1 = glob("*");
    foreach ($g1 as $filename) {
      if (is_dir($filename)) {
        $this->directory_list[] =  $filename;
      } else {
        echo "$filename size " . filesize($filename) . "<br />";
        $kelas_path = pathinfo($filename);
        $kelas_nama = $this->__getClass($filename);
        echo 'Class: '.$kelas_nama.'<br />';
        $methods = get_class_methods($kelas_nama);
        $i=1;
        foreach ($methods as $method) {
          echo $i.'. Method: '.$method.'<br />';
          $i++;
        }
      }
    }
  }
}
echo '---<br />';
//$sr = new SENE_Runner();
//$sr->scan();
$methods = get_class_methods("Apikey");
echo '<pre>';
var_dump($methods);
echo '</pre>';
