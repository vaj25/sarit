<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<div class="modal fade" id="modal_delegado" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Asignaci&oacute;n del delegado</h4>
            </div>

            <div class="modal-body" id="">
                <?php echo form_open('', array('id' => 'formajax9', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                <div class="row">
                    <input type="hidden" id="id_reglamento" name="id_reglamento" value="<?=$id?>">
                    <div class="form-group col-lg-12 col-sm-12">
                        <div class="form-group">
                            <h5>Delegado/a:<span class="text-danger">*</h5>
                            <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%"
                                required="">
                                <option value="">[Todos los empleados]</option>
                                <?php
                                    $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                                    if($otro_empleado->num_rows() > 0){
                                        foreach ($otro_empleado->result() as $fila) {
                                            echo '<option class="m-l-50" value="'.$fila->id_empleado.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Fecha de cambio de delegado: <span class="text-danger">*</span></h5>
                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_delegado" name="fecha_delegado" placeholder="dd/mm/yyyy" readonly="">
                    </div>
                </div>
                <div align="right">
                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit2" class="btn btn-info waves-effect text-white">Guardar</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#formajax9").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax9"));

            $.ajax({
                    url: "<?php echo site_url(); ?>/reglamento/gestionar_reglamento_delegado",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                })
                .done(function (res) {
                    if (res == "fracaso") {
                        swal({
                            title: "¡Ups! Error",
                            text: "Intentalo nuevamente.",
                            type: "error",
                            showConfirmButton: true
                        });
                    } else {
                        swal({
                            title: "¡Registro exitoso!",
                            type: "success",
                            showConfirmButton: true
                        });

                        $('#modal_establecimiento').modal('toggle');
                    }
                });
            $('#modal_delegado').remove();
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            tablaReglamentos();
        });
    });

    $(function () {
        $(document).ready(function () {
            $('#fecha_delegado').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                startDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });
</script>