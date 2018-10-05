<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadistico extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('reportes_colectivos_model');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('reportes/estadistico');
		$this->load->view('templates/footer');
    }
    
}

?>