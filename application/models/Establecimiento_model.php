<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_empresa($data) {
        
        if(
            $this->db->insert(
                'sge_empresa', 
                array(
                    'numinscripcion_empresa' => $data['numinscripcion_empresa'], 
                    'nombre_empresa' => $data['nombre_empresa'],
                    'abreviatura_empresa' => $data['abreviatura_empresa'],
                    'direccion_empresa' => $data['direccion_empresa'],
                    'telefono_empresa' => $data['telefono_empresa'],
                    'id_catalogociiu' => $data['id_catalogociiu'],
                    'id_municipio' => $data['id_municipio']
                )
            )) {
            return $this->db->insert_id();
        } else{
            return "fracaso";
        }

    }

    public function cantidad_expediente_establecimiento($id) {
        $this->db->select('count(*) expedientes')
                ->from('sge_empresa a')
                ->join('sri_expedientert b', 'b.id_empresart = a.id_empresa')
                ->where('a.id_empresa', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }
    }
    
}

?>