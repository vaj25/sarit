<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadistico extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('reportes_colectivos_model');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('reportes/estadistico');
		$this->load->view('templates/footer');
    }

    public function estadistico_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE RELACIONES COLECTIVAS',
				periodo($data));

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, 'html');
			$body .= $this->estadistico_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->estadistico_html($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Reporte Estadistico');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Reporte estadistico - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->relaciones_colectivas_excel($data, $titles);
		}
    }
    
    public function estadistico_html($data) {
        return $this->load->view('reportes/tabla_estadistico', array('hola' => 1), true);
    }
    
}

?>