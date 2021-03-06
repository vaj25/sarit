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
        $this->db->select('
                CASE
                    WHEN TIMESTAMPDIFF( MONTH, c.fecha_exp_est, now() ) IS NULL THEN 8
                    ELSE TIMESTAMPDIFF( MONTH, c.fecha_exp_est, now() )
                END AS meses 
                ')
                ->from('sri_expedientert a')
                ->join('sri_solicitud b', 'b.id_expedientert = a.id_expedientert')
                ->join('sri_expediente_estado c', 'c.id_expedientert = b.id_solicitud')
                ->where('b.id_solicitud = (
                    SELECT
                        MAX(aa.id_solicitud)
                    FROM
                        sri_solicitud aa
                    WHERE
                        aa.id_expedientert = a.id_expedientert )')
                ->where('c.id_expediente_estado = (
                    SELECT
                        MAX(ba.id_expediente_estado)
                    FROM
                        sri_expediente_estado ba
                    WHERE
                        ba.id_expedientert = b.id_solicitud )')
                ->where('( c.id_estadort <> 3 OR c.id_estadort <> 9 )')
                ->where('a.id_empresart', $id);

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