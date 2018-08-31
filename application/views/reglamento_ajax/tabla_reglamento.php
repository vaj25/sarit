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
                        <th style="min-width: 85px;">(*)</th>
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
                                echo ($fila->id_estadort == "1") ? '<td><span class="label label-success">'.$fila->estado_estadort.'</span></td>' : '<td><span class="label label-danger">'.$fila->estado_estadort.'</span></td>';

                                echo "<td>";
                                $array = array($fila->id_expedientert);
                                
                                if(tiene_permiso($segmentos=1,$permiso=4)){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                                }
                                
                                if(tiene_permiso($segmentos=1,$permiso=3)){
                                unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                                array_push($array, "delete");
                                echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-close","Eliminar");
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
