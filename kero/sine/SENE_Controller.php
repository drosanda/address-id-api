<?php
/**
 * Abstract Class for SemeFramework Class Controller
 *
 * @author Daeng Rosanda
 * @version 4.0.3
 *
 * @package SemeFramework\Kero
 * @since 3.0.0
 */
#[AllowDynamicProperties]
abstract class SENE_Controller
{
    protected static $__instance;
    public $input;
    public $db;
    public $lang = 'en';
    public $title = 'SEME Framework';
    public $content_language = 'id';
    public $canonical = 'id';
    public $pretitle = '';
    public $posttitle = '';
    public $robots = 'INDEX,FOLLOW';
    public $description = 'Created By Seme Framework. The light weight framework that fit your needs with automation generated model.';
    public $keyword = 'lightweight, framework, php, api, generator';
    public $author = '';
    public $icon = 'favicon.png';
    public $shortcut_icon = 'favicon.png';
    public $content_type = 'text/html; charset=utf-8';

    public $css_json = 'theme.json';
    public $js_json = 'script.json';

    /**
     * For additional CSS
     * @var string
     */
    public $additional = array();
    public $additionalBefore = array();
    public $additionalAfter = array();

    public $theme = 'front/';
    public $js_footer = array();
    public $js_ready = "";

    /** @var string put unrendered string content for view */
    public $__content = "";

    /** @var string used by putThemeContent */
    public $__themeContent = "";

    /** @var string used by putRightThemeContent */
    public $__themeRightContent = "";

    /** @var string used by putLeftThemeContent */
    public $__themeLeftContent = "";

    /** @var string  */
    public $__bodyBefore = "";


