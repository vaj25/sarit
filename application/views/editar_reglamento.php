<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">

  function iniciar(){
    <?php //if(tiene_permiso($segmentos=1,$permiso=1)){ ?>
    <?php if(true){ ?>
      $("#cnt_form_main").show();
      $("#tipo_expediente").val(<?= $solicitud->id_tipo_solicitud ?>).trigger('change.select2');
      combo_establecimiento( '<?= $solicitud->id_empresa ?>', 1 );
      combo_delegado( '<?= $solicitud->id_empleado ?>', 1);
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

              <input type="hidden" id="id_expediente" name="id_expediente" value="<?= $solicitud->id_expedientert ?>">
              <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?= $solicitud->id_solicitud ?>">
              <input type="hidden" id="id_representante" name="id_representantert" value="<?= $solicitud->id_representantert ?>">
              <input type="hidden" id="dui_representante" name="dui_representante" value="<?= $solicitud->dui_representantert ?>">

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
                      <input type="text" id="num_expediente_anterior" name="num_expediente_anterior" class="form-control" value="<?= $solicitud->numeroexpediente_anterior ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Archivo del expediente: </h5>
                    <div class="controls">
                      <input type="text" id="archivo_expedientert" name="archivo_expedientert" class="form-control" 
                        placeholder="Archivo del expediente"  value="<?= $solicitud->archivo_expedientert ?>">
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo de Expediente: <span class="text-danger">*</span></h5>
                    <div class="controls">
                    <select id="tipo_expediente" name="tipo_expediente" class="form-control" required>
                        <option value="">[Seleccione]</option>
                        <?php foreach ($tipo_expediente->result() as $fila) {
                          echo "<option value=$fila->id_tipo_solicitud >$fila->nombre_tipo_solicitud</option>";
                        } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Fecha de Creación: </h5>
                    <div class="controls">
                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_creacion" 
                        name="fecha_creacion" placeholder="dd/mm/yyyy" readonly="">
                    </div>
                  </div>
                </div>
              </blockquote>

              <!-- Finalización del formulario del expediente -->
              
              <!-- Inicia del formulario del establecimiento -->

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
                          if ($fila->id_tipo_solicitante == $solicitud->tipopersona_expedientert) {
                            echo "<option value=$fila->id_tipo_solicitante selected >$fila->nombre_tipo_solicitante</option>";
                          } else {
                            echo "<option value=$fila->id_tipo_solicitante >$fila->nombre_tipo_solicitante</option>";
                          }
                        } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </blockquote>

              <!-- Finalización del formulario del establecimiento -->

              <!-- Inicia el formulario del representante -->

              <span class="etiqueta">Representante Legal o Apoderado</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Nombres: </h5>
                    <div class="controls">
                      <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres" 
                        value="<?= $solicitud->nombres_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Apellidos: </h5>
                    <div class="controls">
                      <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos"
                        value="<?= $solicitud->apellidos_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Sexo: </h5>
                    <div class="controls">
                      <select id="sexo" name="sexo" class="form-control" >
                        <option value="">[Seleccione]</option>
                        <option value="1" <?= ($solicitud->sexo_representantert == 1) ? "selected" : "" ?> >Hombre</option>
                        <option value="2" <?= ($solicitud->sexo_representantert == 2) ? "selected" : "" ?>>Mujer</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>DUI: </h5>
                    <div class="controls">
                      <input type="text" placeholder="Documento Unico de Identidad" id="dui" name="dui"
                        class="form-control" data-mask="99999999-9" value="<?= $solicitud->dui_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>NIT: </h5>
                    <div class="controls">
                      <input type="text" id="nit" name="nit" class="form-control" placeholder="No. De Idententificaci&oacute;n Tributaria"
                        data-mask="9999-999999-999-9" value="<?= $solicitud->nit_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Telefono: </h5>
                    <div class="controls">
                      <input type="text" placeholder="Telefono" id="telefono" name="telefono" class="form-control" data-mask="9999-9999"
                        value="<?= $solicitud->telefono_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Correo:</h5>
                    <div class="controls">
                      <input type="text" id="correo" name="correo" class="form-control" placeholder="Correo" 
                        value="<?= $solicitud->correo_representantert ?>">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo representante: </h5>
                    <div class="controls">
                      <select id="tipo_representante" name="tipo_representante" class="form-control" >
                        <option value="">[Seleccione]</option>
                        <option value="Representante Legal" <?= ($solicitud->cargo_representantert == "Representante Legal") ? "selected" : "" ?> >Representante Legal</option>
                        <option value="Propietario" <?= ($solicitud->cargo_representantert == "Propietario") ? "selected" : "" ?> >Propietario</option>
                        <option value="Apoderado" <?= ($solicitud->cargo_representantert == "Apoderado") ? "selected" : "" ?> >Apoderado</option>
                      </select>
                    </div>
                  </div>
                </div>

              </blockquote>

              <!-- Finalización del formulario del representante -->

              <!-- Inicia el formulario del documento -->

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

                          <input type="checkbox" id="reglamento_interno" name="reglamento_interno" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->docreglamento_documentort) ? "checked" : "" ?>>
                          <label for="reglamento_interno">Reglamento Interno de Trabajo</label>

                          <input type="checkbox" id="constitucion_sociedad" name="constitucion_sociedad" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->escritura_documentort) ? "checked" : "" ?> >
                          <label for="constitucion_sociedad">Escritura de Constituci&oacute;n de la Sociedad</label>

                          <input type="checkbox" id="credencial_representante" name="credencial_representante" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->credencial_documentort) ? "checked" : "" ?> >
                          <label for="credencial_representante">Credencial Vigente del Representante Legal</label>

                          <br>
                          <input type="checkbox" id="dui_doc" name="dui_doc" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->dui_documentort) ? "checked" : "" ?> >
                          <label for="dui">DUI</label>

                          <br>
                          <input type="checkbox" id="poder" name="poder" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->poder_documentort) ? "checked" : "" ?> >
                          <label for="poder">Poder</label>

                          <br>
                          <input type="checkbox" id="matricula" name="matricula" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->matricula_documentort) ? "checked" : "" ?> >
                          <label for="matricula">Matricula de Comercio</label>
                        </div>

                        <div class="col-lg-6">

                          <br>
                          <input type="checkbox" id="estatutos" name="estatutos" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->estatutos_documentort) ? "checked" : "" ?> >
                          <label for="estatutos">Estatutos</label>

                          <br>
                          <input type="checkbox" id="acuerdo_creacion" name="acuerdo_creacion" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->acuerdoejec_documentort) ? "checked" : "" ?> >
                          <label for="acuerdo_creacion">Acuerdo Ejecutivo de Creaci&oacute;n</label>

                          <br>
                          <input type="checkbox" id="nominacion" name="nominacion" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->nominayfuncion_documentort) ? "checked" : "" ?> >
                          <label for="nominacion">Nominaci&oacute;n y Funcionamiento del Centro Educativo</label>

                          <br>
                          <input type="checkbox" id="creacion_escritura" name="creacion_escritura" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->leycreacionescritura_documentort) ? "checked" : "" ?> >
                          <label for="creacion_escritura">Ley de creación de la escritura</label>

                          <br>
                          <input type="checkbox" id="acuerdo_ejecutivo" name="acuerdo_ejecutivo" class="filled-in chk-col-light-blue"
                            value="1" <?= ($solicitud->acuerdoejecutivo_documentort) ? "checked" : "" ?> >
                          <label for="acuerdo_ejecutivo">Acuerdo ejecutivo de nombramiento</label>
                        </div>
                      </div>

                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>
              </blockquote>

              <!-- Finaliza el formulario del documento -->

              <!-- Inicia el formulario del delegado -->

              <span class="etiqueta">Delegado</span>

              <blockquote class="m-t-0">

                <div class="row">
                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado"></div>
                </div>

              </blockquote>

              <!-- Finaliza el formulario del delegado -->

              <!-- Inicia el formulario de resultado -->

              <span class="etiqueta">Resultado</span>

              <blockquote class="m-t-0">

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Resoluci&oacute;n: </h5>
                    <div class="controls">
                      <select id="resolucion" name="resolucion" class="form-control" >
                        <option value="">[Seleccione]</option>
                        <option value="Aprobado" <?= ($solicitud->resolucion_solicud == "Aprobado") ? "selected" : "" ?>>Aprobado</option>
                        <option value="Denegado" <?= ($solicitud->resolucion_solicud == "Denegado") ? "selected" : "" ?>>Denegado</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Reglamento con Observaci&oacute;n de Genero: </h5>
                    <div class="controls">
                      <select id="ob_genero" name="ob_genero" class="form-control" >
                        <option value="">[Seleccione]</option>
                        <option value="Si" <?= ($solicitud->obsergenero_solicitud == "Si") ? "selected" : "" ?>>Si</option>
                        <option value="No" <?= ($solicitud->obsergenero_solicitud == "No") ? "selected" : "" ?>>No</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Fecha de Resolución: </h5>
                    <div class="controls">
                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" class="form-control" 
                        id="fecha_resolucion" name="fecha_resolucion" placeholder="dd/mm/yyyy" readonly="">
                    </div>
                  </div>
                </div>

              </blockquote>

              <!-- Finaliza el formulario de resultado -->

              <!-- Inicia el formulario de notificaciones -->

              <span class="etiqueta">Notificaciones</span>

              <blockquote class="m-t-0">

                <div class="row">
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Notificaci&oacute;n de resultado de estudio: </h5>
                    <div class="controls">
                      <select id="notificacion" name="notificacion" class="form-control" >
                        <option value="">[Seleccione]</option>
                        <option value="Si" <?= ($solicitud->notificacion_solicitud == "Si") ? "selected" : "" ?>>Si</option>
                        <option value="No" <?= ($solicitud->notificacion_solicitud == "No") ? "selected" : "" ?>>No</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>" id="notificacion_container">
                    <h5>Fecha y Hora de Notificaci&oacute;n: </h5>
                    <div class="controls">
                      <input type="datetime-local" class="form-control" id="notificacion_fecha" nombre="notificacion_fecha"
                      value="<?= date('Y-m-d\TH:i:s', strtotime($solicitud->fechanotificacion_solicitud)) ?>" >
                    </div>
                  </div>
                </div>

              </blockquote>

              <!-- Finaliza el formulario de notificaciones -->

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

<div id="cnt_model_establecimiento"></div>

<script>

$(function(){
    $("#formajax").on("submit", function(e){
      e.preventDefault();
      var formData = new FormData(document.getElementById("formajax"));
      formData.append("notificacion_fecha", $("#notificacion_fecha").val());
      
      $.ajax({
        url: "<?php echo site_url(); ?>/editar_reglamento/gestionar_reglamento",
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
          swal({ 
            title: "¡Reglamento actualizado exitosamente!", 
            type: "success", 
            showConfirmButton: true},
            function(isConfirm){
              setInterval(function () {
                location.href = "<?= base_url('index.php/reglamento')?>";
              }, 200);
            });

        }
      });
            
    });
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

$(function () {
    $(document).ready(function () {
      $('#fecha_resolucion').datepicker({
          format: 'dd-mm-yyyy',
          autoclose: true,
          todayHighlight: true
      }).datepicker("setDate", new Date('<?= $solicitud->fecharesolucion_solicitud ?>'));
    });
});

$('#notificacion').change(function () {
  if ($('#notificacion').val() == 'No') {
    $('#notificacion_container').hide();
  } else {
    $('#notificacion_container').show();
  }
});

</script>