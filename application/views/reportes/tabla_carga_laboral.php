<style>
    .verticalText {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
    }
</style>

<div class="table table-responsive">
    <table border="1" style="width:100%; border-collapse: collapse;">
        <tr>
            <th colspan="4"></th>
            <?php
                foreach ($colaboradores as $value) {
                    $nombre = $value['nombre_completo'];
                    echo "<th scope='row'>$nombre</th>";
                }
            ?>
            <th scope="col">Total</th>
        </tr>
        <tr>
            <td rowspan="16"><p class="verticalText">REGLAMENTOS</p></td>
            <td colspan="3">Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior</td>
            <?php
                $mes_ant = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $entradas[$value['id_empleado']][0]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $mes_ant += $cantidad;
                }
                echo "<td>$mes_ant</td>"
            ?>
        </tr>
        <tr>
            <td rowspan="4"><p class="verticalText">ENTRADAS</p></td>
            <th scope="row">1</th>
            <td>Reglamentos Internos de Trabajo Recibidos (nuevos)</td>
            <?php
                $nuevos = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $entradas[$value['id_empleado']][1]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $nuevos += $cantidad;
                }
                echo "<td>$nuevos</td>"
            ?>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Reglamentos Internos de Trabajo Recibidos con Correcciones</td>
            <?php
                $correcciones = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $entradas[$value['id_empleado']][2]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $correcciones += $cantidad;
                }
                echo "<td>$correcciones</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">3</th>
            <td>Reformas a Reglamentos Internos Recibidas</td>
            <?php
                $reformas = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $entradas[$value['id_empleado']][3]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $reformas += $cantidad;
                }
                echo "<td>$reformas</td>"
            ?>
        </tr>

        <tr>
            <td colspan="2">Reglamentos Internos a estudiar durante el mes</td>
            <?php
                $sub_total_entradas = 0;
                $total_entradas = 0;
                foreach ($colaboradores as $value) {
                    $sub_total_entradas = 0;
                    for ($i=0; $i < 4; $i++) {
                        $cantidad = $entradas[$value['id_empleado']][$i]['cantidad'];
                        $sub_total_entradas += $cantidad;
                    }
                    $total_entradas += $sub_total_entradas;
                    echo "<td>$sub_total_entradas</td>";
                }
                echo "<td>$total_entradas</td>";
            ?>
        </tr>

        <tr>
            <td rowspan="11"><p class="verticalText">ENTRADAS</p></td>
            <th scope="row">1</th>
            <td>Reglamentos Internos de Trabajo con Observaciones Realizadas</td>
            <?php
                $observaciones = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][0]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $observaciones += $cantidad;
                }
                echo "<td>$observaciones</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">2</th>
            <td>Proyectos de Reglamentos Interos con Observaciones de Género</td>
            <?php
                $genero = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][5]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $genero += $cantidad;
                }
                echo "<td>$genero</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">3</th>
            <td>Proyectos de Reglamentos Internos Aprobados</td>
            <?php
                $aprobados = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][7]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $aprobados += $cantidad;
                }
                echo "<td>$aprobados</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">4</th>
            <td>Reformas de Reglamentos Internos Aprobados</td>
            <?php
                $reformas = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][6]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $reformas += $cantidad;
                }
                echo "<td>$reformas</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">5</th>
            <td>Proyectos de Reglamentos Internos Desistidos</td>
            <?php
                $desistidos = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][4]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $desistidos += $cantidad;
                }
                echo "<td>$desistidos</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">6</th>
            <td>Proyectos de Reglamentos Internos Declarados Improponibles</td>
            <?php
                $improponibles = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][3]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $improponibles += $cantidad;
                }
                echo "<td>$improponibles</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">7</th>
            <td>Proyectos de Reglamentos Internos Prevenidos</td>
            <?php
                $prevenidos = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][1]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $prevenidos += $cantidad;
                }
                echo "<td>$prevenidos</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">8</th>
            <td>Proyectos de Reglamentos Internos en Calificacion de Labores (DGPS)</td>
            <?php
                $dgps = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][2]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $dgps += $cantidad;
                }
                echo "<td>$dgps</td>"
            ?>
        </tr>

        <tr>
            <th scope="row">9</th>
            <td>Casos Reasignados (cambio de Colaborador)</td>
            <?php
                $reasignados = 0;
                foreach ($colaboradores as $value) {
                    $cantidad = $resultados[$value['id_empleado']][8]['cantidad'];
                    echo "<td>$cantidad</td>";
                    $reasignados += $cantidad;
                }
                echo "<td>$reasignados</td>"
            ?>
        </tr>

        <tr>
            <td colspan="2">Total de Estudios de Reglamento efectuados</td>
            <?php
                $sub_total_resultados = 0;
                $total_resultados = 0;
                foreach ($colaboradores as $value) {
                    $sub_total_resultados = 0;
                    for ($i=0; $i < 9; $i++) {
                        if ( !($i == 8 || $i == 5) ) {
                            $cantidad = $resultados[$value['id_empleado']][$i]['cantidad'];
                            $sub_total_resultados += $cantidad;
                        }
                    }
                    $total_resultados += $sub_total_resultados;
                    echo "<td>$sub_total_resultados</td>";
                }
                echo "<td>$total_resultados</td>";
            ?>
        </tr>

        <tr>
            <td colspan="2">Reglamento pendientes para el proximo mes</td>
            <?php
                $sub_total_resultados = 0;
                $total_resultados = 0;

                $sub_total_entradas = 0;
                $total_entradas = 0;

                foreach ($colaboradores as $value) {
                    $sub_total_resultados = 0;
                    $sub_total_entradas = 0;
                    for ($i=0; $i < 9; $i++) {
                        
                        if ( !($i == 8 || $i == 5) ) {
                            $cantidad = $resultados[$value['id_empleado']][$i]['cantidad'];
                            $sub_total_resultados += $cantidad;
                        }

                        if ($i < 4) {
                            $cantidad = $entradas[$value['id_empleado']][$i]['cantidad'];
                            $sub_total_entradas += $cantidad;
                        }

                    }
                    $total_resultados += $sub_total_resultados;
                    $total_entradas += $sub_total_entradas;

                    echo "<td>".($sub_total_entradas - $sub_total_resultados)."</td>";
                }
                echo "<td>".($total_entradas - $total_resultados)."</td>";
            ?>
        </tr>

    </table>

</div>