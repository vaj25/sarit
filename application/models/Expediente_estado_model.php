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

    public function obtener_reglamento_estados($id_exp) {
        $this->db->select('')
               ->from('sri_expediente_estado a ')
               ->join('sri_estadort b', 'a.id_estadort = b.id_estadort')
               ->where('a.id_expedientert', $id_exp);
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
        /* Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior */

        $this->db->select("'Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior' titulo,
                    count(*) cantidad")
				->from('sri_expedientert a')
                ->join('sri_expediente_estado f', 'f.id_expedientert = a.id_expedientert')
                ->join('sri_expediente_empleado b', 'b.id_expedientert = a.id_expedientert')
                ->join('sir_empleado bc', 'bc.id_empleado = b.id_empleado')
                ->where('a.id_expedientert in (
                    select max(aa.id_expedientert)
                    from sri_expedientert aa
                    join sri_expediente_estado fa on fa.id_expedientert = aa.id_expedientert
                    join sri_estadort da on da.id_estadort = fa.id_estadort
                    where fa.fecha_exp_est = (
                            select eea.fecha_exp_est
                            from sri_expedientert ea
                            join sri_expediente_estado eea on eea.id_expedientert = ea.id_expedientert
                            join sri_estadort esa on esa.id_estadort = eea.id_estadort
                            where ea.id_expedientert = aa.id_expedientert and esa.id_estadort <> 9 and eea.fecha_exp_est =(
                                    select max(eeea.fecha_exp_est) from sri_expediente_estado eeea
                                    where eeea.id_expedientert = ea.id_expedientert)) group by aa.numexpediente_expedientert )')
                ->where('f.fecha_exp_est = (
                    select ee.fecha_exp_est from sri_expedientert e
                    join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
                    join sri_estadort es on es.id_estadort = ee.id_estadort
                    where e.id_expedientert = a.id_expedientert
                        and ee.fecha_exp_est =(
                        select max(eee.fecha_exp_est)
                        from sri_expediente_estado eee
                        where eee.id_expedientert = e.id_expedientert))')
                ->where('b.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = a.id_expedientert ))')
                ->where('f.etapa_exp_est <> 4')
                ->where('f.id_estadort = 1');
                
        if ($empleado) {
            $this->db->where('b.id_empleado', $empleado);
        }

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(f.fecha_exp_est) <=', $data["anio"])
                    ->where('MONTH(f.fecha_exp_est) <', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	
            $tminicio = $tmfin-2;
            print('1-'.$tminicio.'-'.$data["anio"]);
            $this->db->where('f.fecha_exp_est <', $data["anio"].'-'.$tminicio.'-'.'01-');
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	
            $sminicio = $smfin-5;
            $this->db->where('YEAR(f.fecha_exp_est) <', $data["anio"].'-'.$sminicio.'-'.'01-');
        }else if($data["tipo"] == "periodo"){
            $this->db->where("f.fecha_exp_est < ", $data["value"]);
        }else{
            $this->db->where('YEAR(f.fecha_exp_est) <', $data["anio"]);
        }

        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Reglamentos Internos de Trabajo Recibidos (nuevos) */

        $this->db->select("aa.numexpediente_expedientert")
				->from('sri_expedientert aa')
                ->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where("aa.tiposolicitud_expedientert", 1)
                //->where('(ab.id_estadort = 1 or ab.id_estadort = 3)')
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
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

        $this->db->select("aa.numexpediente_expedientert")
				->from('sri_expedientert aa')
                ->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where("aa.tiposolicitud_expedientert", 1)
                ->where('ab.fecha_exp_est = (
                            select ee.fecha_exp_est from sri_expedientert e
                            join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
                            join sri_estadort es on es.id_estadort = ee.id_estadort
                            where e.id_expedientert = aa.id_expedientert
                                and ee.fecha_exp_est =(
                                select max(eee.fecha_exp_est)
                                from sri_expediente_estado eee
                                where eee.id_expedientert = e.id_expedientert))')
                ->where('(select count(*) from sri_expediente_estado aab where aab.id_expedientert = aa.id_expedientert) >', '1')
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
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
                ->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where("(aa.tiposolicitud_expedientert = 2 or aa.tiposolicitud_expedientert = 3)")
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
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
       /* Reglamentos Internos de Trabajo Recibidos (nuevos) */

       $this->db->select("aa.numexpediente_expedientert, ab.id_estadort")
				->from('sri_expedientert aa')
				->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
				->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where('aa.id_expedientert in (
                    select max(aaa.id_expedientert)
                    from sri_expedientert aaa
                    join sri_expediente_estado aab on aab.id_expedientert = aaa.id_expedientert
                    where aab.fecha_exp_est = (
                            select eea.fecha_exp_est
                            from sri_expedientert ea
                            join sri_expediente_estado eea on eea.id_expedientert = ea.id_expedientert
                            join sri_estadort esa on esa.id_estadort = eea.id_estadort
                            where ea.id_expedientert = aaa.id_expedientert
                                and eea.fecha_exp_est =(
                                    select max(eeea.fecha_exp_est)
                                    from sri_expediente_estado eeea
                                    where eeea.id_expedientert = ea.id_expedientert))
                    group by aaa.numexpediente_expedientert )')
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->where('ab.fecha_exp_est = (
                        select ee.fecha_exp_est
                        from sri_expedientert e
                        join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
                        join sri_estadort es on es.id_estadort = ee.id_estadort
                        where e.id_expedientert = aa.id_expedientert
                        and ee.fecha_exp_est =(
                            select max(eee.fecha_exp_est)
                            from sri_expediente_estado eee
                            where eee.id_expedientert = e.id_expedientert))')
                ->group_by('ab.id_estadort')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(ab.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("ab.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"]);
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

        $this->db->select("aa.numexpediente_expedientert, ab.id_estadort")
				->from('sri_expedientert aa')
                ->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where('(aa.tiposolicitud_expedientert = "Reforma Parcial" or aa.tiposolicitud_expedientert = "Reforma Total")')
                ->where('ab.id_estadort', '3')
                ->where('aa.id_expedientert in (
                    select max(aaa.id_expedientert)
                    from sri_expedientert aaa
                    join sri_expediente_estado aab on aab.id_expedientert = aaa.id_expedientert
                    where aab.fecha_exp_est = (
                            select eea.fecha_exp_est
                            from sri_expedientert ea
                            join sri_expediente_estado eea on eea.id_expedientert = ea.id_expedientert
                            join sri_estadort esa on esa.id_estadort = eea.id_estadort
                            where ea.id_expedientert = aaa.id_expedientert
                                and eea.fecha_exp_est =(
                                    select max(eeea.fecha_exp_est)
                                    from sri_expediente_estado eeea
                                    where eeea.id_expedientert = ea.id_expedientert))
                    group by aaa.numexpediente_expedientert )')
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->where('ab.fecha_exp_est = (
                    select ee.fecha_exp_est from sri_expedientert e
                    join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
                    join sri_estadort es on es.id_estadort = ee.id_estadort
                    where e.id_expedientert = aa.id_expedientert
                        and ee.fecha_exp_est =(
                        select max(eee.fecha_exp_est)
                        from sri_expediente_estado eee
                        where eee.id_expedientert = e.id_expedientert))')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(ab.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("ab.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Reformas de Reglamentos Internos Aprobados' titulo,
                count(*) cantidad")
                ->from($query_interna);
                
        $sql[] = '('.$this->db->get_compiled_select().')';

        /* Proyectos de Reglamentos Internos Aprobados */

        $this->db->select("aa.numexpediente_expedientert, ab.id_estadort")
				->from('sri_expedientert aa')
                ->join('sri_expediente_estado ab', 'ab.id_expedientert = aa.id_expedientert')
                ->join('sri_expediente_empleado ac', 'ac.id_expedientert = aa.id_expedientert')
                ->join('sir_empleado ad', 'ad.id_empleado = ac.id_empleado')
                ->where('aa.tiposolicitud_expedientert', 1)
                ->where('ab.id_estadort', '3')
                ->where('aa.id_expedientert in (
                    select max(aaa.id_expedientert)
                    from sri_expedientert aaa
                    join sri_expediente_estado aab on aab.id_expedientert = aaa.id_expedientert
                    where aab.fecha_exp_est = (
                            select eea.fecha_exp_est
                            from sri_expedientert ea
                            join sri_expediente_estado eea on eea.id_expedientert = ea.id_expedientert
                            join sri_estadort esa on esa.id_estadort = eea.id_estadort
                            where ea.id_expedientert = aaa.id_expedientert
                                and eea.fecha_exp_est =(
                                    select max(eeea.fecha_exp_est)
                                    from sri_expediente_estado eeea
                                    where eeea.id_expedientert = ea.id_expedientert))
                    group by aaa.numexpediente_expedientert )')
                ->where('ad.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = aa.id_expedientert ))')
                ->where('ab.fecha_exp_est = (
                    select ee.fecha_exp_est from sri_expedientert e
                    join sri_expediente_estado ee on ee.id_expedientert = e.id_expedientert
                    join sri_estadort es on es.id_estadort = ee.id_estadort
                    where e.id_expedientert = aa.id_expedientert
                        and ee.fecha_exp_est =(
                        select max(eee.fecha_exp_est)
                        from sri_expediente_estado eee
                        where eee.id_expedientert = e.id_expedientert))')
                ->group_by('aa.numexpediente_expedientert')
                ->order_by('aa.numexpediente_expedientert desc')
                ->order_by('ab.fecha_exp_est asc');

        if ($empleado) {
            $this->db->where('ad.id_empleado', $empleado);
        }
        
        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(ab.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ab.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("ab.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(ab.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Proyectos de Reglamentos Internos Aprobados' titulo,
                count(*) cantidad")
                ->from($query_interna);
        
        $sql[] = '('.$this->db->get_compiled_select().')';

         /* Casos Reasignados (cambio de Colaborador) */

         $this->db->select("aa.numexpediente_expedientert, count(*) cant, aa.id_expedientert")
            ->from('sri_expedientert aa')
            ->join('sri_expediente_empleado ab', 'aa.id_expedientert = ab.id_expedientert')
            ->join('sri_expediente_estado ac', 'ac.id_expedientert = aa.id_expedientert')
            ->where('aa.id_expedientert in (
                select max(aaa.id_expedientert)
                from sri_expedientert aaa
                join sri_expediente_estado aab on aab.id_expedientert = aaa.id_expedientert
                where aab.fecha_exp_est = (
                        select eea.fecha_exp_est
                        from sri_expedientert ea
                        join sri_expediente_estado eea on eea.id_expedientert = ea.id_expedientert
                        join sri_estadort esa on esa.id_estadort = eea.id_estadort
                        where ea.id_expedientert = aaa.id_expedientert
                            and eea.fecha_exp_est =(
                                select max(eeea.fecha_exp_est)
                                from sri_expediente_estado eeea
                                where eeea.id_expedientert = ea.id_expedientert))
                group by aaa.numexpediente_expedientert )')
            ->group_by('aa.numexpediente_expedientert')
            ->having('count(distinct aa.numexpediente_expedientert, ab.id_empleado) > 1');

        if($data["tipo"] == "mensual"){
            $this->db->where('YEAR(ac.fecha_exp_est)', $data["anio"])
                    ->where('MONTH(ac.fecha_exp_est)', $data["value"]);
        }else if($data["tipo"] == "trimestral"){
            $tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
            $this->db->where('YEAR(ac.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ac.fecha_exp_est) BETWEEN '".$tminicio."' AND '".$tmfin."'");
        }else if($data["tipo"] == "semestral"){
            $smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
            $this->db->where('YEAR(ac.fecha_exp_est)', $data["anio"])
                ->where("MONTH(ac.fecha_exp_est) BETWEEN '".$sminicio."' AND '".$smfin."'");
        }else if($data["tipo"] == "periodo"){
            $this->db->where("ac.fecha_exp_est BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
        }else{
            $this->db->where('YEAR(ac.fecha_exp_est)', $data["anio"]);
        }

        $query_interna = '('.$this->db->get_compiled_select().') a';

        $this->db->select("'Casos Reasignados (cambio de Colaborador)' titulo,
                count(*) cantidad")
                ->from($query_interna)
                ->join('sri_expediente_empleado b', 'b.id_expedientert = a.id_expedientert')
                ->where('b.id_empleado = ( select see.id_empleado from sri_expediente_empleado see
                        where see.id_exp_emp = ( select max(se.id_exp_emp) from sri_expediente_empleado se 
                        where se.id_expedientert = a.id_expedientert ))');
        
        if ($empleado) {
            $this->db->where('b.id_empleado', $empleado);
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

        }else{

            $this->db->where('YEAR(a.fecha_ingresar_exp_est)', $data["anio"]);

        }

        $this->db->select("
                    a.fecha_ingresar_exp_est, 
                    b.estado_estadort, 
                    c.numexpediente_expedientert, 
                    c.fechacrea_expedientert, 
                    d.nombre_empresa, 
                    e.seccion_catalogociiu,
                    DATEDIFF(a.fecha_ingresar_exp_est, c.fechacrea_expedientert) servicio")
				->from('sri_expediente_estado a')
                ->join('sri_estadort b', 'b.id_estadort = a.id_estadort')
                ->join('sri_expedientert c', 'c.id_expedientert = a.id_expedientert')
                ->join('sge_empresa d', 'c.id_empresart = d.id_empresa')
                ->join('sge_catalogociiu e', 'e.id_catalogociiu = d.id_catalogociiu')
                ->join('sri_expediente_empleado f', 'f.id_expedientert = c.id_expedientert')
                ->where('f.id_empleado = (
                        SELECT af.id_empleado
                        FROM sri_expediente_empleado af
                        WHERE af.id_exp_emp = (
                            SELECT MAX(aaf.id_exp_emp)
                            FROM sri_expediente_empleado aaf
                            WHERE aaf.id_expedientert = c.id_expedientert AND aaf.fecha_exp_emp <= "'.$fecha_ultima->format('Y-m-d').'"))')
                ->group_by('a.id_expedientert')
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
            $this->db->where('f.id_empleado', $data["empleado"]);
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
		}
		else {
			return FALSE;
		}

    }

}

?>