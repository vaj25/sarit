<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
  function cambiar_editar(id_reglamento, bandera){
    $("#id_expedientert").val(id_reglamento);

    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/registros_reglamentos_documentos",
      type: "POST",
      data: {id : id_reglamento}
    })
    .done(function(res){
      result = JSON.parse(res)[0];

      $("#id_expedientert").val(result.id_expedientert);
      $("#id_expediente").val(result.id_expedientert);
      $("#tipo_solicitante").val(result.tipopersona_expedientert).trigger('change.select2');

      $("#reglamento_interno").attr('checked',result.docreglamento_documentort);
      $("#constitucion_sociedad").attr('checked',result.escritura_documentort);
      $("#credencial_representante").attr('checked',result.credencial_documentort);
      $("#poder").attr('checked',result.poder_documentort);
      $("#dui").attr('checked',result.dui_documentort);
      $("#matricula").attr('checked',result.matricula_documentort);
      $("#estatutos").attr('checked',result.estatutos_documentort);
      $("#acuerdo_creacion").attr('checked',result.acuerdoejec_documentort);
      $("#nominacion").attr('checked',result.nominayfuncion_documentort);
      $("#creacion_escritura").attr('checked',result.leycreacionescritura_documentort);
      $("#acuerdo_ejecutivo").attr('checked',result.acuerdoejecutivo_documentort);

      $("#id_comisionado").val(result.id_representantert);
      $("#nombres").val(result.nombres_representantert);
      $("#apellidos").val(result.apellidos_representantert);
      $("#dui_comisionado").val(result.dui_representantert);
      $("#nit").val(result.nit_representantert);
      $("#telefono").val(result.telefono_representantert);
      $("#correo").val(result.correo_representantert);
      $("#tipo_representante").val(result.cargo_representantert).trigger('change.select2');
      $("#sexo").val(result.sexo_representantert).trigger('change.select2');

      if(bandera == "edit"){

        $("#tipo_solicitud").val(result.tiposolicitud_expedientert);
        combo_establecimiento(result.id_empresart);

      } else {
        
        combo_establecimiento(result.id_empresart, 'readonly');
        $("#tipo_solicitante").attr('readonly', 'readonly');

      }

      combo_delegado(result.id_empleado, 'readonly');

    });

    $("#ttl_form").removeClass("bg-success");
    $("#ttl_form").addClass("bg-info");
    $("#btnadd1").hide(0);
    $("#btnedit1").show(0);
    $("#btnadd2").hide(0);
    $("#btnedit2").show(0);
    $("#cnt-tabla").hide(0);
    $("#cnt_form_main").show(0);

    if(bandera == "edit"){

      $("#band").val("edit");
      $("#band1").val("edit");
      $("#band2").val("edit");
      $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Expediente");

    } else if(bandera == "reforma_parcial") {

      $("#band").val("reforma_parcial");
      $("#band1").val("reforma_parcial");
      $("#band2").val("reforma_parcial");
      $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Reforma Parcial");
      $("#tipo_solicitud").val('Reforma Parcial');

    } else if(bandera == "reforma_total") {

      $("#band").val("reforma_total");
      $("#band1").val("reforma_total");
      $("#band2").val("reforma_total");
      $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Reforma Total");
      $("#tipo_solicitud").val('Reforma Total');

    } else {
      cambiar_nuevo();
    }

  }
   
  function eliminar_reglamento(){
    $("#band").val("delete");
    swal({
      title: "¿Está seguro?", 
      text: "¡Desea eliminar el registro!",
      type: "warning", 
      showCancelButton: true, 
      confirmButtonColor: "#fc4b6c", 
      confirmButtonText: "Sí, deseo eliminar!", 
      closeOnConfirm: false
    }, function(){
        
      $( "#formajax" ).submit();

    });
   }

  function cambiar_nuevo(){
    $("#id_expediente").val("");
    $("#id_expedient").val("");
    $("#tipo_solicitante").val("").trigger('change.select2');
    $("#tipo_solicitante").removeAttr('readonly');
    $("#tipo_solicitud").val('Registro');

    $("#reglamento_interno").prop('checked',false);
    $("#constitucion_sociedad").attr('checked',false);
    $("#credencial_representante").attr('checked',false);
    $("#poder").attr('checked',false);
    $("#dui").attr('checked',false);
    $("#establecimiento").attr('checked',false);
    $("#matricula").attr('checked',false);
    $("#estatutos").attr('checked',false);
    $("#acuerdo_creacion").attr('checked',false);
    $("#nominacion").attr('checked',false);
    $("#creacion_escritura").attr('checked',false);
    $("#acuerdo_ejecutivo").attr('checked',false);

    $("#nombres").val("");
    $("#apellidos").val("");
    $("#dui_comisionado").val("");
    $("#nit").val("");
    $("#telefono").val("");
    $("#correo").val("");
    $("#tipo_representante").val("").trigger('change.select2');
    $("#sexo").val("").trigger('change.select2');

    $("#band").val("save");
    $("#band1").val("save");
    $("#band2").val("save");

    combo_delegado('');

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);
    $("#cnt-tabla").hide(0);
    $(".cnt_form").hide(0);
    $("#cnt_form1").show(0);
    $("#cnt_form_main").show(0);
    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Aprobaci&oacute;n de Reglamentos");
    
    combo_establecimiento('');
  }

  function cerrar_mantenimiento(){
    $("#cnt-tabla").show(0);
    $("#cnt_form_main").hide(0);
    $("#cnt_actions").hide(0);
    $("#cnt_actions").remove('.card');
    open_form(1);
  }

  function iniciar(){
    <?php if(tiene_permiso($segmentos=1,$permiso=1)){ ?>
      tablaReglamentos();
    <?php }else{ ?>
      $("#cnt-tabla").html("Usted no tiene permiso para este formulario.");     
    <?php } ?>
  }

  function tablaReglamentos(){
    var nr_empleado = $("#nr_search").val();
    $( "#cnt_tabla_expedientes" ).load("<?php echo site_url(); ?>/reglamento/tabla_reglamento?nr="+nr_empleado+"&tipo="+estado_pestana, function() {
      $('#myTable').DataTable();
      $('[data-toggle="tooltip"]').tooltip();
    });
  }

