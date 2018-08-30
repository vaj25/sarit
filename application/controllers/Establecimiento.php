<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function combo_actividad_economica() {
		
		$this->load->view('establecimiento_ajax/combo_establecimiento', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);

    }
    
    public function combo_establecimiento() {
		
		$this->load->view('establecimiento_ajax/combo_municipio', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);

	}
}
?>