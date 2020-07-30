<?php
/**
* Class helper for generates JSON
*/
class SENE_JSON_Engine
{
  private function safe_json_encode($value)
  {
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
      $encoded = json_encode($value, JSON_PRETTY_PRINT);
    } else {
      $encoded = json_encode($value);
    }
    switch (json_last_error()) {
      case JSON_ERROR_NONE:
      return $encoded;
      case JSON_ERROR_DEPTH:
      return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_STATE_MISMATCH:
      return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_CTRL_CHAR:
      return 'Unexpected control character found';
      case JSON_ERROR_SYNTAX:
      return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
      case JSON_ERROR_UTF8:
      $clean = $this->utf8ize($value);
      return $this->safe_json_encode($clean);
      default:
      return 'Unknown error'; // or trigger_error() or throw new Exception()
    }
  }
  private function utf8ize($mixed)
  {
    if (is_array($mixed)) {
      foreach ($mixed as $key => $value) {
        $mixed[$key] = $this->utf8ize($value);
      }
    } elseif (is_string($mixed)) {
      return utf8_encode($mixed);
    }
    return $mixed;
  }
  private function latin1_to_utf8($dat)
  {
    if (is_string($dat)) {
      return utf8_encode($dat);
    } elseif (is_array($dat)) {
      $ret = [];
      foreach ($dat as $i => $d) {
        $ret[ $i ] = self::latin1_to_utf8($d);
      }

      return $ret;
    } elseif (is_object($dat)) {
      foreach ($dat as $i => $d) {
        $dat->$i = self::latin1_to_utf8($d);
      }

      return $dat;
    } else {
      return $dat;
    }
  }
  private function log($str){
    $p = 'json.log';
    if(defined('SENEROOT')){
      $p = SENEROOT.'seme.log';
    }
    $f = fopen($p,'a+');
    fwrite($f,date("Y-m-d H:i:s").' - ');
    fwrite($f,'kero/lib/SJE -- ERR '.$str.PHP_EOL);
    fclose($f);
  }
  public function out($data, $allowed="*")
  {
    header("Access-Control-Allow-Origin: ".$allowed);
    header("Content-Type: application/json");
    header("charset: utf-8");
    $otp = $this->safe_json_encode($data);
    if(json_last_error()){
      $this->log(json_last_error_msg());
    }
    echo $otp;
  }
}
