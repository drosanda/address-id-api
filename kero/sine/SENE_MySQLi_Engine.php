<?php
/**
 * Engine Class for MySQL / MariaDB database type
 * Contains some query builder that compatible with MySQL or MariaDB query / command.
 *
 * @author: Daeng Rosanda
 * @version 4.0.3
 *
 * @package SemeFramework\Kero\Sine
 * @since 2.0.0
 */
#[AllowDynamicProperties]
class SENE_MySQLi_Engine
{
    protected static $__instance;
    public $__mysqli;
    protected $koneksi;
    protected $fieldname = array();
    protected $fieldvalue = array();
    public $last_id = 0;
    public $in_select = '';
    public $in_where = '';
    public $in_order = '';
    public $in_group = '';
    public $table = '';
    public $is_flush = '';
    public $is_cache = '';
    public $page = '';
    public $pagesize = '';
    public $cache_save = '';
    public $cache_flush = '';
    public $is_limit=0;
    public $tis_limit=0;
    public $limit_a=0;
    public $limit_b=0;
    public $as_from=0;
    public $join=0;
    public $in_join=0;
    public $join_multi=0;
    public $in_join_multi=0;
    public $query_last;
    public $union;
    public $is_debug;

    public function __construct()
    {
        $this->directories = $GLOBALS['SEMEDIR'];
        $this->config = $GLOBALS['SEMECFG'];
        $port = 3306;
        if (isset($this->config->database->port)) {
            if (strlen($this->config->database->port)>0) {
                $port = $this->config->database->port;
            }
        }
        mysqli_report(MYSQLI_REPORT_STRICT);
        $this->__mysqli = new mysqli();
        try {
            $this->__mysqli->connect($this->config->database->host, $this->config->database->user, $this->config->database->pass, $this->config->database->name, $port);
        } catch (mysqli_sql_exception $e) {
            if ($this->config->environment == 'development') {
                trigger_error(TEM_ERR.': Cannot connect to database server using the supplied settings.', E_USER_ERROR);
                throw $e;
            } elseif ($this->config->environment == 'staging' && $this->__mysqli->connect_errno) {
                header("content-type: application/json");
                http_response_code(200);
                $data = array();
                $data['status'] = $this->__mysqli->connect_errno;
                $data['message'] = 'Failed to connect to Database: '.$this->__mysqli->connect_error;
                $data['data'] = array();
                echo json_encode($data);
                return;
            }
        }

        $cs = 'utf8';
        if (isset($this->config->database->charset) && strlen($this->config->database->charset)>0) {
            $cs = $this->config->database->charset;
        }
        $this->__mysqli->set_charset($cs);

        self::$__instance = $this;

        $this->in_select = '';
        $this->in_where = '';
        $this->in_order = '';
        $this->table = '';
        $this->is_flush = 0;
        $this->is_cache = 0;
        $this->page = 0;
        $this->pagesize = 0;
        $this->cache_save = 0;
        $this->cache_flush = 0;
        $this->tis_limit = 0;
        $this->in_join=0;
        $this->in_join_multi=0;
        $this->as_from = array();
        $this->join = array();
        $this->join_multi = array();
        $this->query_last = '';
        $this->is_debug = 1;

        $this->_union_init();
    }

    private function _union_init()
    {
        $this->union = new stdClass();
		$this->union->select = array();
		$this->union->table = array();
		$this->union->from_as = 'u1';
		$this->union->group_by = '';
		$this->union->order_by = array();
		$this->union->limit = '';

        return $this;
    }

    public static function getInstance()
    {
        return self::$_instance;
    }

    private function has_bracket_open($bracket_flag)
    {
        if ($bracket_flag == true || !empty($bracket_flag)) {
            $this->in_where .= " ( ";
        }

        return $this;
    }

    private function has_bracket_close($bracket_flag)
    {
        if ($bracket_flag == true || !empty($bracket_flag)) {
            $this->in_where .= " ) ";
        }

        return $this;
    }

    public function autocommit($var=1)
    {
        return $this->__mysqli->autocommit($var);
    }
    public function begin()
    {
        return $this->__mysqli->begin_transaction();
    }
    public function commit()
    {
        return $this->__mysqli->commit();
    }
    public function rollback()
    {
        return $this->__mysqli->rollback();
    }
    public function savepoint($sp)
    {
        return $this->__mysqli->savepoint($sp);
    }
    public function debug($sql='')
    {
        $this->fieldname[] = 'error';
        $this->fieldname[] = 'code';
        $this->fieldname[] = 'sql';
        $this->fieldvalue[] = $this->__mysqli->errno;
        $this->fieldvalue[] = $this->__mysqli->error;
        $this->fieldvalue[] = $sql;
    }
    public function exec($sql)
    {
        $res = $this->__mysqli->query($sql);
        if ($res) {
            return 1;
        } else {
            if ($this->is_debug) {
                trigger_error(TEM_ERR.': '.$this->__mysqli->error.'. '.$sql, E_USER_NOTICE);
            }
            return 0;
        }
    }
    public function select_as($skey, $sval='')
    {
        if (is_array($skey)) {
            foreach ($skey as $k=>$v) {
                $this->in_select .= ''.$k." '".$v."', ";
            }
        } else {
            $this->in_select .= ''.$skey." AS '".$sval."', ";
        }
        return $this;
    }
    public function query($sql, $cache_enabled=0, $flushcache=0, $type="object")
    {
        if ($cache_enabled) {
            $name = '';
            $names = explode("from", strtolower($sql));
            if (isset($names[1])) {
                $name = trim($names[1]);
                $name = strtr($name, '`', '');
                $names = explode(' ', $name);

                if (isset($names[0])) {
                    $name = $names[0];
                }
                unset($names);
            }
            $cache=md5($sql).".json";
            if (!empty($name)) {
                $cache=$name."-".md5($sql).".json";
            }

            if (isset($GLOBALS['semeflush'])) {
                $flushcache = $GLOBALS['semeflush'];
            }
            if (isset($GLOBALS['semecache'])) {
                $cache_enabled = $GLOBALS['semecache'];
            }
            if ($flushcache) {
                if (file_exists(SENECACHE.'/'.$cache)) {
                    unlink(SENECACHE.'/'.$cache);
                }
            }
            if (file_exists(SENECACHE.'/'.$cache)) {
                $fp = fopen(SENECACHE.'/'.$cache, "r");
                $str = fread($fp, filesize(SENECACHE.'/'.$cache));
                fclose($fp);
                $dataz = json_decode($str);
                return $dataz;
            } else {
                $res = $this->__mysqli->query($sql);
                if ($res) {
                    $dataz=array();
                    if ($type=="array") {
                        while ($data=$res->fetch_array()) {
                            array_push($dataz, $data);
                        }
                    } elseif ($type=="assoc") {
                        while ($data=$res->fetch_assoc()) {
                            array_push($dataz, $data);
                        }
                    } else {
                        while ($data=$res->fetch_object()) {
                            $dataz[] = $data;
                        }
                    }
                    $res->free();
                    $str = json_encode($dataz);
                    $fp = fopen(SENECACHE.'/'.$cache, "w+");
                    fwrite($fp, $str);
                    fclose($fp);
                    return $dataz;
                } else {
                    $this->debug($sql);
                    trigger_error(TEM_ERR.': '.$this->__mysqli->error.'. '.$sql, E_USER_NOTICE);
                    return $this->fieldvalue;
                }
            }
        } else {
            $res = $this->__mysqli->query($sql);
            if ($res) {
                $dataz=array();
                if ($type=="array") {
                    while ($data=$res->fetch_array()) {
                        array_push($dataz, $data);
                    }
                } elseif ($type=="assoc") {
                    while ($data=$res->fetch_assoc()) {
                        array_push($dataz, $data);
                    }
                } else {
                    if (!is_bool($res)) {
                        while ($data=$res->fetch_object()) {
                            $dataz[] = $data;
                        }
                    }
                }
                if (!is_bool($res)) {
                    $res->free();
                }
                return $dataz;
            } else {
                $this->debug($sql);
                trigger_error(TEM_ERR.': '.$this->__mysqli->error.'. '.$sql, E_USER_NOTICE);
                return $this->fieldvalue;
            }
        }
    }

