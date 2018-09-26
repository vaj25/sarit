<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de Reglamentos</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
        <div class="pull-right">
          <?php if(tiene_permiso($segmentos=1,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
          <?php } ?>
        </div>

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
                        <th style="min-width: 120px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($reglamentos) {
                        if($reglamentos->num_rows() > 0){
                            $i=1;
                            foreach ($reglamentos->result() as $fila) {
                                echo "<tr>";
                                // echo "<td>".$fila->numexpediente_expedientert."</td>";
                                echo "<td>".$i."</td>";
                                echo "<td>".$fila->nombre_empresa."</td>";
                                echo "<td>".$fila->nombre_empleado."</td>";
                                echo "<td>".$fila->tiposolicitud_expedientert."</td>";
                                echo "<td>".$fila->fecha_exp_est."</td>";
                                echo ($fila->id_estadort != "9") ? '<td><span class="label label-success">'.$fila->estado_estadort.'</span></td>' : '<td><span class="label label-danger">'.$fila->estado_estadort.'</span></td>';
                                $i++;
                                echo "<td>";
                                $array = array($fila->id_expedientert);

                                if(tiene_permiso($segmentos=1,$permiso=4)){

                                    if ($fila->id_estadort != "9") {
                                        array_push($array, "edit");
                                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                                        
                                        /*array_pop($array);
                                        array_push($array, "reforma_parcial");
                                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Reforma Parcial");

                                        array_pop($array);
                                        array_push($array, "reforma_total");
                                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Reforma Total");*/

                                        ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="ti-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedientert?>)">Visualizar</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="cambiar_editar(<?=$fila->id_expedientert?>, 'reforma_parcial')">Reforma Parcial</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="cambiar_editar(<?=$fila->id_expedientert?>, 'reforma_total')">Reforma Total</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="actualizar_estado(<?=$fila->id_expedientert?>)">Actualizar Estado del Expediente</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_expedientert.','.$fila->id_personal?>)">Cambiar Delegado</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedientert?>)">Registrar Resolución</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="notificacion_resolucion(<?=$fila->id_expedientert?>)">Registrar Notificaci&oacute;n Resolución</a>
                                                
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
                                                <a class="dropdown-item" href="javascript:;" onClick="adjuntar_reglamento(<?=$fila->id_expedientert?>)">Adjuntar Reglamento</a>
                                                <!-- <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedientert?>)">Inhabilitar Expediente</a> -->
                                            </div>
                                        </div>
                                <?php
                                    } else {
                                ?>
                                        <button type = "button" class = "btn waves-effect waves-light btn-rounded btn-sm btn-info"
                                        onclick = "habilitar(<?=$fila->id_expedientert?>)" data-toggle = "tooltip" title = ""
                                        data-original-title = "Habilitar"> <span class = "fa fa-check"></span></button >
                                        &nbsp;                                       
                                        <button type = "button" class = "btn waves-effect waves-light btn-rounded btn-sm btn-info"
                                        onclick = "visualizar(<?=$fila->id_expedientert?>)" data-toggle = "tooltip" title = ""
                                        data-original-title = "Visualizar"> <span class = "fa fa-file"></span></button >
                                <?php
                                    }
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
