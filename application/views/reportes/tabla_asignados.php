<style>

.total {
    text-align: right;
    font-weight: bold;
}

</style>

<div class="table table-responsive">
    <table border="1" style="width:100%; border-collapse: collapse;">
        <tr>
            <th>N°</th>
            <th>Nombre de la empresa</th>
            <th>Sector económico</th>
            <th>Fecha de Recibido</th>
            <th>Estado</th>
            <th>Fecha Estado</th>
            <th>Duración del servicio</th>
        </tr>

        <?php
        foreach ($expedientes as $expediente) {
            echo "<tr>
                <td>$expediente->numexpediente_expedientert</td>
                <td>$expediente->nombre_empresa</td>
                <td>$expediente->seccion_catalogociiu</td>
                <td>".date("d/m/Y", strtotime($expediente->fechacrea_expedientert))."</td>
                <td>$expediente->estado_estadort</td>
                <td>".date("d/m/Y", strtotime($expediente->fecha_ingresar_exp_est))."</td>
                <td class='total'>$expediente->servicio</td>
                </tr>";
        }
        ?>
    </table>
</div>