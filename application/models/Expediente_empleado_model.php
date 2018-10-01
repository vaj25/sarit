<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente_empleado_model extends CI_Model {

    function __construct(){
		parent::__construct();
    }

    public function insertar_expediente_empleado($data) {
        if(
            $this->db->insert('sri_expediente_empleado',$data
        )) {
            return "exito";
        } else{
            return "fracaso";
        }
    }

}

?>