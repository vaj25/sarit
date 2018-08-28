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
                    'nominayfuncion_documentort' => $data['nominayfuncion_documentort']
                )
            )) {
            return "exito";
        } else{
            return "fracaso";
        }

    }

    public function editar_documento($data){
		$this->db->where("id_documentort",$data["id_documentort"]);
		if($this->db->update(
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
                'nominayfuncion_documentort' => $data['nominayfuncion_documentort']
                )
           )){
			return "exito";
		}else{
			return "fracaso";
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
    
}

?>