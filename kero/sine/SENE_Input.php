<?php
/**
 * Helper Class for Input through HTTP method like GET, POST, and REQUEST
 *
 * @author Daeng Rosanda
 * @version 4.0.3
 *
 * @package SemeFramework\Kero\Sine
 * @since 4.0.0
 */
#[AllowDynamicProperties]
class SENE_Input
{
    /**
     * Get value from $_POST payload
     *
     * @param  string $k    keyname of POST
     * @param  mixed  $d    default return value
     *
     * @return mixed        value from current keyname
     */
    public function post(string $k, $d = 0)
    {
        if (isset($_POST[$k])) {
            return $_POST[$k];
        } else {
            return $d;
        }
    }

    /**
     * Get value from $_GET payload
     *
     * @param  string $k    keyname of GET
     * @param  mixed  $d    default return value
     *
     * @return mixed        value from current keyname
     */
    public function get(string $k, $d = 0)
    {
        if (isset($_GET[$k])) {
            return $_GET[$k];
        } else {
            return $d;
        }
    }

    /**
     * Get value from $_REQUEST payload
     *
     * @param  string $k    keyname of REQUEST
     * @param  mixed  $d    default return value
     *
     * @return mixed        value from current keyname
     */
    public function request(string $k, $d = 0)
    {
        if (isset($_REQUEST[$k])) {
            return $_REQUEST[$k];
        } else {
            return $d;
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
        return array("post_param"=>$_POST,"get_param"=>$_GET,"request_param"=>$_REQUEST,"file_param"=>$_FILES);
    }
}
