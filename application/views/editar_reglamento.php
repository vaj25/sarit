<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">

  function cerrar_mantenimiento(){
    $("#cnt-tabla").show(0);
    $("#cnt_form_main").hide(0);
    $("#cnt_form_main2").hide(0);
    $("#cnt_actions").hide(0);
    $("#cnt_actions").remove('.card');
    open_form(1);
  }

  function iniciar(){
    <?php //if(tiene_permiso($segmentos=1,$permiso=1)){ ?>
    <?php if(true){ ?>
      $("#cnt_form_main").show();
      $("#tipo_expediente").val(<?= $solicitud->tiposolicitud_expedientert ?>).trigger('change.select2');
      combo_establecimiento( '<?= $solicitud->id_empresa ?>', 1 );
    <?php }else{ ?>
      $("#cnt-tabla").html("Usted no tiene permiso para este formulario.");     
    <?php } ?>
  }

  function combo_establecimiento(seleccion, numero, disable){

    var disable = disable || false;
    
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/combo_establecimiento",
      type: "post",
      dataType: "html",
      data: {id : seleccion, disable: disable}
    })
    .done(function(res){

      if (numero == 1) {
        $('#div_combo_establecimiento').html(res);
      } else {
        $('#div_combo_establecimiento'+numero).html(res);
      }

      $("#establecimiento").select2({
        'minimumInputLength': 3,
        'language': {
          noResults: function () {
            return '<a href="javascript:;" data-toggle="modal" title="Agregar nuevos establecimientos" onClick="cerrar_combo_establecimiento()">Agregar uno nuevo</a>';
          }
        },
        'escapeMarkup': function (markup) {
          return markup;
        },
        'readonly': disable
      });
    });

  }

  function cerrar_combo_establecimiento() {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/modal_establecimiento",
      type: "post",
      dataType: "html"
    })
    .done(function(res){
      $('#cnt_model_establecimiento').html(res);
      combo_actividad_economica();
      combo_municipio();
      $('#modal_establecimiento').modal('show');
    });

    $(".select2").select2('close');
  }

  function combo_actividad_economica(){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/establecimiento/combo_actividad_economica",
      type: "post",
      dataType: "html"
    })
    .done(function(res){
      $('#div_combo_actividad_economica').html(res);
      $(".select2").select2();
    });

  }

  function combo_municipio(){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/establecimiento/combo_municipio",
      type: "post",
      dataType: "html"
    })
    .done(function(res){
      $('#div_combo_municipio').html(res);
      $(".select2").select2();
    });

  }

  function combo_delegado(seleccion, numero, disable=''){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/combo_delegado",
      type: "post",
      dataType: "html",
      data: {id : seleccion, disable: disable}
    })
    .done(function(res){

      if (numero == 1) {
        $('#div_combo_delegado').html(res);
      } else {
        $('#div_combo_delegado'+numero).html(res);
      }
      $(".select2").select2();
    });

  }

  function open_form(num){
    $(".cnt_form").hide(0);
    $("#cnt_form"+num).show(0);

    if($("#band"+num).val() == "save"){
        $("#btnadd"+num).show(0);
        $("#btnedit"+num).hide(0);
    }else{
        $("#btnadd"+num).hide(0);
        $("#btnedit"+num).show(0);
    }
  }

  function volver(num) {
    open_form(num);
    $("#band"+num).val("edit")
  }

</script>

