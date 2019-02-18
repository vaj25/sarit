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

                <div class="page-wrapper">

                    <div class="">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="message-box contact-box">
                                    <div class="message-widget contact-widget">
                                        <!-- Message -->
                                        <a href="#" onClick="cambiar_editar(<?=$id?>, 'reforma_parcial')">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span>
                                                <span class="profile-status away pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Reforma Parcial</h5> <span class="mail-desc"></span>
                                            </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" onClick="cambiar_editar(<?=$id?>, 'reforma_total')">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span>
                                                <span class="profile-status offline pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Reforma Total</h5> <span class="mail-desc"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>