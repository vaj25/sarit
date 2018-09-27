<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_entrega_resolucion" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Actualizar estado del Reglamento</h4>
      </div>

      <div class="modal-body" id="">

        <div id="cnt_form7" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax10', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <hr class="m-t-0 m-b-30">

          <input type="hidden" id="id_reglamento_resolucion" name="id_reglamento_resolucion" value="<?= $id_expediente ?>">

          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Quien recibe: <span class="text-danger">*</span></h5>
                <input type="text" required="" class="form-control" id="recibe" name="recibe" placeholder="Nombre de la persona que recibe" >
            </div>

            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Fecha de entrega: <span class="text-danger">*</span></h5>
                <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_entrega" name="fecha_entrega" placeholder="dd/mm/yyyy" readonly="">
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
    $("#formajax10").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax10"));
        $('#modal_entrega_resolucion').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_entrega_resolucion",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });

        $('#modal_entrega_resolucion').remove();
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        tablaReglamentos();
            
    });
});

$(function () {
        $(document).ready(function () {
            $('#fecha_entrega').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                startDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });

</script>