<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model(array("reglamento_model"));
	}

	public function generar_acta($id, $contenido){

        $data = $this->reglamento_model->obtener_reglamento( $id )->result_array()[0];

        switch ($data['id_estadort']) {
            case 3:
                $this->generar_acta_aprobar($id);
                break;
            default:
                # code...
                break;
        }

    }
    
    public function generar_acta_aprobar($expediente) {
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoAprobado/APROBACION_NUEVOREGLAMENTO-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', date('H'));
        $templateWord->setValue('dias_letras', date('d'));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', date('Y'));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);

        

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='aprobacion_reglamento_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    private function municipio($numero_expediente) {
        $abr = substr($numero_expediente, -2);

        switch ($abr) {
            case 'SS':
                return "SAN SALVADOR";
                break;
            case 'AH':
                return "AHUACHAPAN";
                break;
            case 'SO':
                return "SONSONATE";
                break;
            case 'SA':
                return "SANTA ANA";
                break;
            case 'LL':
                return "LA LIBERTAD";
                break;
            case 'CU':
                return "CUSCATLAN";
                break;
            case 'CH':
                return "CHALATENANGO";
                break;
            case 'CA':
                return "CABANAS";
                break;
            case 'SV':
                return "SAN VICENTE";
                break;
            case 'US':
                return "USULUTAN";
                break;
            case 'MO':
                return "MORAZAN";
                break;
            case 'SM':
                return "SAN MIGUEL";
                break;
            case 'LU':
                return "LA UNION";
                break;
            default:
                # code...
                break;
        }
    }

    private function random() {
        $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
        $code = "";
        $longitud=5;
        for($i=0;$i<$longitud;$i++){
            $code .= $alpha[rand(0, strlen($alpha)-1)];
        }
        return $code;
    }

    public function adjetivo_genero($genero) {
        return ($genero) ? 'El Señor' : 'La Señora' ;
    }

}
?>