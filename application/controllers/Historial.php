<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "expediente_estado_model", "expediente_empleado_model"));
    }

    public function index(){
			$data['delegados'] = $this->expediente_empleado_model->obtener_delegados_seccion();
			$this->load->view('templates/header');
			$this->load->view('historial', $data);
			$this->load->view('templates/footer');
    }
    
    public function tabla_reglamento(){
      $data['reglamentos'] = $this->reglamento_model->obtener_reglamentos_numero(false, $this->input->get('nr'));
			$this->load->view('historial_ajax/tabla_historial', $data);
	}

	public function ver_historial() {
		$data['historial'] = $this->reglamento_model->obtener_reglamentos_numero( $this->input->post('num'));

		$this->load->view('historial_ajax/vista_historial', $data);
	}

	public function ver_detalle() {
		$data['detalle'] = $this->expediente_estado_model->obtener_reglamento_estados( $this->input->post('id') );
		$data['reglamento'] = $this->reglamento_model->obtener_reglamento_empresa( $this->input->post('id') );

		$this->load->view('historial_ajax/vista_detalle', $data);
	}

}

?>