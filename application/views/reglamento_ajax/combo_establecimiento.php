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

<div class="modal fade" id="modal_representante" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Seleccionar representante</h4>
      </div>

      <div class="modal-body" >

        <div id="table-representante"></div>

        <form id="formajax11">
            <input type="hidden" name="id_representante_modal" id="id_representante_modal">

            <div align="right">
                <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="submit11" class="btn btn-info waves-effect text-white">Guardar</button>
            </div>
        </form>

      </div>

    </div>

  </div>

</div>

<script>

function seleccionar_representante(obj, id_representante_modal){
    $("#id_representante_modal").val(id_representante_modal);
    $(obj).parent().addClass('table-active active');
    $(obj).parent().siblings('tr').removeClass('table-active active');
    $(obj).parent().siblings('tr').each(function () {
        $( $(this).children()[0] ).html('');
    });
    
    var tds = $(obj).siblings('td');
    $(tds[0]).html('<span class="round round-primary">R</span>');
}

$('#establecimiento').on('select2:select', function (e) {
    var d = e.params.data;
    
    $.ajax({
        url: "<?php echo site_url(); ?>/establecimiento/consultar_establecimiento_expediente",
        type: "post",
        dataType: "html",
        data: {id: d['id']}
    }).
    done(function (data) {
        if (data == "2") {
            swal({ title: "¡Ups!", text: "Esta empresa ya dispone de un expediente.", type: "warning", showConfirmButton: true });
            $('#establecimiento').val("").trigger("change");
        } else if (data == "1") {
            $("#nuevo_expediente").val(1);

            $.ajax({
                url: "<?php echo site_url(); ?>/establecimiento/tabla_representante",
                type: "post",
                dataType: "html",
                data: {id: d['id']}
            }).
            done(function (data) {
                $('#table-representante').html(data);
                $('#modal_representante').modal('show');
            });

        }
    }).
    fail(function (data) {
        swal({ title: "¡Ups! Error", text: "Upps, ha ocurrido un error.", type: "error", showConfirmButton: true });
    });

});

$(function(){
    $("#formajax11").on("submit", function(e){
        e.preventDefault();
        var formData = new FormData(document.getElementById("formajax11"));
        
        $.ajax({
            url: "<?php echo site_url(); ?>/establecimiento/obtener_representante",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "false"){
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
                $("#id_comisionado").val(res.id_representantert);
                $("#nombres").val(res.nombres_representantert);
                $("#apellidos").val(res.apellidos_representantert);
                $("#dui_comisionado").val(res.dui_representantert);
                $("#nit").val(res.nit_representantert);
                $("#telefono").val(res.telefono_representantert);
                $("#correo").val(res.correo_representantert);
                $("#tipo_representante").val(res.cargo_representantert).trigger('change.select2');
                $("#sexo").val(res.sexo_representantert).trigger('change.select2');

                $('#modal_representante').modal('hide');
            }
        });
            
    });
});

</script>