<?php
class Provinsi extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lib("seme_purifier");
        $this->load("api/d_provinsi_model", 'dpm');
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


        $sortDir = strtoupper($sSortDir_0);
        if (empty($sortDir)) {
            $sortDir = "DESC";
        }
        if (strtolower($sortDir) != "desc") {
            $sortDir = "ASC";
        }

        $tbl_as = $this->dpm->getTblAs();
        $tbl2_as = $this->dpm->getTblAs2();

        switch ($iSortCol_0) {
            case 0:
                $sortCol = "d_negara_nation_code";
                break;
            case 1:
                $sortCol = "nama";
                break;
            case 2:
                $sortCol = "latitude";
                break;
            case 3:
                $sortCol = "longitude";
                break;
            default:
                $sortCol = "id";
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

        $this->status = 200;
        $this->message = 'Berhasil';
        $dcount = $this->dpm->countAll($keyword);
        $ddata = $this->dpm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword);

        foreach ($ddata as &$gd) {
            if (isset($gd->is_active)) {
                if (!empty($gd->is_active)) {
                    $gd->is_active = 'Aktif';
                } else {
                    $gd->is_active = 'Tidak Aktif';
                }
            }
        }

        $data['cabang'] = $ddata;
        //sleep(3);
        $another = array();
        $this->__jsonDataTable($ddata, $dcount);
    }

    public function detail($id)
    {
        $id = (int) $id;
        $d = $this->__init();
        $data = array();

        $this->status = 200;
        $this->message = 'Berhasil';
        $data = $this->dpm->getById($id);
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
        $nation_code = (int) $this->input->request("nation_code");
        if ($nation_code <= 0) {
            $nation_code="";
        }
        $negara = $this->input->request("negara");
        if (empty($negara)) {
            $negara="";
        }
        $keyword = $this->input->request("keyword");
        if (empty($keyword)) {
            $keyword="";
        }
        $data = $this->dpm->getSearch($nation_code, $negara, $keyword);
        $this->__json_out($data);
    }
}
