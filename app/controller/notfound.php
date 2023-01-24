<?php
/**
 * Controller class for throw 404 response code
 *
 * @package SemeFramework
 * @since SemeFramework 1.0
 *
 * @codeCoverageIgnore
 */
class Notfound extends JI_Controller
{
    public function __constructx()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = $this->__init(); //method from app/core/ji_controller
        $data['brand'] = $this->site_name;
        //set header
        header("HTTP/1.0 404 Not Found");

        $this->setTitle('Not Found '.$this->site_suffix);
        $this->setDescription($this->site_description);
        $this->loadLayout('notfound', $data);
        $this->render();
    }
}
