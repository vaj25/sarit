<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de Reglamentos</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">

        <div class="table-responsive">
          
            <table id="myTable" class="table table-bordered product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>N&uacute;mero de expediente</th>
                        <th>Nombre del solicitante</th>
                        <th>Colaborador Asignado (a) </th>
                        <th>Tipo Solicitud </th>
                        <th>Fecha Resoluci&oacute;n </th>
                        <th>Estado </th>
                        <th style="min-width: 80px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($reglamentos) {
                        if($reglamentos->num_rows() > 0){
                            foreach ($reglamentos->result() as $fila) {
                                echo "<tr>";
                                echo "<td>".$fila->numexpediente_expedientert."</td>";
                                echo "<td>".$fila->nombre_empresa."</td>";
                                echo "<td>".$fila->nombre_empleado."</td>";
                                echo "<td>".$fila->tiposolicitud_expedientert."</td>";
                                echo "<td>".$fila->fecharesolucion_expedientert."</td>";
                                echo ($fila->id_estadort != "9") ? '<td><span class="label label-success">'.$fila->estado_estadort.'</span></td>' : '<td><span class="label label-danger">'.$fila->estado_estadort.'</span></td>';

                                echo "<td>";
                                // $array = array($fila->id_expedientert);

                                if(tiene_permiso($segmentos=1,$permiso=4)){

                                    ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti-settings"></i>
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedientert?>)">Visualizar</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="historial('<?=$fila->numexpediente_expedientert?>')">Ver historial</a>
                                            <?php
                                                if ( $fila->id_estadort == 6 || $fila->id_estadort == 2 || $fila->id_estadort == 9 ) {
                                            ?>
                                                    <a class="dropdown-item"href="<?=base_url('index.php/acta/generar_acta/'.$fila->id_expedientert.'/asdfa')?>">Generar Acta</a>
                                            <?php    
                                                } elseif ($fila->id_estadort == 3) {
                                            ?>
                                                    <a class="dropdown-item" href="javascript:;" onClick="modal_acta_aprobada(<?=$fila->id_expedientert?>)">Generar Acta</a>
                                            <?php
                                                }
                                            ?>                                                
                                            
                                            <?php
                                                if ($fila->archivo_expedientert != "") {
                                            ?>
                                                    <a class="dropdown-item" href="<?=base_url('index.php/reglamento/descargar_reglamento/'.$fila->id_expedientert)?>" >Descargar Reglamento</a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
