<?php
/**
 * Abstract class for controller wrapper
 */
abstract class SENE_Controller
{
    protected static $__instance;
    public $input;
    public $db;
    public $lang = 'en';
    public $title = 'SEME Framework';
    public $content_language = 'id';
    public $canonical = 'id';
    public $pretitle = 'SEME Framework';
    public $posttitle = 'SEME Framework';
    public $robots = 'INDEX,FOLLOW';
    public $description = 'Created By Seme Framework. The light weight framework that fit your needs with automation generated model.';
    public $keyword = 'lightweight, framework, php, api, generator';
    public $author = 'SEME Framework';
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

    /** @var string used by putLeftThemeContent */
    public $__themeRightMenu = "";

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
     * Loads CSS and another header files from theme.json
     * relatives to theme location
     * @return object this object
     */
    private function getThemeConfig()
    {
        if (file_exists($this->directories->app_view.'/'.$this->getTheme().'/'.$this->css_json)) {
            return json_decode($this->fgc($this->directories->app_view.$this->getTheme().'/'.$this->css_json));
        } else {
            return array();
        }
        return $this;
    }

    /**
     * Loads javascript from script.json
     * relative to theme location
     * @return object this object
     */
    private function getJsFooterBasic()
    {
        if (file_exists($this->directories->app_view.'/'.$this->getTheme().'/'.$this->js_json)) {
            return json_decode($this->fgc($this->directories->app_view.$this->getTheme().'/'.$this->js_json));
        } else {
            return array();
        }
        return $this;
    }

