<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<div class="modal fade" id="modal_acta_aprobada" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Emitir Ficha</h4>
            </div>

            <div class="modal-body" id="">
                <?php echo form_open('', array('id' => 'formajax10', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                <div class="row">
                    <input type="hidden" id="id_reglamento" name="id_reglamento" value="<?=$id?>">
                    <div class="form-group col-lg-12 col-sm-12">
                        <div class="form-group">
                            <h5>Contenido: <span class="text-danger">*</span></h5>
                            <div class="controls">
                            <input type="text" id="contenido" name="contenido" class="form-control" required="" placeholder="Contenido">
                            <div class="help-block"></div>
                            </div>
                        </div>
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
        $("#formajax10").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax10"));

            $.ajax({
                url: "<?php echo site_url(); ?>/reglamento/gestionar_acta_aprobada",
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
                    location.href="<?=base_url('index.php/acta/generar_acta/')?>" + formData.get('id_reglamento') + "/adas";
                }
            });
            $('#modal_acta_aprobada').remove();
            $('.modal-backdrop').remove();
        });
    });
</script>