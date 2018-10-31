<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	public function obtener_estadistica_estado_reglamento(){
		$query=$this->db->query('SELECT ca.estado_estadort AS nombre, (SELECT COUNT(*) FROM (select ex.ID_EXPEDIENTERT, f.ID_ESTADORT
		from sri_expedientert ex
		join sri_expediente_estado f on f.id_expedientert = ex.id_expedientert
		where f.fecha_exp_est = (select ee.fecha_exp_est
			from sri_expedientert e
			join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
			join sri_estadort es on es.id_estadort = ee.id_estadort
			where e.id_expedientert = ex.id_expedientert
			and ee.id_expediente_estado = (
				select
					max(eee.id_expediente_estado)
				from
					sri_expediente_estado eee
				where
					eee.id_expedientert = e.id_expedientert))) AS a WHERE a.id_estadort = ca.id_estadort) AS cantidad FROM sri_estadort AS ca');
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