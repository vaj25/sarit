<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_model extends CI_Model {

	function __construct(){
		parent::__construct();
  }

  public function insertar_solicitud($data) {
    if(
      $this->db->insert(
          'sri_solicitud',
          $data
      )) {
      return $this->db->insert_id();
    } else{
        return "fracaso";
    }
  }

  public function editar_solitud($data) {
    $this->db->where("id_solicitud",$data["id_solicitud"]);
		if($this->db->update(
            'sri_solicitud',
            $data
            )){
			return $data["id_solicitud"];
		}else{
			return "fracaso";
		}
  }

  public function obtener_solicitud($id) {
    $this->db->where("id_solicitud", $id);
    
    $query=$this->db->get('sri_solicitud');
    
    if ($query->num_rows() > 0) {
        return  $query;
    } else {
        return FALSE;
    }
  }

  public function obtener_solicitud_detallada($id_solicitud) {
    $this->db->select('')
          ->from('sri_expedientert a')
          ->join('sri_solicitud b', 'b.id_expedientert = a.id_expedientert')
          ->join('sri_expediente_empleado f', 'f.id_expedientert = b.id_solicitud')
          ->join('sir_empleado h', 'h.id_empleado = f.id_empleado')
          ->where('b.id_solicitud', $id_solicitud)
          ->where('h.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                    where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                    where se.id_expedientert = b.id_solicitud ))');
    
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->row();
    }
    else {
      return FALSE;
    }
  }

}
?>