    public function __construct()
    {
        $this->directories = $GLOBALS['SEMEDIR'];
        $this->config = $GLOBALS['SEMECFG'];
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

    /**
     * Helper method for loading a file
     * @param  string $path       the path of a file
     * @return string             JSON formatted string
     */
    private function fgc(string $path)
    {
        $x = json_encode(array());
        if (file_exists($path)) {
            $f = fopen($path, "r");
            if (filesize($path)>0) {
                $x = fread($f, filesize($path));
            }
            fclose($f);
            unset($f);
        }
        return $x;
    }

    /**
     * Loads CSS and another header files from theme.json
     * relatives to theme location
     * @return object this object
     */
    private function getThemeConfig()
    {
        if (file_exists($this->directories->app_view.'/'.$this->getTheme().'/'.$this->css_json)) {
            $dj = json_decode($this->fgc($this->directories->app_view.$this->getTheme().'/'.$this->css_json));
            $da = array();
            if (is_array($dj) && count($dj)) {
                foreach ($dj as $d) {
                    if (is_string($d)) {
                        $da[] = $d;
                    }
                }
            } elseif (isset($dj->link) && count($dj->link)) {
                foreach ($dj->link as $d) {
                    $a = '<link ';
                    foreach ($d as $k=>$v) {
                        $a .= $k.'="'.$v.'" ';
                    }
                    $da[] = rtrim($a).' />';
                    unset($a,$k,$v);
                }
            }
            unset($dj,$d);
            return $da;
        } else {
            return array();
        }
    }

    /**
     * Loads javascript from script.json
     * relative to theme location
     * @return object this object
     */
    private function getJsFooterBasic()
    {
        if (file_exists($this->directories->app_view.'/'.$this->getTheme().'/'.$this->js_json)) {
            $dj = json_decode($this->fgc($this->directories->app_view.$this->getTheme().'/'.$this->js_json));
            $da = array();
            if (is_array($dj) && count($dj)) {
                foreach ($dj as $d) {
                    if (is_string($d)) {
                        $da[] = $d;
                    }
                }
            } elseif (isset($dj->script) && count($dj->script)) {
                foreach ($dj->script as $d) {
                    $a = '<script ';
                    foreach ($d as $k=>$v) {
                        $a .= $k.'="'.$v.'" ';
                    }
                    $da[] = rtrim($a).'></script>';
                    unset($a,$k,$v);
                }
            }
            unset($dj,$d);
            return $da;
        } else {
            return array();
        }
    }

    /**
     * Set theme location, relative to app/view
     * @param string $theme name of directory theme, e.g. front
     */
    protected function setTheme(string $theme="front")
    {
        $this->theme = rtrim($theme, '/').'/';
        if (!is_dir($this->directories->app_view.$this->theme.'/')) {
            trigger_error(TEM_ERR.': Missing theme directory for '.$theme.'', E_USER_ERROR);
        }
        $this->additional = $this->getThemeConfig();
        $this->js_footer = $this->getJsFooterBasic();
        return $this;
    }

    /**
     * Load the model or library from controller and instantiate object with same name in controller
     * if model relatives to app/model
     * if library relatives to kero/lib
     * @param  string $a          location and name of view without .php suffix
     * @param  string $b          alias of instantiate object, default empty
     * @param  string $c          Type of loaded
     *
     * @return object             return this class
     */
    protected function load(string $a, string $b='', string $c="model")
    {
        if ($c=="model") {
            $mfile = $this->directories->app_model.$a.'.php';
            $cname = basename($mfile, '.php');
            if (empty($b)) {
                $b = $cname;
            }
            $b = strtolower($b);
            if (file_exists($mfile)) {
                require_once $mfile;
                if (!class_exists($cname)) {
                    $namespace = isset($_SESSION['namespace']) ? $_SESSION['namespace'] : '';
                    $cname = $namespace.'\\'.$cname;
                }

                if (!class_exists($cname)) {
                    trigger_error(TEM_ERR.': could load model '.$a.' on '.$mfile.' ('.$cname.')', E_USER_ERROR);
                }

                $this->{$b} = new $cname();
            } else {
                trigger_error(TEM_ERR.': could not find model '.$a.'  on '.$mfile, E_USER_ERROR);
            }
        } elseif ($c=="lib") {
            $mfile = $this->directories->kero_lib.$a.'.php';
            if (empty($b)) {
                $b = basename($mfile, '.php');
            }
            $b = strtolower($b);
            if (file_exists($mfile)) {
                require_once $mfile;
                if (!class_exists($cname)) {
                    $namespace = isset($_SESSION['namespace']) ? $_SESSION['namespace'] : '';
                    $b = $namespace.'\\'.$b;
                }

                if (!class_exists($b)) {
                    trigger_error(TEM_ERR.': could load model '.$a.' on '.$mfile.' ('.$b.')', E_USER_ERROR);
                }
                $this->$b = new $b();
            } else {
                trigger_error(TEM_ERR.': could not find library '.$a.'  on '.$mfile, E_USER_ERROR);
            }
        } else {
            $mfile = $this->directories->kero_lib.$a.'.php';
            if (file_exists($this->directories->kero_lib.$a.'.php')) {
                require_once $this->directories->kero_lib.$a.'.php';
            } else {
                trigger_error(TEM_ERR.': could not find require_once library '.$a.' on '.$mfile, E_USER_ERROR);
            }
        }
        return $this;
    }

    /**
     * bring view from another file
     *   relatives from theme location
     * @param  string $a         location and name of view without .php suffix
     * @param  array  $__forward data that will be passed to
     * @return object            return this class
     */
    protected function getThemeElement(string $a='', array $__forward=array(), int $cacheable=0)
    {
        if (!empty($a)) {
            $this->view(strtr($this->theme.DS.$a, "//", "/"), $__forward);
            $this->render($cacheable);
        }
        return $this;
    }

    /**
     * For loading layout from a theme
     * Default file location app/view/front/page/
     * @param  string $a          name of layout without .php suffix
     * @param  array  $__forward  data that will be passed to
     * @return object             return this class
     */
    protected function loadLayout(string $a, $__forward=array())
    {
        if (empty($a)) {
            trigger_error(TEM_ERR.': Layout not found. Please check layout file at '.$this->directories->app_view.$this->getTheme().'page/ executed', E_USER_ERROR);
        }
        $this->view($this->getTheme().'page/'.$a, $__forward);
        return $this;
    }

    /**
     * Empty the __themeContent variable
     * @return object            return this class
     */
    protected function resetThemeContent()
    {
        $this->__themeContent = '';
        return $this;
    }

    /**
     * Inject view to a layout
     * @param  string $u         relative theme location filename without .php extension
     * @param  array  $__forward  data that will be passed to
     * @return object             return this class
     */
    protected function putThemeContent(string $u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->__themeContent .= ob_get_contents();
            ob_end_clean();
            return 0;
        } else {
            trigger_error('unable to load putThemeContent for '.$v.'.php', E_USER_ERROR);
        }
        return $this;
    }

