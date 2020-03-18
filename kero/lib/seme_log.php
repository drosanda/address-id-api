<?php
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
      touch($this->path);
    }
  }
  public function changeFilename($filename){
    $this->filename = $filename;
    $this->directory = SENEROOT;
    $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
    if(!file_exists($this->path)) touch($this->path);
    if(!is_writable($this->path)){
      $this->directory = SENECACHE;
      $this->path = $this->directory.DIRECTORY_SEPARATOR.$this->filename;
      touch($this->path);
    }
  }

  public function write($str){
    $f = fopen($this->path,'a+');
    fwrite($f,date("Y-m-d H:i:s").' - ');
    fwrite($f,$str.PHP_EOL);
    fclose($f);
  }
  public function getPath(){
    return $this->path;
  }
}
