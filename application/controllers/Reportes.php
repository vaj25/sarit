<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('reportes_colectivos_model');
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('reportes/menu_reportes');
		$this->load->view('templates/footer');
	}

	public function ejemplo(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes/ejemplo');
		$this->load->view('templates/footer');
	}

	function ejemplo_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE RELACIONES COLECTIVAS');

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, $data, 'html');
			//$body .= $this->relaciones_colectivas_html($data);
			echo $body;
		}else{
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	//$body .= $this->relaciones_colectivas_html($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Asistencia a personas usuarias');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
		}
	}

	function relaciones_colectivas_html($data){
		$cuerpo = "";

		$cuerpo .= '<div class="table table-responsive">
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th align="center">N° Exp.</th>
						<th align="center">Depto.</th>
						<th align="center">Delegado</th>
						<th align="center">Fecha inicio</th>
						<th align="center">Fecha fin</th>
						<th align="center">Persona Solicitante</th>
						<th align="center">M</th>
						<th align="center">F</th>
						<th align="center">Patronos</th>
						<th align="center">Edad</th>
						<th align="center">Personas con discapacidad</th>
						<th align="center">Persona solicitada</th>
						<th align="center">Causas</th>	
						<th align="center">Rama económica</th>
						<th align="center">Actividad económica</th>
						<th align="center">Resolución</th>
						<th align="center">Cantidad pagada Hombres</th>
						<th align="center">Cantidad pagada Mujeres</th>
						<th align="center">Cantidad pagada Total</th>
						<th align="center">Observaciones</th>
					</tr>
				</thead>
				<tbody>';

				/*$registros = $this->reportes_colectivos_model->registros_relaciones_colectivas($data);
				if($registros->num_rows()>0){
					foreach ($registros->result() as $rows) {
						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.implode(" ", array($rows->primer_nombre, $rows->segundo_nombre, $rows->tercer_nombre, $rows->primer_apellido, $rows->segundo_apellido, $rows->apellido_casada)).'</td>
							<td align="center" style="width:180px">'.fecha_ESP($rows->fechacrea_expedienteci).'</td>
							<td align="center" style="width:180px">'.fecha_ESP($rows->fechacrea_expedienteci).'</td>
							<td align="center" style="width:180px">'.$rows->nombre_personaci.' '.$rows->apellido_personaci.'</td>
							<td align="center" style="width:180px">'.$rows->cant_masc.'</td>
							<td align="center" style="width:180px">'.$rows->cant_feme.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.calcular_edad($rows->fnacimiento_personaci).'</td>
							<td align="center" style="width:180px">'.$rows->discapacidadci.'</td>
							<td align="center" style="width:180px">'.$rows->nombre_empresa.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->grupo_catalogociiu.'</td>
							<td align="center" style="width:180px">'.$rows->actividad_catalogociiu.'</td>
							<td align="center" style="width:180px">'.$rows->resultado_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center" style="width:180px">'.$rows->numerocaso_expedienteci.'</td>
						</tr>';
					}
				}*/

				$cuerpo .= '	
				</tbody>
			</table></div>';
		return $cuerpo;
	}


}
?>
