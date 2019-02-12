<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar_reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('reglamento_model','solicitud_model', 'expediente_empleado_model', 
			'documento_model', 'comisionado_model', 'expediente_estado_model'));
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
			'organizacionsocial_expedientert' => '',
			'contratocolectivo_expedientert' => '',
			'archivo_expedientert' => $this->input->post('archivo_expedientert'),
			'contenidoTitulos_expedientert' => '',
			'fechacrea_expedientert' => $this->input->post('fecha_creacion')
		);

		$solicitud = array(
			'id_solicitud' => $this->input->post('id_solicitud'),
			'id_expedientert' => $this->input->post('id_expediente'),
			'id_tipo_solicitud' => $this->input->post('tipo_expediente'),
			'notificacion_solicitud' => $this->input->post('notificacion'),
			'fechanotificacion_solicitud' => date("Y-m-d H:i:s", strtotime($this->input->post('notificacion_fecha'))),
			'resolucion_solicud' => $this->input->post('resolucion'),
			'fecharesolucion_solicitud' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_resolucion'))),
			'obsergenero_solicitud' => $this->input->post('ob_genero')
		);

		$representante = array(
			'id_representantert' => $this->input->post('id_representantert'),
			'id_empresart' => $this->input->post('establecimiento'),
			'nombres_representantert' => $this->input->post('nombres'),
			'apellidos_representantert' => $this->input->post('apellidos'),
			'dui_representantert' => $this->input->post('dui'),
			'nit_representantert' => $this->input->post('nit'),
			'telefono_representantert' => $this->input->post('telefono'),
			'correo_representantert' => $this->input->post('correo'),
			'cargo_representantert' => $this->input->post('tipo_representante'),
			'sexo_representantert' => $this->input->post('sexo')
		);

		$documentacion = array(
			'id_expedientert' => $this->input->post('id_solicitud'), 
			'docreglamento_documentort' => $this->input->post('reglamento_interno'),
			'escritura_documentort' => $this->input->post('constitucion_sociedad'),
			'credencial_documentort'  => $this->input->post('credencial_representante'),
			'dui_documentort' => $this->input->post('dui_doc'),
			'poder_documentort' => $this->input->post('poder'),
			'matricula_documentort' => $this->input->post('matricula'),
			'estatutos_documentort' => $this->input->post('estatutos'),
			'acuerdoejec_documentort' => $this->input->post('acuerdo_creacion'),
			'nominayfuncion_documentort' => $this->input->post('nominacion'),
			'leycreacionescritura_documentort' => $this->input->post('creacion_escritura'),
			'acuerdoejecutivo_documentort' => $this->input->post('acuerdo_ejecutivo')
		);

		$delegado = array(
			'id_expedientert' => $this->input->post('id_solicitud'),
			'id_empleado' => $this->input->post('colaborador'),
			'fecha_exp_emp ' => date("Y-m-d H:i:s")
		);

		$id_representante = 0;
		if ($this->input->post('dui_representante') === $this->input->post('dui')) {
			$id_representante = $this->comisionado_model->editar_comisionado($representante);
		} else {
			$id_representante = $this->comisionado_model->insertar_comisionado($representante);
		}

		$reglamentos['id_representante'] = $id_representante;
		
		$this->reglamento_model->editar_reglamento($reglamentos);

		$this->documento_model->editar_documento($documentacion);

		$this->solicitud_model->editar_solitud($solicitud);

		$this->expediente_empleado_model->insertar_expediente_empleado($delegado);

		$estado = 6;
		if ($this->input->post('resolucion') == 'Aprobado') {
			$estado = 3;
		}

		$fecha_estado = strtotime($this->input->post('fecha_resolucion'));
		if ($fecha_estado < 0) {
			$fecha_estado = strtotime(date("Y-m-d H:i:s"));
		}

		$this->expediente_estado_model->insertar_expediente_estado(
			array(
			'id_estadort' => $estado,
			'id_expedientert' => $this->input->post('id_solicitud'),
			'fecha_exp_est' => date("Y-m-d H:i:s", $fecha_estado),
			'fecha_ingresar_exp_est' => date("Y-m-d H:i:s", $fecha_estado),
			'etapa_exp_est' => 1
		));

		echo json_encode("exito");

	}

}
?>