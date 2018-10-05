<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carga_laboral extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('reportes_colectivos_model');
    }

    public function index() {
        $this->load->view('templates/header');
		$this->load->view('reportes/carga_laboral');
		$this->load->view('templates/footer');
    }
    
}

?>