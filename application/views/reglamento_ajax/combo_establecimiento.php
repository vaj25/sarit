<h5>Establecimiento: <span class="text-danger">*</span></h5>
<div class="controls">
    <input type="hidden" name="nuevo_expediente" id="nuevo_expediente" value="0">
    <select id="establecimiento" name="establecimiento" required <?= $disable ?> class="select2" onchange="" style="width: 100%">
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

<script>

$('#establecimiento').on('select2:select', function (e) {
    var data = e.params.data;
    
    $.ajax({
        url: "<?php echo site_url(); ?>/establecimiento/consultar_establecimiento_expediente",
        type: "post",
        dataType: "html",
        data: {id: data['id']}
    }).
    done(function (data) {
        if (data == "2") {
            swal({ title: "¡Ups!", text: "Esta empresa ya dispone de un expediente.", type: "warning", showConfirmButton: true });
            $('#establecimiento').val("").trigger("change");
        } else if (data == "1") {
            $("#nuevo_expediente").val(1);
        }
    }).
    fail(function (data) {
        swal({ title: "¡Ups! Error", text: "Upps, ha ocurrido un error.", type: "error", showConfirmButton: true });
    });

});

</script>