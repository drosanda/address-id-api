<?php
class M_Engine extends SENE_Model{
  protected $dbname;
	public function __construct(){
    parent::__construct();
		$db = $GLOBALS['db'];
    $this->dbname = $db['name'];
  }
	public function showTables(){
		$sql="SHOW TABLES";
		return $this->select($sql);
	}
	public function getRelation(){
    
    //print_r($db);
   // die();
		$sql="SELECT CONCAT( table_name, '.', column_name, ' -> ', 
  referenced_table_name, '.', referenced_column_name ) AS list_of_fks 
FROM INFORMATION_SCHEMA.key_column_usage 
WHERE referenced_table_schema = '".$this->dbname."' 
  AND referenced_table_name IS NOT NULL 
ORDER BY table_name, column_name;";
		return $this->select($sql);
	}
  public function getColumns($tablename){
    $sql="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$this->dbname."' AND `TABLE_NAME`='".$tablename."'";
    return $this->select($sql);
  }
}
?>
