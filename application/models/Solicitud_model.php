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

}
?>