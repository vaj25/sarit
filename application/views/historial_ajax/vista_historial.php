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

        <?php
            if ($historial) {
                if($historial->num_rows() > 0){
                    
                    $reglamento = $historial->row(0);
                    
                }
            }
        ?>
            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Nombre Empresa:
                </div>
                <div class="form-group col-lg-5" syle="height: 20px;">
                    <h5><?= $reglamento->nombre_empresa ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    N&uacute;mero del expediente:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->numexpediente_expedientert ?></h5>
                </div>
            </div>

            <?php
                if ($reglamento->numeroexpediente_anterior != null) {
            ?>
                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        N&uacute;mero del expediente anterior:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= $reglamento->numeroexpediente_anterior ?></h5>
                    </div>
                </div>
            <?php
                }
            ?>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Historial</span>
        <blockquote class="m-t-0">
            <div class="card-body b-t" style="padding-top: 7px;">

                <div class="table-responsive">

                    <table id="table-historial" class="table table-bordered product-overview">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>N&uacute;mero</th>
                                <th>Nombre del representante</th>
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
                                        echo "<td>".$fila->nombre_representante."</td>";
                                        echo "<td>".$fila->nombre_empleado."</td>";
                                        echo "<td>".$fila->nombre_tipo_solicitud."</td>";
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
