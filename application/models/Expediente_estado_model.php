<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente_estado_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_expediente_estado($data) {
        if(
            $this->db->insert('sri_expediente_estado',$data
        )) {
            return "exito";
        } else{
            return "fracaso";
        }
    }

    public function obtener_reglamento_estados($id_sol) {
        $this->db->select('')
               ->from('sri_expediente_estado a ')
               ->join('sri_estadort b', 'a.id_estadort = b.id_estadort')
               ->where('a.id_expedientert', $id_sol);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }
    }

    public function obtener_ultimo_estado($id_exp) {
        $this->db->select('MAX(a.fecha_exp_est), a.id_expedientert, a.etapa_exp_est, b.id_estadort, b.estado_estadort')
               ->from('sri_expediente_estado a ')
               ->join('sri_estadort b', 'a.id_estadort = b.id_estadort')
               ->where('a.id_expedientert', $id_exp)
               ->group_by('a.id_expedientert');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }
    }

    public function obtener_entradas_reporte($data, $empleado = FALSE) {

        $data["empleado"] = $empleado;

        ini_set('max_execution_time', 0); 

        /* Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior */
        
        $this->db->select("'Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior' titulo,
                    count(*) cantidad")
				->from('sri_expedientert a')
                ->join('sri_solicitud b', 'b.id_expedientert = a.id_expedientert')
                ->join('sri_expediente_estado c', 'c.id_expedientert = b.id_solicitud')
                ->join('sri_expediente_empleado d', 'd.id_expedientert = b.id_solicitud', 'left')
                ->join('(SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
                        FROM sri_solicitud aa
                        JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
                        GROUP BY aa.id_expedientert ) e', 'e.id_solicitud = b.id_solicitud AND e.id_expediente_estado = c.id_expediente_estado')
                ->where('c.id_estadort = 1');
                
        if ($empleado) {
            $this->buscar_empleado('d', 'f', $data);
        }

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(c.fecha_exp_est) <=', $data["anio"])
                    ->where('MONTH(c.fecha_exp_est) <', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	
            $tminicio = $tmfin-2;
            print('1-'.$tminicio.'-'.$data["anio"]);
            $this->db->where('c.fecha_exp_est <', $data["anio"].'-'.$tminicio.'-'.'01-');
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	
            $sminicio = $smfin-5;
            $this->db->where('YEAR(c.fecha_exp_est) <', $data["anio"].'-'.$sminicio.'-'.'01-');
        }else if($data["tipo"] == "periodo"){
            $this->db->where("c.fecha_exp_est < ", $data["value"]);
        }else{
            $this->db->where('YEAR(c.fecha_exp_est) <', $data["anio"]);
        }

        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Reglamentos Internos de Trabajo Recibidos (nuevos) */

        $this->db->select("aa.numexpediente_expedientert")
				->from('sri_expedientert aa')
                ->join('sri_solicitud ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_estado ac', 'ac.id_expedientert = ab.id_solicitud')
                ->join('sri_expediente_empleado ad', 'ad.id_expedientert = ab.id_solicitud', 'left')
                ->join('sir_empleado ae', 'ae.id_empleado = ad.id_empleado', 'left')
                ->where("aa.tiposolicitud_expedientert", 1)
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ac.fecha_exp_est asc');

        if ($empleado) {
            $this->buscar_empleado('ad', 'af', $data);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                    ->where('MONTH(aa.fechacrea_expedientert)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("aa.fechacrea_expedientert BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';
        
        $this->db->select("'Reglamentos Internos de Trabajo Recibidos (nuevos)',
                    count(*) cantidad")
                ->from($query_interna);
                
        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Reglamentos Internos de Trabajo Recibidos con Correcciones */

        $this->db->select("id_expedientert")
                ->from('sri_expediente_estado aab')
                ->where("aab.id_estadort", 2);

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(aab.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(aab.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(aab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(aab.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(aab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(aab.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("aab.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(aab.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') af';

        $this->db->select("aa.numexpediente_expedientert")
				->from('sri_expedientert aa')
                ->join('sri_solicitud ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_estado ac', 'ac.id_expedientert = ab.id_solicitud')
                ->join('sri_expediente_empleado ad', 'ad.id_expedientert = ab.id_solicitud', 'left')
                ->join('sir_empleado ae', 'ae.id_empleado = ad.id_empleado', 'left')
                ->join($query_interna, 'af.id_expedientert = ab.id_solicitud')
                ->where("aa.tiposolicitud_expedientert", 1)
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ac.fecha_exp_est asc');

        if ($empleado) {
            $this->buscar_empleado('ad', 'ag', $data);
        }

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                    ->where('MONTH(aa.fechacrea_expedientert)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("aa.fechacrea_expedientert BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';
        
        $this->db->select("'Reglamentos Internos de Trabajo Recibidos con Correcciones',
                count(*) cantidad")
                ->from($query_interna);

        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Reformas a Reglamentos Internos Recibidas */

        $this->db->select("aa.numexpediente_expedientert")
				->from('sri_expedientert aa')
                ->join('sri_solicitud ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_estado ac', 'ac.id_expedientert = ab.id_solicitud')
                ->join('sri_expediente_empleado ad', 'ad.id_expedientert = ab.id_solicitud', 'left')
                ->join('sir_empleado ae', 'ae.id_empleado = ad.id_empleado', 'left')
                ->where("(aa.tiposolicitud_expedientert = 2 or aa.tiposolicitud_expedientert = 3)")
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ac.fecha_exp_est asc');

        if ($empleado) {
            $this->buscar_empleado('ad', 'af', $data);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                    ->where('MONTH(aa.fechacrea_expedientert)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(aa.fechacrea_expedientert) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("aa.fechacrea_expedientert BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(aa.fechacrea_expedientert)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';
        
        $this->db->select("'Reformas a Reglamentos Internos Recibidas',
                count(*) cantidad")
                ->from($query_interna);

        $sql[] = '('.$this->db->get_compiled_select().')';

        $sql = implode(' UNION ', $sql);
			
		$query=$this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query;
		}
		else {
			return FALSE;
		}

    }

    public function obtener_resultados_reporte($data, $empleado = FALSE) {
        $data["empleado"] = $empleado;
       
        /* Reglamentos Internos de Trabajo Recibidos (nuevos) */

       $this->db->select("s.id_expedientert, f.id_estadort")
				->from('sri_solicitud s')
                ->join('sri_expediente_estado f', 'f.id_expedientert = s.id_solicitud')
                ->join('sri_expediente_empleado g', 'g.id_expedientert = s.id_solicitud', 'left')
				->join('( SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
                        FROM sri_solicitud aa
                        JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
                        GROUP BY aa.id_expedientert ) b', 's.id_solicitud = b.id_solicitud AND f.id_expediente_estado = b.id_expediente_estado')
                ;

        if ($empleado) {
            $this->buscar_empleado('g', 'h', $data);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(f.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(f.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(f.fecha_exp_est)', $data["anio"])
                ->where("MONTH(f.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(f.fecha_exp_est)', $data["anio"])
                ->where("MONTH(f.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("f.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(f.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '(select count(*) cant from ('.$this->db->get_compiled_select().') b where b.id_estadort = a.id_estadort) cantidad';

        $this->db->select("case 
                when a.id_estadort = 2 then
                    'Reglamentos Internos de Trabajo con Observaciones Realizadas'
                when a.id_estadort = 9 then
                    'Proyectos de Reglamentos Internos Desistidos'
                when a.id_estadort = 7 then
                    'Proyectos de Reglamentos Internos Declarados Improponibles'
                when a.id_estadort = 4 then
                    'Proyectos de Reglamentos Internos Prevenidos'
                when a.id_estadort = 5 then
                    'Proyectos de Reglamentos Internos en Calificacion de Labores (DGPS)'
                else '' end titulo, " . $query_interna )
            ->from('sri_estadort a')
            ->where("a.id_estadort", 2)
            ->or_where("a.id_estadort", 9)
            ->or_where("a.id_estadort", 7)
            ->or_where("a.id_estadort", 4)
            ->or_where("a.id_estadort", 5);

        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Proyectos de Reglamentos Internos con Observaciones de Género */

        $this->db->select("'Proyectos de Reglamentos Internos con Observaciones de Género', 
                count(*) cantidad")
				->from('sri_expedientert a')
				->join('sri_expediente_estado b', 'b.id_expedientert = a.id_expedientert')
                ->where('a.obsergenero_expedientrt', 'Si');

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(a.fechacrea_expedientert)', $data["anio"])
                    ->where('MONTH(a.fechacrea_expedientert)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(a.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(a.fechacrea_expedientert) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(a.fechacrea_expedientert)', $data["anio"])
                ->where("MONTH(a.fechacrea_expedientert) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("a.fechacrea_expedientert BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(a.fechacrea_expedientert)', $data["anio"]);
        }

        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Reformas de Reglamentos Internos Aprobados */

        $this->db->select("a.numexpediente_expedientert,
                    c.id_estadort,
                    c.fecha_exp_est")
				->from('sri_expedientert a')
                ->join('sri_solicitud b', 'a.id_expedientert = b.id_solicitud')
                ->join('sri_expediente_estado c', 'c.id_expedientert = b.id_solicitud')
                ->join('( SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
                        FROM sri_solicitud aa
                        JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
                        GROUP BY aa.id_expedientert ) d', 'd.id_solicitud = b.id_solicitud AND d.id_expediente_estado = c.id_expediente_estado')
                ->join('sri_expediente_empleado e', 'e.id_expedientert = b.id_solicitud', 'left')
                ->where('(b.id_tipo_solicitud = 2 OR b.id_tipo_solicitud = 3)')
                ->where('c.id_estadort', '3')
                ->order_by('a.numexpediente_expedientert desc')
                ->order_by('c.fecha_exp_est asc');

        if ($empleado) {
            $this->buscar_empleado('e', 'f', $data);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(c.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                ->where("MONTH(c.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                ->where("MONTH(c.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("c.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Reformas de Reglamentos Internos Aprobados' titulo,
                count(*) cantidad")
                ->from($query_interna);
                
        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Proyectos de Reglamentos Internos Aprobados */

        $this->db->select("a.numexpediente_expedientert,
                    c.id_estadort,
                    c.fecha_exp_est")
				->from('sri_expedientert a')
                ->join('sri_solicitud b', 'a.id_expedientert = b.id_solicitud')
                ->join('sri_expediente_estado c', 'c.id_expedientert = b.id_solicitud')
                ->join('( SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
                        FROM sri_solicitud aa
                        JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
                        GROUP BY aa.id_expedientert ) d', 'd.id_solicitud = b.id_solicitud AND d.id_expediente_estado = c.id_expediente_estado')
                ->join('sri_expediente_empleado e', 'e.id_expedientert = b.id_solicitud', 'left')    
                ->where('b.id_solicitud', 1)
                ->where('c.id_estadort', '3')
                ->order_by('a.numexpediente_expedientert desc')
                ->order_by('c.fecha_exp_est asc');

        if ($empleado) {
            $this->buscar_empleado('e', 'f', $data);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(c.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                ->where("MONTH(c.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"])
                ->where("MONTH(c.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("c.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(c.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Proyectos de Reglamentos Internos Aprobados' titulo,
                count(*) cantidad")
                ->from($query_interna);
        
        $sql[] = '('.$this->db->get_compiled_select().')';

         /* Casos Reasignados (cambio de Colaborador) */

         $this->db->select("a.numexpediente_expedientert,
                    count(*) cant,
                    a.id_expedientert")
            ->from('sri_expedientert a')
            ->join('sri_solicitud b', 'a.id_expedientert = b.id_expedientert')
            ->join('sri_expediente_empleado c', 'c.id_expedientert = b.id_solicitud')
            ->join('sri_expediente_estado d', 'd.id_expedientert = b.id_solicitud')
            ->group_by('b.id_solicitud')
            ->having('count(distinct a.numexpediente_expedientert, c.id_empleado) > 1');

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(d.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(d.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(d.fecha_exp_est)', $data["anio"])
                ->where("MONTH(d.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(d.fecha_exp_est)', $data["anio"])
                ->where("MONTH(d.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("d.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(d.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Casos Reasignados (cambio de Colaborador)' titulo,
                count(*) cantidad")
                ->from($query_interna)
                ->join('sri_expediente_empleado b', 'b.id_expedientert = a.id_expedientert');
        
        if ($empleado) {
            $this->buscar_empleado('b', 'e', $data);
        }
        
        $sql[] = '('.$this->db->get_compiled_select().')';

        $sql = implode(' UNION ', $sql);
			
		$query=$this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query;
		}
		else {
			return FALSE;
		}
    }

    public function obtener_asignados_reporte($data, $empleado = FALSE) {

        $this->db->select("
                    a.fecha_ingresar_exp_est,
                    b.estado_estadort,
                    g.numexpediente_expedientert,
                    g.fechacrea_expedientert,
                    d.nombre_empresa,
                    e.seccion_catalogociiu,
                    DATEDIFF(a.fecha_ingresar_exp_est,
                    g.fechacrea_expedientert) servicio")
				->from('sri_expediente_estado a')
                ->join('sri_estadort b', 'b.id_estadort = a.id_estadort')
                ->join('sri_solicitud c', 'c.id_solicitud = a.id_expedientert')
                ->join('sri_expedientert g', 'g.id_expedientert = c.id_solicitud')
                ->join('sge_empresa d', 'g.id_empresart = d.id_empresa')
                ->join('sge_catalogociiu e', 'e.id_catalogociiu = d.id_catalogociiu')
                ->join('sri_expediente_empleado f', 'f.id_expedientert = c.id_expedientert', 'left')
                ->join('( SELECT max(aa.id_solicitud) id_solicitud, max(ab.id_expediente_estado) id_expediente_estado
                            FROM sri_solicitud aa
                            JOIN sri_expediente_estado ab ON ab.id_expedientert = aa.id_solicitud
                            GROUP BY aa.id_expedientert ) h', 'h.id_solicitud = c.id_solicitud AND h.id_expediente_estado = a.id_expediente_estado')
                ->order_by('b.id_estadort DESC');

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(a.fecha_ingresar_exp_est)', $data["anio"])
                    ->where('MONTH(a.fecha_ingresar_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(a.fecha_ingresar_exp_est)', $data["anio"])
                ->where("MONTH(a.fecha_ingresar_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(a.fecha_ingresar_exp_est)', $data["anio"])
                ->where("MONTH(a.fecha_ingresar_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("a.fecha_ingresar_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(a.fecha_ingresar_exp_est)', $data["anio"]);
        }

        if ( $data["empleado"] != '' ) {

            $this->buscar_empleado('f', 'i', $data);

        }

        $sql = $this->db->get_compiled_select();

        $query = $this->db->query($sql);
		if ($query->num_rows() > 0) {

            $this->db->select("sum(aa.servicio) duracion, avg(aa.servicio) prom_duracion")
                     ->from("( $sql ) aa");

            $query2 = $this->db->get();

			return array(
                'expedientes' => $query->result(),
                'duracion' => $query2->row()
            );
		} else {
			return FALSE;
		}

    }

    private function buscar_empleado( $tabla_empleado, $alias, $data ) {
        $fecha_ultima = new DateTime();

        if($data["tipo"] == "mensual"){

            $fecha_ultima->modify('last day of '. date('F', mktime(0, 0, 0, $data["value"], 1, $data["anio"])) .' '. $data["anio"]);

        }else if($data["tipo"] == "trimestral"){

            $tmfin = (intval($data["value"])*3);
            $fecha_ultima->modify('last day of '. date('F', mktime(0, 0, 0, $tmfin, 1, $data["anio"])) .' '. $data["anio"]);

        }else if($data["tipo"] == "semestral"){

            $smfin = (intval($data["value"])*6);
            $fecha_ultima->modify('last day of '. date('F', mktime(0, 0, 0, $tmfin, 1, $data["anio"])) .' '. $data["anio"]);

        }else if($data["tipo"] == "periodo"){

            $fecha_ultima->modify('last day of '. date('F', $data["value2"]) .' '. date('Y', $data["value2"]));

        }

        $this->db->where( $tabla_empleado . '.id_empleado', $data["empleado"])
                ->join('( SELECT max(ac.id_exp_emp) id_expediente_empleado, ac.fecha_exp_emp
                            FROM sri_solicitud aa
                            JOIN sri_expediente_empleado ac ON ac.id_expedientert = aa.id_solicitud
                            GROUP BY aa.id_expedientert) ' . $alias , 
                            $alias . '.id_expediente_empleado = '. $tabla_empleado .'.id_exp_emp AND '. $alias .'.fecha_exp_emp <= "'.$fecha_ultima->format('Y-m-d').'"');
    }

}

?>