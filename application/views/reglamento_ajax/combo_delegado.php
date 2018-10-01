<label for="colaborador" class="font-weight-bold">Asignar Delegado: <span class="text-danger"></span></label>
<select id="colaborador" name="colaborador" <?= $disable ?> class="select2" onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($colaborador)){
            foreach ($colaborador->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_empleado ?>" <?php if($fila->id_empleado==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_empleado;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>