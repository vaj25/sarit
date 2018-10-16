<style>
    .verticalText {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
    }

    .total {
        text-align: right;
        font-weight: bold;
    }
</style>

<?php 
    $cant_entradas = 0;
    foreach ($entradas as $value) {
        $cant_entradas += $value['cantidad'];
    }

    $cant_resultados = 0;
    foreach ($resultados as $key => $value) {
        if ( !($key == 8 || $key == 5) ) {
            $cant_resultados += $value['cantidad'];
        }
    }
?>

<div class="table table-responsive">
    <table border="1" style="width:100%; border-collapse: collapse;">
        <tr>
            <th scope="col" colspan="5">INFORME DE ACTIVIDADES REGLAMENTOS INTERNOS DE TRABAJO</th>
        </tr>
        <tr>
            <td rowspan="16"><p class="verticalText">REGLAMENTOS</p></td>
            <td colspan="3">Proyectos de Reglamentos Internos de Trabajo pendientes del mes anterior</td>
            <td><?= $entradas[0]['cantidad'] ?></td>
        </tr>
        <tr>
            <td rowspan="4"><p class="verticalText">ENTRADAS</p></td>
            <th scope="row">1</th>
            <td>Reglamentos Internos de Trabajo Recibidos (nuevos)</td>
            <td><?= $entradas[1]['cantidad'] ?></td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Reglamentos Internos de Trabajo Recibidos con Correcciones</td>
            <td><?= $entradas[2]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">3</th>
            <td>Reformas a Reglamentos Internos Recibidas</td>
            <td><?= $entradas[3]['cantidad'] ?></td>
        </tr>

        <tr>
            <td colspan="2">Reglamentos Internos a estudiar durante el mes</td>
            <td class="total"><?= $cant_entradas ?></td>
        </tr>

        <tr>
            <td rowspan="11"><p class="verticalText">RESULTADOS</p></td>
            <th scope="row">1</th>
            <td>Reglamentos Internos de Trabajo con Observaciones Realizadas</td>
            <td><?= $resultados[0]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">2</th>
            <td>Proyectos de Reglamentos Interos con Observaciones de Género</td>
            <td><?= $resultados[5]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">3</th>
            <td>Proyectos de Reglamentos Internos Aprobados</td>
            <td><?= $resultados[7]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">4</th>
            <td>Reformas de Reglamentos Internos Aprobados</td>
            <td><?= $resultados[6]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">5</th>
            <td>Proyectos de Reglamentos Internos Desistidos</td>
            <td><?= $resultados[4]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">6</th>
            <td>Proyectos de Reglamentos Internos Declarados Improponibles</td>
            <td><?= $resultados[3]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">7</th>
            <td>Proyectos de Reglamentos Internos Prevenidos</td>
            <td><?= $resultados[1]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">8</th>
            <td>Proyectos de Reglamentos Internos en Calificacion de Labores (DGPS)</td>
            <td><?= $resultados[2]['cantidad'] ?></td>
        </tr>

        <tr>
            <th scope="row">9</th>
            <td>Casos Reasignados (cambio de Colaborador)</td>
            <td><?= $resultados[8]['cantidad'] ?></td>
        </tr>

        <tr>
            <td colspan="2">Total de Estudios de Reglamento efectuados</td>
            <td class="total"><?= $cant_resultados ?></td>
        </tr>

        <tr>
            <td colspan="2">Reglamento pendientes para el proximo mes</td>
            <td class="total"><?= $cant_entradas - $cant_resultados ?></td>
        </tr>

    </table>

</div>