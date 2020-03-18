<?php
class D_KabKota_Model extends SENE_Model{
  var $tbl = 'd_kabkota';
  var $tbl_as = 'dk';
  var $tbl2 = 'd_provinsi';
  var $tbl2_as = 'dp';

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

  public function set($di){
    return $this->db->insert($this->tbl,$di,0,0);
  }
  public function update($id,$du){
    $this->db->where('id',$id);
    return $this->db->update($this->tbl,$du,0);
  }
  public function del($id){
    $this->db->where('id',$id);
    return $this->db->delete($this->tbl);
  }

  public function get($d_provinsi_id){
    $this->db->cache_save=1;
    if(strlen($d_provinsi_id)>0 && $d_provinsi_id>0) $this->db->where("d_provinsi_id",$d_provinsi_id);
    $this->db->order_by("nama","asc");
    return $this->db->get();
  }

  public function getById($id){
    $this->db->cache_save=1;
    $this->db->where("id",$id);
    return $this->db->get_first();
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


  public function getAll($page='0',$pagesize='10',$sortCol="id",$sortDir="asc",$keyword="",$is_active=""){
    $this->db->cache_save=1;
    $this->db->select_as("$this->tbl_as.id",'id',0);
    $this->db->select_as("$this->tbl_as.d_provinsi_id",'d_provinsi_id',0);
    $this->db->select_as("$this->tbl_as.nama",'nama',0);
    $this->db->select_as("$this->tbl_as.latitude",'latitude',0);
    $this->db->select_as("$this->tbl_as.longitude",'longitude',0);
    $this->db->from($this->tbl,$this->tbl_as);
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,1);
    }
    $this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
    return $this->db->get("object",0);
  }
  public function countAll($keyword="",$is_active=""){
    $this->db->cache_save=1;
    $this->db->flushQuery();
		$this->db->select_as("COUNT(*)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
    if(strlen($keyword) > 0){
      $this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,1);
    }
		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
  }
  public function getSelect2($keyword){
    $this->db->select_as("id","id",0);
    $this->db->select_as("nama","text",0);
    $this->db->where("nama",$keyword,"AND","LIKE%%");
    if(strlen($keyword)<=0) $this->db->limit(0,10);
    return $this->db->get();
  }

  public function getSearch($d_provinsi_id,$provinsi_nama,$keyword=""){
    $this->db->cache_save=1;
    $this->db->select_as("$this->tbl_as.id","id",0);
    $this->db->select_as("$this->tbl_as.nama","text",0);
    $this->db->from($this->tbl,$this->tbl_as);
    $this->db->join($this->tbl2,$this->tbl2_as,'id',$this->tbl_as,'d_provinsi_id','left');
    if(strlen($d_provinsi_id)>0 && $d_provinsi_id>0) $this->db->where_as("COALESCE($this->tbl2_as.id)",$this->db->esc($d_provinsi_id),"AND","LIKE");
    if(strlen($provinsi_nama)>0) $this->db->where_as("COALESCE($this->tbl2_as.nama)",$this->db->esc($provinsi_nama),"AND","LIKE");
    if(strlen($keyword)>0) $this->db->where_as("$this->tbl_as.nama",($keyword),"OR","LIKE%%");
    $this->db->order_by("$this->tbl_as.nama","asc");
    return $this->db->get('',0);
  }
}
