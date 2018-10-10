<style>
    .verticalText {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
    }
</style>

<?php 
    $cant_entradas = 0;
    // foreach ($entradas as $value) {
    //     $cant_entradas += $value['cantidad'];
    // }

    $cant_resultados = 0;
    foreach ($resultados as $value) {
        $cant_resultados += $value['cantidad'];
    }

    $total_col = count($colaboradores);
?>

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
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[0]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">2</th>
            <td>Proyectos de Reglamentos Interos con Observaciones de Género</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[5]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">3</th>
            <td>Proyectos de Reglamentos Internos Aprobados</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[7]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">4</th>
            <td>Reformas de Reglamentos Internos Aprobados</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[6]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">5</th>
            <td>Proyectos de Reglamentos Internos Desistidos</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[4]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">6</th>
            <td>Proyectos de Reglamentos Internos Declarados Improponibles</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[3]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">7</th>
            <td>Proyectos de Reglamentos Internos Prevenidos</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[1]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">8</th>
            <td>Proyectos de Reglamentos Internos en Calificacion de Labores (DGPS)</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[2]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">9</th>
            <td>Casos Reasignados (cambio de Colaborador)</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $resultados[8]['cantidad'] ?></td>
        </tr>

        <tr>
            <td colspan="2">Total de Estudios de Reglamento efectuados</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $cant_resultados ?></td>
        </tr>

        <tr>
            <td colspan="2">Reglamento pendientes para el proximo mes</td>
            <?php
                foreach ($colaboradores as $value) {
                    $cantidad = $value['id_empleado'];
                    echo "<td>$cantidad</td>";
                }
            ?>
            <td><?= $cant_entradas - $cant_resultados ?></td>
        </tr>

    </table>

</div>