<h5>Establecimiento: <span class="text-danger">*</span></h5>
<div class="controls">
    <select id="establecimiento" name="establecimiento" required class="select2" onchange="" style="width: 100%">
        <option value="">[Seleccione]</option>
            <?php
                if(!empty($establecimiento)){
                foreach ($establecimiento->result() as $fila) {
            ?>
                <option  value="<?php echo $fila->id_empresa ?>" <?php if($fila->id_empresa==$id){?> selected  <?php }?>>
            <?php
                echo $fila->nombre_empresa;
            ?>
                </option>;
            <?php
                }
                }
            ?>
    </select>
</div>