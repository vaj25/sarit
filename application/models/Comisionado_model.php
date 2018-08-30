<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comisionado_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_comisionado($data) {
        
        if(
            $this->db->insert(
                'sri_representantert',
                array(
                    'id_empresart' => $data['id_empresart'], 
                    'nombres_representantert' => $data['nombres_representantert'],
                    'apellidos_representantert' => $data['apellidos_representantert'],
                    'dui_representantert'  => $data['dui_representantert'],
                    'nit_representantert' => $data['nit_representantert'],
                    'telefono_representantert' => $data['telefono_representantert'],
                    'correo_representantert' => $data['correo_representantert'],
                    'cargo_representantert' => $data['cargo_representantert'],
                    'sexo_representantert' => $data['sexo_representantert']
                )
            )) {
            return "exito";
        } else{
            return "fracaso";
        }

    }

    public function editar_estado($data){
		$this->db->where("id_estadort",$data["id_estadort"]);
		if($this->db->update(
            'sri_estadort', 
            array(
                    'estado_estadort' => $data['estado_estadort'], 
                    'descripcion_estadort' => $data['descripcion_estadort']
                )
            )){
			return "exito";
		}else{
			return "fracaso";
		}
    }
    
    public function eliminar_estado($data){
        if($this->db->delete(
            "sri_estadort",
            array('id_estadort' => $data['id_estadort']))){
            return "exito";
        }else{
            return "fracaso";
        }
    }

    public function obtener_estado() {
        
        $this->db->select('')
               ->from('sri_expedientert a')
               ->join('lista_empleados_estado b','b.id_empleado = a.id_personal')
               ->join('sge_empresa c','c.id_empresa = a.id_empresart');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamentos_documentos($id) {
        
        $this->db->select('')
               ->from('sri_expedientert a')
               ->join('sri_documentort b ', ' b.id_expedientert = a.id_expedientert')
               ->where('a.id_expedientert', $id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }

    }
    
}

?>