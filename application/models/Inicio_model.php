<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	public function obtener_estadistica_estado_reglamento(){
		$query=$this->db->query('SELECT ca.estado_estadort AS nombre, (SELECT COUNT(*) FROM sri_expedientert AS a WHERE a.id_estadort = ca.id_estadort) AS cantidad FROM sri_estadort AS ca');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_asociacion(){
		$query=$this->db->query("SELECT 'Sin resolución' AS nombre, COUNT(*) AS cantidad, (SELECT count(*) FROM sri_expedientert) AS total FROM sri_expedientert AS a WHERE a.resolucion_expedientert <> 'Aprobado' UNION SELECT 'Con resolución' AS nombre, COUNT(*) AS cantidad, (SELECT count(*) FROM sri_expedientert) AS total FROM sri_expedientert AS a WHERE a.resolucion_expedientert = 'Aprobado'");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_solicitante(){
		$query=$this->db->query("SELECT 'SOCIEDAD' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 'Sociedad' UNION SELECT 'PERSONA NATURAL' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 'Persona Natural' UNION SELECT 'ASOCIACIÓN' AS nombre, COUNT(*) AS cantidad FROM sri_expedientert AS a WHERE a.tipopersona_expedientert = 'Asociación'");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_sexo_comisionado(){
		$query=$this->db->query("SELECT 'MASCULINO' AS nombre, COUNT(*) AS cantidad FROM sri_representantert AS a WHERE a.sexo_representantert = '1' UNION SELECT 'FEMENINO' AS nombre, COUNT(*) AS cantidad FROM sri_representantert AS a WHERE a.sexo_representantert = '2'");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

}