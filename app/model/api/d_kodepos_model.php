<?php
class D_KodePos_Model extends SENE_Model{
  var $tbl = 'd_kodepos';
  var $tbl_as = 'dkp';
  var $tbl2 = 'd_kabkota';
  var $tbl2_as = 'dkk';
  var $tbl3 = 'd_kecamatan';
  var $tbl3_as = 'dkec';

  public function __construct(){
    parent::__construct();
    $this->db->from($this->tbl,$this->tbl_as);
  }
  public function getTblAs(){
    return $this->tbl_as;
  }
  public function getTblAs2(){
    return $this->tbl2_as;
  }
  public function getTblAs3(){
    return $this->tbl3_as;
  }

  public function set($di){
    return $this->db->insert($this->tbl,$di,0,0);
  }
  public function update($id,$du){
    $this->db->where('id',$id);
    return $this->db->update($this->tbl,$du,0);
  }
  public function trans_start(){
		$r = $this->db->autocommit(0);
		if($r) return $this->db->begin();
		return false;
	}
  public function trans_commit(){
		return $this->db->commit();
	}
  public function trans_end(){
		return $this->db->autocommit(1);
	}
  public function del($id){
    $this->db->where('id',$id);
    return $this->db->delete($this->tbl);
  }

  public function get($d_kabkota_id="",$d_kecamatan_id=""){
    if(strlen($d_kabkota_id)>0 && $d_kabkota_id>0) $this->db->where("d_kabkota_id",$d_kabkota_id);
    if(strlen($d_kecamatan_id)>0 && $d_kecamatan_id>0) $this->db->where("d_kecamatan_id",$d_kecamatan_id);
    $this->db->order_by("nama","asc");
    return $this->db->get();
  }

  public function getById($id){
    $this->db->where("id",$id);
    return $this->db->get_first();
  }

  public function getAll($page='0',$pagesize='10',$sortCol="id",$sortDir="asc",$keyword=""){
    $this->db->select_as("$this->tbl_as.id",'id',0);
    $this->db->select_as("$this->tbl2_as.nama",'kabkota',0);
    $this->db->select_as("$this->tbl3_as.nama",'kecamatan',0);
    $this->db->select_as("$this->tbl_as.kodepos",'kodepos',0);
    $this->db->from($this->tbl,$this->tbl_as);
    $this->db->join($this->tbl2,$this->tbl2_as,"id",$this->tbl_as,"d_kabkota_id","left");
    $this->db->join($this->tbl3,$this->tbl3_as,"id",$this->tbl_as,"d_kecamatan_id","left");
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.kodepos",$keyword,"OR","%like%",1,0);
      $this->db->where_as("$this->tbl2_as.nama",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl3_as.nama",$keyword,"OR","%like%",0,1);
    }
    $this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
    return $this->db->get("object",0);
  }
  public function countAll($keyword=""){
    $this->db->flushQuery();
		$this->db->select_as("COUNT(*)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.kodepos",$keyword,"OR","%like%",1,0);
      $this->db->where_as("$this->tbl2_as.nama",$keyword,"OR","%like%",0,0);
      $this->db->where_as("$this->tbl3_as.nama",$keyword,"OR","%like%",0,1);
    }
		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
  }

  public function getSearch($keyword=""){
    $this->db->select_as("$this->tbl_as.id","id",0);
    $this->db->select_as("$this->tbl_as.kodepos","text",0);
    $this->db->from($this->tbl,$this->tbl_as);
    $this->db->join($this->tbl2,$this->tbl2_as,'id',$this->tbl_as,'d_kabkota_id','left');
    $this->db->join($this->tbl3,$this->tbl3_as,'id',$this->tbl_as,'d_kecamatan_id','left');
    if(strlen($keyword)>0){
      $this->db->where_as("$this->tbl_as.kodepos",($keyword),"OR","LIKE%%",1,0);
      $this->db->where_as("$this->tbl2_as.nama",($keyword),"OR","LIKE%%",0,0);
      $this->db->where_as("$this->tbl3_as.nama",($keyword),"OR","LIKE%%",0,1);
    }
    $this->db->order_by("$this->tbl_as.kodepos","asc");
    return $this->db->get('',0);
  }
}
