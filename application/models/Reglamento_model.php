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
                    'id_personal' => $data['id_personal'],
                    'id_estadort' => $data['id_estadort'],
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
                    'inhabilitado_expedientert' => $data['inhabilitado_expedientert'],
                    'archivo_expedientert' => $data['archivo_expedientert'],
                    'fechacrea_expedientert' => date("Y-m-d H:i:s")
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
                'id_personal' => $data['id_personal'],
                'id_estadort' => $data['id_estadort'],
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
                'inhabilitado_expedientert' => $data['inhabilitado_expedientert'],
                'archivo_expedientert' => $data['archivo_expedientert']
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
               ->where('id_expedientert', $id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamentos() {
        
        $this->db->select("
                a.id_expedientert,
                numexpediente_expedientert,
                nombre_empresa,
                tiposolicitud_expedientert,
                fecharesolucion_expedientert,
                d.id_estadort,
                a.id_personal,
                estado_estadort,
                a.archivo_expedientert,
                concat_ws(' ',b.primer_nombre,b.segundo_nombre,b.tercer_nombre,b.primer_apellido,b.segundo_apellido,b.apellido_casada) AS nombre_empleado")
               ->from('sri_expedientert a')
               ->join('sir_empleado b','b.id_empleado = a.id_personal', 'left')
               ->join('sge_empresa c','c.id_empresa = a.id_empresart')
               ->join('sri_estadort d','a.id_estadort = d.id_estadort')
               ->where('a.id_expedientert IN ( SELECT MAX(e.id_expedientert)  FROM sri_expedientert e GROUP BY e.numexpediente_expedientert )');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_reglamentos_documentos($id) {
        
        $this->db->select('')
               ->from('sri_expedientert a')
               ->join('sri_representantert b', 'a.id_empresart = b.id_empresart')
               ->join('sri_documentort d', 'd.id_expedientert = a.id_expedientert')
               ->join('sge_empresa e', 'e.id_empresa = a.id_empresart')
               ->where('a.id_expedientert', $id)
               ->where('b.ID_REPRESENTANTERT = ( SELECT MAX(c.ID_REPRESENTANTERT) FROM SRI_REPRESENTANTERT c WHERE c.ID_EMPRESART = a.ID_EMPRESART )');
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
               ->from('sri_expedientert a')
               ->join('sge_empresa b', 'b.id_empresa = a.id_empresart')
               ->join('sge_catalogociiu c', 'c.id_catalogociiu = b.id_catalogociiu')
               ->join('org_municipio d', 'd.id_municipio = b.id_municipio')
               ->join('sri_representantert e', 'e.id_empresart = b.id_empresa')
               ->join('sir_empleado f','f.id_empleado = a.id_personal', 'left')
               ->where('a.id_expedientert', $id);
        $query=$this->db->get();
        //print $this->db->get_compiled_select();
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