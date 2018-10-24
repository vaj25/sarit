<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asignados extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('expediente_estado_model');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('reportes/asignados');
		$this->load->view('templates/footer');
    }

    public function asignados_report(){
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
			$body .= $this->asignados_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->asignados_html($data);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Reporte Asignado');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Reporte asignado - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->asignados_excel($data);
		}
    }
    
    public function asignados_html($data, $empleado = FALSE) {

        return $this->load->view(
			'reportes/tabla_asignados',
			$this->expediente_estado_model->obtener_asignados_reporte($data),
			true
		);
	}
	
	function asignados_excel($data, $empleado = FALSE){

		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); 
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de conciliación de conflictos de trabajo");

		$titulo = 'INFORME DE REGLAMENTOS INTERNOS';

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'G';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(10,80,25,20,15,20,10),
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
		$title = array("MINISTERIO DE TRABAJO Y PREVISION SOCIAL", "UNIDAD FINANCIERA INSTITUCIONAL", $titulo),
		$letradesde, $letrahasta, $f);
		

		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/
		$titles_head = array(
			'N°.',
			'Nombre de la empresa',
			'Sector económico',
			'Fecha de Recibido',
			'Estado',
			'Fecha Estado',
			'Duración del servicio'
		);
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_head, $letradesde, $letrahasta, $f, $estilo);

	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


	 	/********************************* 	   INICIO DE LOS REGISTROS DE LA TABLA   	***********************************/
	 	$registros = $this->expediente_estado_model->obtener_asignados_reporte($data);
		if(count($registros) > 0){
			foreach ($registros['expedientes'] as $rows) {

				$cell_row = array(
					$rows->numexpediente_expedientert,
					$rows->nombre_empresa,
					$rows->seccion_catalogociiu,
					date("d/m/Y", strtotime($rows->fechacrea_expedientert)),
					$rows->estado_estadort,
					date("d/m/Y", strtotime($rows->fecha_ingresar_exp_est)),
					$rows->servicio
				);

				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C'.($f+3).':C'.($f+4));
		$this->objPHPExcel->getActiveSheet()
						->getCell('C'.($f+3))
						->setValue('Duración de Servicio');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C'.($f+3))
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C'.($f+3))
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->objPHPExcel->getActiveSheet()
						->getCell('D'.($f+3))
						->setValue($registros['duracion']->duracion);

		$this->objPHPExcel->getActiveSheet()
						->getCell('D'.($f+4))
						->setValue($registros['duracion']->prom_duracion);

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('E'.($f+3).':E'.($f+4));
		$this->objPHPExcel->getActiveSheet()
						->getCell('E'.($f+3))
						->setValue('Días Hábiles');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('E'.($f+3))
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->objPHPExcel->getActiveSheet()
						->getStyle('E'.($f+3))
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->objPHPExcel->getActiveSheet()->getStyle('C'.($f+3).':E'.($f+3))->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('C'.($f+4).':E'.($f+4))->applyFromArray($estilo);

		/******************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	***********************************/
		
		$this->objPHPExcel->getActiveSheet()->getStyle($letradesde.'1:'.$letrahasta.$this->objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

		$f+=8;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario'));
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($titulo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$titulo.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
    	$writer = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		$writer->save('php://output');
	}
    
}

?>