    /**
     * Inject view for left content
     * @param  string $u                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putThemeRightContent(string $u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->__themeRightContent = ob_get_contents();
            ob_end_clean();
            return $this;
        } else {
            trigger_error('unable to load putThemeRightContent for '.$v.'.php', E_USER_ERROR);
        }
    }

    /**
     * Inject view for left content
     * @param  string $u                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putThemeLeftContent(string $u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->__themeLeftContent .= ob_get_contents();
            ob_end_clean();
            return $this;
        } else {
            trigger_error('unable to load putThemeLeftContent for '.$v.'.php', E_USER_ERROR);
        }
    }

    /**
     * Inject javascript from php files
     * @param  string $u                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putJsReady(string $u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->js_ready .= ob_get_contents();
            ob_end_clean();
            return $this;
        } else {
            trigger_error(TEM_ERR.': unable to load putJsReady for '.$v.'.php', E_USER_ERROR);
        }
    }

    /**
     * Get injected main view into a layout
     */
    protected function getThemeContent()
    {
        echo $this->__themeContent;
    }

    /**
     * Get injected right view into a layout
     */
    protected function getThemeRightContent()
    {
        echo $this->__themeRightContent;
    }

    /**
     * Get injected left view into a layout
     */
    protected function getThemeLeftContent()
    {
        echo $this->__themeLeftContent;
    }

    /**
     * Get injected javascript content in php file into a layout
     */
    protected function getJsReady()
    {
        echo $this->js_ready;
    }
    /**
     * Inject javascript content from php files
     * @param  string $u                  template location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putJsContent(string $u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->__jsContent .= ob_get_contents();
            ob_end_clean();
            return $this;
        } else {
            trigger_error(TEM_ERR.': unable to load putJsContent for '.$v.'.php', E_USER_ERROR);
        }
        return $this;
    }

    /**
     * Inject html view before body
     * @param  string $u                  template location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putBodyBefore($u='', $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.'.php');
            $this->__bodyBefore .= ob_get_contents();
            ob_end_clean();
            return $this;
        } else {
            trigger_error(TEM_ERR.': unable to load putBodyBefore for '.$v.'.php', E_USER_ERROR);
        }
    }

    /**
     * Get JavaScript content injected from putBodyBefore
     */
    protected function getBodyBefore()
    {
        echo $this->__bodyBefore;
    }

    /**
     * Get JavaScript content injected from putJsContent
     */
    protected function getJsContent()
    {
        echo $this->__jsContent;
    }

    /**
     * inject html script for javacript source to before body element
     * @param  string  $src         js url, if $ext = 0 use without .js suffix
     * @param  integer $ext         1 if external have to include extension, 0 if internal doesnt need include .js suffix
     * @return object               this object
     */
    protected function putJsFooter($src, $ext=0)
    {
        if (!empty($ext)) {
            $this->js_footer[] = '<script src="'.$src.'"></script>';
        } else {
            $src = rtrim($src, '.js');
            $this->js_footer[] = '<script src="'.$src.'.js"></script>';
        }
        return $this;
    }

