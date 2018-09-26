<?php
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
        <h4 class="card-title m-b-0 text-white">Historial del Expediente</h4>
    </div>

    <div class="card-body b-t" style="padding-top: 7px;">
        <span class="label label-success" style="font-size: 16px;">Expediente</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>N&uacute;mero del expediente:</td>
                        <td class="font-medium">
                            <?= $this->input->post('num') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Historial</span>
        <blockquote class="m-t-0">
            <div class="card-body b-t" style="padding-top: 7px;">

                <div class="table-responsive">

                    <table id="table-historial" class="table table-bordered product-overview">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>N&uacute;mero</th>
                                <th>N&uacute;mero de expediente</th>
                                <th>Nombre del solicitante</th>
                                <th>Colaborador asignado </th>
                                <th>Tipo de solicitud </th>
                                <th>(*)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if ($historial) {
                            if($historial->num_rows() > 0){
                                $i = 1;
                                foreach ($historial->result() as $fila) {

                                    $array = array($fila->id_expedientert);
                                    echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".$fila->numexpediente_expedientert."</td>";
                                        echo "<td>".$fila->nombre_empresa."</td>";
                                        echo "<td>".$fila->nombre_empleado."</td>";
                                        echo "<td>".$fila->tiposolicitud_expedientert."</td>";
                                        echo "<td>".generar_boton($array,"detalle","btn-info","mdi mdi-details","Ver detalle")."</td>";
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
