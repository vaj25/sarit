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
                estado_estadort,
                a.archivo_expedientert,
                concat_ws(' ',b.primer_nombre,b.segundo_nombre,b.tercer_nombre,b.primer_apellido,b.segundo_apellido,b.apellido_casada) AS nombre_empleado")
               ->from('sri_expedientert a')
               ->join('sir_empleado b','b.id_empleado = a.id_personal', 'left')
               ->join('sge_empresa c','c.id_empresa = a.id_empresart')
               ->join('sri_estadort d','a.id_estadort = d.id_estadort');
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
               ->join('sri_documentort b ', ' b.id_expedientert = a.id_expedientert')
               ->join('sge_empresa c','c.id_empresa = a.id_empresart')
               ->join('sri_representantert d ', ' c.id_empresa = a.id_empresart')
               ->where('a.id_expedientert', $id);
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
               ->join('sge_representante e', 'e.id_empresa = b.id_empresa')
               ->where('a.id_expedientert', $id);
        $query=0;//$this->db->get();
        print $this->db->get_compiled_select();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }
    
}

?>