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
    $("#cnt_actions").hide(0);
    $("#cnt_actions").remove('.card');
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
    $( "#cnt-tabla-historial" ).load("<?php echo site_url(); ?>/historial/tabla_reglamento?nr="+nr_empleado, function() {
      $('#myTable').DataTable();
      $('[data-toggle="tooltip"]').tooltip();
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

  function historial(num_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/historial/ver_historial",
      type: "post",
      dataType: "html",
      data: {num : num_reglamento}
    })
    .done(function(res){
      $('#cnt_actions').html(res);
      $('#table-historial').DataTable();
      $("#cnt_actions").show(0);
      $("#cnt-tabla").hide(0);
      $("#cnt_form_main").hide(0);
    });
  }

  function detalle(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/historial/ver_detalle",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_actions').html(res);
      $('#table-detalle').DataTable();
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

  function inhabilitar(id_reglamento) {
    swal({
      title: "Inhabilitar Expediente",
      text: "Motivo de Inhabilitar Expediente: *",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      inputPlaceholder: "Motivo para inhabilitar"
    }, function (inputValue) {
      if (inputValue === false) return false;
      if (inputValue === "") {
        swal.showInputError("Se necesita un motivo para inhabilitar.");
        return false
      }
      $.ajax({
          url: "<?php echo site_url(); ?>/reglamento/gestionar_inhabilitar_reglamento",
          type: "post",
          dataType: "html",
          data: {
            id_reglamento_resolucion: id_reglamento,
            mov_inhabilitar: inputValue
          }
        })
        .done(function (res) {
          if(res == "exito"){
            tablaReglamentos();
            swal({ title: "¡Expediente inhabilitado exitosamente!", type: "success", showConfirmButton: true });
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

  function combo_delegado(seleccion){
    
    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/combo_delegado",
      type: "post",
      dataType: "html",
      data: {id : seleccion}
    })
    .done(function(res){
      $('#div_combo_delegado').html(res);
      $(".select2").select2();
    });

  }
</script>

<div class="page-wrapper">
    <div class="col-lg-1"></div>
    <div class="col-lg-12" id="cnt-tabla">
      <div class="card">
        <div class="card-header">
          <div class="card-actions">

          </div>
          <h4 class="card-title m-b-0">Listado de Reglamentos</h4>
        </div>
        <div class="card-body b-t" style="padding-top: 7px;">
          <div class="pull-left">
            <?php if (obtener_rango($segmentos=1, $permiso=1) > 1) { ?>
            <div class="form-group" style="width: 400px;">
              <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tablaReglamentos();">
                <option value="">[Todos los empleados]</option>
                <?php
                if($delegados){
                  foreach ($delegados->result() as $fila) {
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
            <?php } else { ?>
              <input type="hidden" id="nr_search" name="nr_search" value="<?= $this->session->userdata('id_usuario')?>">
            <?php }?>
          </div>

          <div id="cnt-tabla-historial"></div>

        </div>
      </div>
    </div>
    <div class="col-lg-1"></div>
    <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
</div>
</div>

<div id="cnt_model_establecimiento"></div>
<div id="cnt_modal_acciones"></div>