var estado_pestana = "";
function cambiar_pestana(tipo){
    estado_pestana = tipo;
    tablaReglamentos();
}

  function combo_establecimiento(seleccion, disable=''){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/combo_establecimiento",
      type: "post",
      dataType: "html",
      data: {id : seleccion, disable: disable}
    })
    .done(function(res){
      $('#div_combo_establecimiento').html(res);
      $("#establecimiento").select2({
        'minimumInputLength': 3,
        'language': {
          noResults: function () {
            return '<a href="javascript:;" data-toggle="modal" title="Agregar nuevos establecimientos" onClick="cerrar_combo_establecimiento()">Agregar uno nuevo</a>';
          }
        },
        'escapeMarkup': function (markup) {
          return markup;
        }
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

  function visualizar(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/ver_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_actions').html(res);
      $("#cnt_actions").show(0);
      $("#cnt-tabla").hide(0);
      $("#cnt_form_main").hide(0);
    });
  }

  function resolucion(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/resolucion_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('#modal_resolucion').modal('show');
    });
  }

  function notificacion_resolucion(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/notificacion_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('#modal_notificacion').modal('show');
    });
  }

  function actualizar_estado(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/estado_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('.select2').select2();
      $('#modal_actualizar_estado').modal('show');
    });
  }

  function entrega_resolucion(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/entrega_resolucion",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('#modal_entrega_resolucion').modal('show');
    });
  }

  function modal_delegado(id_reglamento, id_delegado) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/delegado_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('.select2').select2();
      $("#id_personal_copia").val(id_delegado).trigger('change.select2');
      $('#modal_delegado').modal('show');
    });
  }

  function modal_acciones(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/modal_acciones",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('.select2').select2();
      $('#modal_acciones').modal('show');
    });
  }

  function modal_acta_aprobada(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/modal_acta_aprobada",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('#modal_acta_aprobada').modal('show');
    });
  }

  function adjuntar_reglamento(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/adjuntar_reglamento",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('.dropify').dropify();
      $('#modal_adjuntar').modal('show');
    });
  }

  function desistir(id_reglamento) {
    swal({
      title: "Desistir de Expediente",
      text: "¿Motivo por el cual desiste? *",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      inputPlaceholder: "Motivo para desistir"
    }, function (inputValue) {
      if (inputValue === false) return false;
      if (inputValue === "") {
        swal.showInputError("Se necesita un motivo para desistir.");
        return false
      }
      $.ajax({
          url: "<?php echo site_url(); ?>/reglamento/gestionar_desistir_reglamento",
          type: "post",
          dataType: "html",
          data: {
            id_reglamento_resolucion: id_reglamento,
            mov_disistir: inputValue
          }
        })
        .done(function (res) {
          if(res == "exito"){
            tablaReglamentos();
            swal({ title: "¡Expediente desistido exitosamente!", type: "success", showConfirmButton: true });
          }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });
  }

  function habilitar(id_reglamento) {
    swal({
        title: "Confirmar Habilitación",
        text: "¿Está seguro que desea habilitar el expediente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success2",
        confirmButtonText: "Si",
        closeOnConfirm: false
      },
      function () {
        $.ajax({
            url: "<?php echo site_url(); ?>/reglamento/gestionar_habilitar_reglamento",
            type: "post",
            dataType: "html",
            data: {
              id_reglamento_resolucion: id_reglamento,
            }
          })
          .done(function (res) {
            if(res == "exito"){
              tablaReglamentos();
              swal({ title: "¡Expediente habilitado exitosamente!", type: "success", showConfirmButton: true });
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
          });
      });
  }

  function combo_delegado(seleccion, disable=''){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/combo_delegado",
      type: "post",
      dataType: "html",
      data: {id : seleccion, disable: disable}
    })
    .done(function(res){
      $('#div_combo_delegado').html(res);
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
        <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Aprobaci&oacute;n de Reglamentos</h3>
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
                <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
              </div>
              <h4 class="card-title m-b-0 text-white">Listado de Expedientes</h4>
            </div>
          <div class="card-body b-t">

            <div id="cnt_form1" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

              <h3 class="box-title" style="margin: 0px;">
                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                  1</button>&emsp;
                Datos del esblecimiento y del Representante Legal o Apoderado
              </h3>
              <hr class="m-t-0 m-b-30">

              <input type="hidden" id="band" name="band">

              <input type="hidden" id="band1" name="band1">
              <input type="hidden" id="id_expediente" name="id_expediente">
              <input type="hidden" id="id_comisionado" name="id_comisionado">
              <input type="hidden" id="tipo_solicitud" name="tipo_solicitud">

              <span class="etiqueta">Establecimiento</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo de Solicitante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_solicitante" name="tipo_solicitante" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <option value="Sociedad">Sociedad</option>
                        <option value="Persona Natural">Persona Natural</option>
                        <option value="Asociación">Asociación</option>
                        <option value="Autonomas">Autonomas</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento"></div>
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
                    <h5>NIT: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="text" id="nit" name="nit" class="form-control" placeholder="No. De Idententificaci&oacute;n Tributaria"
                        required="" data-mask="9999-999999-999-9">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Telefono: </h5>
                    <div class="controls">
                      <input type="text" placeholder="Telefono" id="telefono" name="telefono" class="form-control"
                        data-mask="9999-9999">
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
    </div>
  </div>
  <div class="col-lg-1"></div>
  <div class="col-lg-12" id="cnt-tabla">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title m-b-0">Listado de Expedientes</h4>
      </div>
      <div class="card-body b-t" style="padding-top: 7px;">
        <div>
          <div class="pull-left">
            <div class="form-group" style="width: 400px;">
              <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tablaReglamentos();">
                <option value="">[Todos los empleados]</option>
                <?php
                $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                if($otro_empleado->num_rows() > 0){
                  foreach ($otro_empleado->result() as $fila) {
                    if($nr_usuario == $fila->nr){
                      echo '<option class="m-l-50" value="'.$fila->nr.'" selected>'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                    }else{
                      echo '<option class="m-l-50" value="'.$fila->nr.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                    }
                  }
                }
              ?>
              </select>
            </div>
          </div>
          <div class="pull-right">
            <?php if(tiene_permiso($segmentos=1,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip"><span
                class="mdi mdi-plus"></span> Nuevo registro</button>
            <?php } ?>
          </div>
        </div>
        <div class="row" style="width: 100%"></div>
        <div class="row col-lg-12">
          <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo " pull-left"; } ?>" role="tablist"
            style='width:
            100%;'>
            <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
              <a class="nav-link active" onclick="cambiar_pestana('');" data-toggle="tab" href="#">
                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                <span class="hidden-xs-down">Todas</span></a>
            </li>
            <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
              <a class="nav-link" onclick="cambiar_pestana('1');" data-toggle="tab" href="#">
                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                <span class="hidden-xs-down">En estudio</span></a>
            </li>
            <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
              <a class="nav-link" onclick="cambiar_pestana('2');" data-toggle="tab" href="#">
                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                <span class="hidden-xs-down">Observado</span></a>
            </li>
            <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
              <a class="nav-link" onclick="cambiar_pestana('3');" data-toggle="tab" href="#">
                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                <span class="hidden-xs-down">Aprobado</span></a>
            </li>
          </ul>
        </div>
        <div id="cnt_tabla_expedientes"></div>
      </div>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
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
              open_form(2);
              $("#id_expediente").val(res);
              $("#id_expedient").val(res);
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
    $("#formajax3").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax3"));
        
        $.ajax({
          url: "<?php echo site_url(); ?>/establecimiento/gestionar_establecimiento",
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
              swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });

              var data = {
                  id: res,
                  text: $("#nombre_establecimiento").val()
              };

              var newOption = new Option(data.text, data.id, false, false);
              $('#establecimiento').append(newOption).trigger('change');
              $('#establecimiento').val(data.id).trigger("change");
              $('#modal_establecimiento').modal('toggle');
            }
        });
            
    });
});

</script>
