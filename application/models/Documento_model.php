<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documento_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_documento($data) {
        
        if(
            $this->db->insert(
                'sri_documentort', 
                array(
                    'id_expedientert' => $data['id_expedientert'], 
                    'docreglamento_documentort' => $data['docreglamento_documentort'],
                    'escritura_documentort' => $data['escritura_documentort'],
                    'credencial_documentort' => $data['credencial_documentort'],
                    'poder_documentort' => $data['poder_documentort'],
                    'dui_documentort' => $data['dui_documentort'],
                    'matricula_documentort' => $data['matricula_documentort'],
                    'estatutos_documentort' => $data['estatutos_documentort'],
                    'acuerdoejec_documentort' => $data['acuerdoejec_documentort'],
                    'nominayfuncion_documentort' => $data['nominayfuncion_documentort'],
                    'leycreacionescritura_documentort' => $data['leycreacionescritura_documentort'],
                    'acuerdoejecutivo_documentort' => $data['acuerdoejecutivo_documentort']
                )
            )) {
            return "exito";
        } else{
            return "fracaso";
        }

    }

    public function editar_documento($data){
        if ($this->obtener_documentos($data['id_expedientert'])) {
            
            $this->db->where('id_expedientert', $data['id_expedientert']);
            if($this->db->update(
                'sri_documentort', 
                array( 
                    'docreglamento_documentort' => $data['docreglamento_documentort'],
                    'escritura_documentort' => $data['escritura_documentort'],
                    'credencial_documentort' => $data['credencial_documentort'],
                    'poder_documentort' => $data['poder_documentort'],
                    'dui_documentort' => $data['dui_documentort'],
                    'matricula_documentort' => $data['matricula_documentort'],
                    'estatutos_documentort' => $data['estatutos_documentort'],
                    'acuerdoejec_documentort' => $data['acuerdoejec_documentort'],
                    'nominayfuncion_documentort' => $data['nominayfuncion_documentort'],
                    'leycreacionescritura_documentort' => $data['leycreacionescritura_documentort'],
                    'acuerdoejecutivo_documentort' => $data['acuerdoejecutivo_documentort']
                    )
            )){
                return "exito";
            }else{
                return "fracaso";
            }

        } else {
            $this->insertar_documento($data);
        }
		
    }
    
    public function eliminar_documento($data){
        if($this->db->delete(
            "sri_documentort",
            array('id_documentort' => $data['id_documentort']))){
            return "exito";
        }else{
            return "fracaso";
        }
    }

    public function obtener_documentos($id_solicitud) {
        $this->db->where('id_expedientert', $id_solicitud);
        $query = $this->db->get('sri_documentort');
        if ($query->num_rows() > 0) {
            return  $query->row();
        }
        else {
            return FALSE;
        }
    }
    
}

?>