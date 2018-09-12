<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_notificacion" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar Notificaci&oacute;n Resultado del Expediente</h4>
      </div>

      <div class="modal-body" id="">
        <div id="cnt_form5" class="cnt_form">

          <form id="formajax5" method="post" class="m-t-40">

            <hr class="m-t-0 m-b-30">

            <input type="hidden" id="id_reglamento_resolucion" name="id_reglamento_resolucion" value="<?= $id?>">

            <div class="row">
              <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Notificaci&oacute;n de resultado de estudio: <span class="text-danger">*</span></h5>
                <div class="controls">
                  <select id="notificacion" name="notificacion" class="form-control" required>
                    <option value="">[Seleccione]</option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Fecha y Hora de Notificaci&oacute;n: <span class="text-danger">*</span></h5>
                <div class="controls">
                  <input type="datetime-local" class="form-control" id="notificacion_fecha" nombre="notificacion_fecha"
                    required>
                </div>
              </div>
            </div>

            <div align="right" id="btnadd1">
              <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn waves-effect waves-light btn-success2">
                Guardar <i class="mdi mdi-chevron-right"></i>
              </button>
            </div>
            <?php echo form_close(); ?>
        </div>
      </div>

    </div>

  </div>

</div>

<script>

$(function(){
    $("#formajax5").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax5"));
        formData.append("fecha", $("#notificacion_fecha").val());
        $('#modal_notificacion').modal('hide');
        
        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_notificacion_reglamento",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                //cerrar_mantenimiento();
                swal({ title: "¡La resolucion se aplico con exito!", type: "success", showConfirmButton: true });
                //tablaEstados();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
        $('#modal_notificacion').remove();
        $('.modal-backdrop').remove();
            
    });
});

</script>