    /**
     * Set content language for meta content language
     * @param string $l language string
     */
    protected function setContentLanguage($l)
    {
        $this->content_language = $l;
        return $this;
    }

    /**
     * Get content language for meta content language
     *
     * @return string   current content language
     */
    protected function getContentLanguage()
    {
        return $this->content_language;
    }

    /**
     * Set language value
     * @param string $lang language value for html tag
     */
    protected function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Set value for html head title
     * @param string $title title for html
     */
    protected function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set value for meta description
     * @param string $description [description]
     */
    protected function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set value for meta keyword
     * @param string $keyword the keyword value
     */
    protected function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Set robots properties for html meta head
     * @param string $robots robots configuration (INDEX,FOLLOW|INDEX,NOFOLLOW)
     */
    protected function setRobots($robots)
    {
        $this->robots = $robots;
        return $this;
    }

    /**
     * Set html favicon
     * @param string $icon icon file location
     */
    protected function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Set shortcut icon properties for html meta head
     * @param string $icon icon file location
     */
    protected function setShortcutIcon($shortcut_icon)
    {
        $this->shortcut_icon = $shortcut_icon;
        return $this;
    }

    /**
     * Set authorname properties for html meta head
     * @param string $icon icon file location
     */
    protected function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set canonical URL
     * @param  string   $l  canonical url
     * @return object       this object
     */
    protected function setCanonical($l='')
    {
        $this->canonical = $l;
        return $this;
    }

    /**
     * Set additional CSS files
     * @param mixed     $val    mixed value
     * @return object           this object
     */
    protected function setAdditional($val)
    {
        end($this->additional);
        $key = (int) key($this->additional);
        $key = $key+1;
        $this->additional[$key] = $val;
        return $this;
    }

    /**
     * Set additional CSS files before current default configuration from theme.json
     */
    protected function setAdditionalBefore($val)
    {
        if (!is_array($this->additionalBefore)) {
            $this->additionalBefore = array();
        }
        if (is_array($val)) {
            foreach ($val as $v) {
                $this->additionalBefore[] = $v;
            }
        } elseif (is_string($val)) {
            $this->additionalBefore[] = $val;
        }
    }

    /**
     * Set additional CSS files after current default configuration from theme.json
     */
    protected function setAdditionalAfter($val)
    {
        if (!is_array($this->additionalAfter)) {
            $this->additionalAfter = array();
        }
        if (is_array($val)) {
            foreach ($val as $v) {
                $this->additionalAfter[] = $v;
            }
        } elseif (is_string($val)) {
            $this->additionalAfter[] = $val;
        }
    }

    /**
     * Load css stylesheet file
     * @param  string $css_url load CSS url
     * @param  string $utype   (before | after) main css defined on theme.json
     * @return object          this object
     */
    protected function loadCss($src, $utype='after')
    {
        if (strtolower($utype)=='after') {
            $this->setAdditionalAfter('<link rel="stylesheet" href="'.$src.'.css" />');
        } else {
            $this->setAdditionalBefore('<link rel="stylesheet" href="'.$src.'.css" />');
        }
        return $this;
    }

    /**
     * Remove additional CSS
     * @param  string $key  key of css array
     * @return object          this object
     */
    protected function removeAdditional($key)
    {
        if (isset($this->additional[$key])) {
            unset($this->additional[$key]);
        }
        return $this;
    }

    /**
     * Return author name for html head meta language
     * @return string     language defined from setLang() method
     */
    protected function getLang()
    {
        return $this->lang;
    }

    /**
     * Return title text for html head title
     * @return string title
     */
    protected function getTitle()
    {
        return $this->title;
    }

    /**
     * Return author name for html head meta author
     * @return string author
     */
    protected function getAuthor()
    {
        return $this->author;
    }

    /**
     * Return string for html head meta description
     * @return string description
     */
    protected function getDescription()
    {
        return $this->description;
    }

