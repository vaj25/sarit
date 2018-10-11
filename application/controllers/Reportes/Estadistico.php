<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadistico extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('expediente_estado_model');
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
			$this->estadistico_excel($data);
		}
    }
    
    public function estadistico_html($data) {

        return $this->load->view(
			'reportes/tabla_estadistico', 
			array(
				'entradas' => $this->expediente_estado_model->obtener_entradas_reporte($data)->result_array(),
				'resultados' => $this->expediente_estado_model->obtener_resultados_reporte($data)->result_array()
			), 
			true
		);
	}
	
	public function estadistico_excel($data) {
		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); date_default_timezone_set('America/Mexico_City');
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel, "SARIT");

		$titulo = 'INFORME DE ACTIVIDADES ';

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'F';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(10,6,6,4,56,20), 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
		$title = array("MINISTERIO DE TRABAJO Y PREVISION SOCIAL", "UNIDAD FINANCIERA INSTITUCIONAL", $titulo),
		$letradesde, $letrahasta, $f);
		

		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('B5:F5');
		$this->objPHPExcel->getActiveSheet()
    					->getCell('B5')
						->setValue('INFORME DE ACTIVIDADES REGLAMENTOS INTERNOS DE TRABAJO');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('B5')
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('B6:B21');
		$this->objPHPExcel->getActiveSheet()
						->getCell('B6')
						->setValue('REGLAMENTOS');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('B6')
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->objPHPExcel->getActiveSheet()
						->getStyle('B6')
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->objPHPExcel->getActiveSheet()
						->getStyle('B6')
						->getAlignment()
						->setTextRotation(90);

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C7:C10');
		
		$this->objPHPExcel->getActiveSheet()
						->getCell('C7')
						->setValue('Entradas');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C7')
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C7')
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->objPHPExcel->getActiveSheet()
						->getStyle('C7')
						->getAlignment()
						->setTextRotation(90);

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C11:C19');

		$this->objPHPExcel->getActiveSheet()
						->getCell('C11')
						->setValue('Resultados');
					
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C11')
						->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$this->objPHPExcel->getActiveSheet()
						->getStyle('C11')
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->objPHPExcel->getActiveSheet()
						->getStyle('C11')
						->getAlignment()
						->setTextRotation(90);

		for ($i=1; $i <= 3; $i++) { 
			$this->objPHPExcel->getActiveSheet()
						->getCell('D' . (6 + $i) )
						->setValue($i);
		}

		for ($i=1; $i <= 9; $i++) { 
			$this->objPHPExcel->getActiveSheet()
						->getCell('D' . (10 + $i) )
						->setValue($i);
		}

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C6:E6');

		$this->objPHPExcel->getActiveSheet()
						->getCell('C6')
						->setValue('Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior');

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('D10:E10');

		$this->objPHPExcel->getActiveSheet()
						->getCell('D10')
						->setValue('Reglamentos Internos a estudiar durante el mes');

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C20:E20');

		$this->objPHPExcel->getActiveSheet()
						->getCell('C20')
						->setValue('Total de Estudios de Reglamento efectuados');

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C21:E21');

		$this->objPHPExcel->getActiveSheet()
						->getCell('C21')
						->setValue('Reglamento pendientes para el proximo mes');

		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('E7', 'Reglamentos Internos de Trabajo Recibidos (nuevos)')
						->setCellValue('E8', 'Reglamentos Internos de Trabajo Recibidos  con Correcciones')
						->setCellValue('E9', 'Reformas a Reglamentos Internos Recibidas')
						->setCellValue('E11', 'Reglamentos Internos de Trabajo con Observaciones Realizadas')
						->setCellValue('E12', 'Proyectos de Reglamentos Interos con Observaciones de Género')
						->setCellValue('E13', 'Proyectos de Reglamentos Internos Aprobados')
						->setCellValue('E14', 'Reformas de Reglamentos Internos Aprobados')
						->setCellValue('E15', 'Proyectos de Reglamentos Internos Desistidos')
						->setCellValue('E16', 'Proyectos de Reglamentos Internos Declarados Improponibles')
						->setCellValue('E17', 'Proyectos de Reglamentos Internos Prevenidos')
						->setCellValue('E18', 'Proyectos de Reglamentos Internos en Calificacion de Labores (DGPS)')
						->setCellValue('E19', 'Casos Reasignados (cambio de Colaborador)');

	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


		 /*********************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	****************************************/

		$entradas = $this->expediente_estado_model->obtener_entradas_reporte($data)->result_array();
		$resultados = $this->expediente_estado_model->obtener_resultados_reporte($data)->result_array();

		$cant_entradas = 0;
		foreach ($entradas as $value) {
			$cant_entradas += $value['cantidad'];
		}

		$cant_resultados = 0;
		foreach ($resultados as $key => $value) {
			if ( !($key == 8 || $key == 5) ) {
				$cant_resultados += $value['cantidad'];
			}
		}
		 
		 $this->objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('F6', $entradas[0]['cantidad'])
							->setCellValue('F7', $entradas[1]['cantidad'])
							->setCellValue('F8', $entradas[2]['cantidad'])
							->setCellValue('F9', $entradas[3]['cantidad'])
							->setCellValue('F10', $cant_entradas)
							->setCellValue('F11', $resultados[0]['cantidad'])
							->setCellValue('F12', $resultados[5]['cantidad'])
							->setCellValue('F13', $resultados[7]['cantidad'])
							->setCellValue('F14', $resultados[6]['cantidad'])
							->setCellValue('F15', $resultados[4]['cantidad'])
							->setCellValue('F16', $resultados[3]['cantidad'])
							->setCellValue('F17', $resultados[1]['cantidad'])
							->setCellValue('F18', $resultados[2]['cantidad'])
							->setCellValue('F19', $resultados[8]['cantidad'])
							->setCellValue('F20', $cant_resultados)
							->setCellValue('F21', $cant_entradas - $cant_resultados);

		 /*********************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************************/

		$f+=18;

		$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario_centro'));
		
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