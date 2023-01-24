<?php
class D_Provinsi_Model extends SENE_Model
{
    public $tbl = 'd_provinsi';
    public $tbl_as = 'dp';
    public $tbl2 = 'd_negara';
    public $tbl2_as = 'dn';
    public $tbl3 = 'd_kabkota';
    public $tbl3_as = 'dkk';
    public $tbl4 = 'd_kecamatan';
    public $tbl4_as = 'dkc';
    public $tbl5 = 'd_kelurahan';
    public $tbl5_as = 'dkl';
    public $tbl6 = 'd_kodepos';
    public $tbl6_as = 'dkp';

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }
    public function getTblAs()
    {
        return $this->tbl_as;
    }
    public function getTblAs2()
    {
        return $this->tbl2_as;
    }

    public function get($d_negara_nation_code)
    {
        $this->db->cache_save=1;
        $this->db->select_as("id", "id", 0);
        $this->db->select_as("nama", "text", 0);
        if (strlen($d_negara_nation_code)>0 && $d_negara_nation_code>0) {
            $this->db->where("d_negara_nation_code", $d_negara_nation_code);
        }
        $this->db->order_by("nama", "asc");
        return $this->db->get();
    }

    public function getById($id)
    {
        $this->db->cache_save=1;
        $this->db->where("id", $id);
        return $this->db->get_first();
    }

    public function getAll($page='0', $pagesize='10', $sortCol="id", $sortDir="asc", $keyword="", $is_active="")
    {
        $this->db->cache_save=1;
        $this->db->select_as("$this->tbl_as.id", 'id', 0);
        $this->db->select_as("$this->tbl_as.d_negara_nation_code", 'd_negara_nation_code', 0);
        $this->db->select_as("$this->tbl_as.nama", 'nama', 0);
        $this->db->select_as("$this->tbl_as.latitude", 'latitude', 0);
        $this->db->select_as("$this->tbl_as.longitude", 'longitude', 0);
        $this->db->from($this->tbl, $this->tbl_as);
        if (strlen($keyword) > 0) {
            $this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 1, 1);
        }
        $this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
        return $this->db->get("object", 0);
    }
    public function countAll($keyword="", $is_active="")
    {
        $this->db->cache_save=1;
        $this->db->flushQuery();
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->from($this->tbl, $this->tbl_as);
        if (strlen($keyword) > 0) {
            $this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 1, 1);
        }
        $d = $this->db->get_first("object", 0);
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }

    public function set($di)
    {
        return $this->db->insert($this->tbl, $di, 0, 0);
    }
    public function update($id, $du)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tbl, $du, 0);
    }
    public function del($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tbl);
    }

    public function getSearch($nation_code, $negara_nama, $keyword="")
    {
        $this->db->cache_save=1;
        $this->db->select_as("$this->tbl_as.id", "id", 0);
        $this->db->select_as("$this->tbl_as.nama", "text", 0);
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->join($this->tbl2, $this->tbl2_as, 'nation_code', $this->tbl_as, 'd_negara_nation_code', 'left');
        if (strlen($nation_code)>0 && $nation_code>0) {
            $this->db->where_as("COALESCE($this->tbl2_as.nation_code)", $this->db->esc($nation_code), "AND", "LIKE");
        }
        if (strlen($negara_nama)>0) {
            $this->db->where_as("COALESCE($this->tbl2_as.nama)", $this->db->esc($negara_nama), "AND", "LIKE");
        }
        if (strlen($keyword)>0) {
            $this->db->where_as("$this->tbl_as.nama", ($keyword), "OR", "LIKE%%");
        }
        $this->db->order_by("$this->tbl_as.nama", "asc");
        return $this->db->get('', 0);
    }
    public function cari($keyword)
    {
        $this->db->select_as("$this->tbl2_as.nama", "negara", 0);
        $this->db->select_as("$this->tbl_as.nama", "provinsi", 0);
        $this->db->select_as("$this->tbl3_as.nama", "kabkota", 0);
        $this->db->select_as("$this->tbl4_as.nama", "kecamatan", 0);
        $this->db->select_as("$this->tbl5_as.nama", "desakel", 0);

        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->join($this->tbl2, $this->tbl2_as, 'nation_code', $this->tbl_as, 'd_negara_nation_code', '');
        $this->db->join($this->tbl3, $this->tbl3_as, 'd_provinsi_id', $this->tbl_as, 'id', '');
        $this->db->join($this->tbl4, $this->tbl4_as, 'd_kabkota_id', $this->tbl3_as, 'id', '');
        $this->db->join($this->tbl5, $this->tbl5_as, 'd_kecamatan_id', $this->tbl4_as, 'id', '');
        if (strlen($keyword)>0) {
            $this->db->where_as("$this->tbl_as.nama", $keyword, 'OR', 'like%%', 1, 0);
            $this->db->where_as("$this->tbl2_as.nama", $keyword, 'OR', 'like%%', 0, 0);
            $this->db->where_as("$this->tbl3_as.nama", $keyword, 'OR', 'like%%', 0, 0);
            $this->db->where_as("$this->tbl4_as.nama", $keyword, 'OR', 'like%%', 0, 0);
            $this->db->where_as("$this->tbl5_as.nama", $keyword, 'OR', 'like%%', 0, 1);
        }
        $this->db->order_by("$this->tbl_as.nama", "asc");
        $this->db->order_by("$this->tbl3_as.nama", "asc");
        $this->db->order_by("$this->tbl4_as.nama", "asc");
        $this->db->order_by("$this->tbl5_as.nama", "asc");
        return $this->db->get('', 0);
    }
}