    /**
     * Return string for html head meta keyword
     * @return string keyword
     */
    protected function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Return string for robots.txt location
     * @return string keyword
     */
    protected function getRobots()
    {
        return $this->robots;
    }

    /**
     * Return string for favicon / icon location
     * @return string keyword
     */
    protected function getIcon()
    {
        return $this->icon;
    }

    /**
     * Return string for shortcut favicon / icon location
     * @return string keyword
     */
    protected function getShortcutIcon()
    {
        return $this->shortcut_icon;
    }

    /**
     * get Canonical URL
     * @return string canonical url
     */
    protected function getCanonical()
    {
        if (strlen($this->canonical)<4) {
            return rtrim(base_url('')."".$_SERVER['REQUEST_URI']."", '/').'/';
        }
        return rtrim($this->canonical, '/').'/';
    }

    /*
    * Get list of array CSS before default configration from theme.json
    */
    protected function getAdditionalBefore()
    {
        foreach ($this->additionalBefore as $a) {
            if (is_string($a)) {
                if (strpos($a, LBL_BASE_URL) !== false) {
                    $a = str_replace(LBL_BASE_URL, base_url(''), $a);
                } elseif (strpos($a, LBL_BASE_URL_ADMIN) !== false) {
                    $a = str_replace(LBL_BASE_URL_ADMIN, base_url_admin(''), $a);
                } elseif (strpos($a, LBL_CDN_URL) !== false) {
                    $cdn_url = '';
                    if (isset($this->config->cdn_url)) {
                        $cdn_url = $this->config->cdn_url;
                    }
                    if (strlen($cdn_url)>4) {
                        $a = str_replace(LBL_CDN_URL, $cdn_url, $a);
                    } else {
                        $a = str_replace(LBL_CDN_URL, base_url(''), $a);
                    }
                }
                echo "\n\t".$a;
            }
        }
    }

    /**
     * Get list of array CSS after default configration from theme.json
     * @return void
     */
    protected function getAdditional()
    {
        if (is_array($this->additional)) {
            foreach ($this->additional as $a) {
                if (is_string($a)) {
                    if (strpos($a, LBL_BASE_URL) !== false) {
                        $a = str_replace(LBL_BASE_URL, base_url(''), $a);
                    } elseif (strpos($a, LBL_BASE_URL_ADMIN) !== false) {
                        $a = str_replace(LBL_BASE_URL_ADMIN, base_url_admin(''), $a);
                    } elseif (strpos($a, LBL_CDN_URL) !== false) {
                        $cdn_url = '';
                        if (isset($this->config->cdn_url)) {
                            $cdn_url = $this->config->cdn_url;
                        }
                        if (strlen($cdn_url)>4) {
                            $a = str_replace(LBL_CDN_URL, $cdn_url, $a);
                        } else {
                            $a = str_replace(LBL_CDN_URL, base_url(''), $a);
                        }
                    }
                    echo "\n\t".$a;
                }
            }
        }
    }

    /**
     * Get list of array CSS after default configration from theme.json
     * @return void
     */
    protected function getAdditionalAfter()
    {
        foreach ($this->additionalAfter as $a) {
            if (is_string($a)) {
                if (strpos($a, LBL_BASE_URL) !== false) {
                    $a = str_replace(LBL_BASE_URL, base_url(''), $a);
                } elseif (strpos($a, LBL_BASE_URL_ADMIN) !== false) {
                    $a = str_replace(LBL_BASE_URL_ADMIN, base_url_admin(''), $a);
                } elseif (strpos($a, LBL_CDN_URL) !== false) {
                    $cdn_url = '';
                    if (isset($this->config->cdn_url)) {
                        $cdn_url = $this->config->cdn_url;
                    }
                    if (strlen($cdn_url)>4) {
                        $a = str_replace(LBL_CDN_URL, $cdn_url, $a);
                    } else {
                        $a = str_replace(LBL_CDN_URL, base_url(''), $a);
                    }
                }
                echo "\n\t".$a;
            }
        }
    }

