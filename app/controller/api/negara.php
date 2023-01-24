<?php
class Negara extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lib("seme_purifier");
        $this->load("api/d_negara_model", 'dnm');
    }

    public function index()
    {
        $d = $this->__init();
        $data = array();
        $pengguna = $d['sess']->admin;

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

        switch ($iSortCol_0) {
            case 0:
                $sortCol = "nation_code";
                break;
            case 1:
                $sortCol = "iso2";
                break;
            case 2:
                $sortCol = "nama";
                break;
            case 3:
                $sortCol = "mata_uang";
                break;
            case 4:
                    $sortCol = "is_active";
                break;
            default:
                $sortCol = "nation_code";
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
        $dcount = $this->dnm->countAll($page, $pagesize, $sortCol, $sortDir, $keyword);
        $ddata = $this->dnm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword);

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
        $d = $this->__init();
        $data = array();
        if (!$this->admin_login) {
            $this->status = 400;
            $this->message = 'Harus login';
            header("HTTP/1.0 400 Harus login");
            $this->__json_out($data);
            die();
        }
        $pengguna = $d['sess']->admin;

        $id = (int) $id;
        if ($id<=0) {
            $this->status = 941;
            $this->message = 'Invalid ID';
            $this->__json_out($data);
            die();
        }

        $data = $this->dnm->getById($id);
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
        $data = $this->dnm->get();
        $this->__json_out($data);
    }
}
