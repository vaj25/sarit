<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "representante_model"));
    }

    public function index(){
		$this->load->view('templates/header');
		$this->load->view('historial');
		$this->load->view('templates/footer');
    }
    
    public function tabla_reglamento(){
        $data['reglamentos'] = $this->reglamento_model->obtener_reglamentos_numero();
		$this->load->view('reglamento_ajax/tabla_reglamento', $data);
	}

}

?>