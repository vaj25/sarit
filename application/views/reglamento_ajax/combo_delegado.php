<?php if (obtener_rango($segmentos=1, $permiso=1) > 1) { ?>
<label for="colaborador" class="font-weight-bold">Asignar Delegado: <span class="text-danger"></span></label>
<select id="colaborador" name="colaborador" <?= $disable ?> class="select2" onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if($colaborador){
            foreach ($colaborador->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_empleado ?>" <?php if($fila->id_empleado==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_completo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
<?php } else { ?>
    <input type="hidden" id="colaborador" name="colaborador" value="<?= $this->session->userdata('id_usuario')?>">
<?php }?>