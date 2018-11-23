<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reglamento_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_reglamento($data) {
        
        if(
            $this->db->insert(
                'sri_expedientert',
                array(
                    'id_empresart' => $data['id_empresart'],
                    'numexpediente_expedientert' => $data['numexpediente_expedientert'],
                    'tipopersona_expedientert' => $data['tipopersona_expedientert'],
                    'tiposolicitud_expedientert' => $data['tiposolicitud_expedientert'],
                    'organizacionsocial_expedientert' => $data['organizacionsocial_expedientert'],
                    'contratocolectivo_expedientert' => $data['contratocolectivo_expedientert'],
                    'notificacion_expedientert' => $data['notificacion_expedientert'],
                    'fechanotificacion_expedientert' => $data['fechanotificacion_expedientert'],
                    'resolucion_expedientert' => $data['resolucion_expedientert'],
                    'fecharesolucion_expedientert' => $data['fecharesolucion_expedientert'],
                    'archivo_expedientert' => $data['archivo_expedientert'],
                    'obsergenero_expedientrt' => $data['obsergenero_expedientrt'],
                    'contenidoTitulos_expedientert' => $data['contenidoTitulos_expedientert'],
                    'desistido_expedientert' => $data['desistido_expedientert'],
                    'archivo_expedientert' => $data['archivo_expedientert'],
                    'fechacrea_expedientert' => date("Y-m-d H:i:s"),
                    'numeroexpediente_anterior' => $data['numeroexpediente_anterior']
                )
            )) {
            return $this->db->insert_id();
        } else{
            return "fracaso";
        }

    }

    public function editar_reglamento($data){
		$this->db->where("id_expedientert",$data["id_expedientert"]);
		if($this->db->update(
            'sri_expedientert', 
            array(
                'id_empresart' => $data['id_empresart'],
                'numexpediente_expedientert' => $data['numexpediente_expedientert'],
                'tipopersona_expedientert' => $data['tipopersona_expedientert'],
                'tiposolicitud_expedientert' => $data['tiposolicitud_expedientert'],
                'organizacionsocial_expedientert' => $data['organizacionsocial_expedientert'],
                'contratocolectivo_expedientert' => $data['contratocolectivo_expedientert'],
                'notificacion_expedientert' => $data['notificacion_expedientert'],
                'fechanotificacion_expedientert' => $data['fechanotificacion_expedientert'],
                'resolucion_expedientert' => $data['resolucion_expedientert'],
                'fecharesolucion_expedientert' => $data['fecharesolucion_expedientert'],
                'archivo_expedientert' => $data['archivo_expedientert'],
                'obsergenero_expedientrt' => $data['obsergenero_expedientrt'],
                'contenidoTitulos_expedientert' => $data['contenidoTitulos_expedientert'],
                'desistido_expedientert' => $data['desistido_expedientert'],
                'archivo_expedientert' => $data['archivo_expedientert'],
                'fecha_entrega' => $data['fecha_entrega'],
                'persona_recibe' => $data['persona_recibe']
                )
            )){
			return $data["id_expedientert"];
		}else{
			return "fracaso";
		}
    }
    
    public function eliminar_documento($data){
        if($this->db->delete(
            "sri_expedientert",
            array('id_expedientert' => $data['id_expedientert']))){
            return "exito";
        }else{
            return "fracaso";
        }
    }

    public function obtener_reglamento($id) {
        
        $this->db->select('')
               ->from('sri_expedientert a')
               ->join('sri_expediente_empleado f', 'f.id_expedientert = a.id_expedientert')
               ->join('sir_empleado h', 'h.id_empleado = f.id_empleado')
               ->where('a.id_expedientert', $id)
               ->where('h.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = a.id_expedientert ))');
        $query=$this->db->get();
        // print $this->db->get_compiled_select();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamentos($nr = false, $tipo = false, $letra = false) {
        
        $this->db->select("
                a.id_expedientert,
                a.numexpediente_expedientert,
                a.numeroexpediente_anterior,
                a.archivo_expedientert,
                b.id_solicitud,
                b.fecharesolucion_solicitud,
                b.fechacrea_solicitud,
                c.nombre_empresa,
                d.fecha_exp_est,
                d.etapa_exp_est,
                e.id_estadort,
                e.estado_estadort,
                f.id_empleado id_personal,
                CASE f.id_empleado
                    WHEN NULL THEN ''
                    ELSE ( SELECT CONCAT_WS(' ', ba.primer_nombre, ba.segundo_nombre, ba.tercer_nombre, 
                            ba.primer_apellido, ba.segundo_apellido, ba.apellido_casada) FROM sir_empleado ba
                            WHERE ba.id_empleado = f.id_empleado )
                END AS nombre_empleado,
                CASE g.id_representantert
                    WHEN NULL THEN g.id_representantert
                    ELSE ( SELECT max(ca.id_representantert) FROM sri_representantert ca
                            WHERE ca.id_empresart = g.id_empresart )
                END AS id_representantert,
                h.nombre_tipo_solicitud tiposolicitud_expedientert")
               ->from('sri_expedientert a')
               ->join('sri_solicitud b ', 'a.id_expedientert = b.id_expedientert')
               ->join('sge_empresa c', 'c.id_empresa = a.id_empresart')
               ->join('sri_expediente_estado d', 'd.id_expedientert = b.id_solicitud')
               ->join('sri_estadort e', 'e.id_estadort = d.id_estadort')
               ->join('sri_expediente_empleado f', 'f.id_expedientert = b.id_solicitud', 'left')
               ->join('sri_representantert g', 'a.id_empresart = g.id_empresart', 'left')
               ->join('sri_tipo_solicitud h', 'a.tiposolicitud_expedientert = h.id_tipo_solicitud')
               ->join('sir_empleado i', 'i.id_empleado = f.id_empleado', 'left')
               ->where('b.id_solicitud = (
                    SELECT max(aa.id_solicitud)
                    FROM sri_solicitud aa
                    WHERE aa.id_expedientert = a.id_expedientert
                )')
               ->where('d.id_expediente_estado = (
                    SELECT max(ca.id_expediente_estado)
                    FROM sri_expediente_estado ca
                    WHERE ca.id_expedientert = b.id_solicitud
                )')
                ->where('CASE
                        WHEN f.id_empleado IS NOT NULL THEN (
                            f.id_exp_emp = (SELECT max(da.id_exp_emp)
                            FROM sri_expediente_empleado da
                            WHERE da.id_expedientert = a.id_expedientert ) )
                        ELSE TRUE END')
                ->order_by('d.fecha_exp_est', 'desc')
                ->order_by('d.id_estadort', 'asc');
        if ($nr) {
            $this->db->where('i.nr', $nr);
        }
        
        if ($tipo) {
            $this->db->where('d.id_estadort', $tipo);
        }

        if ($letra) {
            $this->db->like('c.nombre_empresa', $letra, 'after');
        }
        
        // print $this->db->get_compiled_select();
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamentos_numero( $numero = false ) {
        
        $this->db->select("
                a.id_expedientert,
                a.numeroexpediente_anterior,
	            a.numexpediente_expedientert,
                c.nombre_empresa,
                d.id_empleado,
                CASE
                    d.id_empleado
                    WHEN NULL THEN ''
                    ELSE (
                    SELECT
                        CONCAT_WS(' ', ba.primer_nombre, ba.segundo_nombre, ba.tercer_nombre, ba.primer_apellido, ba.segundo_apellido, ba.apellido_casada)
                    FROM
                        sir_empleado ba
                    WHERE
                        ba.id_empleado = d.id_empleado )
                END AS nombre_empleado,
                f.nombre_tipo_solicitud,
                e.id_representantert,
                CONCAT_WS(' ', e.nombres_representantert, e.apellidos_representantert) AS nombre_representante")
               ->from('sri_expedientert a')
               ->join('sri_solicitud b', 'a.id_expedientert = b.id_expedientert')
               ->join('sge_empresa c', 'c.id_empresa = a.id_empresart')
               ->join('sri_expediente_empleado d', 'd.id_expedientert = b.id_solicitud', 'left')
               ->join('sri_representantert e', 'a.id_empresart = e.id_empresart', 'left')
               ->join('sri_tipo_solicitud f', 'a.tiposolicitud_expedientert = f.id_tipo_solicitud')
               ->join('sir_empleado g', 'g.id_empleado = d.id_empleado', 'left')
               ->where('a.id_expedientert', $numero);
        
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        } else {
            return FALSE;
        }

    }

    public function obtener_reglamentos_documentos($id, $old = FALSE) {
        
        $this->db->select("
                a.id_solicitud,
                a.id_tipo_solicitud tiposolicitud_expedientert,
                b.id_expedientert,
                b.tipopersona_expedientert,
                c.id_representantert,
                c.nombres_representantert,
                c.apellidos_representantert,
                c.dui_representantert,
                c.nit_representantert,
                c.telefono_representantert,
                c.correo_representantert,
                c.cargo_representantert,
                c.sexo_representantert,
                d.docreglamento_documentort,
                d.escritura_documentort,
                d.credencial_documentort,
                d.poder_documentort,
                d.dui_documentort,
                d.matricula_documentort,
                d.estatutos_documentort,
                d.acuerdoejec_documentort,
                d.nominayfuncion_documentort,
                d.leycreacionescritura_documentort,
                d.acuerdoejecutivo_documentort,
                e.id_empresa,
                CASE f.id_empleado
                    WHEN NULL THEN ''
                    ELSE f.id_empleado
                END AS id_empleado
                ")
               ->from('sri_solicitud a')
               ->join('sri_expedientert b', 'b.id_expedientert = a.id_expedientert')
               ->join('sri_representantert c', 'c.id_empresart = b.id_empresart', 'left')
               ->join('sri_documentort d', 'd.id_expedientert = a.id_expedientert', 'left')
               ->join('sge_empresa e', 'e.id_empresa = b.id_empresart')
               ->join('sri_expediente_empleado f', 'f.id_expedientert = a.id_expedientert', 'left')
               ->join('sir_empleado h', 'h.id_empleado = f.id_empleado', 'left')
               ->where('a.id_solicitud', $id)
               ->where('f.id_empleado = (SELECT max(aa.id_empleado) FROM sri_expediente_empleado aa WHERE aa.id_expedientert = a.id_solicitud)');
               
        if ($old) {
            $this->db->where('c.id_representantert = (SELECT max(ab.id_representantert) FROM sri_representantert ab WHERE ab.id_empresart = e.id_empresa)');
        }
        // print $this->db->get_compiled_select();
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamento_empresa($id) {
        
        $this->db->select('')
               ->from('sri_solicitud a')
               ->join('sri_expedientert b', 'a.id_expedientert = b.id_expedientert')
               ->join('sge_empresa c', 'c.id_empresa = b.id_empresart')
               ->join('sge_catalogociiu d', 'd.id_catalogociiu = c.id_catalogociiu')
               ->join('org_municipio e', 'e.id_municipio = c.id_municipio')
               ->join('sri_representantert f', 'f.id_empresart = c.id_empresa', 'left')
               ->join('sri_tipo_solicitante g', 'b.tipopersona_expedientert = g.id_tipo_solicitante')
               ->join('sri_tipo_solicitud h', 'a.id_tipo_solicitud = h.id_tipo_solicitud')
               ->where('a.id_solicitud', $id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function jefe_direccion_trabajo() {
        $query = $this->db->query("SELECT
                CONCAT_WS(' ',e.primer_nombre,e.segundo_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) nombre_completo_jefa,
                s.nombre_seccion,
                CASE
                    WHEN na.NIVEL_ACADEMICO = 'LICENCIATURAS' THEN
                        CASE 
                            WHEN e.ID_GENERO = 2 THEN 'Licda.'
                            ELSE 'Lic.'
                        END
                    WHEN na.NIVEL_ACADEMICO = 'INGENIERÍAS' THEN
                        CASE
                            WHEN e.ID_GENERO = 2 THEN 'Inga.'
                            ELSE 'Ing.'
                        END
                    WHEN na.NIVEL_ACADEMICO = 'DOCTORADOS' THEN
                        CASE
                            WHEN e.ID_GENERO = 2 THEN 'Dra.'
                            ELSE 'Dr.'
                        END
                    ELSE ''
                END abr_nivel_academico,
                na.nivel_academico
            FROM org_seccion s
            JOIN sir_empleado_informacion_laboral il on il.id_seccion=s.id_seccion
            JOIN sir_empleado e on e.id_empleado=il.id_empleado
            JOIN sir_cargo_funcional cf on cf.id_cargo_funcional=il.id_cargo_funcional
            JOIN SIR_EMPLEADO_TITULO et ON et.ID_EMPLEADO = e.ID_EMPLEADO
            JOIN SIR_TITULO_ACADEMICO ta ON ta.ID_TITULO_ACADEMICO = et.ID_TITULO_ACADEMICO
            JOIN SIR_NIVEL_ACADEMICO na ON na.ID_NIVEL_ACADEMICO = ta.ID_NIVEL_ACADEMICO
            WHERE s.id_seccion=43 AND cf.id_nivel=1 AND e.id_estado=1
            ORDER BY il.ID_EMPLEADO_INFORMACION_LABORAL DESC
            LIMIT 1;");

        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }
    }
    
}

?>