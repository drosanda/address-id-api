<?php
class kodepos extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api/d_kodepos_model",'dkm');
	}

	public function index(){
		$d = $this->__init();
		$data = array();

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch");
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortDir = strtoupper($sSortDir_0);
		if(empty($sortDir)) $sortDir = "DESC";
		if(strtolower($sortDir) != "desc"){
			$sortDir = "ASC";
		}

    $tbl_as = $this->dkm->getTblAs();
    $tbl2_as = $this->dkm->getTblAs2();
    $tbl3_as = $this->dkm->getTblAs3();

		switch($iSortCol_0){
			case 0:
				$sortCol = "$tbl_as.id";
				break;
			case 1:
				$sortCol = "$tbl2_as.nama";
				break;
			case 2:
				$sortCol = "$tbl3_as.nama";
				break;
        case 3:
  				$sortCol = "$tbl_as.kodepos";
  				break;
			default:
				$sortCol = "$tbl_as.id";
		}

		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->dkm->countAll($keyword);
		$ddata = $this->dkm->getAll($page,$pagesize,$sortCol,$sortDir,$keyword);

		foreach($ddata as &$gd){
			if(isset($gd->is_active)){
				if(!empty($gd->is_active)){
					$gd->is_active = 'Aktif';
				}else{
					$gd->is_active = 'Tidak Aktif';
				}
			}

		}

		$data['cabang'] = $ddata;
		//sleep(3);
		$another = array();
		$this->__jsonDataTable($ddata,$dcount);
	}

	public function detail($id){
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login && empty($id)){
			$this->status = 400;
			$this->message = 'Harus login';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->dkm->getById($id);
		if(!isset($data->id)){
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$this->__json_out($data);
	}
  public function get(){
		$this->status = 200;
		$this->message = 'Berhasil';
		$d_kabkota_id = (int) $this->input->request("d_kabkota_id");
		if($d_kabkota_id<=0) $d_kabkota_id="";
    $d_kecamatan_id = (int) $this->input->request("d_kecamatan_id");
		if($d_kabkota_id<=0) $d_kecamatan_id="";
		if(strlen($d_kabkota_id)<=0 && strlen($d_kecamatan_id)<=0){
			$this->status = 200;
			$this->message = 'ID kecamatan dan ID kabkota tidak valid';
			$data = array();
		}else{
			$data = $this->dkm->getByKabKotaIdKecamatanId($d_kabkota_id, $d_kecamatan_id);
		}
    $this->__json_out($data);
  }
  public function search(){
		$this->status = 200;
		$this->message = 'Berhasil';
    $keyword = $this->input->request("keyword");
		$l = mb_strlen($keyword);
		if($l<=0 && $l>32){
			$keyword = '';
		}
    $data = $this->dkm->getSearch($keyword);
    $this->__json_out($data);
  }
  public function getbyid(){
		$this->status = 200;
		$this->message = 'Berhasil';
    $d_kabkota_id = $this->input->request("d_kabkota_id");
		if(strlen($d_kabkota_id)==0 || empty($d_kabkota_id)) $d_kabkota_id="";
    $d_kecamatan_id = $this->input->request("d_kecamatan_id");
		if(strlen($d_kecamatan_id)==0 || empty($d_kecamatan_id)) $d_kecamatan_id="";
    $data = $this->dkm->getByKabKotaIdKecamatanId($d_kabkota_id, $d_kecamatan_id);
    $this->__json_out($data);
  }
}
