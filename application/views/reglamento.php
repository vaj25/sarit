<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
  function cambiar_editar(id_reglamento,bandera){
    $("#id_expedientert").val(id_reglamento);
    //$("#nombre_vyp_actividades").val(nombre_vyp_actividades);
    //$("#depende_vyp_actividades").val(depende_vyp_actividades).trigger('change.select2');

    $.ajax({
      url: "<?php echo site_url(); ?>/reglamento/registros_reglamentos_documentos",
      type: "post",
      dataType: "json",
      data: {id : id_reglamento},
      cache: false,
      contentType: false,
      processData: false
    })
    .done(function(res){
      
    });


    if(bandera == "edit"){

      $("#ttl_form").removeClass("bg-success");
      $("#ttl_form").addClass("bg-info");
      $("#btnadd").hide(0);
      $("#btnedit").show(0);
      $("#cnt-tabla").hide(0);
      $("#cnt_form").show(0);
      combo_actividades(depende_vyp_actividades);
      $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Actividad");
    }else{           
      eliminar_reglamento();
    }
  }

  function editar_actividad(){ $("#band").val("edit"); enviarDatos(); }
   
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
    $("#id_expedientert").val("");
    $("#depende_vyp_actividades").val("0").trigger('change.select2');
    $("#id_vyp_actividades").val("");

    $("#reglamento_interno").val("");
    $("#constitucion_sociedad").val("");
    $("#credencial_representante").val("");
    $("#poder").val("");
    $("#establecimiento").val("");
    $("#matricula").val("");
    $("#estatutos").val("");
    $("#acuerdo_creacion").val("");
    $("#nominacion").val("");

    $("#band").val("save");
    //combo_actividades('0');
    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);
    $("#cnt-tabla").hide(0);
    $("#cnt_form").show(0);
    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Aprobaci&oacute;n de Reglamentos");
  }

  function cerrar_mantenimiento(){
    $("#nombre_vyp_actividades").val("");
    $("#depende_vyp_actividades").val("0").trigger('change.select2');
    $("#id_vyp_actividades").val("");
    $("#band").val("save");
    $("#cnt-tabla").show(0);
    $("#cnt_form").hide(0);
  }

  function iniciar(){
    <?php if(tiene_permiso($segmentos=1,$permiso=1)){ ?>
      tablaReglamentos();
    <?php }else{ ?>
      $("#cnt-tabla").html("Usted no tiene permiso para este formulario.");     
    <?php } ?>
  }

  function objetoAjax(){
    var xmlhttp = false;
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
    return xmlhttp;
  }

  function tablaReglamentos(){
    $( "#cnt-tabla" ).load("<?php echo site_url(); ?>/reglamento/tabla_reglamento/", function() {
      $('#myTable').DataTable();
      $('[data-toggle="tooltip"]').tooltip();
    });
  }

  function combo_actividades(seleccion){
    id=seleccion;
    if(window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();
    }else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTPB"); }
    
    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("div_combo_actividades").innerHTML=xmlhttp.responseText;
        $(".select2").select2();
      }
    }
    xmlhttp.open("GET","<?php echo site_url(); ?>/configuraciones/actividad/mostrarActividad/"+id,true);
    xmlhttp.send();
  }
