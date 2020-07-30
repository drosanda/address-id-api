<?php
/**
 * Seme Framework logging class
 * Default log file name is seme.log
 * Default location 
 * - root if write permission enable
 * - app/cache if write permission enable
 */
class Seme_Log {
  var $directory = '';
  var $filename = 'seme.log';
  var $path = '';

  public function __construct(){
    $this->directory = SENEROOT;
    $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
    if(!file_exists($this->path)) touch($this->path);
    if(!is_writable($this->path)){
      $this->directory = SENECACHE;
      $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
      if(is_writable($this->path)) touch($this->path);
    }
  }
  
  /**
   * Change filename of log file
   * @param  string $filename new file name
   * @return object           this class
   */
  public function changeFilename($filename){
    $this->filename = $filename;
    $this->directory = SENEROOT;
    $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
    if(!file_exists($this->path)) touch($this->path);
    if(!is_writable($this->path)){
      $this->directory = SENECACHE;
      $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
      if(is_writable($this->path)) touch($this->path);
    }
    return $this;
  }
  
  /**
   * Write message to log
   * @param  string $str      log message
   * @return object           this class
   */
  public function write($str){
    $f = fopen($this->path,'a+');
    fwrite($f,date("Y-m-d H:i:s").' - ');
    fwrite($f,$str.PHP_EOL);
    fclose($f);
    return $this;
  }
  
  /**
   * Get path of log file
   * @return string           full path
   */
  public function getPath(){
    return $this->path;
  }
}
