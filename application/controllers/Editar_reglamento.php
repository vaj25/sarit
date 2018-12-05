<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar_reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('reglamento_model','expediente_empleado_model'));
	}

	public function index() {
		$this->load->view('templates/header');
        $this->load->view('editar_reglamento',
            array(
				'solicitud' => $this->reglamento_model->obtener_reglamentos_documentos($this->input->get('id_solicitud'))->row(),
				'delegados' => $this->expediente_empleado_model->obtener_delegados_seccion(),
				'tipo_persona' => $this->db->get('sri_tipo_solicitante'),
				'tipo_expediente' => $this->db->get('sri_tipo_solicitud')
			));
		$this->load->view('templates/footer');
	}
}
?>