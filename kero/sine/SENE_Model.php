<?php
/**
 * Abstract Class for SemeFramework Class Model
 *
 * @author Daeng Rosanda
 * @version 4.0.3
 *
 * @package SemeFramework\Kero
 * @since 4.0.0
 */
#[AllowDynamicProperties]
abstract class SENE_Model
{
    public $db;
    protected $directories;
    protected $config;
    public $field = array();

    public function __construct()
    {
        $this->directories = $GLOBALS['SEMEDIR'];
        $this->config = $GLOBALS['SEMECFG'];
        $this->loadEngine($this->config->database);
    }

    /**
     * Load the database engine configuration
     *
     * @param  object $db   Database configuration
     *
     * @return object       this object
     */
    private function loadEngine($db)
    {
        if (!is_object($db)) {
            $db = new stdClass();
        }
        if (!isset($db->engine)) {
            $db->engine = '';
        }
        require_once($this->directories->kero_sine."SENE_MySQLi_Engine.php");
        $this->db = new SENE_MySQLi_Engine($db);
        return $this;
    }

    /**
     * Encrypt the string
     *
     * @param  string $val plain string
     *
     * @return string      Full encrypt string command on SQL format
     */
    public function __encrypt($val)
    {
        return 'AES_ENCRYPT('.$this->db->esc($val).',"'.$this->config->database->enckey.'")';
    }

    /**
     * Decrypt the string
     *
     * @param  string $val decrypted string
     *
     * @return string      Full decrypt string command on SQL format
     */
    public function __decrypt($key)
    {
        return 'AES_DECRYPT('.$key.',"'.$this->config->database->enckey.'")';
    }
}
