<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "documento_model", "comisionado_model" ));
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reglamento');
		$this->load->view('templates/footer');
	}

	public function tabla_reglamento(){
		$data['reglamentos'] = $this->reglamento_model->obtener_reglamentos();
		$this->load->view('reglamento_ajax/tabla_reglamento', $data);
	}

	public function gestionar_reglamento() {

		if($this->input->post('band1') == "save"){

			$data = array(
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => '',
				'id_estadort' => 'ninguno',
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => '',
				'organizacionsocial_expedientert' => '',
				'contratocolectivo_expedientert' => '',
				'notificacion_expedientert' => '',
				'fechanotificacion_expedientert' => '',
				'resolucion_expedientert' => '',
				'fecharesolucion_expedientert' => '',
				'archivo_expedientert' => '',
				'obsergenero_expedientrt' => '',
				'contenidoTitulos_expedientert' => '',
				'inhabilitado_expedientert' => '',
				'archivo_expedientert' => ''
			);

			$data2 = array(
				'id_empresart' =>$this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			$this->comisionado_model->insertar_comisionado($data2);

			echo $this->reglamento_model->insertar_reglamento($data);

			/*$data = array(
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => $this->input->post('colaborador'),
				'id_estadort' => 'ninguno',
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => '',
				'organizacionsocial_expedientert' => '',
				'contratocolectivo_expedientert' => '',
				'notificacion_expedientert' => '',
				'fechanotificacion_expedientert' => '',
				'resolucion_expedientert' => '',
				'fecharesolucion_expedientert' => '',
				'archivo_expedientert' => '',
				'obsergenero_expedientrt' => '',
				'contenidoTitulos_expedientert' => '',
				'inhabilitado_expedientert' => '',
				'archivo_expedientert' => ''
			);

			$data2 = array(
				'id_expedientert' => $this->reglamento_model->insertar_reglamento($data), 
				'docreglamento_documentort' => $this->input->post('reglamento_interno'),
				'escritura_documentort' => $this->input->post('constitucion_sociedad'),
				'credencial_documentort'  => $this->input->post('credencial_representante'),
				'poder_documentort' => $this->input->post('poder'),
				'dui_documentort' => $this->input->post('establecimiento'),
				'matricula_documentort' => $this->input->post('matricula'),
				'estatutos_documentort' => $this->input->post('estatutos'),
				'acuerdoejec_documentort' => $this->input->post('acuerdo_creacion'),
				'nominayfuncion_documentort' => $this->input->post('nominacion')
			);

			echo $this->documento_model->insertar_documento($data2);*/

		}else if($this->input->post('band') == "edit"){

			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert'),
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => $this->input->post('colaborador'),
				'id_estadort' => 'ninguno',
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => '',
				'organizacionsocial_expedientert' => '',
				'contratocolectivo_expedientert' => '',
				'notificacion_expedientert' => '',
				'fechanotificacion_expedientert' => '',
				'resolucion_expedientert' => '',
				'fecharesolucion_expedientert' => '',
				'archivo_expedientert' => '',
				'obsergenero_expedientrt' => '',
				'contenidoTitulos_expedientert' => '',
				'inhabilitado_expedientert' => '',
				'archivo_expedientert' => ''
			);

			$data2 = array(
				'id_expedientert' => $this->input->post('id_expedientert'), 
				'docreglamento_documentort' => $this->input->post('reglamento_interno'),
				'escritura_documentort' => $this->input->post('constitucion_sociedad'),
				'credencial_documentort'  => $this->input->post('credencial_representante'),
				'poder_documentort' => $this->input->post('poder'),
				'dui_documentort' => $this->input->post('establecimiento'),
				'matricula_documentort' => $this->input->post('matricula'),
				'estatutos_documentort' => $this->input->post('estatutos'),
				'acuerdoejec_documentort' => $this->input->post('acuerdo_creacion'),
				'nominayfuncion_documentort' => $this->input->post('nominacion')
			);

			echo $this->documento_model->editar_documento($data2);

		}else if($this->input->post('band') == "delete"){
			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert')
			);
			echo $this->reglamento_model->eliminar_documento($data);

		}

	}

	public function registros_reglamentos_documentos() {

		print json_encode(
			$this->reglamento_model->obtener_reglamentos_documentos($this->input->post('id'))->result()
		);
		
	}

	public function combo_establecimiento() {
		
		$this->load->view('reglamento_ajax/combo_establecimiento', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);

	}

	public function combo_delegado() {
		
		$this->load->view('reglamento_ajax/combo_delegado', 
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $this->db->get('lista_empleados_estado')
			)
		);

	}

}
?>