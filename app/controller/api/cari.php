<?php
class Cari extends JI_Controller{

  public function __construct(){
    parent::__construct();
    $this->setTheme('front');
    $this->load('api/d_provinsi_model',"dpm");
  }


	public function index(){
		$d = $this->__init();
		$data = array();

		$keyword = $this->input->request("keyword");
    $keyword = trim(strip_tags($keyword));
    if(strlen($keyword)<=3){
  		$this->status = 200;
  		$this->message = 'Keyword too short';
    }else{
      $this->status = 200;
  		$this->message = 'Berhasil';
      $data = $this->dpm->cari($keyword);
    }
		$this->__json_out($data);
	}
}