</script>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row page-titles">
      <div class="align-self-center" align="center">
        <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Aprobaci&oacute;n de Reglamentos</h3>
      </div>
    </div>
    <div class="row">

      <div class="col-lg-1"></div>
      <div class="col-lg-10" id="cnt_form" style="display: none;">
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
            <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40', 'novalidate' => '')); ?>
            <input type="hidden" id="band" name="band" value="save">
            <input type="hidden" id="id_expedientert" name="id_expedientert">
            <div class="row">
              <div class="col-lg-6 <?php if($navegatorless){ echo " pull-left "; } ?>">
                <div class="form-group">
                  <label for="tipo_solicitante" class="font-weight-bold">Tipo de Solicitante:
                    <span class="text-danger">*</span>
                  </label>
                  <select id="tipo_solicitante" name="tipo_solicitante" class="form-control" onchange="" required="">
                    <option value="0">[Seleccione]</option>
                    <option value="1">Opcion 1</option>
                    <option value="2">Opcion 2</option>
                  </select>
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento">
                <div>
                  <label for="establecimiento" class="font-weight-bold">Establecimiento:
                    <span class="text-danger">*</span>
                  </label>
                  <select id="establecimiento" name="establecimiento" class="form-control" onchange="" required="" >
                    <option value="0">[Seleccione]</option>
                    <option value="1">Opcion 1</option>
                    <option value="2">Opcion 2</option>
                  </select>
                  <div class="help-block"></div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 <?php if($navegatorless){ echo " pull-left "; } ?>">
                <div class="form-group">
                  <label for="tipo_solicitante" class="font-weight-bold">
                    Documentaci&oacute;n:
                  </label>

                  <div class="row">
                    <div class="col-lg-6">

                      <input type="checkbox" id="reglamento_interno" class="filled-in chk-col-light-blue">
                      <label for="reglamento_interno">Reglamento Interno de Trabajo</label>
                    
                      <input type="checkbox" id="constitucion_sociedad" class="filled-in chk-col-light-blue">
                      <label for="constitucion_sociedad">Escritura de Constituci&oacute;n de la Sociedad</label>

                      <input type="checkbox" id="credencial_representante" class="filled-in chk-col-light-blue">
                      <label for="credencial_representante">Credencial Vigente del Representante Legal</label>
                      
                      <br>                    
                      <input type="checkbox" id="dui" class="filled-in chk-col-light-blue">
                      <label for="dui">DUI:</label>

                      <br>                    
                      <input type="checkbox" id="poder" class="filled-in chk-col-light-blue">
                      <label for="poder">Poder:</label>

                    </div>
                    
                    <div class="col-lg-6" >
                        <input type="checkbox" id="matricula" class="filled-in chk-col-light-blue">
                        <label for="matricula">Matricula de Comercio:</label>
                        
                        <br>
                        <input type="checkbox" id="estatutos" class="filled-in chk-col-light-blue">
                        <label for="estatutos">Estatutos:</label>
                        
                        <br>
                        <input type="checkbox" id="acuerdo_creacion" class="filled-in chk-col-light-blue">
                        <label for="acuerdo_creacion">Acuerdo Ejecutivo de Creaci&oacute;n:</label>

                        <br>
                        <input type="checkbox" id="nominacion" class="filled-in chk-col-light-blue">
                        <label for="nominacion">Nominaci&oacute;n y Funcionamiento del Centro Educativo:</label>
                    </div>
                  </div>

                  <div class="help-block"></div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-lg-6 <?php if($navegatorless){ echo " pull-left "; } ?>">
                <div class="form-group">
                  <label for="colaborador" class="font-weight-bold">Asignar Delegado:
                    <span class="text-danger">*</span>
                  </label>
                  <select id="colaborador" name="colaborador" class="form-control" onchange="">
                    <option value="0">[Seleccione]</option>
                    <option value="1">Opcion 1</option>
                    <option value="2">Opcion 2</option>
                  </select>
                  <div class="help-block"></div>
                </div>
              </div>
            </div>

            <button id="submit" type="submit" style="display: none;"></button>
            <div align="right" id="btnadd">
              <button type="reset" class="btn waves-effect waves-light btn-success">
                <i class="mdi mdi-recycle"></i> Limpiar</button>
              <button type="submit" class="btn waves-effect waves-light btn-success2">
                <i class="mdi mdi-plus"></i> Guardar</button>
            </div>
            <div align="right" id="btnedit" style="display: none;">
              <button type="reset" class="btn waves-effect waves-light btn-success">
                <i class="mdi mdi-recycle"></i> Limpiar</button>
              <button type="button" onclick="editar_horario()" class="btn waves-effect waves-light btn-info">
                <i class="mdi mdi-pencil"></i> Editar</button>
            </div>
            </div>
            <?php echo form_close(); ?>
          </div>

        </div>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-12" id="cnt-tabla">

      </div>

    </div>
  </div>
</div>

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
            if(res == "exito"){
                cerrar_mantenimiento();
                if($("#band").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
                }
                tablaReglamentos();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
            
    });
});

</script>