    /**
     * get injected html script to put in before closing body
     * @return void
     */
    protected function getJsFooter()
    {
        if (is_array($this->js_footer)) {
            foreach ($this->js_footer as $a) {
                if (is_string($a)) {
                    if (strpos($a, LBL_BASE_URL) !== false) {
                        $a = str_replace(LBL_BASE_URL, base_url(''), $a);
                    } elseif (strpos($a, LBL_BASE_URL_ADMIN) !== false) {
                        $a = str_replace(LBL_BASE_URL_ADMIN, base_url_admin(''), $a);
                    } elseif (strpos($a, LBL_CDN_URL) !== false) {
                        $cdn_url = '';
                        if (isset($this->config->cdn_url)) {
                            $cdn_url = $this->config->cdn_url;
                        }
                        if (strlen($cdn_url)>4) {
                            $a = str_replace(LBL_CDN_URL, $cdn_url, $a);
                        } else {
                            $a = str_replace(LBL_CDN_URL, base_url(''), $a);
                        }
                    }
                    echo "\n\t".$a;
                }
            }
        }
    }

    /**
     * Set meta content value
     * @param string $content_type content type value
     */
    protected function setContentType($content_type)
    {
        $this->content_type = $content_type;
        return $this;
    }

    /**
     * get value for meta content type
     * @return string content type
     */
    protected function getContentType()
    {
        return $this->content_type;
    }

    /**
     * Get current theme
     * @return string     name of theme
     */
    protected function getTheme()
    {
        return $this->theme;
    }

    /**
     * Load html view by theme
     * @param  string $el                 View theme element
     * @param  array  $comp               Theme components
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function getThemeView($el='', $comp='page', $__forward=array())
    {
        if (!empty($el)) {
            $this->view($this->theme.'/'.$comp.'/'.$el, $__forward);
        }
    }

    /**
     * echo string as HTML5 Entity
     * @param  string $a    string
     * @return void
     */
    protected function __($a)
    {
        echo htmlentities((string) $a, ENT_HTML5, 'UTF-8');
    }

    public static function getInstance()
    {
        return self::$_instance;
    }

    /**
     * Loading html view relative to selected theme
     * - extract forwarded variable(s) from array to single variable
     * @param  string $v              [description]
     * @param  array  $__forward      forwarded data
     */
    private function view($a, $__forward=array())
    {
        if (file_exists($this->directories->app_view.$a.'.php')) {
            $keytemp = microtime();
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($this->directories->app_view.$a.'.php');
            $this->__content = ob_get_contents();
            ob_end_clean();
        } else {
            trigger_error('unable to load view '.$this->directories->app_view.$a.'.php', E_USER_ERROR);
        }
        return $this;
    }

