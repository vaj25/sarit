<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_adjuntar" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ajuntar Expediente</h4>
      </div>

      <div class="modal-body" id="">

        <div id="cnt_form8" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax8', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <hr class="m-t-0 m-b-30">

          <input type="hidden" id="id_reglamento_resolucion" name="id_reglamento_resolucion" value="<?= $id?>">

          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Adjuntar Expediente: <span class="text-danger">*</span></h5>
              <div class="controls">
                <input type="file" class="dropify" name="archivo_reglamento">
              </div>
            </div>
          </div>
          <div align="right" id="btnadd1">
            <button type="reset" class="btn waves-effect waves-light btn-success">
              <i class="mdi mdi-recycle"></i> Limpiar</button>
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
    $("#formajax8").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax8"));
        $('#modal_adjuntar').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_adjuntar_reglamento",
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
                swal({ title: "¡El estado se aplico con exito!", type: "success", showConfirmButton: true });
                //tablaEstados();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });

      $('#modal_adjuntar').remove();
      $('.modal-backdrop').remove();
      tablaReglamentos();
            
    });
});

</script>