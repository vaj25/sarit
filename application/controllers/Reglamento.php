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
				'id_estadort' => 1,
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => 'Registro',
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

			if ("exito" == $this->comisionado_model->insertar_comisionado($data2)) {
				echo $this->reglamento_model->insertar_reglamento($data);
			} else {
				echo "fracaso";
			}

		}else if($this->input->post('band1') == "edit"){

			$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];

			$data['id_empresart'] = $this->input->post('establecimiento');
			$data['tipopersona_expedientert'] = $this->input->post('tipo_solicitante');

			$data2 = array(
				'id_representantert' => $this->input->post('id_comisionado'),
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

			if ("exito" == $this->comisionado_model->editar_comisionado($data2)) {
				echo $this->reglamento_model->editar_reglamento($data);
			} else {
				echo "fracaso";
			}

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

	public function ver_reglamento() {

		$data['reglamento'] = $this->reglamento_model->obtener_reglamento_empresa(25);

		$this->load->view('templates/header');
		$this->load->view('reglamento_ajax/vista_reglamento', $data);
		$this->load->view('templates/footer');
	}

}
?>