<div class="page-wrapper">
  <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
    <div class="row page-titles">
      <div class="align-self-center" align="center">
        <h3 class="text-themecolor m-b-0 m-t-0">Edición especial de Reglamentos</h3>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Fin TITULO de la página de sección -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Inicio del CUERPO DE LA SECCIÓN -->
    <!-- ============================================================== -->
    <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'"; } ?>>
      <!-- ============================================================== -->
      <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
      <!-- ============================================================== -->

      <div class="col-lg-1"></div>
      <div class="col-lg-10" id="cnt_form_main" style="display: none;">
        <div class="card">
          <div class="card-header bg-success2" id="ttl_form">
            <div class="card-actions text-white">
              <a style="font-size: 16px;" href="<?= base_url('index.php/reglamento')?>"><i class="mdi mdi-window-close"></i></a>
            </div>
            <h4 class="card-title m-b-0 text-white">Expediente</h4>
          </div>
          <div class="card-body b-t">

            <div id="cnt_form1" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

              <h3 class="box-title" style="margin: 0px;">
                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                  1</button>&emsp;
                Datos del expediente
              </h3>
              <hr class="m-t-0 m-b-30">

              <input type="hidden" id="band" name="band">

              <input type="hidden" id="band1" name="band1">
              <input type="hidden" id="id_expediente" name="id_expediente" value="<?= $solicitud->id_expedientert ?>">
              <input type="hidden" id="id_solicitud" name="id_solicitud">
              <input type="hidden" id="id_comisionado" name="id_comisionado">
              <input type="hidden" id="tipo_solicitud" name="tipo_solicitud">

            <!-- Inicio del formulario del expediente -->

              <span class="etiqueta">Expediente</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>N° de Expediente: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="text" id="num_expediente" name="num_expediente" class="form-control" required="" value="<?= $solicitud->numexpediente_expedientert ?>">
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>N° de Expediente Anterior: </h5>
                    <div class="controls">
                      <input type="text" id="num_expediente_anterior" name="num_expediente_anterior" class="form-control" required="" value="<?= $solicitud->numeroexpediente_anterior ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo de Expediente: <span class="text-danger">*</span></h5>
                    <div class="controls">
                    <select id="tipo_expediente" name="tipo_expediete" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <?php foreach ($tipo_expediente->result() as $fila) {
                          echo "<option value=$fila->id_tipo_solicitud >$fila->nombre_tipo_solicitud</option>";
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Fecha de Creación: </h5>
                    <div class="controls">
                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_creacion" name="fecha_creacion" placeholder="dd/mm/yyyy" readonly="">
                    </div>
                  </div>
                </div>
              </blockquote>

              <!-- Finalización del formulario del expediente -->

              <span class="etiqueta">Establecimiento</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento"></div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo de Solicitante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_solicitante" name="tipo_solicitante" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <?php foreach ($tipo_persona->result() as $fila) {
                          echo "<option value=$fila->id_tipo_solicitante >$fila->nombre_tipo_solicitante</option>";
                        } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </blockquote>

              <span class="etiqueta">Representante Legal o Apoderado</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Nombres: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="text" id="nombres" name="nombres" class="form-control" required="" placeholder="Nombres">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Apellidos: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos"
                        required="" data-validation-required-message="Este campo es requerido">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Sexo: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="sexo" name="sexo" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>DUI: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="text" placeholder="Documento Unico de Identidad" id="dui_comisionado" name="dui_comisionado"
                        class="form-control" required="" data-mask="99999999-9">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>NIT: </h5>
                    <div class="controls">
                      <input type="text" id="nit" name="nit" class="form-control" placeholder="No. De Idententificaci&oacute;n Tributaria"
                        data-mask="9999-999999-999-9">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Telefono: </h5>
                    <div class="controls">
                      <input type="text" placeholder="Telefono" id="telefono" name="telefono" class="form-control" data-mask="9999-9999">
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Correo:</h5>
                    <div class="controls">
                      <input type="text" id="correo" name="correo" class="form-control" placeholder="Correo">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo representante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_representante" name="tipo_representante" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <option value="Representante Legal">Representante Legal</option>
                        <option value="Propietario">Propietario</option>
                        <option value="Apoderado">Apoderado</option>
                      </select>
                    </div>
                  </div>
                </div>

              </blockquote>

              <div align="right" id="btnadd1">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" class="btn waves-effect waves-light btn-success2">
                  Siguiente <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
              <div align="right" id="btnedit1" style="display: none;">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" class="btn waves-effect waves-light btn-info">
                  Siguiente <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
              <?php echo form_close(); ?>
            </div>

            <div id="cnt_form2" class="cnt_form" style="display: none;">
              <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

              <h3 class="box-title" style="margin: 0px;">
                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                  2</button>&emsp;
                Documentaci&oacute;n y designados:
              </h3>
              <hr class="m-t-0 m-b-30">

              <input type="hidden" id="band2" name="band2" value="save">
              <input type="hidden" id="id_expedient" name="id_expedient">
              <input type="hidden" id="id_solicitud2" name="id_solicitud2">

              <span class="etiqueta">Documentaci&oacute;n</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="col-lg-12 <?php if($navegatorless){ echo " pull-left "; } ?>">
                    <div class="form-group">
                      <label for="tipo_solicitante" class="font-weight-bold">
                        Documentaci&oacute;n:
                      </label>

                      <div class="row">
                        <div class="col-lg-6">

                          <input type="checkbox" id="reglamento_interno" name="reglamento_interno" class="filled-in chk-col-light-blue">
                          <label for="reglamento_interno">Reglamento Interno de Trabajo</label>

                          <input type="checkbox" id="constitucion_sociedad" name="constitucion_sociedad" class="filled-in chk-col-light-blue">
                          <label for="constitucion_sociedad">Escritura de Constituci&oacute;n de la Sociedad</label>

                          <input type="checkbox" id="credencial_representante" name="credencial_representante" class="filled-in chk-col-light-blue">
                          <label for="credencial_representante">Credencial Vigente del Representante Legal</label>

                          <br>
                          <input type="checkbox" id="dui" name="dui" class="filled-in chk-col-light-blue">
                          <label for="dui">DUI</label>

                          <br>
                          <input type="checkbox" id="poder" name="poder" class="filled-in chk-col-light-blue">
                          <label for="poder">Poder</label>

                          <br>
                          <input type="checkbox" id="matricula" name="matricula" class="filled-in chk-col-light-blue">
                          <label for="matricula">Matricula de Comercio</label>
                        </div>

                        <div class="col-lg-6">

                          <br>
                          <input type="checkbox" id="estatutos" name="estatutos" class="filled-in chk-col-light-blue">
                          <label for="estatutos">Estatutos</label>

                          <br>
                          <input type="checkbox" id="acuerdo_creacion" name="acuerdo_creacion" class="filled-in chk-col-light-blue">
                          <label for="acuerdo_creacion">Acuerdo Ejecutivo de Creaci&oacute;n</label>

                          <br>
                          <input type="checkbox" id="nominacion" name="nominacion" class="filled-in chk-col-light-blue">
                          <label for="nominacion">Nominaci&oacute;n y Funcionamiento del Centro Educativo</label>

                          <br>
                          <input type="checkbox" id="creacion_escritura" name="creacion_escritura" class="filled-in chk-col-light-blue">
                          <label for="creacion_escritura">Ley de creación de la escritura</label>

                          <br>
                          <input type="checkbox" id="acuerdo_ejecutivo" name="acuerdo_ejecutivo" class="filled-in chk-col-light-blue">
                          <label for="acuerdo_ejecutivo">Acuerdo ejecutivo de nombramiento</label>
                        </div>
                      </div>

                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
              </blockquote>

              <span class="etiqueta">Delegado</span>

              <blockquote class="m-t-0">

                <div class="row">
                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado"></div>
                </div>

              </blockquote>

              <div class="pull-left">
                <button type="button" class="btn waves-effect waves-light btn-default" onclick="volver(1)"><i class="mdi mdi-chevron-left"></i>
                  Volver</button>
              </div>
              <div align="right" id="btnadd2">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar
                </button>
                <button type="submit" class="btn waves-effect waves-light btn-success2">Finalizar
                  <i class="mdi mdi-chevron-right"></i></button>
              </div>
              <div align="right" id="btnedit2" style="display: none;">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" class="btn waves-effect waves-light btn-info">Finalizar
                  <i class="mdi mdi-chevron-right"></i></button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-10" id="cnt_form_main2" style="display: none;">
        <div class="card">
          <div class="card-header bg-success2" id="ttl_form">
            <div class="card-actions text-white">
              <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
            </div>
            <h4 class="card-title m-b-0 text-white">Listado de Expedientes</h4>
          </div>
          <div class="card-body b-t">

            <div id="cnt_form11" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax11', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

              <hr class="m-t-0 m-b-30">

              <input type="hidden" id="band11" name="band">
              <input type="hidden" id="tipo_solicitud2" name="tipo_solicitud">

              <span class="etiqueta">Establecimiento</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo de Solicitante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_solicitante2" name="tipo_solicitante" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <?php foreach ($tipo_persona->result() as $fila) {
                          echo "<option value=$fila->id_tipo_solicitante >$fila->nombre_tipo_solicitante</option>";
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento2"></div>
                </div>
              </blockquote>

              <span class="etiqueta">Delegado</span>

              <blockquote class="m-t-0">

                <div class="row">
                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado2"></div>
                </div>

              </blockquote>

              <div align="right" id="btnadd2">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar
                </button>
                <button type="submit" class="btn waves-effect waves-light btn-success2">Finalizar
                  <i class="mdi mdi-chevron-right"></i></button>
              </div>

              <?php echo form_close(); ?>
            </div>

          </div>
        </div>
      </div>
  </div>
</div>

<div id="cnt_model_establecimiento"></div>
<div id="cnt_modal_acciones"></div>

<script>

$(function(){
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        
        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_reglamento",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              open_form(2);
              $("#id_solicitud").val(res.solicitud);
              $("#id_solicitud2").val(res.solicitud);
              $("#id_expedient").val(res.expediente);
              $("#id_expediente").val(res.expediente);
              $("#band1").val( $("#band").val() );
              $("#band2").val( $("#band").val() );
              if($("#band").val() == "delete"){
                swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
            }
        });
            
    });
});

$(function(){
    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax2"));
        
        $.ajax({
          url: "<?php echo site_url(); ?>/documentacion/gestionar_documentacion",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              cerrar_mantenimiento();
              if($("#band2").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band2").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              tablaReglamentos();
            }
        });
            
    });
});

$(function(){
    $("#formajax11").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax11"));
        
        $.ajax({
          url: "<?php echo site_url(); ?>/reglamento/insertar_reglamentos_filtro",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              cerrar_mantenimiento();
              if($("#band11").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }
              tablaReglamentos();
            }
        });
            
    });
});

$('.change-letter').click(function () {
  $(this).siblings('button').each(function () {
    $(this).removeClass('btn-info');
    $(this).addClass('btn-secondary');
  });

  $(this).removeClass('btn-secondary');
  $(this).addClass('btn-info');

  letra = $(this).data('letra');

  tablaReglamentos();
});

$(function () {
    $(document).ready(function () {
      $('#fecha_creacion').datepicker({
          format: 'dd-mm-yyyy',
          autoclose: true,
          todayHighlight: true
      }).datepicker("setDate", new Date('<?= $solicitud->fechacrea_expedientert ?>'));
    });
});

</script>