    public function select($sql='', $cache_enabled=0, $flushcache=0, $type="object")
    {
        $exp1 = 0;
        $exp2 = 0;
        if (!is_array($sql)) {
            $exp1 = count(explode("SELECT", $sql));
            $exp2 = count(explode("FROM", $sql));
        }
        if ($exp1>1 && $exp2>1) {
            return $this->query($sql, $cache_enabled, $flushcache, $type);
        } elseif (is_array($sql)) {
            foreach ($sql as $s) {
                if ($s!="*") {
                    $this->in_select .= '`'.$s."`, ";
                } else {
                    $this->in_select .= " * , ";
                }
            }
            return $this;
        } elseif (!empty($sql)) {
            if ($sql!="*") {
                $this->in_select .= '`'.$sql."`, ";
            } else {
                $this->in_select .= ''.$sql.", ";
            }
            return $this;
        } else {
            $this->in_select .= "*";
            return $this;
        }
    }

    public function getStat()
    {
        return array("fieldname"=>$this->fieldname,"fieldvalue"=>$this->fieldvalue);
    }
    public function lastId()
    {
        return $this->__mysqli->insert_id;
    }
    public function esc($var)
    {
        if (is_array($var)) {
        } else {
            if (strtolower($var)=='null') {
                return "NULL";
            } else {
                return '"'.$this->__mysqli->real_escape_string($var).'"';
            }
        }
    }
    public function __destruct()
    {
        if (is_resource($this->__mysqli)) {
            $this->__mysqli->close();
        }
    }
    public function getField()
    {
        return array("field"=>$this->fieldname,"value"=>fieldvalue);
    }

    public function where_null($col_params, $operand="AND", $comp="", $bracket=0, $bracket2=0)
    {
        $this->has_bracket_open($bracket);
        $this->in_where .= ' '.$col_params.' IS NULL ';
        $this->has_bracket_close($bracket2);
        $this->in_where .= ' '.$operand.' ';

        return $this;
    }

    public function where_not_null($col_params, $operand="AND", $comp="", $bracket=0, $bracket2=0)
    {
        $this->has_bracket_open($bracket);
        $this->in_where .= ' '.$col_params.' IS NOT NULL ';
        $this->has_bracket_close($bracket2);
        $this->in_where .= ' '.$operand.' ';

        return $this;
    }

