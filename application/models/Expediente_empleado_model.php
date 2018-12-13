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

    public function obtener_delegados_seccion() {
        $this->db->select("
                e.id_empleado,
                e.nr,
                upper(concat_ws(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) as nombre_completo,
                r.nombre_rol,
                r.id_rol,
                u.sexo
                ")
               ->from('sir_empleado e')
               ->join('org_usuario u', 'e.nr = u.nr')
               ->join('org_usuario_rol ur', 'u.id_usuario = ur.id_usuario')
               ->join('org_rol r', 'ur.id_rol = r.id_rol')
               ->where('e.id_estado', '00001')
               ->where('r.id_rol', COLABORADOR)
               ->or_where('r.id_rol', FILTRO)
               ->order_by('e.primer_nombre,
                    e.segundo_nombre,
                    e.tercer_nombre,
                    e.primer_apellido,
                    e.segundo_apellido,
                    e.apellido_casada');
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