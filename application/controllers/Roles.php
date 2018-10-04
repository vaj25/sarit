<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model(array("expediente_empleado_model"));
    }
    
    public function index(){
			$data['colaboradores'] = $this->expediente_empleado_model->obtener_delegados_seccion();
			$this->load->view('templates/header');
			$this->load->view('roles', $data);
			$this->load->view('templates/footer');
    }
    


}

?>