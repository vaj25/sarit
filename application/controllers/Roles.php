<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model(array("expediente_empleado_model", "login_model"));
    }
    
    public function index(){
			$data['colaboradores'] = $this->expediente_empleado_model->obtener_delegados_seccion();
			$this->load->view('templates/header');
			$this->load->view('roles', $data);
			$this->load->view('templates/footer');
    }
    
	public function gestionar_roles() {
		
		$colaboradores = $this->expediente_empleado_model->obtener_delegados_seccion();

		if ($colaboradores) {
			foreach ($colaboradores->result() as $delegado) {
				if ( $this->input->post($delegado->id_empleado) ) {
					echo $this->login_model->cambiar_rol($delegado->id_empleado, 71);
				}
			}
		} else {
			echo "fracaso";
		}

	}

}

?>