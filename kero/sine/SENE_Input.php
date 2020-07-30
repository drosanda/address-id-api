<?php
/**
 * Input class helper
 */
class SENE_Input
{
    /**
     * Get value from $_POST payload
     * @param  string $k    keyname of POST
     * @return mixed        value from current keyname
     */
    public function post(string $k)
    {
        if (isset($_POST[$k])) {
            return $_POST[$k];
        } else {
            return 0;
        }
    }
    
    /**
     * Get value from $_GET payload
     * @param  string $k    keyname of GET
     * @return mixed        value from current keyname
     */
    public function get(string $k)
    {
        if (isset($_GET[$k])) {
            return $_GET[$k];
        } else {
            return 0;
        }
    }
    
    /**
     * Get value from $_REQUEST payload
     * @param  string $k    keyname of GET
     * @return mixed        value from current keyname
     */
    public function request(string $k)
    {
        if (isset($_REQUEST[$k])) {
            return $_REQUEST[$k];
        } else {
            return 0;
        }
    }
    
    public function file($var)
    {
        if (isset($_FILES[$var])) {
            return $_FILES[$var];
        } else {
            return 0;
        }
    }
    public function debug()
    {
        return array("post_param"=>$_POST,"get_param"=>$_GET,"file_param"=>$_FILES);
    }
}