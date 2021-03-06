<?php $color = array('#26c6da', '#1e88e5', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4'); 
$color2 = array('#1e88e5', '#c8d5dc', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4');
?>
<script type="text/javascript">

    function iniciar(){
         
	}

</script>
<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <br>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

	                <div class="col-lg-12">
	                    <div class="card">
	                        <div class="card-body">
	                            <h4 align="center">ESTADÍSTICAS POR ESTADO DE REGLAMENTO</h4>
	                            <div class="d-flex flex-row" align="center">
	                            <?php
	                            	$total = 0;
	                            	if($estado_reglamento->num_rows() > 0){
	                                    foreach ($estado_reglamento->result() as $fila_ca) { $total += $fila_ca->cantidad; }
	                                }
	                                if($estado_reglamento->num_rows() > 0){
	                                    foreach ($estado_reglamento->result() as $fila_ca) {
	                            ?>
	                                <div class="p-0 b-r" align="center" style="width: 50%;">
	                                    <h6 class="font-light"><?=$fila_ca->nombre?></h6><h6><?=$fila_ca->cantidad?></h6><h6><?php echo number_format((($fila_ca->cantidad/$total)*100),2); ?>%</h6>
	                                </div>

	                            <?php } }?>
	                            </div>
	                        </div>
	                    </div>
	                </div>

					<div class="col-lg-12">
						<div class="row">
							<div class="col-md-5 col-lg-5">
		                        <div class="card">
		                            <div class="card-body" style="position: relative;">
		                                <h3 class="card-title">Estadística Tipo Solicitante </h3>
		                                <h3 align="center" class="text-muted" style="z-index:0; left:50%; top: 60%; position: absolute; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">Tipo Solicitante</h3>
	                                    <div style="margin-left: 10px; margin-right: 10px;">
	                                        <canvas id="myChart3" style="height: 200px;"></canvas>
	                                    </div>
		                            </div>
		                            <div>
		                                <hr class="m-t-0 m-b-0">
		                            </div>
		                            <div class="card-body text-center">
		                                <ul class="list-inline m-b-0">
		                                    <?php
		                                	$cont = 0;
		                                	if($tipo_solicitante->num_rows() > 0){
        										foreach ($tipo_solicitante->result() as $fila_sa) {
        									?>
        										<li>
			                                        <h6 style="color: <?=$color[$cont]?>;">
			                                        	<i class="fa fa-circle font-10 m-r-10 "></i><?=$fila_sa->nombre?>
			                                        </h6> 
		                                        </li>
        									<?php $cont++;} } ?>
		                                </ul>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="col-md-3 col-lg-3">
		                        <div class="card">
		                            <div class="card-body" style="position: relative;">
		                                <h3 class="card-title">Estadística resueltos</h3>
		                                <h3 align="center" class="text-muted" style="left:50%; top: 60%; position: absolute; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">Resueltos <br><?php $porcent = $tipo_asociacion->result()[0]; $porcentaje = number_format( (($porcent->cantidad/$porcent->total)*100), 2, '.', '');
		                                		$split = explode('.',$porcentaje);
		                                	if($split[1] == "00"){
		                                		echo $split[0]."%";
		                                	}else{
		                                		echo $porcentaje."%";
		                                	}

		                                ?></h3>
	                                    <div style="margin-left: 10px; margin-right: 10px;">
	                                        <canvas id="myChart2" style="height: 200px;"></canvas>
	                                    </div>
		                            </div>
		                            <div>
		                                <hr class="m-t-0 m-b-0">
		                            </div>
		                            <div class="card-body text-center ">
		                                <ul class="list-inline m-b-0">
		                                	<?php
		                                	$cont = 0;
		                                	if($tipo_asociacion->num_rows() > 0){
        										foreach ($tipo_asociacion->result() as $fila_sa) {
        									?>
        										<li>
			                                        <h6 style="color: <?=$color2[$cont]?>;">
			                                        	<i class="fa fa-circle font-10 m-r-10 "></i><?=$fila_sa->nombre?>
			                                        </h6> 
		                                        </li>
        									<?php $cont++;} } ?>
		                                </ul>
		                            </div>
		                        </div>
		                    </div>

							<!-- Column -->
		                    <div class="col-md-3 col-lg-3">
		                        <div class="card">
		                            <div class="card-body" style="position: relative;">
		                                <h3 class="card-title">Estadística por sexo </h3>
		                                <h3 align="center" class="text-muted" style="left:50%; top: 60%; position: absolute; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">Comisionado <br><?php $total = 0;
		                                	if($sector_asociacion->num_rows() > 0){
        										foreach ($sector_asociacion->result() as $fila_sa) {
        											$total += $fila_sa->cantidad;
        										}
        									}

        									$texto = ""; $i=0;
        									if($sector_asociacion->num_rows() > 0 && $total > 0){
        										foreach ($sector_asociacion->result() as $fila_sa) {
        											$porcentaje = number_format( (($fila_sa->cantidad/$total)*100), 2, '.', '');
		                                			$split = explode('.',$porcentaje);
				                                	if($split[1] == "00"){
				                                		$texto .= "<small style='color:".$color[$i]."'>".$split[0]."%</small>/";
				                                	}else{
				                                		$texto .= "<small style='color:".$color[$i]."'>".$porcentaje."%</small>/";
				                                	}
				                                	$i++;
        										}
        									}
        									echo substr($texto, 0, -1);

		                                ?></h3>
	                                    <div style="margin-left: 10px; margin-right: 10px;">
	                                        <canvas id="myChart" style="height: 200px;"></canvas>
	                                    </div>
		                            </div>
		                            <div>
		                                <hr class="m-t-0 m-b-0">
		                            </div>
		                            <div class="card-body text-center ">
		                                <ul class="list-inline m-b-0">
		                                	<?php
		                                	$cont = 0;
		                                	if($sector_asociacion->num_rows() > 0){
        										foreach ($sector_asociacion->result() as $fila_sa) {
        									?>
        										<li>
			                                        <h6 style="color: <?=$color[$cont]?>;">
			                                        	<i class="fa fa-circle font-10 m-r-10 "></i><?=$fila_sa->nombre?>
			                                        </h6> 
		                                        </li>
        									<?php $cont++;} } ?>
		                                </ul>
		                            </div>
		                        </div>
		                    </div>

						</div>
                    </div>

                </div>


                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->


<script src="<?php echo base_url(); ?>assets/js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/echarts/echarts-all.js"></script>
<script type="text/javascript">
var ctx;
var chart;

$( document ).ready(function() {

ctx = document.getElementById('myChart').getContext('2d');
chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: [<?php
	$contador = 0;
	if($sector_asociacion->num_rows() > 0){
        foreach ($sector_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($sector_asociacion->num_rows() == $contador){echo '"'.$fila_sa->nombre.'"';}else{echo '"'.$fila_sa->nombre.'",';} }
    }
?>],
        datasets: [{
            backgroundColor: [<?php
	$contador = 0;
	if($sector_asociacion->num_rows() > 0){
        foreach ($sector_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($sector_asociacion->num_rows() == $contador){echo '"'.$color[$contador-1].'"';}else{echo '"'.$color[$contador-1].'",';} }
    }
?>],
            data: [<?php
	$contador = 0;
	if($sector_asociacion->num_rows() > 0){
        foreach ($sector_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($sector_asociacion->num_rows() == $contador){echo $fila_sa->cantidad;}else{echo $fila_sa->cantidad.',';} }
    }
?>],
        }]
    },

    // Configuration options go here
    options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false} }

});

});

