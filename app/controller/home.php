<?php
// require_once SEMEROOT."kero/lib/vendor/autoload.php";

class Home extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('front');
	}
	public function index(){
		$data = $this->__init();
		$this->setTitle('Seme Address (ID) '.$this->site_suffix);
		$this->setDescription($this->site_description);
		$this->setKeyword($this->site_author);
		$this->putThemeContent("home/home",$data);
		$this->putJsContent("home/home_bottom",$data);
		$this->loadLayout("col-2-left",$data);
		$this->render();
	}
}