    /**
     * Load the library from controller and instantiate object with same name in controller
     * relatives to kero/lib
     * @param  string $a          location and name of view without .php suffix
     * @param  string $b          alias of instantiate object, default empty
     * @param  string $c          type of embedding. lib: autoinstantiate, otherwise include only
     * @return object             return this class
     */
    protected function lib($a, $b='', $c="lib")
    {
        if ($c=='lib') {
            $lpath = strtr($this->directories->kero_lib.$a.'.php', "\\", "/");
            if (file_exists(strtolower($lpath))) {
                require_once(strtolower($lpath));
                $cname = basename($lpath, '.php');
                if (empty($b)) {
                    $b = $cname;
                }
                if (!class_exists($cname)) {
                    $namespace = isset($_SESSION['namespace']) ? $_SESSION['namespace'] : '';
                    $cname = $namespace.'\\'.$cname;
                } else {
                    $cname = '\\'.$cname;
                }

                if (!class_exists($cname)) {
                    trigger_error(TEM_ERR.': could load library '.$a.' on '.$lpath.' ('.$cname.')', E_USER_ERROR);
                }
                $method = new $cname();
                $b = strtolower($b);
                $this->{$b} = $method;
            } elseif (file_exists($lpath)) {
                require_once($lpath);
                $cname = basename($lpath, '.php');
                $method = new $cname();
                if (empty($b)) {
                    $b = $cname;
                }
                $b = strtolower($b);
                $this->{$b} = $method;
            } else {
                trigger_error('unable to load library on '.$lpath, E_USER_ERROR);
            }
        } else {
            if (file_exists(strtolower($this->directories->kero_lib.$a.'.php'))) {
                require_once(strtolower($this->directories->kero_lib.$a.'.php'));
            } elseif (file_exists($this->directories->kero_lib.$a.'.php')) {
                require_once($this->directories->kero_lib.$a.'.php');
            } else {
                trigger_error('unable to load library on '.strtolower($this->directories->kero_lib.$a.".php x"), E_USER_ERROR);
            }
        }
        return $this;
    }

    /**
     * Session set value
     * @param mixed $a    value(s) want to saved to session
     */
    protected function setKey($a)
    {
        $_SESSION[$this->config->saltkey]=$a;
        return $this;
    }

    /**
     * Session get saved value
     * @return mixed      Saved value(s) from session, return false if error
     */
    protected function getKey()
    {
        if (isset($_SESSION[$this->config->saltkey])) {
            return $_SESSION[$this->config->saltkey];
        } else {
            return 0;
        }
    }

    /**
     * Delete session key
     * @return object this object
     */
    protected function delKey()
    {
        unset($_SESSION[$this->config->saltkey]);
        session_destroy();
        return $this;
    }

    /**
     * Get the cookie with supplied key
     * @param  string $k    Key for cookie
     * @return mixed        Return the value or empty
     */
    protected function getcookie($k='')
    {
        if (empty($k)) {
            return 0;
        }
        if (isset($_COOKIE[$k])) {
            return $_COOKIE[$k];
        } else {
            return 0;
        }
    }

    /**
     * Set the cookie with supplied key and value
     * @param  string $k    Key for cookie
     * @param  mixed $val   The value
     * @return object       this object
     */
    protected function setcookie($k='', $val="0")
    {
        $_COOKIE[$k] = $val;
        return $this;
    }

    /**
     * Show printed content of variable
     * @param  mixed $a     [description]
     * @return void
     */
    protected function debug($a)
    {
        echo '<pre>';
        print_r($a);
        echo '</pre>';
    }

    /**
     * Print variable dump
     * @param  mixed $a     [description]
     */
    protected function dd($a)
    {
        echo '<pre>';
        var_dump($a);
        echo '</pre>';
    }

    /**
     * Render buffered view content into browser
     * @param  integer $cacheable true or false
     * @return void
     */
    protected function render($cacheable=0)
    {
        $cacheable = (int) $cacheable;
        if ($cacheable) {
            $cache_filename = md5($_SERVER['REQUEST_URI']);
            $cache_file = SENECACHE.'/'.$cache_filename;
            if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * $cacheable))) {
                echo file_get_contents($cache_file);
            } else {
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
        } else {
            echo $this->__content;
        }
    }

    /**
     * Get CDN URL if exist or fallback to base_url if not exists
     * @param  string $url      URL wihtout base_url
     * @return string           URL with CDN URL
     */
    protected function cdn_url($url='')
    {
        $cdn_url = '';
        if (isset($this->config->cdn_url)) {
            $cdn_url = $this->config->cdn_url;
        }
        if ($this->config->environment == 'development' || empty($this->config->environment)) {
            return base_url($url);
        }
        if (strlen($cdn_url)>6) {
            return $cdn_url.$url;
        } else {
            return base_url($url);
        }
    }

    /**
     * Create abstract method index, so every controller has index method
     */
    abstract protected function index();
}
