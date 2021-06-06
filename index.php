<?php
/**
* Seme Framework - Lightweight PHP Framework
* Version 4.0.0
*/
define('SEME_VERSION', '4.0.0');
define('SEME_START', microtime(true));
define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');
/*
* Load var
*/
session_start();
ini_set('error_reporting', E_ALL);

//declare SEMEROOT
if (!defined('SEMEROOT')) {
  define('SEMEROOT', __DIR__.DIRECTORY_SEPARATOR);
}
// change directory from stdin
if (defined('STDIN')) {
  chdir(dirname(__FILE__));
}
$SEMEDIR = new stdClass();
if(!isset($GLOBALS['SEMEDIR'])){
  $GLOBALS['SEMEDIR'] = new stdCLass();
}

/**
* Register a direcotyr
* @param  string $directory directory name
* @param  string $name      alias
*/
$regdir = function ($directory, $name, $constant='') {
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
  if (strlen($constant)>0 && !defined(strtoupper($constant))) {
    define(strtoupper($constant), $directory);
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

// CLI Validation
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
// apply configuration
$config_values['baseurl'] = $site;
$config_values['method'] = $method;
$config_values['baseurl_admin'] = $admin_secret_url;
$config_values['cdn_url'] = $cdn_url;
$config_values['database'] = new stdClass();
$config_values['saltkey'] = $saltkey;
$config_values['core_prefix'] = $core_prefix;
$config_values['core_controller'] = $core_controller;
$config_values['core_model'] = $core_model;
$config_values['controller_main'] = $controller_main;
$config_values['controller_404'] = $controller_404;
$config_values['timezone'] = $timezone;
$config_values['routes'] = $routes;
$config_values['semevar'] = new stdClass();

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

//include core file
require_once $GLOBALS['SEMEDIR']->kero.'Functions.php';
require_once $GLOBALS['SEMEDIR']->kero_sine.'SENE_Engine.php';

//instantiate object
$se = new SENE_Engine();
$se->run();
