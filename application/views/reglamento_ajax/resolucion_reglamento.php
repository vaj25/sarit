<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row page-titles">
      <div class="align-self-center" align="center">
        <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Aprobaci&oacute;n de Reglamentos</h3>
      </div>
    </div>
    <div class="row">

      <div class="col-lg-1"></div>
      <div class="col-lg-10">
        <div class="card">
          <div class="card-header bg-success2" id="ttl_form">
            <div class="card-actions text-white">
              <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
              </a>
            </div>
            <h4 class="card-title m-b-0 text-white">Listado de Actividades</h4>
          </div>
          <div class="card-body b-t">

            <div id="cnt_form4" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                <hr class="m-t-0 m-b-30">

                <input type="hidden" id="id_reglamento_resolucion" name="id_reglamento_resolucion">

                <div class="row">
                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Resoluci&oacute;n: <span class="text-danger">*</span></h5>
                        <div class="controls">
                        <select id="resolucion" name="resolucion" class="form-control" required>
                            <option value="">[Seleccione]</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Denegado">Denegado</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Reglamento con Observaci&oacute;n de Genero: <span class="text-danger">*</span></h5>
                        <div class="controls">
                        <select id="ob_genero" name="ob_genero" class="form-control" required>
                            <option value="">[Seleccione]</option>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
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
  </div>

</div>

<script>

$(function(){     
    $("#formajax4").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax4"));
        
        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_resolucion_reglamento",
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
            
    });
});

</script>