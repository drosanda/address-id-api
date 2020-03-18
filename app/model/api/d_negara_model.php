<?php
class D_Negara_Model extends SENE_Model{
  var $tbl = 'd_negara';
  var $tbl_as = 'dn';

  public function __construct(){
    parent::__construct();
    $this->db->from($this->tbl,$this->tbl_as);
  }

  public function trans_start(){
		$r = $this->db->autocommit(0);
		if($r) return $this->db->begin();
		return false;
	}
	public function trans_commit(){
		return $this->db->commit();
	}
	public function trans_rollback(){
		return $this->db->rollback();
	}
	public function trans_end(){
		return $this->db->autocommit(1);
	}

  private function __joinTbl2(){
    $cps = array();
    $cps[] = $this->db->composite_create("$this->tbl_as.nation_code","=","$this->tbl2_as.nation_code");
    $cps[] = $this->db->composite_create("$this->tbl_as.b_user_id","=","$this->tbl2_as.id");
    return $cps;
  }

  public function get(){
    $this->db->where("is_active",1);
    $this->db->order_by("nama","asc");
    return $this->db->get();
  }

  public function getById($nation_code){
    $this->db->where("nation_code",$nation_code);
    return $this->db->get_first();
  }

  public function getByNationCode($nation_code){
    $this->db->where("nation_code",$nation_code);
    return $this->db->get_first();
  }

  public function getAll($page='0',$pagesize='10',$sortCol="nation_code",$sortDir="asc",$keyword="",$is_active=""){
    $this->db->select_as("$this->tbl_as.nation_code",'nation_code',0);
    $this->db->select_as("$this->tbl_as.iso2",'iso2',0);
    $this->db->select_as("$this->tbl_as.nama",'nama',0);
    $this->db->select_as("$this->tbl_as.mata_uang",'mata_uang',0);
    $this->db->select_as("$this->tbl_as.is_active",'is_active',0);
    $this->db->from($this->tbl,$this->tbl_as);
    if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active));
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.nation_code",$keyword,"OR","%like%",1,0);
      $this->db->where_as("$this->tbl_as.iso2",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl_as.mata_uang",$keyword,"OR","%like%",0,1);
    }
    $this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
    return $this->db->get("object",0);
  }
  public function countAll($keyword="",$is_active=""){
    $this->db->flushQuery();
		$this->db->select_as("COUNT(*)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
    if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active));
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.nation_code",$keyword,"OR","%like%",1,0);
      $this->db->where_as("$this->tbl_as.iso2",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl_as.mata_uang",$keyword,"OR","%like%",0,1);
    }
		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
  }

  public function set($di){
    return $this->db->insert($this->tbl,$di,0,0);
  }
  public function update($nation_code,$du){
    $this->db->where_as('nation_code',$this->db->esc($nation_code));
    return $this->db->update($this->tbl,$du,0);
  }
  public function del($nation_code){
    $this->db->where('nation_code',$nation_code);
    return $this->db->delete($this->tbl);
  }
}
