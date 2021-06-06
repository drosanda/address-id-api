<?php
/**
 * @author: Daeng Rosanda
 * @package SemeFramework
 * @since SemeFramework 3.0.0
 */

/** CONSTANTS */
if (!defined('TEM_ERR')) {
  define('TEM_ERR', 'Error');
}
if (!defined('LBL_BASE_URL')) {
  define('LBL_BASE_URL', '{{base_url}}');
}
if (!defined('LBL_BASE_URL_ADMIN')) {
  define('LBL_BASE_URL_ADMIN', '{{base_url_admin}}');
}
if (!defined('LBL_CDN_URL')) {
  define('LBL_CDN_URL', '{{cdn_url}}');
}

/**
 * Main Engine Class of seme framework
 *
 * @codeCoverageIgnore
 */
class SENE_Engine
{
    protected static $__instance;

    public $directories;
    public $config;
    public $notfound;
    public $default;
    public $core_prefix = 'SM_';
    public $core_controller = '';
    public $core_model = '';

    public function __construct()
    {
        $this->directories = $GLOBALS['SEMEDIR'];
        $this->config = $GLOBALS['SEMECFG'];
        $this->default = $this->config->controller_main;
        $this->notfound = $this->config->controller_404;

        require_once $this->directories->kero_sine.'SENE_Input.php';
        require_once $this->directories->kero_sine.'SENE_Controller.php';
        require_once $this->directories->kero_sine.'SENE_Model.php';

        $rs = array();
        foreach ($this->config->routes as $key=>$val) {
            $key = trim($key, '/');
            $val = trim($val, '/');
            $rs[$key] = $val;
        }
        $this->config->routes = $rs;

        $sene_method = $this->config->method;
        if (isset($_SERVER['argv']) && count($_SERVER['argv'])>1) {
            $i=0;
            $_SERVER[$sene_method] = '';
            foreach ($_SERVER['argv'] as $argv) {
                $i++;
                if ($i==1) {
                    continue;
                }
                $_SERVER[$sene_method] .= '/'.$argv;
            }
            unset($i);
            unset($argv);
        }
        unset($sene_method);
        self::$__instance = $this;
        $GLOBALS['SEMECFG'] = $this->config;
    }


    public static function getInstance()
    {
        return self::$_instance;
    }

    /**
     * Run the framework
     */
    public function run()
    {
        if (strlen($this->config->core_prefix)) {
            if (strlen($this->config->core_controller)) {
                $core_controller_file = $this->directories->app_core.$this->config->core_prefix.$this->config->core_controller.'.php';
                if (file_exists($core_controller_file)) {
                    require_once($core_controller_file);
                } else {
                    $error_msg = 'unable to load core controller on '.$core_controller_file;
                    error_log($error_msg);
                    trigger_error($error_msg);
                }
            }
            if (strlen($this->config->core_model)) {
                $core_model_file = $this->directories->app_core.$this->config->core_prefix.$this->core_model.'.php';
                if (file_exists($core_model_file)) {
                    require_once($core_model_file);
                } else {
                    $error_msg = 'unable to load core model on '.$core_model_file;
                    error_log($error_msg);
                    trigger_error($error_msg);
                }
            }
        }
        $this->newRouteFolder();
    }

    private function defaultController()
    {
        $cname = $this->default.'';
        require_once $this->directories->app_controller.$this->default.".php";
        $cname = new $cname();
        $cname->index();
    }

    private function notFound($newpath='')
    {
        $cname = $this->notfound.'';
        if (file_exists($newpath.$this->notfound.".php")) {
            require_once($newpath.$this->notfound.".php");
        } else {
            require_once($this->directories->app_controller.$this->notfound.".php");
        }
        $cname = new $cname();
        $cname->index();
    }

    private function globalHandlerCMSPage($path=array())
    {
        if (count($path)>1) {
            $slug_parent = $path[1];
            $slug_child = '';
            if (isset($path[2])) {
                $slug_child = $path[2];
            }
            $filename = realpath($this->directories->app_controller."cms_handler.php");
            if (is_file($filename) && !empty($slug_parent)) {
                require_once $filename;
                $cname = basename($filename, ".php");
                $cname = strtr($cname,'-', '_');
                if (class_exists($cname)) {
                    $cname = new $cname();
                    $func = "slugParent";
                    $reflection = new ReflectionMethod($cname, $func);
                    $args = array();
                    $args[0] = $slug_parent;
                    $args[1] = $slug_child;
                    $reflection->invokeArgs($cname, $args);
                }
            }
        }
    }

    private function ovrRoutes($paths=array())
    {
        $path = strtolower(implode('/', $paths));
        $path = trim($path, '/');
        $target = '';
        $routes = $this->config->routes;
        foreach ($routes as $key=>$val) {
            $key = strtolower(trim($key, '/'));
            $val = strtolower(trim($val, '/'));
            $key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
            if (preg_match('#^'.$key.'$#', $path, $matches)) {
                $target = '/'.preg_replace('#^'.$key.'$#', $val, $path);
            }
        }
        if (!empty($target)) {
            return explode('/', $target);
        }
        return $paths;
    }

