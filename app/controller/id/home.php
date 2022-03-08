<?php
// require_once SEMEROOT."kero/lib/vendor/autoload.php";

class Home extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('front');
	}
	public function index(){
		$data = $this->__init();
		$this->setLang('id-ID');
		$this->setTitle('Dokumentasi API Alamat di Indonesia '.$this->site_suffix);
		$this->setDescription('Indonesia memiliki 5 tingkat daerah pengalamatan yaitu provinsi, kabupaten atau kota, kecamatan, dan desa atau kelurahan.');
		$this->setKeyword($this->site_author);
		$this->putThemeContent("id/home/home",$data);
		$this->putJsContent("id/home/home_bottom",$data);
		$this->loadLayout("col-2-left",$data);
		$this->render();
	}
}
