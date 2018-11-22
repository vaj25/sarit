<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_actualizar_estado" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Actualizar estado del Reglamento</h4>
      </div>

      <div class="modal-body" id="">

        <div id="cnt_form7" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax7', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <hr class="m-t-0 m-b-30">

          <input type="hidden" id="id_reglamento_resolucion" name="id_reglamento_resolucion" value="<?= $id_solicitud ?>">

          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Estado del Expediente: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="estado" name="estado" class="select2" onchange="" style="width: 100%">
                  <option value="">[Seleccione]</option>
                  <?php
                    if(!empty($estado)){
                    foreach ($estado->result() as $fila) {
                      if ($fila->id_estadort != 9) {
                  ?>
                  <option value="<?php echo $fila->id_estadort ?>" <?php if($fila->id_estadort==$id){?> selected
                    <?php }?>>
                    <?php
                      echo $fila->estado_estadort;
                    ?>
                  </option>;
                  <?php
                    }
                    }
                    }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Fecha de cambio de estado: <span class="text-danger">*</span></h5>
              <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_estado" name="fecha_estado" placeholder="dd/mm/yyyy" readonly="">
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
    $("#formajax7").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax7"));
        $('#modal_actualizar_estado').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_estado_reglamento",
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

        $('#modal_actualizar_estado').remove();
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        tablaReglamentos();
        
    });
});

$(function () {
        $(document).ready(function () {
            $('#fecha_estado').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                startDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });

</script>