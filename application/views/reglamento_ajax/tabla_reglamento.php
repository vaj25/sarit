<div class="table-responsive">

    <table id="myTable" class="table table-bordered product-overview">
        <thead class="bg-info text-white">
            <tr>
                <th>N&uacute;mero</th>
                <th>N&uacute;mero de expediente</th>
                <th>Persona solicitante</th>
                <th>Persona Colaboradora</th>
                <th>Tipo Solicitud </th>
                <th>Fecha Modificación </th>
                <th>Estado </th>
                <th style="min-width: 120px;">(*)</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    if ($reglamentos) {
                        if($reglamentos->num_rows() > 0){
                            $i=1;
                            $permiso_actualizar = tiene_permiso($segmentos=1,$permiso=4);
                            $permiso_personal = obtener_rango($segmentos=1, $permiso=1);
                            foreach ($reglamentos->result() as $fila) {
                                echo "<tr>";
                                echo "<td>".$i."</td>";
                                echo "<td>". ( ($fila->numeroexpediente_anterior != null) ? $fila->numexpediente_expedientert ." <br> ". $fila->numeroexpediente_anterior : $fila->numexpediente_expedientert ) . "</td>";
                                echo "<td>".$fila->nombre_empresa."</td>";
                                echo "<td>".$fila->nombre_empleado."</td>";
                                echo "<td>".$fila->tiposolicitud_expedientert."</td>";
                                echo "<td>".$fila->fecha_exp_est."</td>";
                                echo ($fila->id_estadort != "9") ? '<td><span class="label label-success">'.$fila->estado_estadort.'</span></td>' : '<td><span class="label label-danger">'.$fila->estado_estadort.'</span></td>';
                                $i++;
                                echo "<td>";

                                $array = array($fila->id_solicitud);

                                if($permiso_actualizar){

                                    if ($fila->id_estadort != "9") {

                                        if ($fila->id_representantert == null) {
                                            array_push($array, "edit_new");
                                        } else {
                                            array_push($array, "edit");
                                        }

                                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");

                                        ?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ti-settings"></i>
                </button>
                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_solicitud?>)">Visualizar</a>
                    <!-- <a class="dropdown-item" href="javascript:;" onClick="cambiar_editar(<?=$fila->id_expedientert?>, 'reforma_parcial')">Reforma Parcial</a>
                                                <a class="dropdown-item" href="javascript:;" onClick="cambiar_editar(<?=$fila->id_expedientert?>, 'reforma_total')">Reforma Total</a> -->
                    <a class="dropdown-item" href="javascript:;" onClick="actualizar_estado(<?=$fila->id_solicitud?>)">Actualizar
                        Estado</a>
                    <?php if ($permiso_personal > 1) { ?>
                        <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_solicitud.','.$fila->id_personal?>)">Cambiar
                        Delegado</a>
                    <?php }?>
                    <?= ($fila->id_estadort == 3) ? '<a class="dropdown-item" href="javascript:;" onClick="modal_acciones('.$fila->id_solicitud.')">Agregar Reforma</a>' : 
                                                    '<a class="dropdown-item" href="javascript:;" onClick="desistir('.$fila->id_solicitud.')">Desistir</a>'?>
                    <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedientert?>)">Registrar
                        Resolución</a>
                    <?php if ($fila->id_estadort > 1) { ?>
                        <a class="dropdown-item" href="javascript:;" onClick="notificacion_resolucion(<?=$fila->id_solicitud?>)">Registrar
                            Notificaci&oacute;n Resolución</a>
                        <a class="dropdown-item" href="javascript:;" onClick="entrega_resolucion(<?=$fila->id_solicitud?>)">Entrega
                            Resolución</a>
                    <?php }?>
                    <a class="dropdown-item" href="<?= base_url('index.php/Editar_reglamento?id_solicitud='.$fila->id_solicitud)?>" >Editar
                            Historial</a>
                </div>
            </div>
            <?php
                                    } else {
                                ?>
            <button type="button" class="btn waves-effect waves-light btn-rounded btn-sm btn-info" onclick="habilitar(<?=$fila->id_expedientert?>)"
                data-toggle="tooltip" title="" data-original-title="Habilitar"> <span class="fa fa-check"></span></button>
            &nbsp;
            <button type="button" class="btn waves-effect waves-light btn-rounded btn-sm btn-info" onclick="visualizar(<?=$fila->id_expedientert?>)"
                data-toggle="tooltip" title="" data-original-title="Visualizar"> <span class="fa fa-file"></span></button>
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