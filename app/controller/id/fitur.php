<?php
// require_once SEMEROOT."kero/lib/vendor/autoload.php";

class Fitur extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('front');
	}
	public function index(){
		$data = $this->__init();
		$this->setLang('id-ID');
		$this->setTitle('Fitur '.$this->main_title_id);
		$this->setDescription('Fitur dari '.$this->main_title_id);
		$this->setKeyword($this->site_author);
		$this->putThemeContent("id/fitur/home",$data);
		$this->putJsContent("id/fitur/home_bottom",$data);
		$this->loadLayout("col-2-left",$data);
		$this->render();
	}
}