var ctx2;
var chart2;

$( document ).ready(function() {

ctx2 = document.getElementById('myChart2').getContext('2d');
chart2 = new Chart(ctx2, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: [<?php
	$contador = 0;
	if($tipo_asociacion->num_rows() > 0){
        foreach ($tipo_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_asociacion->num_rows() == $contador){echo '"'.$fila_sa->nombre.'"';}else{echo '"'.$fila_sa->nombre.'",';} }
    }
?>],
        datasets: [{
            backgroundColor: [<?php
	$contador = 0;
	if($tipo_asociacion->num_rows() > 0){
        foreach ($tipo_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_asociacion->num_rows() == $contador){echo '"'.$color2[$contador-1].'"';}else{echo '"'.$color2[$contador-1].'",';} }
    }
?>],
            data: [<?php
	$contador = 0;
	if($tipo_asociacion->num_rows() > 0){
        foreach ($tipo_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_asociacion->num_rows() == $contador){echo $fila_sa->cantidad;}else{echo $fila_sa->cantidad.',';} }
    }
?>],
        }]
    },

    // Configuration options go here
    options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false} }

});

});

var ctx3;
var chart3;

$( document ).ready(function() {

ctx3 = document.getElementById('myChart3').getContext('2d');
chart3 = new Chart(ctx3, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [<?php
	$contador = 0;
	if($tipo_solicitante->num_rows() > 0){
        foreach ($tipo_solicitante->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_solicitante->num_rows() == $contador){echo '"'.mb_substr($fila_sa->nombre, 0, 10).'.."';}else{echo '"'.mb_substr($fila_sa->nombre, 0, 10).'..",';} }
    }
?>],
        datasets: [{
            backgroundColor: [<?php
	$contador = 0;
	if($tipo_solicitante->num_rows() > 0){
        foreach ($tipo_solicitante->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_solicitante->num_rows() == $contador){echo '"'.$color[$contador-1].'"';}else{echo '"'.$color[$contador-1].'",';} }
    }
?>],
            data: [<?php
	$contador = 0;
	if($tipo_solicitante->num_rows() > 0){
        foreach ($tipo_solicitante->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_solicitante->num_rows() == $contador){echo $fila_sa->cantidad;}else{echo $fila_sa->cantidad.',';} }
    }
?>],
        }]
    },

    // Configuration options go here
    options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false}, scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    } }

});

});


</script>