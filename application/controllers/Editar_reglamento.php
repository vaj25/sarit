<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar_reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index($id_solicitud){
		$this->load->view('templates/header');
        $this->load->view('templates/editar_mantenimiento', 
            array('solicitud' => $this->reglamento_model->obtener_reglamentos_documentos($id_solicitud)->row() ));
		$this->load->view('templates/footer');
	}
}
?>