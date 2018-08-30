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

    public function editar_comisionado($data){
		$this->db->where("id_representantert",$data["id_representantert"]);
		if($this->db->update(
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
            )){
			return "exito";
		}else{
			return "fracaso";
		}
    }
    
}

?>