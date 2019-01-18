<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_representante($data) {
        
        if(
            $this->db->insert(
                'sge_representante', 
                array(
                    'nombres_representante' => $data['nombres_representante'], 
                    'id_empresa' => $data['id_empresa']
                )
            )) {
            return $this->db->insert_id();
        } else{
            return "fracaso";
        }

    }

    public function obtenerRepresentantesEmpresa($id_empresa) {
        $this->db->select('a.*')
                ->from('sri_representantert a')
                ->where('a.id_empresart', $id_empresa);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function obtenerRepresentante($id) {
        $this->db->select('a.*')
                ->from('sri_representantert a')
                ->where('a.id_representantert', $id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
    
}

?>