    /**
     * Set theme location, relative to app/view
     * @param string $theme name of directory theme, e.g. front
     */
    protected function setTheme(string $theme="front")
    {
        $theme = rtrim($theme, '/').'/';
        $this->theme = $theme;
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
     * @param  string $c       [description]
     * @return object             return this class
     */
    protected function load(string $a, string $b="", string $c="model")
    {
        if ($c=="model") {
            $mfile = $this->directories->app_model.$a.'.php';
            $cname = basename($mfile, '.php');
            if (empty($b)) {
                $b = $cname;
            }
            $b = strtolower($b);
            if (file_exists($mfile)) {
                if (!class_exists($cname)) {
                    require_once $mfile;
                }
                $this->{$b} = new $cname();
            } else {
                trigger_error('could not find model '.$a.'  on '.$mfile);
                //die();
            }
        } elseif ($c=="lib") {
            $mfile = $this->directories->kero_lib.$a.'.php';
            if (empty($b)) {
                $b = $cname;
            }
            $b = strtolower($b);
            if (file_exists($mfile)) {
                require_once $mfile;
                $this->$b = new $b();
            } else {
                trigger_error('could not find library '.$a.'  on '.$mfile);
            }
        } else {
            if (file_exists($this->directories->kero_lib.$a.'.php')) {
                require_once $this->directories->kero_lib.$a.'.php';
            } else {
                trigger_error('could not find require_once library '.$a.' on '.$mfile);
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
    protected function getThemeElement(string $a="", array $__forward=array(), int $cacheable=0)
    {
        if (!empty($a)) {
            $this->view(str_replace("//", "/", $this->theme.DS.$a), $__forward);
            $this->render($cacheable);
        }
        return $this;
    }

    /**
     * For loading layout from a theme
     * Default file location app/view/front/page/
     * @param  string $u          name of layout without .php suffix
     * @param  array  $__forward  data that will be passed to
     * @return object             return this class
     */
    protected function loadLayout($a, $__forward=array())
    {
        if (empty($a)) {
            trigger_error("Layout not found. Please check layout file at ".$this->directories->app_view.$this->getTheme()."page/ executed", E_USER_ERROR);
        }
        $this->view($this->getTheme()."page/".$a, $__forward);
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
    protected function putThemeContent($u="", $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$u;
        if (file_exists($v.".php")) {
            $keytemp=md5(date("h:i:s"));
            $_SESSION[$keytemp] = $__forward;
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.".php");
            $this->__themeContent .= ob_get_contents();
            ob_end_clean();
            return 0;
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("unable to putThemeContent ".$v.".php");
            die();
        }
        return $this;
    }
    protected function getRightMenuTitle()
    {
        return $this->__themeRightMenu;
    }
    protected function setRightMenuTitle($title="")
    {
        $this->__themeRightMenu = $title;
    }

    /**
     * Inject view for left content
     * @param  string $a                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putThemeRightContent($a="", $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$a;
        //die($v);
        if (file_exists($v.".php")) {
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
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("unable to putThemeRightContent ".$v.".php");
            die();
        }
    }

    /**
     * Inject view for left content
     * @param  string $a                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putThemeLeftContent($a="", $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$a;
        //die($v);
        if (file_exists($v.".php")) {
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
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("unable to putThemeLeftContent ".$v.".php");
            die();
        }
    }

    /**
     * Inject javascript from php files
     * @param  string $a                  view file location wihtout .php suffix related to theme location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putJsReady($a="", $b=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$a;
        if (file_exists($v.".php")) {
            $keytemp=md5(date("h:i:s"));
            $_SESSION[$keytemp] = $__forward;
            //print_r($_SESSION);
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($v.".php");
            $this->js_ready = ob_get_contents();
            ob_end_clean();
            return 0;
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("putJsReady unable to load  ".$v.".php");
            die();
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
     * @param  string $a                  template location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putJsContent(string $a="", $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$a;
        if (file_exists($v.".php")) {
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
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("putJsContent unable to load  ".$v.".php");
            die();
        }
        return $this;
    }

    /**
     * Inject html view before body
     * @param  string $a                  template location
     * @param  array  $__forward          data to passed
     * @return object                     this class
     */
    protected function putBodyBefore($a="", $__forward=array())
    {
        $v = $this->directories->app_view.$this->theme.'/'.$a;
        //die($v);
        if (file_exists($v.".php")) {
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
        } else {
            $v = str_replace('\\', '/', $v);
            $v = str_replace('//', '/', $v);
            $v = str_replace(SEMEROOT, '', $v);
            trigger_error("putBodyBefore unable to load ".$v.".php");
            die();
        }
    }
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

    private function fgc($path)
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
     * inject html script for javacript source to before body element
     * @param  string  $src         js url, if $ext = 0 use without .js suffix
     * @param  integer $ext (1|0),
     * @return object               this object
     */
    protected function putJsFooter($src, $ext=0)
    {
        if ($ext) {
            $this->js_footer[] = '<script src="'.$src.'"></script>';
        } else {
            $stype = rtrim($stype, '.js');
            $this->js_footer[] = '<script src="'.$src.'.js"></script>';
        }
        return $this;
    }
    protected function setCanonical($l="")
    {
        $this->canonical = $l;
        return $this;
    }
    protected function getCanonical()
    {
        return $this->canonical;
    }
    protected function setContentLanguage($l="en")
    {
        $this->content_language = $l;
    }
    protected function getContentLanguage()
    {
        return $this->content_language;
    }
    protected function setLang($lang="en")
    {
        $this->lang = $lang;
    }
    protected function setTitle($title="SEME FRAMEWORK")
    {
        $this->title = $title;
    }
    protected function setDescription($description="en")
    {
        $this->description = $description;
    }
    protected function setKeyword($keyword="lightweight,framework,php,api,generator")
    {
        $this->keyword = $keyword;
    }

    /**
     * Set robots properties for html meta head
     * @param string $robots robots configuration (INDEX,FOLLOW|INDEX,NOFOLLOW)
     */
    protected function setRobots($robots="INDEX,FOLLOW")
    {
        if ($robots != "INDEX,FOLLOW") {
            $robots='NOINDEX,NOFOLLOW';
        }
        $this->robots = $robots;
        return $this;
    }

    /**
     * Set html favicon
     * @param string $icon icon file location
     */
    protected function setIcon($icon="favicon.png")
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Set shortcut icon properties for html meta head
     * @param string $icon icon file location
     */
    protected function setShortcutIcon($shortcut_icon="favicon.png")
    {
        $this->shortcut_icon = $shortcut_icon;
        return $this;
    }

    /**
     * Set authorname properties for html meta head
     * @param string $icon icon file location
     */
    protected function setAuthor($author="SEME Framework")
    {
        $this->author = $author;
    }

    /**
     * Set additional CSS files
     */
    protected function setAdditional($val)
    {
        end($this->additional);
        $key = (int)key($this->additional);
        $key = $key+1;
        $this->additional[$key] = $val;
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
                $i++;
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
                $i++;
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
    protected function loadCss($src, $utype="after")
    {
        if (strtolower($utype)=="after") {
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
        if(isset($this->additional[$key])) unset($this->additional[$key]);
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
    protected function getIcon($icon="favicon.png")
    {
        return $this->icon;
    }

    /**
     * Return string for shortcut favicon / icon location
     * @return string keyword
     */
    protected function getShortcutIcon($shortcut_icon="favicon.png")
    {
        return $this->shortcut_icon;
    }

    /**
     * Get list of array CSS before default configration from theme.json
     */
    protected function getAdditionalBefore()
    {
        foreach ($this->additionalBefore as $key=>$a) {
            if (is_string($a)) {
                $a = str_replace("{{base_url}}", base_url(), $a);
                $a = str_replace("{{base_url_admin}}", base_url_admin(), $a);
                $cdn_url = '';
                if(isset($this->config->cdn_url)){
                  $cdn_url = $this->config->cdn_url;
                }
                if(strlen($cdn_url)>4){
                    $a = str_replace("{{cdn_url}}", $cdn_url, $a);
                }else{
                    $a = str_replace("{{cdn_url}}", base_url(), $a);
                }
                echo "\n\t".$a;
            }
        }
    }

    /**
     * Get list of array CSS after default configration from theme.json
     */
    protected function getAdditional()
    {
        if (count($this->additional)):
            foreach ($this->additional as $key=>$a) {
                if (is_string($a)) {
                    $a = str_replace("{{base_url}}", base_url(), $a);
                    $a = str_replace("{{base_url_admin}}", base_url_admin(), $a);
                    $cdn_url = '';
                    if(isset($this->config->cdn_url)){
                      $cdn_url = $this->config->cdn_url;
                    }
                    if(strlen($cdn_url)>4){
                        $a = str_replace("{{cdn_url}}", $cdn_url, $a);
                    }else{
                        $a = str_replace("{{cdn_url}}", base_url(), $a);
                    }
                    echo "\n\t".$a;
                }
            }
        endif;
    }

    /**
     * Get list of array CSS after default configration from theme.json
     */
    protected function getAdditionalAfter()
    {
        foreach ($this->additionalAfter as $key=>$a) {
            if (is_string($a)) {
                $a = str_replace("{{base_url}}", base_url(), $a);
                $a = str_replace("{{base_url_admin}}", base_url_admin(), $a);
                $cdn_url = '';
                if(isset($this->config->cdn_url)){
                  $cdn_url = $this->config->cdn_url;
                }
                if(strlen($cdn_url)>4){
                    $a = str_replace("{{cdn_url}}", $cdn_url, $a);
                }else{
                    $a = str_replace("{{cdn_url}}", base_url(), $a);
                }
                echo "\n\t".$a;
            }
        }
    }

    /**
     * get injected html script to put in before closing body
     */
    protected function getJsFooter()
    {
        foreach ($this->js_footer as $key=>$a) {
            if (is_string($a)) {
                $a = str_replace("{{base_url}}", base_url(), $a);
                $a = str_replace("{{base_url_admin}}", base_url_admin(), $a);
                $cdn_url = '';
                if(isset($this->config->cdn_url)){
                  $cdn_url = $this->config->cdn_url;
                }
                if(strlen($cdn_url)>4){
                    $a = str_replace("{{cdn_url}}", $cdn_url, $a);
                }else{
                    $a = str_replace("{{cdn_url}}", base_url(), $a);
                }
                echo "\n\t".$a;
            }
        }
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
    protected function getThemeView($el="", $comp='page', $__forward=array())
    {
        if (!empty($el)) {
            $this->view($this->theme.'/'.$comp.'/'.$el, $__forward);
        }
    }

    /**
     * echo string as HTML5 Entity
     * @param  string $a    string
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
        if (file_exists($this->directories->app_view.$a.".php")) {
            $keytemp=md5(date("h:i:s"));
            $_SESSION[$keytemp] = $__forward;
            //print_r($_SESSION);
            extract($_SESSION[$keytemp]);
            unset($_SESSION[$keytemp]);
            ob_start();
            require_once($this->directories->app_view.$a.".php");
            $this->__content = ob_get_contents();
            ob_end_clean();
        } else {
            trigger_error("unable to load view ".$this->directories->app_view.$v.".php ", E_USER_ERROR);
            die("unable to load view ".$this->directories->app_view.$v.".php");
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
    protected function lib($a, $b="", $c="lib")
    {
        if ($c=='lib') {
            $lpath = str_replace("\\", "/", $this->directories->kero_lib.$a.".php");
            if (file_exists(strtolower($lpath))) {
                require_once(strtolower($lpath));
                $cname = basename($lpath, '.php');
                $method = new $cname();
                if (empty($b)) {
                    $b = $cname;
                }
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
                die("unable to load library on ".$lpath);
            }
        } else {
            if (file_exists(strtolower($this->directories->kero_lib.$a.".php"))) {
                require_once(strtolower($this->directories->kero_lib.$a.".php"));
            } elseif (file_exists($this->directories->kero_lib.$a.".php")) {
                require_once($this->directories->kero_lib.$a.".php");
            } else {
                die("unable to load library on ".strtolower($this->directories->kero_lib.$a.".php x"));
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
     * @return mixed      Saved value(s) from session
     */
    protected function getKey()
    {
        if (isset($_SESSION[$this->config->saltkey])) {
            return $_SESSION[$this->config->saltkey];
        } else {
            return 0;
        }
        return $this;
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

    protected function getcookie($var="")
    {
        if (empty($var)) {
            return 0;
        }
        if (isset($_COOKIE[$var])) {
            return $_COOKIE[$var];
        } else {
            return 0;
        }
    }

    protected function setcookie($var="", $val="0")
    {
        $_COOKIE[$var] = $val;
    }

    /**
     * Show printed content of variable
     * @param  mixed $a     [description]
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
     * Render buffered view to browser
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
     * @param  string $url URL wihtout base_url
     * @return string      URL with CDN URL
     */
    protected function cdn_url($url="")
    {
        $cdn_url = '';
        if(isset($this->config->cdn_url)){
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
