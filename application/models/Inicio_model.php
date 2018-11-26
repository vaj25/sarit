<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	public function obtener_estadistica_estado_reglamento(){
		$query=$this->db->query('SELECT 
			ca.estado_estadort AS nombre,
			(SELECT COUNT(*) FROM (
				SELECT s.id_expedientert, f.id_estadort
				FROM sri_solicitud s
				JOIN sri_expediente_estado f ON f.id_expedientert = s.id_solicitud                    
				JOIN ( SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
						FROM sri_solicitud aa
						JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
						GROUP BY aa.id_expedientert ) b ON s.id_solicitud = b.id_solicitud AND f.id_expediente_estado = b.id_expediente_estado
				) AS a 
			WHERE a.id_estadort = ca.id_estadort) AS cantidad 
			FROM sri_estadort AS ca');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_asociacion(){
		$query=$this->db->query("SELECT 'Con resolución' AS nombre, COUNT(*) AS cantidad, (SELECT count(*) FROM sri_expedientert) AS total FROM sri_expedientert AS a WHERE a.resolucion_expedientert = 'Aprobado' UNION SELECT 'Sin resolución' AS nombre, COUNT(*) AS cantidad, (SELECT count(*) FROM sri_expedientert) AS total FROM sri_expedientert AS a WHERE a.resolucion_expedientert <> 'Aprobado'");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_solicitante(){
		$query=$this->db->query("SELECT 'SOCIEDAD' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 1 UNION SELECT 'PERSONA NATURAL' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 2 UNION SELECT 'ASOCIACIÓN' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 3 UNION SELECT 'AUTONOMAS' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 4");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_sexo_comisionado(){
		$query=$this->db->query("SELECT 'MASCULINO' AS nombre, COUNT(*) AS cantidad FROM sri_representantert AS a WHERE a.sexo_representantert = '1' UNION SELECT 'FEMENINO' AS nombre, COUNT(*) AS cantidad FROM sri_representantert AS a WHERE a.sexo_representantert = '2'");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

}