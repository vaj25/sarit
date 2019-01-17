<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("establecimiento_model", "representante_model"));
	}

	public function combo_actividad_economica() {
		
		$this->load->view('establecimiento_ajax/combo_actividad_economica', 
			array(
				'id' => $this->input->post('id'),
				'catalogo' => $this->db->get('sge_catalogociiu')
			)
		);

    }
    
    public function combo_municipio() {
		
		$this->load->view('establecimiento_ajax/combo_municipio', 
			array(
				'id' => $this->input->post('id'),
				'municipio' => $this->db->get('org_municipio')
			)
		);

	}

	public function gestionar_establecimiento() {
		if($this->input->post('band3') == "save"){

			$data = array(
                'numinscripcion_empresa' => '1-2018 SS', 
                'nombre_empresa' => $this->input->post('nombre_establecimiento'),
                'abreviatura_empresa' => $this->input->post('abre_establecimiento'),
                'direccion_empresa'  => $this->input->post('dir_establecimiento'),
                'telefono_empresa' => $this->input->post('telefono_establecimiento'),
                'id_catalogociiu' => $this->input->post('act_economica'),
                'id_municipio' => $this->input->post('municipio')
            );

			$id_establecimiento = $this->establecimiento_model->insertar_empresa($data);
			
			$data = array(
				'nombres_representante' => $this->input->post('nombre_representante'),
				'id_empresa' => $id_establecimiento
			);
			
			$this->representante_model->insertar_representante($data);

			echo $id_establecimiento;

		}
	}

	public function consultar_establecimiento_expediente() {
		$count = $this->establecimiento_model->cantidad_expediente_establecimiento($this->input->post('id'));

		if ($count) {
			$count = $count->row();
			if ( $count->meses >= 7 ) {
				echo 1;
			} else {
				echo 2;
			}
		} else {
			echo 0;
		}
		
	}

	public function tabla_representante() {
		$this->load->view(
			'establecimiento_ajax/tabla_representante', 
			array(
				'representantes' => $this->representante_model->obtenerRepresentantesEmpresa($this->input->post('id')), 
			)
		);
	}

	public function obtener_representante() {
		echo json_encode($this->representante_model->obtenerRepresentante($this->input->post('id_representante_modal')));
	}

}
?>