    /*
    * Function Where
    * ==========================================================
    * Params1 -> Bisa Array kalau bukan array, parameter 2 wajib
    * array berupa key value pair
    * kalau string berarti key
    * Params2 -> berupa value, default kosong
    * Params3 -> Operan AND, OR, dsb
    * Params4 -> bisa =, <>, like, notlike,
    *            like%,%like,%like%
    *            bisa juga not like%,%like,%like%
    * -----------------------------------------------------------
    */
    public function where($params, $params2='', $operand="AND", $comp='=', $bracket=0, $bracket2=0)
    {
        $comp = strtolower($comp);
        $c='=';
        $operand = strtoupper($operand);
        if (is_array($params)) {
            $comp = $operand;
            $comp = strtolower($comp);
            $operand = $params2;
            foreach ($params as $k=>$v) {
                switch ($comp) {
                    case "like":
                        $c= "LIKE";
                        $val = $this->esc($val);
                        break;
                    case 'like%':
                        $c= "LIKE";
                        $val = ''.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case '%like':
                        $c= "LIKE";
                        $val = '%'.$v.'';
                        $val = $this->esc($val);
                        break;
                    case 'like%%':
                        $c= "LIKE";
                        $val = '%'.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case '%like%':
                        $c= "LIKE";
                        $val = '%'.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case "notlike":
                        $c= "NOT LIKE";
                        $val = $this->esc($val);
                        break;
                    case "notlike%%":
                        $c= "NOT LIKE";
                        $val = '%'.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case "%notlike%":
                        $c= "NOT LIKE";
                        $val = '%'.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case "notlike%":
                        $c= "NOT LIKE";
                        $val = ''.$v.'%';
                        $val = $this->esc($val);
                        break;
                    case "%notlike":
                        $c= "NOT LIKE";
                        $val = '%'.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '!=':
                        $c= '<>';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '<>':
                        $c= '<>';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '>=':
                        $c= '>=';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '<=':
                        $c= '<=';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '>':
                        $c= '>';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    case '<':
                        $c= '<';
                        $val = ''.$v.'';
                        $val = $this->esc($val);
                        break;
                    default:
                        $c = '=';
                        $val = $this->esc($v);
                }

                $this->has_bracket_open($bracket);

                $kst = explode(".", $k);
                if (count($kst)) {
                    $kst = explode(".", $k);
                    foreach ($kst as $ks) {
                        $this->in_where .= '`'.$ks."`.";
                    }
                    $this->in_where = rtrim($this->in_where, ".");
                } else {
                    $this->in_where .= '`'.$k.'`';
                    unset($kst);
                }
                $this->in_where .= ' '.$c.' '.$val.' ';
                $this->has_bracket_close($bracket2);
                $this->in_where .= ' '.strtoupper($operand).' ';
            }
            unset($c);
            unset($v);
            unset($k);
            unset($val);
        } elseif (!empty($params) && !empty($params2)) {
            $val = $params2;
            $v = $params2;

            $this->has_bracket_open($bracket);

            $kst = explode(".", $params);
            if (count($kst)) {
                $kst = explode(".", $params);
                foreach ($kst as $ks) {
                    $this->in_where .= '`'.$ks."`.";
                }
                $this->in_where = rtrim($this->in_where, ".");
            } else {
                $this->in_where .= '`'.$params.'`';
            }
            unset($kst);


            switch ($comp) {
                case "like":
                    $c = "LIKE";
                    $val = $this->esc($val);
                    break;
                case 'like%':
                    $c= "LIKE";
                    $val = ''.$v.'%';
                    $val = $this->esc($val);
                    break;
                case '%like':
                    $c= "LIKE";
                    $val = '%'.$v.'';
                    $val = $this->esc($val);
                    break;
                case 'like%%':
                    $c= "LIKE";
                    $val = '%'.$v.'%';
                    $val = $this->esc($val);
                    break;
                case "%like%":
                    $c= "LIKE";
                    $val = '%'.$v.'%';
                    $val = $this->esc($val);
                    break;
                case "notlike":
                    $c= "NOT LIKE";
                    $val = $this->esc($val);
                    break;
                case "notlike%%":
                    $c= "NOT LIKE";
                    $val = '%'.$v.'%';
                    $val = $this->esc($val);
                    break;
                case "%notlike%":
                    $c= "NOT LIKE";
                    $val = '%'.$v.'%';
                    $val = $this->esc($val);
                    break;
                case "notlike%":
                    $c= "NOT LIKE";
                    $val = ''.$v.'%';
                    $val = $this->esc($val);
                    break;
                case "%notlike":
                    $c= "NOT LIKE";
                    $val = '%'.$v.'';
                    $val = $this->esc($val);
                    break;
                case '!=':
                    $c= '<>';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                case '<>':
                    $c= '<>';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                case '>=':
                    $c= '>=';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                case '<=':
                    $c= '<=';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                case '>':
                    $c= '>';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                case '<':
                    $c= '<';
                    $val = ''.$v.'';
                    $val = $this->esc($val);
                    break;
                default:
                    if (strtoupper(trim($v)) == "IS NULL" || strtoupper(trim($v)) == "IS NOT NULL" || strtoupper(trim($v)) == "IS NOT NULL") {
                        $c = '';
                        $val = strtoupper($v);
                    } else {
                        $c = '=';
                        $val = $this->esc($v);
                    }
            }

            $this->in_where .= ' '.$c.' '.$val.' ';
            $this->has_bracket_close($bracket2);
            $this->in_where .= ' '.$operand.' ';
            unset($c);
            unset($v);
            unset($k);
            unset($val);
        }
        return $this;
    }
    public function where_as($params, $params2='', $operand="AND", $comp='=', $bracket=0, $bracket2=0)
    {
        $comp = strtolower($comp);
        $c='=';
        $operand = strtoupper($operand);
        if (is_array($params)) {
            $comp = $operand;
            $comp = strtolower($comp);
            $operand = $params2;
            foreach ($params as $k=>$v) {
                switch ($comp) {
                    case "like":
                        $c= "LIKE";
                        $val = ($val);
                        break;
                    case 'like%':
                        $c= "LIKE";
                        $val = '\''.$v.'%\'';
                        $val = ($val);
                        break;
                    case '%like':
                        $c= "LIKE";
                        $val = '\'%'.$v.'\'';
                        $val = ($val);
                        break;
                    case 'like%%':
                        $c= "LIKE";
                        $val = '\'%'.$v.'%\'';
                        $val = ($val);
                        break;
                    case '%like%':
                        $c= "LIKE";
                        $val = '\'%'.$v.'%\'';
                        $val = ($val);
                        break;
                    case "notlike":
                        $c= "NOT LIKE";
                        $val = ($val);
                        break;
                    case "notlike%%":
                        $c= "NOT LIKE";
                        $val = '\'%'.$v.'%\'';
                        $val = ($val);
                        break;
                    case "%notlike%":
                        $c= "NOT LIKE";
                        $val = '\'%'.$v.'%\'';
                        $val = ($val);
                        break;
                    case "notlike%":
                        $c= "NOT LIKE";
                        $val = '\''.$v.'%\'';
                        $val = ($val);
                        break;
                    case "%notlike":
                        $c= "NOT LIKE";
                        $val = '\'%'.$v.'\'';
                        $val = ($val);
                        break;
                    case '!=':
                        $c= '<>';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    case '<>':
                        $c= '<>';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    case '>=':
                        $c= '>=';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    case '<=':
                        $c= '<=';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    case '>':
                        $c= '>';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    case '<':
                        $c= '<';
                        $val = ''.$v.'';
                        $val = ($val);
                        break;
                    default:
                        $c = '=';
                        $val = ($v);
                }

                $this->has_bracket_open($bracket);
                $this->in_where .= ''.$k.'';
                unset($kst);

                $this->in_where .= ' '.$c.' '.$val.'';
                $this->has_bracket_close($bracket2);
                $this->in_where .= ' '.strtoupper($operand).' ';
            }
            unset($c);
            unset($v);
            unset($k);
            unset($val);
        } elseif (!empty($params) && !empty($params2)) {
            $val = $params2;
            $v = $params2;

            $this->has_bracket_open($bracket);

            $kst = explode(".", $params);
            if (count($kst)) {
                $kst = explode(".", $params);
                foreach ($kst as $ks) {
                    $this->in_where .= ''.$ks.".";
                }
                $this->in_where = rtrim($this->in_where, ".");
            } else {
                $this->in_where .= ''.$params.'';
                unset($kst);
            }


            switch ($comp) {
                case "like":
                    $c = "LIKE";
                    $val = ($val);
                    break;
                case 'like%':
                    $c= "LIKE";
                    $val = "\'".$v.'%\'';
                    $val = ($val);
                    break;
                case '%like':
                    $c= "LIKE";
                    $val = '\'%'.$v.'\'';
                    $val = ($val);
                    break;
                case 'like%%':
                    $c= "LIKE";
                    $val = '\'%'.$v.'%\'';
                    $val = ($val);
                    break;
                case "%like%":
                    $c= "LIKE";
                    $val = '\'%'.$v.'%\'';
                    $val = ($val);
                    break;
                case "notlike":
                    $c= "NOT LIKE";
                    $val = ($val);
                    break;
                case "notlike%%":
                    $c= "NOT LIKE";
                    $val = '\'%'.$v.'%\'';
                    $val = ($val);
                    break;
                case "%notlike%":
                    $c= "NOT LIKE";
                    $val = '\'%'.$v.'%\'';
                    $val = ($val);
                    break;
                case "notlike%":
                    $c= "NOT LIKE";
                    $val = '\''.$v.'%\'';
                    $val = ($val);
                    break;
                case "%notlike":
                    $c= "NOT LIKE";
                    $val = '\'%'.$v.'\'';
                    $val = ($val);
                    break;
                case '!=':
                    $c= '<>';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '<>':
                    $c= '<>';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '>':
                    $c= '>';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '>=':
                    $c= '>=';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '<':
                    $c= '<';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '<=':
                    $c= '<=';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '>':
                    $c= '>';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                case '<':
                    $c= '<';
                    $val = ''.$v.'';
                    $val = ($val);
                    break;
                default:
                    if (strtoupper(trim($v)) == "IS NULL" || strtoupper(trim($v)) == "IS NOT NULL" || strtoupper(trim($v)) == "IS NOT NULL") {
                        $c = '';
                        $val = strtoupper($v);
                    } else {
                        $c = '=';
                        $val = $v;
                    }
            }
            $this->in_where .= ' '.$c.' '.$val.' ';
            $this->has_bracket_close($bracket2);
            $this->in_where .=  ' '.$operand.' ';

            $this->in_where = trim($this->in_where, '=');

            unset($c);
            unset($v);
            unset($k);
            unset($val);
        }
        return $this;
    }
    public function order_by($params, $params2="ASC")
    {
        if (is_array($params)) {
            foreach ($params as $k=>$v) {
                $this->in_order .= $k.' '.strtoupper($v).", ";
            }
        } elseif (!empty($params) && !empty($params2)) {
            $this->in_order .= $params.' '.strtoupper($params2).", ";
        }
        return $this;
    }
    public function from($table, $as='')
    {
        if (empty($table)) {
            trigger_error(TEM_ERR.': table name required', E_USER_ERROR);
            die();
        }
        if (!empty($as)) {
            $as = strtolower($as);
            if (isset($this->as_from[$as])) {
                if ($this->as_from[$as] != $table) {
                    trigger_error(TEM_ERR.': Table alias "'.$as.'" for "'.$this->as_from[$as].'" has been used, please change!');
                    foreach ($this->as_from as $k=>$v) {
                        trigger_error($k.': '.$v);
                    }
                    die();
                }
            } else {
                $this->as_from[$as] = $table;
            }
        }
        $this->table = $table;
        return $this;
    }
    public function setTableAlias($as)
    {
        if (empty($as)) {
            trigger_error(TEM_ERR.': table alias required.', E_USER_WARNING);
        }
        $this->as_from[$as] = $this->table;
        return $this;
    }
    public function cache_save($cache_save=1)
    {
        $this->cache_save = $cache_save;
        return $this;
    }
    public function cache_flush($cache_flush=1)
    {
        $this->cache_flush = $cache_flush;
        return $this;
    }
    public function pagesize($pagesize)
    {
        $this->tis_limit++;
        $this->is_limit=0;
        $this->pagesize = (int) $pagesize;
        return $this;
    }
    public function nolimit()
    {
        $this->tis_limit = 0;
        $this->is_limit = 0;
        $this->pagesize = 0;
        $this->limit_a = 0;
        $this->limit_b = 0;
        return $this;
    }

