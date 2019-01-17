<div class="table-responsive">

  <table id="tabla_representante" class="table stylish-table">
    <thead class="bg-info text-white">
      <tr>
          <th class="text-white" colspan="2">Persona representante</th>
          <th class="text-white">DUI</th>
          <th class="text-white">NIT</th>
          <th class="text-white">Teléfono</th>
          <th class="text-white">Correo</th>
          <th class="text-white">Cargo</th>
      </tr>
    </thead>
    <tbody>
    <?php
      if($representantes){

        foreach ($representantes as $fila) {
    ?>
    <tr>
      
      <td style="cursor: pointer; min-width: 40px; max-width: 40px;"></td>
      <td onclick="seleccionar_representante(this, <?= $fila->id_representantert ?>);"><?= $fila->nombres_representantert .' '. $fila->apellidos_representantert ?></td>
      <td><?= $fila->dui_representantert?></td>
      <td><?= $fila->nit_representantert?></td>
      <td><?= $fila->telefono_representantert?></td>
      <td><?= $fila->correo_representantert?></td>
      <td><?= $fila->cargo_representantert?></td>
      
      <!-- <td style="cursor: pointer; min-width: 40px; max-width: 40px;" onclick="seleccionar_representante(this,'.$fila->id_representante.');"><span class="round round-primary">R</span></td>';
      <td style="cursor: pointer; min-width: 40px; max-width: 40px;" onclick="seleccionar_representante(this,'.$fila->id_representante.');"></td>'; -->
    </tr>
    <?php
        }
      } else{
        echo "<td colspan='6'>No hay representates registrados</td>";
      }
    ?>
    </tbody>
  </table>
</div>