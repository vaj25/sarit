<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function cambiar_editar(id,estado,descripcion,bandera){
        $("#id_estadort").val(id);
        $("#descripcion").val(descripcion);
        $("#estado").val(estado);

        if(bandera == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd").hide(0);
            $("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar viático");
        }else{
            eliminar_estado();
        }
    }

    function cambiar_nuevo(){
        $("#estado").val("");
        $("#descripcion").val("");
        $("#band").val("save");

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nuevo Estado");
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_form").hide(0);
    }

    function editar_estado(){
        $("#band").val("edit");
        $("#submit").click();
    }

    function eliminar_estado(){
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

    function iniciar(){
        <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
            tablaEstados();
        <?php }else{ ?>
            $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");     
        <?php } ?>
    }

    function tablaEstados(){
        $( "#cnt_tabla" ).load("<?php echo site_url(); ?>/configuraciones/estado/tabla_estados", function() {
            $('#myTable').DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

</script>

<!-- ============================================================== -->
<!-- Inicio de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Estados</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'" ; } ?>>
            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de estados</h4>
                    </div>
                    <div class="card-body b-t">

                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <input type="hidden" id="band" name="band" value="save">
                        <input type="hidden" id="id_estadort" name="id_estadort">
                        <div class="row">
                            <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                <h5>Estado: <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" id="estado" name="estado" class="form-control" required=""
                                        placeholder="Habilitado, Deshabilitado" data-validation-required-message="Este campo es requerido">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                <h5>Descripci&oacute;n: <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control"
                                        required="" data-validation-required-message="Este campo es requerido">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>

                        <button id="submit" type="submit" style="display: none;"></button>
                        <div align="right" id="btnadd">
                            <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i>
                                Limpiar</button>
                            <button type="submit" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-plus"></i>
                                Guardar</button>
                        </div>
                        <div align="right" id="btnedit" style="display: none;">
                            <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i>
                                Limpiar</button>
                            <button type="button" onclick="editar_estado()" class="btn waves-effect waves-light btn-info"><i
                                    class="mdi mdi-pencil"></i> Editar</button>
                        </div>
                    </div>

                </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="col-lg-1"></div>
        <!-- ============================================================== -->
        <!-- Fin del FORMULARIO de gestión -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio de la TABLA -->
        <!-- ============================================================== -->
        <div class="col-lg-12" id="cnt_tabla">

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- Fin de la TABLA -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- Fin CUERPO DE LA SECCIÓN -->
<!-- ============================================================== -->
</div>
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<script>

$(function(){     
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        
        $.ajax({
            url: "<?php echo site_url(); ?>/configuraciones/estado/gestionar_estados",
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
                tablaEstados(<?php echo $this->uri->segment(4);?>);
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
            
    });
});

</script>