  	/**
  	 * Set current limit offset
  	 * @param  int    $a    Offset / Row Count, value range integer >= 0
  	 * @param  int    $b  	Row count, value range integer >= 0
  	 * @return object       return this class
  	 */
  	public function limit($a,$b=''){
  		$this->is_limit=1;
  		$a = (int) $a;
  		$b = (int) $b;
  		if($a > 0 && $b <= 0){
  			$b = $a;
  			$a = 0;
  		}
  		$this->limit_a = $a;
  		$this->limit_b = $b;
  		return $this;
  	}

  	/**
  	 * Set current page and page size
  	 * @param  int    $page       Current page number, value range integer >= 0
  	 * @param  int    $page_size  Page size number, value range integer >= 0
  	 * @return object             return this class
  	 */
  	public function page($page,$page_size=''){
  		$page = (int) $page;
  		$page_size = (int) $page_size;
  		if($page_size > 0 && $page <= 0){
  			$this->is_limit = 1;
  			$this->limit_a  = 0;
  			$this->limit_b  = $page_size;
  		}else if($page_size <= 0 && $page > 0){
  			$this->is_limit = 1;
  			$this->limit_a = 0;
  			$this->limit_b = $page;
  		}else if($page_size > 0 && $page > 0){
  			$this->is_limit = 1;
  			$this->limit_a = ($page * $page_size) - $page_size;
  			if($page == 1) $this->limit_a = ($page * $page_size) - $page_size;
  			$this->limit_b = $page_size;
  		}
  		return $this;
  	}

