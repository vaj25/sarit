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
                switch ($data['tiposolicitud_expedientert']) {
                    case 'Reforma Parcial':
                        $this->generar_acta_aprobar_parcial($id);
                        break;
                    case 'Reforma Total':
                        $this->generar_acta_aprobar_total($id);
                        break;
                    default:
                        $this->generar_acta_aprobar($id);
                        break;
                }
                break;
            case 6:
                switch ($data['tiposolicitud_expedientert']) {
                    case 'Reforma Parcial':
                        $this->generar_acta_denegada_parcial($id);
                        break;
                    case 'Reforma Total':
                        $this->generar_acta_denegada_total($id);
                        break;                    
                    default:
                        $this->generar_acta_denegada($id);
                        break;
                }
                break;
            case 2:
                $this->generar_acta_observado($id);
                break;
            case 4:
                $this->generar_acta_prevenido($id);
                break;
            default:
                # code...
                break;
        }

    }
    
    public function generar_acta_aprobar($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoAprobado/APROBACION_NUEVOREGLAMENTO-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('H')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        $templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        $templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

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

    public function generar_acta_aprobar_parcial($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoAprobadoRefParcial/APROBACION_REFORMAPARCIAL-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('dia_resolucion', dia(date('d', strtotime($expediente->fecharesolucion_expedientert))));
        $templateWord->setValue('mes_resolucion', strtoupper(mes(date('m', strtotime($expediente->fecharesolucion_expedientert)))));
        $templateWord->setValue('anio_resolucion', anio(date('Y', strtotime($expediente->fecharesolucion_expedientert))));
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='aprobacion_reglamento_parcial".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_aprobar_total($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoAprobadoRefTotal/APROBACION_REFORMATOTAL-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('dia_resolucion', dia(date('d', strtotime($expediente->fecharesolucion_expedientert))));
        $templateWord->setValue('mes_resolucion', strtoupper(mes(date('m', strtotime($expediente->fecharesolucion_expedientert)))));
        $templateWord->setValue('anio_resolucion', anio(date('Y', strtotime($expediente->fecharesolucion_expedientert))));
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='aprobacion_reglamento_total_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_denegada($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoDenegado/ADEE_NUEVOREGLAMENTO-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('dia_resolucion', dia(date('d', strtotime($expediente->fecharesolucion_expedientert))));
        $templateWord->setValue('mes_resolucion', strtoupper(mes(date('m', strtotime($expediente->fecharesolucion_expedientert)))));
        $templateWord->setValue('anio_resolucion', anio(date('Y', strtotime($expediente->fecharesolucion_expedientert))));
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='denegacion_reglamento_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_denegada_parcial($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoDenegadoRefParcial/ADEE_REFORMAPARCIAL-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('dia_resolucion', dia(date('d', strtotime($expediente->fecharesolucion_expedientert))));
        $templateWord->setValue('mes_resolucion', strtoupper(mes(date('m', strtotime($expediente->fecharesolucion_expedientert)))));
        $templateWord->setValue('anio_resolucion', anio(date('Y', strtotime($expediente->fecharesolucion_expedientert))));
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='denegacion_reglamento_parcial_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }
    
    public function generar_acta_denegada_total($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoDenegadoRefTotal/ADEE_REFORMATOTAL-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('muni_empresa', $expediente->municipio);
        $templateWord->setValue('dia_resolucion', dia(date('d', strtotime($expediente->fecharesolucion_expedientert))));
        $templateWord->setValue('mes_resolucion', strtoupper(mes(date('m', strtotime($expediente->fecharesolucion_expedientert)))));
        $templateWord->setValue('anio_resolucion', anio(date('Y', strtotime($expediente->fecharesolucion_expedientert))));
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='denegacion_reglamento_total_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_observado($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoObservado/OBSERVACION_NUEVOREGLAMENTO-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        $templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        $templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='observado_reglamento_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_prevenido($id) {

        $expediente = $this->reglamento_model->obtener_reglamento_empresa( $id )->result()[0];

        $jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];
        
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sarit/files/templates/estadoPrevenido/PREVENCION_NUEVOREGLAMENTO-expediente.docx');
        $templateWord->setValue('numero', $expediente->numexpediente_expedientert);
        $templateWord->setValue('municipio', $this->municipio($expediente->numexpediente_expedientert));
        $templateWord->setValue('horas_letras', hora(date('G')));
        $templateWord->setValue('dias_letras', dia(date('d')));
        $templateWord->setValue('mes_letras', strtoupper(mes(date('m'))));
        $templateWord->setValue('anio_letras', anio(date('Y')));
        $templateWord->setValue('hora_crea', hora(date('G', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('dia_crea', dia(date('d', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('mes_crea', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedientert)))));
        $templateWord->setValue('anio_crea', anio(date('Y', strtotime($expediente->fechacrea_expedientert))));
        $templateWord->setValue('adjetivo_representante', $this->adjetivo_genero($expediente->sexo_representantert));
        $templateWord->setValue('nombre_representante', $expediente->nombres_representantert . ' ' . $expediente->apellidos_representantert);
        $templateWord->setValue('cargo_representante', $expediente->cargo_representantert);
        $templateWord->setValue('tipo_persona', $expediente->tipopersona_expedientert);
        $templateWord->setValue('nombre_empresa', $expediente->nombre_empresa);
        $templateWord->setValue('abr_empresa', $expediente->abreviatura_empresa);
        //$templateWord->setValue('contenido', $expediente->contenidoTitulos_expedientert);
        //$templateWord->setValue('estudio_diretor', $jefe->abr_nivel_academico);
        //$templateWord->setValue('nombre_director', $jefe->nombre_completo_jefa);
        $templateWord->setValue('nombre_delegado', 
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='prevencion_reglamento_".date('dmy_His').".docx'");
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