    private function newRouteFolder()
    {
        $sene_method = $this->config->method;
        if (isset($_SERVER[$sene_method])) {
            $path = $_SERVER[$sene_method];
            $path = strtr($path,'//', '/');
            $path = explode('/', strtr($path,'//', '/'));
            $i=0;
            foreach ($path as $p) {
                if (strlen($p)>0) {
                    $pos = strpos($p, '?');
                    if ($pos !== false) {
                        unset($path[$i]);
                    }
                }
                $i++;
            }
            unset($p);
            $path = $this->ovrRoutes($path);
            if (!isset($path[1])) {
                $path[1] = '';
            }
            $path[1] = strtr($path[1],'-', '_');
            if (!empty($path[1])) {
                if ($path[1] == "admin" && $this->config->baseurl_admin !="admin") {
                    $newpath = realpath($this->directories->app_controller.$path[1]);
                    $this->notFound($newpath);
                    return;
                }

                if ($this->config->baseurl_admin==$path[1]) {
                    $path[1]="admin";
                } else {
                    $this->globalHandlerCMSPage($path);
                }

                $newpath = realpath($this->directories->app_controller.$path[1]);

                if (is_dir($newpath)) {
                    $newpath = rtrim($newpath, '/');
                    $newpath = $newpath.'/';
                    if (empty($path[2])) {
                        $path[2] = 'home';
                    }
                    $dirn = $newpath.'/'.$path[2];
                    $filename = realpath($newpath.''.$path[2].".php");

                    if (is_dir($dirn)) {
                        $dirn = rtrim($dirn, '/');
                        $dirn = $dirn.'/';
                        if (!isset($path[3])) {
                            $path[3] = '';
                        }
                        if (empty($path[3])) {
                            $path[3] = 'home';
                        }
                        $filename = realpath($dirn.''.$path[3].".php");
                        if (is_file($filename)) {
                            require_once $filename;
                            $cname = basename($filename, ".php");
                            $cname = strtr($cname,'-', '_');
                            if (!class_exists($cname, false)) {
                                trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".$this->directories->app_controller.$path[2].'/'.$path[3].".php", E_USER_ERROR);
                                return;
                            }
                            $cname = new $cname();
                            $func = "index";
                            if (isset($path[4])) {
                                if (empty($path[4])) {
                                    $func = "index";
                                } else {
                                    $func = $path[4];
                                }
                            }
                            $func = strtr($func,'-', '_');
                            if (method_exists($cname, $func)) {
                                $reflection = new ReflectionMethod($cname, $func);
                                if (!$reflection->isPublic()) {
                                    $this->notFound();
                                }
                                $args=array();
                                $num = $reflection->getNumberOfParameters();
                                if ($num>0) {
                                    for ($j=0;$j<$num;$j++) {
                                        if (isset($path[(5+$j)])) {
                                            $args[]=$path[(5+$j)];
                                        } else {
                                            $args[]=null;
                                        }
                                    }
                                }
                                $reflection->invokeArgs($cname, $args);
                            } else {
                                $this->notFound($newpath);
                            }
                        } else {
                            $this->notFound($newpath);
                        }
                    } elseif (is_file($filename)) {
                        require_once $filename;
                        $cname = basename($filename, ".php");
                        $cname = strtr($cname,'-', '_');
                        if (!class_exists($cname, false)) {
                            trigger_error("Unable to load class: $cname. Please check classname on controller is exists in ".$this->directories->app_controller." triggered ", E_USER_ERROR);
                            return;
                        }
                        $cname = new $cname();
                        $func = "index";
                        if (isset($path[3])) {
                            if (empty($path[3])) {
                                $func = "index";
                            } else {
                                $func = $path[3];
                            }
                        }
                        $func = strtr($func,'-', '_');
                        if (method_exists($cname, $func)) {
                            $reflection = new ReflectionMethod($cname, $func);
                            if (!$reflection->isPublic()) {
                                $this->notFound();
                            }
                            $args=array();
                            $num = $reflection->getNumberOfParameters();
                            if ($num>0) {
                                for ($j=0;$j<$num;$j++) {
                                    if (isset($path[(4+$j)])) {
                                        $args[]=$path[(4+$j)];
                                    } else {
                                        $args[]=null;
                                    }
                                }
                            }
                            $reflection->invokeArgs($cname, $args);
                        } else {
                            $this->notFound($newpath);
                        }
                    } else {
                        $this->notFound($newpath);
                    }
                } else {
                    $filename = realpath($this->directories->app_controller.$path[1].".php");
                    if (is_file($filename)) {
                        include $filename;
                        $cname = basename($filename, ".php");
                        $cname = strtr($cname,'-', '_');
                        if (class_exists($cname)) {
                            $cname = new $cname();
                            $func = "index";
                            if (isset($path[2])) {
                                if (empty($path[2])) {
                                    $func = "index";
                                } else {
                                    $func = $path[2];
                                }
                            }
                            $func = strtr($func,'-', '_');
                            if (method_exists($cname, $func)) {
                                $reflection = new ReflectionMethod($cname, $func);
                                if (!$reflection->isPublic()) {
                                    $this->notFound();
                                }
                                $args=array();
                                $num = $reflection->getNumberOfParameters();
                                if ($num>0) {
                                    for ($j=0;$j<$num;$j++) {
                                        if (isset($path[(3+$j)])) {
                                            $args[]=$path[(3+$j)];
                                        } else {
                                            $args[]=null;
                                        }
                                    }
                                }
                                $reflection->invokeArgs($cname, $args);
                            } else {
                                $this->notFound();
                            }
                        } else {
                            $this->notFound();
                        }
                    } else {
                        $this->notFound();
                    }
                }
            } else {
                $this->defaultController();
            }
        } else {
            $this->defaultController();
        }
    }
}