    public function limitpage($page, $pagesize=10)
    {
        $this->is_limit = 0;
        $this->page = $page;
        $this->pagesize = $pagesize;
        return $this;
    }
    public function get($tipe="object", $is_debug='')
    {
        $this->in_select = rtrim($this->in_select, ", ");
        if (empty($this->in_select)) {
            $this->in_select = "*";
        }
        $sql = 'SELECT '.$this->in_select.' FROM `'.$this->table.'`';

        if (count($this->join) > 0) {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                $sql .= ' '.$table_alias.' ';
                foreach ($this->join as $j) {
                    $sql .= strtoupper($j->method).' JOIN '.$j->table.' ON ';
                    foreach ($j->on as $o) {
                        $sql .= '('.$o.') ';
                    }
                }
            } else {
                trigger_error(TEM_ERR.': Please use alias for main table first, you can set alias using $this->db->setTableAlias("YOURALIAS") OR $this->db->from("tabelname","tablealias");', E_USER_WARNING);
                die();
            }
        } else {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                $sql .= ' '.$table_alias.' ';
            }
        }

        if (count($this->join_multi) > 0) {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                foreach ($this->join_multi as $j) {
                    $sql .= strtoupper($j->method).' JOIN ';
                    $sql .= '`'.$j->table.'` '.$j->table_as.' ON '.$j->on.' ';
                }
            } else {
                trigger_error(TEM_ERR.': Please use alias for main table first in JOIN MULTI, you can set alias using $this->db->setTableAlias("YOURALIAS") OR $this->db->from("tabelname","tablealias");', E_USER_WARNING);
                die();
            }
        }

        if (!empty($this->in_where)) {
            $this->in_where = rtrim($this->in_where, "AND ");
            $this->in_where = rtrim($this->in_where, "OR ");
            $sql .= ' WHERE '.$this->in_where;
        }

        if (!empty($this->in_group)) {
            $this->in_group = rtrim($this->in_group, ", ");
            $sql .= $this->in_group;
        }

        if (!empty($this->in_order)) {
            $this->in_order = rtrim($this->in_order, ", ");
            $sql .= " ORDER BY ".$this->in_order;
        }

        if ($this->is_limit) {
            $a = $this->limit_a;
            $b = $this->limit_b;
            $sql .= ' LIMIT '.$this->limit_a.", ".$this->limit_b;
        } else {
          if($this->page<=1){
            if($this->pagesize > 0) $sql .= " LIMIT ".$this->pagesize;
          }else{
            $sql .= " LIMIT ".$this->page.", ".$this->pagesize;
          }
        }

        $cache_save = 0;
        if (!empty($this->cache_save)) {
            $cache_save = $this->cache_save;
        }

        $cache_flush = 0;
        if (!empty($this->cache_flush)) {
            $cache_flush = $this->cache_flush;
        }
        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->query($sql, $cache_save, $cache_flush, $tipe);
        $this->flushQuery();
        return $res;
    }

    public function get_first($tipe="object", $is_debug='')
    {
        $this->in_select = rtrim($this->in_select, ", ");
        if (empty($this->in_select)) {
            $this->in_select = "*";
        }
        $sql = 'SELECT '.$this->in_select.' FROM `'.$this->table.'`';

        if (count($this->join) > 0) {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                $sql .= ' '.$table_alias.' ';
                foreach ($this->join as $j) {
                    $sql .= strtoupper($j->method).' JOIN '.$j->table.' ON ';
                    foreach ($j->on as $o) {
                        $sql .= '('.$o.') ';
                    }
                }
            } else {
                trigger_error(TEM_ERR.': Please use alias for main table first, you can set alias using $this->db->setTableAlias("YOURALIAS") OR $this->db->from("tabelname","tablealias");', E_USER_WARNING);
                die();
            }
        } else {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                $sql .= ' '.$table_alias.' ';
            }
        }

        if (count($this->join_multi) > 0) {
            $table_alias = array_search($this->table, $this->as_from);
            if ($table_alias !== 0) {
                foreach ($this->join_multi as $j) {
                    $sql .= strtoupper($j->method).' JOIN ';
                    $sql .= '`'.$j->table.'` '.$j->table_as.' ON '.$j->on.' ';
                }
            } else {
                trigger_error(TEM_ERR.': Please use alias for main table first in JOIN MULTI, you can set alias using $this->db->setTableAlias("YOURALIAS") OR $this->db->from("tabelname","tablealias");', E_USER_WARNING);
                die();
            }
        }

        if (!empty($this->in_where)) {
            $this->in_where = rtrim($this->in_where, "AND ");
            $sql .= ' WHERE '.$this->in_where;
        }
        $sql = rtrim($sql, ' ');
        $sql = rtrim($sql, ' AND');
        $sql = rtrim($sql, ' OR');
        $sql = $sql.' ';

        if (!empty($this->in_group)) {
            $this->in_group = rtrim($this->in_group, ", ");
            $sql .= $this->in_group;
        }

        if (!empty($this->in_order)) {
            $this->in_order = rtrim($this->in_order, ", ");
            $sql .= " ORDER BY ".$this->in_order;
        }

        $b = 1;
        $a = 0;
        $sql .= ' LIMIT '.$a.", ".$b;

        $cache_save = 0;
        if (!empty($this->cache_save)) {
            $cache_save = $this->cache_save;
        }

        $cache_flush = 0;
        if (!empty($this->cache_flush)) {
            $cache_flush = $this->cache_flush;
        }
        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->query($sql, $cache_save, $cache_flush, $tipe);
        $this->flushQuery();
        if (isset($res[0])) {
            return $res[0];
        }
        return new stdClass();
    }
    public function flushQuery()
    {
        $this->in_select = '';
        $this->in_where = '';
        $this->in_order = '';
        $this->in_group = '';
        $this->pagesize = 0;
        $this->page = 0;
        $this->is_limit = 0;
        $this->limit_a = 0;
        $this->limit_b = 0;
        $this->tis_limit = 0;
        $this->as_from = array();
        $this->join = array();
        $this->in_join = 0;
        $this->join_multi = array();
        $this->in_join_multi = 0;
        return $this;
    }
    public function query_multi($sql)
    {
        $this->__mysqli->multi_query($sql);
        if ($this->__mysqli->errno) {
            trigger_error($this->__mysqli->error);
        }
    }
    public function insert_batch($table, $datas=array(), $is_debug=0)
    {
        $this->insert_multi($table, $datas, $is_debug);
    }
    public function insert_multi($table, $datas=array(), $is_debug=0)
    {
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }
        $sql = 'INSERT INTO `'.$table.'`'.' (';

        foreach ($datas as $data) {
            if (!is_array($data)) {
                trigger_error(TEM_ERR.' Only accepted array!');
                die();
            }
            foreach ($data as $key=>$val) {
                $sql .=''.$key.',';
            }
            break;
        }
        $sql = rtrim($sql, ',');
        $sql .= ') VALUES(';

        foreach ($datas as $ds) {
            foreach ($ds as $key=>$val) {
                if (strtolower($val)=='now()' || strtolower($val)=='null') {
                    $sql .=''.$val.',';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            $sql = rtrim($sql, ',');
            $sql .= '),(';
        }
        $sql = rtrim($sql, ',(');
        $sql .= ';';

        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->exec($sql);
        $this->flushQuery();
        return $res;
    }
    public function insert_ignore_multi($table, $datas=array(), $is_debug=0)
    {
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }
        $sql = 'INSERT IGNORE INTO `'.$table.'`'.'(';

        foreach ($datas as $data) {
            if (!is_array($data)) {
                trigger_error(TEM_ERR.': Only accepted array!');
                die();
            }
            foreach ($data as $key=>$val) {
                $sql .='`'.$key."`,";
            }
            break;
        }
        $sql = rtrim($sql, ',');
        $sql .= ') VALUES'.'(';

        foreach ($datas as $ds) {
            foreach ($ds as $key=>$val) {
                if (strtolower($val)=='now()' || strtolower($val)=='null') {
                    $sql .=''.$val.',';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            $sql = rtrim($sql, ',');
            $sql .= '),(';
        }
        $sql = rtrim($sql, ',(');
        $sql .= ';';

        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->exec($sql);
        $this->flushQuery();
        return $res;
    }
    public function insert($table, $datas=array(), $multi_array=0, $is_debug=0)
    {
        $this->last_id = 0;
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }
        if ($multi_array) {
            $this->insert_multi($table, $datas, $is_debug);
        } else {
            $sql = 'INSERT INTO `'.$table.'`'.'(';

            foreach ($datas as $key=>$val) {
                $sql .='`'.$key."`,";
            }
            $sql  = rtrim($sql, ',');
            $sql .= ') VALUES(';

            foreach ($datas as $key=>$val) {
                if ($val=='NOW()' || $val=='now()') {
                    $sql .=''.$val.',';
                } elseif (strtolower($val)=='null') {
                    $sql .='NULL,';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            $sql = rtrim($sql, ',');
            $sql .= ');';

            if ($is_debug) {
                http_response_code(500);
                die($sql);
            }
            $res = $this->exec($sql);
            $this->last_id = $this->lastId();
            $this->flushQuery();
            return $res;
        }
    }
    public function update($table, $datas=array(), $is_debug=0)
    {
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }

        $sql = "UPDATE `".$table."` SET ";
        foreach ($datas as $key=>$val) {
            if ($val=='now()' || $val=='NOW()' || $val=="NULL" || $val=='null') {
                $sql .='`'.$key.'` = '.$val.',';
            } else {
                $sql .='`'.$key.'` = '.$this->esc($val).',';
            }
        }
        $sql = rtrim($sql, ',');

        if (!empty($this->in_where)) {
            $this->in_where = rtrim($this->in_where, "AND ");
            $this->in_where = rtrim($this->in_where, "OR ");
            $sql .= ' WHERE '.$this->in_where;
        }

        if (!empty($this->pagesize) && ($this->tis_limit>0)) {
            $b = $this->pagesize;
            $sql .= ' LIMIT '.$b;
        }

        $this->query_last = $sql;
        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->exec($sql);
        $this->flushQuery();
        return $res;
    }
    public function delete($table, $is_debug=0)
    {
        if (empty($table)) {
            trigger_error(TEM_ERR.': Missing table name while deleting');
            die();
        }

        $sql = 'DELETE FROM `'.$table.'`';

        if (!empty($this->in_where)) {
            $this->in_where = rtrim($this->in_where, "AND ");
            $this->in_where = rtrim($this->in_where, "OR ");
            $sql .= ' WHERE '.$this->in_where;
        }
        if (!empty($this->pagesize) && ($this->tis_limit>0)) {
            $b = $this->pagesize;
            $sql .= ' LIMIT '.$b;
        }

        $this->query_last = $sql;
        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->exec($sql);
        $this->flushQuery();
        return $res;
    }

    public function join($table, $table_as, $table_key, $reff_as, $reff_key, $method='left')
    {
        $join = new stdClass();
        $join->method = strtoupper(trim($method));
        $join->table = '`'.$table.'` '.$table_as;
        $join->on = array($table_as.'.`'.$table_key.'` = '.$reff_as.'.`'.$reff_key.'`');
        $this->join[] = $join;
        $this->in_join = $this->in_join+1;
        $this->as_from[$table_as] = $table;
        return $this;
    }

    public function join_as($tbl, $reff, $method='left')
    {
        $join = new stdClass();
        $join->method = strtoupper(trim($method));
        $join->table = $tbl;
        $join->on = array($reff);
        $this->join[] = $join;
        $this->in_join = $this->in_join+1;
        return $this;
    }

    public function composite_create($key1, $operator, $key2, $method="AND", $bracket_open=0, $bracket_close=0)
    {
        $composite = new stdClass();
        $composite->key1 = $key1;
        $composite->key2 = $key2;
        $composite->method = $method;
        $composite->operator = $operator;
        $composite->bracket_open = $bracket_open;
        $composite->bracket_close = $bracket_close;
        return $composite;
    }
    public function join_composite($table, $table_alias, $composites=array(), $method='')
    {
        $method = strtoupper($method);
        switch ($method) {
            case 'INNER':
                $method = 'INNER';
                break;
            case 'OUTER':
                $method = 'OUTER';
                break;
            case 'LEFT':
                $method = 'LEFT';
                break;
            case 'RIGHT':
                $method = 'RIGHT';
                break;
            default:
                $method='';
                break;
        }
        //set table alias
        $this->as_from[$table_alias] = $table;

        //building new join class
        $join_composite = new stdClass();
        $join_composite->method = $method;
        $join_composite->table = $table;
        $join_composite->table_as = $table_alias;
        $join_composite->on = '';

        //building composite
        if (!is_array($composites)) {
            trigger_error(TEM_ERR.': JOIN_COMPOSITE the composites parameter must be array!');
        }
        $composites_count = count($composites);
        $composite_i = 0;
        foreach ($composites as $comp) {
            $composite_i++;
            if (isset($comp->bracket_open)) {
                if (!empty($comp->bracket_open)) {
                    $join_composite->on .= '(';
                }
            }
            if (isset($comp->key1)) {
                $join_composite->on .= $comp->key1;
            }
            if (isset($comp->operator)) {
                $join_composite->on .= " $comp->operator ";
            }
            if (isset($comp->key2)) {
                $join_composite->on .= $comp->key2;
            }
            if ($composite_i < $composites_count) {
                if (isset($comp->method)) {
                    $join_composite->on .= ' '.strtoupper($comp->method).' ';
                }
            }
            if (isset($comp->bracket_close)) {
                if (!empty($comp->bracket_close)) {
                    $join_composite->on .= ')';
                }
            }
        }

        //insert to global var
        $this->join_multi[$this->in_join_multi] = $join_composite;
        $this->in_join_multi = $this->in_join_multi+1;
        return $this;
    }
    public function between($key, $val1, $val2, $is_not=0)
    {
        $this->in_where .= '(';
        $this->in_where .= ' '.$key.'';
        if ($is_not) {
            $this->in_where .= " NOT ";
        }
        $this->in_where .= " BETWEEN ".$val1." AND ".$val2.'';
        $this->in_where .= ") AND ";
        return $this;
    }
    public function group_by($params)
    {
        if (is_array($params)) {
            foreach ($params as $k=>$v) {
                $this->in_group .= " GROUP BY ".$v.", ";
            }
        } else {
            $this->in_group .= " GROUP BY ".$params.", ";
        }
        return $this;
    }

    public function replace($table, $datas=array(), $multi_array=0, $is_debug=0)
    {
        $this->last_id = 0;
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }
        if ($multi_array) {
            $this->replace_multi($table, $datas, $is_debug);
        } else {
            $sql = 'REPLACE INTO `'.$table.'`'.' (';

            foreach ($datas as $key=>$val) {
                $sql .='`'.$key."`,";
            }
            $sql  = rtrim($sql, ',');
            $sql .= ') VALUES(';

            foreach ($datas as $key=>$val) {
                if ($val=='NOW()' || $val=='now()') {
                    $sql .=''.$val.',';
                } elseif (strtolower($val)=='null') {
                    $sql .='NULL,';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            $sql = rtrim($sql, ',');
            $sql .= ');';

            if ($is_debug) {
                http_response_code(500);
                die($sql);
            }
            $res = $this->exec($sql);
            $this->last_id = $this->lastId();
            $this->flushQuery();
            return $res;
        }
    }
    public function replace_multi($table, $datas=array(), $is_debug=0)
    {
        if (!is_array($datas)) {
            trigger_error(TEM_ERR.': Only accepted array!');
            die();
        }
        $sql = 'REPLACE INTO `'.$table.'`'.' (';

        foreach ($datas as $data) {
            if (!is_array($data)) {
                trigger_error(TEM_ERR.': Only accepted array!');
                die();
            }
            foreach ($data as $key=>$val) {
                if (strtolower($val)=='now()' || strtolower($val)=='null') {
                    $sql .=''.$val.',';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            break;
        }
        $sql = rtrim($sql, ',');
        $sql .= ') VALUES(';

        foreach ($datas as $ds) {
            foreach ($ds as $key=>$val) {
                if (strtolower($val)=='now()' || strtolower($val)=='null') {
                    $sql .=''.$val.',';
                } else {
                    $sql .=''.$this->esc($val).',';
                }
            }
            $sql = rtrim($sql, ',');
            $sql .= '),(';
        }
        $sql = rtrim($sql, ',(');
        $sql .= ';';

        if ($is_debug) {
            http_response_code(500);
            die($sql);
        }
        $res = $this->exec($sql);
        $this->flushQuery();
        return $res;
    }

    /**
     * Filter data by WHERE IN
     * @param  string  $tbl_key     column name
     * @param  array   $values      plain array
     * @param  int     $is_not      flag is not select (1|0)
     * @param  string  $after       operand (AND|OR)
     * @return object               this object model
     */
    public function where_in($tbl_key, $values, $is_not=0, $after="AND")
    {
        $not = '';
        if ($is_not == '1' || $is_not == 1) {
            $not = 'NOT';
        }
        $this->in_where .= ' '.$tbl_key.' '.$not.' IN (';
        foreach ($values as $v) {
            $this->in_where .= $this->esc($v).", ";
        }
        $this->in_where = rtrim($this->in_where, ", ");
        $this->in_where .= ') '.$after.' ';
        return $this;
    }

    public function getCharSet()
    {
        $res = $this->__mysqli->character_set_name();
        if (!$res) {
            trigger_error(TEM_ERR.': Cannot get charset from database.');
        }
        return $res;
    }
    public function setCharSet($char_set)
    {
        $res = $this->__mysqli->set_charset($char_set);
        if (!$res) {
            trigger_error(TEM_ERR.': Cant change charset from '.$this->__mysqli->character_set_name().' to '.$char_set.' to database.');
        }
        return 1;
    }
    public function getLastQuery()
    {
        return $this->query_last;
    }

    /**
     * Set debug flag for query command
     *
     * @param boolean $is_debug     (1|0)
     */
    public function setDebug($is_debug)
    {
        if (!empty($is_debug)) {
            $this->is_debug = 1;
        } else {
            $this->is_debug = 0;
        }
        return $this;
    }

    /**
     * Encrypt the string
     * @param  string $s plain string
     * @return string      encrypt command
     */
    public function __encrypt($s)
    {
        return 'AES_ENCRYPT('.$this->db->esc($s).',"'.$this->config->database->enckey.'")';
    }

    /**
     * Decrypt the string
     *
     * @param  string $s decrypted string
     *
     * @return string      decrypt command
     */
    public function __decrypt($s)
    {
        return 'AES_DECRYPT('.$s.',"'.$this->config->database->enckey.'")';
    }



	/**
	 * Created Union Query table from query builder (last query)
	 * @return boolean $flush 		0 not flush, 1 flush
	 * @return string  Query
	 */
	public function union_create($flush=1){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->table)) $this->union->table = array();
		if(!is_array($this->union->table)) $this->union->table = array();

		$this->in_select = rtrim($this->in_select,", ");
		if(empty($this->in_select)) $this->in_select = "*";
		$sql = "SELECT ".$this->in_select." FROM `".$this->table."`";

		if (count($this->join) > 0) {
      $table_alias = array_search($this->table, $this->as_from);
      if ($table_alias !== 0) {
          $sql .= ' '.$table_alias.' ';
          foreach ($this->join as $j) {
              $sql .= strtoupper($j->method).' JOIN '.$j->table.' ON ';
              foreach($j->on as $o){
                $sql .= '('.$o.') ';
              }
          }
      } else {
          trigger_error('Please use alias for main table first, you can set alias using $this->db->setTableAlias("YOURALIAS") OR $this->db->from("tabelname","tablealias");');
          die();
      }
    } else {
        $table_alias = array_search($this->table, $this->as_from);
        if ($table_alias !== 0) {
            $sql .= ' '.$table_alias.' ';
        }
    }

		if(!empty($this->in_where)){
			$this->in_where = rtrim($this->in_where,"AND ");
			$this->in_where = rtrim($this->in_where,"OR ");
			$sql .= " WHERE ".$this->in_where;
		}
		if(!empty($flush)) $this->flushQuery();
		$this->union->table[] = $sql;
		return $this;
	}

	/**
	 * Add select column for union method
	 *
	 * @param  string $k 	Column name
	 * @param  string $a 	Column Alias string
	 *
	 * @return object    	this object
	 */
	public function union_select($k,$a=''){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->select)) $this->union->select = array();
		if(!is_array($this->union->select)) $this->union->select = array();
		if(strlen($a)==0){
			$a = $k;
		}
		$this->union->select[$a] = $k;
		return $this;
	}

	/**
	 * Set alias table for union method
	 *
	 * @param  string $a 	Alias string
	 *
	 * @return object    	this object
	 */
	public function union_alias($a){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->from_as)) $this->union->from_as = '';
		if(!is_string($this->union->from_as)) $this->union->from_as = '';
		if(strlen($a)==0){
			trigger_error('Empty union_alias parameter');
			die();
		}
		$this->union->from_as = $a;
		return $this;
	}

	/**
	 * Set group by criteria for union method
	 *
	 * @param  string $g 	Group by string
	 *
	 * @return object    	this object
	 */
	public function union_group_by($g){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->group_by)) $this->union->group_by = '';
		if(!is_string($this->union->group_by)) $this->union->group_by = '';
		if(strlen($g)==0){
			trigger_error('Empty union_grup_by parameter');
			die();
		}
		$this->union->group_by = $g;
		return $this;
	}

	/**
	 * Add order by criteria for union method
	 *
	 * @param  string $c 	sort by column name
	 * @param  string $d 	sort direction
	 *
	 * @return object    	this object
	 */
	public function union_order_by($c,$d){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->order_by)) $this->union->order_by = array();
		if(!is_array($this->union->order_by)) $this->union->order_by = array();
		if(strlen($c)==0){
			trigger_error('Empty union_grup_by parameter');
			die();
		}
		$d = strtoupper($d);
		if(strlen($d)==0){
			$d = 'ASC';
		}
		$this->union->order_by[] = $c.' '.$d;
		return $this;
	}

	/**
	 * Add limit query result for union
	 *
	 * @param  string $a 	sort by column name
	 * @param  string $b 	sort direction
	 *
	 * @return object    	this object
	 */
	public function union_limit($a='',$b=''){
		if(!isset($this->union)) $this->union = new stdClass();
		if(!isset($this->union->order_by)) $this->union->order_by = array();
		if(!is_array($this->union->order_by)) $this->union->order_by = array();

		$a = (int) $a;
		$b = (int) $b;
		if($a<=0) $a = '';
		if($b<=0) $b = '';
		if(strlen($a) && strlen($b)){
			$this->union->limit = $a.', '.$b;
		}elseif(strlen($a) && strlen($b)==0){
			$this->union->limit = $a;
		}elseif(strlen($a)==0 && strlen($b)){
			$this->union->limit = $b;
		}else{
			$this->union->limit = '';
		}

		return $this;
	}

	/**
	 * Get result Executed union query
	 *
	 * @return object this object
	 */
	public function union_get($is_debug=0){
		if(!isset($this->union->select)){
			trigger_error('Missing union.select object on union_get');
			die();
		}
		if(!isset($this->union->from_as)){
			trigger_error('Missing union.from_as object on union_get');
			die();
		}
		if(!isset($this->union->table)){
			trigger_error('Missing union.table object on union_get');
			die();
		}
		if(!is_array($this->union->table)){
			trigger_error('Invalid type union.table object, is not an array');
			die();
		}
		if(count($this->union->table)==0){
			trigger_error('Empty union table');
			die();
		}
		if(!is_string($this->union->group_by)){
			trigger_error('Invalid type union.group_by object, is not a string');
			die();
		}
		if(!is_array($this->union->order_by)){
			trigger_error('Invalid type union.order_by object, is not an array');
			die();
		}
		$sql = 'SELECT ';
		if(count($this->union->select)){
			foreach($this->union->select as $k=>$v){
				if(!is_numeric($k) && $k != $v){
					$sql .= ' '.$v.' AS '.$k.' ';
				}else{
					$sql .= ' '.$v.' ';
				}
			}
		}else{
			$sql .= ' * ';
		}
		$sql .= ' FROM ( ';
		foreach($this->union->table as $k=>$v){
			$sql .= $v.' UNION ';
		}
		$sql = chop($sql, ' UNION ');
		$sql .= ' ) AS '.$this->union->from_as.' ';
		if(strlen($this->union->group_by)){
			$sql .= ' GROUP BY '.$this->union->group_by;
		}
		if(count($this->union->order_by)){
			$sql .= ' ORDER BY ';
			foreach($this->union->order_by as $k=>$v){
				$sql .= ''.$v.', ';
			}
			$sql = rtrim($sql,', ');
		}
		if(strlen($this->union->limit)){
			$sql .= ' LIMIT '.$this->union->limit;
		}
		if($is_debug){
			die($sql);
		}
		return $this->query($sql);
	}

	/**
	 * Reset union object to its default value
	 *
	 * @return object this object
	 */
	public function union_flush(){
		$this->union = new stdClass();
		$this->union->select = array();
		$this->union->from_as = 'u1';
		$this->union->table = array();
		$this->union->group_by = '';
		$this->union->order_by = array();
		$this->union->limit = '';
		return $this;
	}
}
