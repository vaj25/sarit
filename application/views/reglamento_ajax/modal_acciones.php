<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<div class="modal fade" id="modal_acciones" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Reforma</h4>
            </div>

            <div class="modal-body" id="">
                <div class='row'>
                    <div class="col-12">
                        <div class="card-body">
                            <a href="#" class="btn btn-info btn-lg active btn-rounded" onClick="cambiar_editar(<?=$id?>, 'reforma_parcial')" role="button" aria-pressed="true" data-dismiss="modal">Reforma Parcial</a>
                        </div>

                        <div class="card-body">
                            <a href="#" class="btn btn-success btn-lg active btn-rounded" onClick="cambiar_editar(<?=$id?>, 'reforma_total')" role="button" aria-pressed="true" data-dismiss="modal">Reforma Total</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>