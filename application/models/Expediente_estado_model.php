<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente_estado_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_expediente_estado($data) {
        if(
            $this->db->insert('sri_expediente_estado',$data
        )) {
            return "exito";
        } else{
            return "fracaso";
        }
    }

    public function obtener_reglamento_estados($id_exp) {
        $this->db->select('')
               ->from('sri_expediente_estado a ')
               ->join('sri_estadort b', 'a.id_estadort = b.id_estadort')
               ->where('a.id_expedientert', $id_exp);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }
    }

    public function obtener_ultimo_estado($id_exp) {
        $this->db->select('MAX(a.fecha_exp_est), a.id_expedientert, a.etapa_exp_est, b.id_estadort, b.estado_estadort')
               ->from('sri_expediente_estado a ')
               ->join('sri_estadort b', 'a.id_estadort = b.id_estadort')
               ->where('a.id_expedientert', $id_exp)
               ->group_by('a.id_expedientert');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }
    }

}

?>