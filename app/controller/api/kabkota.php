<?php
class Kabkota extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
        $this->load('api/d_kabkota_model', "dkm");
    }
    public function index()
    {
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


        $sortCol = "date";
        $sortDir = strtoupper($sSortDir_0);
        if (empty($sortDir)) {
            $sortDir = "DESC";
        }
        if (strtolower($sortDir) != "desc") {
            $sortDir = "ASC";
        }

        $tbl_as = $this->dkm->getTblAs();
        switch ($iSortCol_0) {
      case 0:
        $sortCol = "$tbl_as.id";
        break;
      case 1:
        $sortCol = "$tbl_as.d_provinsi_id";
        break;
      case 2:
        $sortCol = "$tbl_as.nama";
        break;
      case 3:
        $sortCol = "$tbl_as.latitude";
        break;
      case 4:
        $sortCol = "$tbl_as.longitude";
        break;
        }

        if (empty($draw)) {
            $draw = 0;
        }
        if (empty($pagesize)) {
            $pagesize=10;
        }
        if (empty($page)) {
            $page=0;
        }

        $keyword = $sSearch;

        //Advanced filter
        $status_teks = $this->input->post("status_teks");

        //put to result
        $this->status = 200;
        $this->message = 'Berhasil';
        $dcount = $this->dkm->countAll($keyword, $status_teks);
        $ddata = $this->dkm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword, $status_teks);
        foreach ($ddata as &$gd) {
            if (isset($gd->is_active)) {
                if (empty($gd->is_active)) {
                    $gd->is_active = '<label class="label label-default">Tidak Aktif</label>';
                } else {
                    $gd->is_active = '<label class="label label-success">Aktif</label>';
                }
            }
        }
        $this->__jsonDataTable($ddata, $dcount);
    }
  
    public function detail($id)
    {
        $id = (int) $id;
        $d = $this->__init();
        $data = array();

        $this->status = 200;
        $this->message = 'Berhasil';
        $data = $this->dkm->getById($id);
        if (!isset($data->id)) {
            $data = new stdClass();
            $this->status = 441;
            $this->message = 'No Data';
            $this->__json_out($data);
            die();
        }

        $this->__json_out($data);
    }
  
    public function get()
    {
        $this->status = 200;
        $this->message = 'Berhasil';
        $d_provinsi_id = (int) $this->input->request("d_provinsi_id");
        if ($d_provinsi_id <= 0) {
            $d_provinsi_id="";
        }
        $provinsi = $this->input->request("provinsi");
        if (empty($provinsi)) {
            $provinsi="";
        }
        $keyword = $this->input->request("keyword");
        if (empty($keyword)) {
            $keyword="";
        }
        $data = $this->dkm->getSearch($d_provinsi_id, $provinsi, $keyword);
        $this->__json_out($data);
    }
}
