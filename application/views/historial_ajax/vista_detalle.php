<?php 
    $reglamento = $reglamento->result()[0];
    // Características del navegador
    $ua=$this->config->item("navegator");
    $navegatorless = false;
    if(floatval($ua['version']) < $this->config->item("last_version")){
        $navegatorless = true;
    }
?>
               
<div class="card">

    <div class="card-header bg-info" id="ttl_form">
        <div class="card-actions text-white">
            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
            </a>
        </div>
        <h4 class="card-title m-b-0 text-white">Detalle del Expediente</h4>
    </div>

    <div class="card-body b-t" style="padding-top: 7px;">
        <span class="label label-success" style="font-size: 16px;">Expediente</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>N&uacute;mero del expediente:</td>
                        <td class="font-medium">
                            <?= $reglamento->numexpediente_expedientert ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Detalle</span>
        <blockquote class="m-t-0">
            <div class="card-body b-t" style="padding-top: 7px;">

                <div class="table-responsive">

                    <table id="table-detalle" class="table table-bordered product-overview">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>N&uacute;mero</th>
                                <th>Nivel de Proceso</th>
                                <th>Estado </th>
                                <th>Fecha </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if ($detalle) {
                            if($detalle->num_rows() > 0){
                                $i = 1;
                                foreach ($detalle->result() as $fila) {
                                    echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".$fila->etapa_exp_est."</td>";
                                        echo "<td>".$fila->estado_estadort."</td>";
                                        echo "<td>".date("d-M-Y g:i:s A", strtotime($fila->fecha_exp_est))."</td>";
                                    echo "</tr>";

                                    $i++;
                                }
                            }
                        }
                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </blockquote>
    </div>
</div>
