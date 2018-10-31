<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carga_laboral extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expediente_empleado_model", "expediente_estado_model"));
    }

    public function index() {
        $this->load->view('templates/header');
		$this->load->view('reportes/carga_laboral');
		$this->load->view('templates/footer');
	}
	
	public function carga_laboral_report() {
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE CARGA LABORAL',
				periodo($data));

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, 'html');
			$body .= $this->carga_laboral_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->carga_laboral_html($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Reporte Estadistico');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Reporte estadistico - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->carga_laboral_excel($data);
		}
	}

	public function carga_laboral_html($data) {

		$colaboradores = $this->expediente_empleado_model->obtener_delegados_seccion()->result_array();

		$entradas = array();
		$resultados = array();

		foreach ($colaboradores as $colaborador) {
			$entradas[$colaborador['id_empleado']] = 
							$this->expediente_estado_model->obtener_entradas_reporte($data, $colaborador['id_empleado'])->result_array();
			$resultados[$colaborador['id_empleado']] =
							$this->expediente_estado_model->obtener_resultados_reporte($data, $colaborador['id_empleado'])->result_array();
		}
		
		return $this->load->view(
			'reportes/tabla_carga_laboral',
			array(
				'entradas' => $entradas,
				'resultados' => $resultados,
				'colaboradores' => $colaboradores
			), 
			true
		);

	}

	public function carga_laboral_excel($data) {
		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); date_default_timezone_set('America/Mexico_City');
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel, "SARIT");

		$titulo = 'CARGA LABORAL';

		$colaboradores = $this->expediente_empleado_model->obtener_delegados_seccion()->result_array();

		$f=1;
		$letradesde = 'A';
		$letrahasta = obtener_columna_excel(6 + count($colaboradores));

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		$width = array(10,6,6,4,56,10);
		$width = array_pad($width, (6 + count($colaboradores)), 10);
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width, 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
		$title = array("MINISTERIO DE TRABAJO Y PREVISION SOCIAL", "UNIDAD FINANCIERA INSTITUCIONAL", $titulo),
		$letradesde, $letrahasta, $f);

		$estilo_contenido = array(
			'font' => array(
			  'name' => 'Calibri',
			  'bold' => FALSE,
			  'size' => 11,
			),
			'borders' => array(
			  'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			  ),
			  'color' => array('rgb' => '676767'),
			),
			'alignment' => array(
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			  'rotation' => 0,
			  'wrap' => TRUE,
			),
		  );
		

		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/

		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('B5:E5');
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('B6:B21');
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C7:C10');
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C11:C19');

		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B5', ' ')
						->setCellValue('B6', 'REGLAMENTOS')
						->setCellValue('C7', 'Entradas')
						->setCellValue('C11', 'Resultados');
					
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
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('D10:E10');
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C20:E20');
		$this->objPHPExcel->setActiveSheetIndex()->mergeCells('C21:E21');

		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C6', 'Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior')
						->setCellValue('D10', 'Reglamentos Internos a estudiar durante el mes')
						->setCellValue('C20', 'Total de Estudios de Reglamento efectuados')
						->setCellValue('C21', 'Reglamento pendientes para el proximo mes');

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

		for ($i=0; $i < count($colaboradores); $i++) { 
			$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(obtener_columna_excel( 6 + $i ) . '5', $colaboradores[$i]['nombre_completo']);
		}

		$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(obtener_columna_excel( 6 + $i ) . '5', 'Total');

		$this->objPHPExcel->getActiveSheet()->getStyle(obtener_columna_excel( 6 ) . '5:' . obtener_columna_excel( 6 + $i ) . '5')->applyFromArray($estilo_contenido);
					
	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


		 /*********************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	****************************************/

		$cant_entradas = 0;
		// foreach ($entradas as $value) {
		// 	$cant_entradas += $value['cantidad'];
		// }

		$cant_resultados = 0;
		// foreach ($resultados as $value) {
		// 	$cant_resultados += $value['cantidad'];
		// }
		
		$subtotal = array();
		$subtotal_res = array();

		foreach ($colaboradores as $key => $colaborador) {
			
			$entradas = $this->expediente_estado_model->obtener_entradas_reporte($data, $colaborador['id_empleado'])->result_array();
			$resultados = $this->expediente_estado_model->obtener_resultados_reporte($data, $colaborador['id_empleado'])->result_array();

			$sub_cant_entrada = 0;
			$sub_cant_resultados = 0;

			for ($i=0; $i < 5; $i++) {
				
				if (!array_key_exists($i, $subtotal)) {
					$subtotal[$i] = 0;
				}
				
				if ($i < 4) {
					$cantidad = $entradas[$i]['cantidad'];
					$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key) . '' . (6 + $i), $cantidad);
					$sub_cant_entrada += $cantidad;
					
					$subtotal[$i] = $subtotal[$i] + $cantidad;
				} else {
					$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key) . '' . (6 + $i), $sub_cant_entrada);

					$subtotal[$i] = $subtotal[$i] + $sub_cant_entrada;
				}

			}

			for ($i=0; $i < 5; $i++) {
				$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key + 1) . '' . (6 + $i), $subtotal[$i]);
			}

			$j = 0;

			for ($i=0; $i < 10; $i++) {

				if (!array_key_exists($i, $subtotal_res)) {
					$subtotal_res[$i] = 0;
				}
			
				if ($i < 9) {
					switch ($i) {
						case (2 - 1):
							$j = 5;
							break;
						case (3 - 1):
							$j = 7;
							break;
						case (4 -1):
							$j = 6;
							break;
						case (6 - 1):
							$j = 3;
							break;
						case (7 - 1):
							$j = 1;
							break;
						case (8 - 1):
							$j = 2;
							break;
						default:
							$j = $i;
							break;
					}

					$cantidad = $resultados[$j]['cantidad'];
					$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key) . '' . (11 + $i), $cantidad);

					if ( !($i == 8 || $i == 5) ) {
						$sub_cant_resultados += $cantidad;
						$subtotal_res[$i] = $subtotal_res[$i] + $cantidad;
					}

				} else {
					$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key) . '' . (11 + $i), $sub_cant_resultados);

					$subtotal_res[$i] = $subtotal_res[$i] + $sub_cant_resultados;
				}

			}

			for ($i=0; $i < 10; $i++) {
				$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key + 1) . '' . (11 + $i), $subtotal_res[$i]);
			}

			$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key) . '21', ( $sub_cant_entrada - $sub_cant_resultados ));
			
		}

		$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue(obtener_columna_excel(6 + $key +1) . '21', ( array_pop($subtotal) - array_pop($subtotal_res) ));

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