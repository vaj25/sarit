<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estado extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("estado_model");
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('configuraciones/estado');
		$this->load->view('templates/footer');
	}

	public function tabla_estados(){
		$data['estados'] = $this->db->get('sri_estadort');
		$this->load->view('configuraciones/estado_ajax/tabla_estado', $data);
	}

	public function gestionar_estados() {

		if($this->input->post('band') == "save"){

			$data = array(
				'estado_estadort' => $this->input->post('estado'),
				'descripcion_estadort' => $this->input->post('descripcion')
			);

			echo $this->estado_model->insertar_estado($data);

		}else if($this->input->post('band') == "edit"){

			$data = array(
				'id_estadort' => $this->input->post('id_estadort'),
				'estado_estadort' => $this->input->post('estado'),
				'descripcion_estadort' => $this->input->post('descripcion')
			);

			echo $this->estado_model->editar_estado($data);

		}else if($this->input->post('band') == "delete"){
			$data = array(
				'id_estadort' => $this->input->post('id_estadort')
			);
			echo $this->estado_model->eliminar_estado($data);

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