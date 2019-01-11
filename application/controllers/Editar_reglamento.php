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
			)
		);
		$this->load->view('templates/footer');
	}

	public function gestionar_reglamento() {
		
		$reglamentos = array(
			'id_expedientert' => $this->input->post('id_expediente'),
			'id_empresart' => $this->input->post('establecimiento'),
			'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
			'numexpediente_expedientert' => $this->input->post('num_expediente'),
			'numeroexpediente_anterior' => $this->input->post('num_expediente_anterior'),
			'tiposolicitud_expedientert' => $this->input->post('tipo_expediente'),
			'organizacionsocial_expedientert' => '',
			'contratocolectivo_expedientert' => '',
			// 'notificacion_expedientert' => $this->input->post('notificacion'),
			// 'fechanotificacion_expedientert' => $this->input->post('notificacion_fecha'),
			// 'resolucion_expedientert' => $this->input->post('resolucion'),
			// 'obsergenero_expedientrt' => $this->input->post('ob_genero'),
			// 'fecharesolucion_expedientert' => $this->input->post('fecha_resolucion'),
			'archivo_expedientert' => $this->input->post('archivo_expedientert'),
			'contenidoTitulos_expedientert' => '',
			'fechacrea_expedientert' => $this->input->post('fecha_creacion')
		);

	}

}
?>