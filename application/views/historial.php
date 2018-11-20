<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
  var letra = 'A';

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

    $.ajax({
      url: "<?php echo site_url(); ?>/historial/tabla_reglamento",
      type: "get",
      dataType: "html",
      data: {nr: nr_empleado, letra: letra},
      beforeSend: function(){
        $( "#cnt-tabla-historial" ).html("<div style='width:100%; padding:0.5em; text-align:center;'><span class='fa fa-spinner fa-spin' style='font-size:2em;'></span></div>");
      }
    })
    .done(function (result) {
      $( "#cnt-tabla-historial" ).html(result);
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
          <input type="hidden" id="nr_search" name="nr_search" value="<?= $this->session->userdata('nr')?>">
          <?php }?>
        </div>

        <div class="row" style="width: 100%"></div>
        <div class="row col-lg-12">
          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
              <div class="btn-group mr-2" role="group" aria-label="First group">
                  <button type="button" class="change-letter btn btn-info" data-letra="A">A</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="B">B</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="C">C</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="D">D</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="E">E</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="F">F</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="G">G</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="H">H</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="I">I</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="J">J</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="K">K</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="L">L</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="M">M</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="N">N</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="Ñ">Ñ</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="O">O</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="P">P</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="Q">Q</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="R">R</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="S">S</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="T">T</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="U">U</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="V">V</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="W">W</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="X">X</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="Y">Y</button>
                  <button type="button" class="change-letter btn btn-secondary" data-letra="Z">Z</button>
              </div>
          </div>
        </div>

        <br>

        <div id="cnt-tabla-historial"></div>

      </div>
    </div>
  </div>
  <div class="col-lg-1"></div>
  <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
</div>

<div id="cnt_model_establecimiento"></div>
<div id="cnt_modal_acciones"></div>

<script>

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

</script>