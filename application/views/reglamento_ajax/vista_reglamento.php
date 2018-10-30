<?php 
    $reglamento = $reglamento->result()[0];
    // Características del navegador
    $ua=$this->config->item("navegator");
    $navegatorless = false;
    if(floatval($ua['version']) < $this->config->item("last_version")){
        $navegatorless = true;
    }
?>
               
<div class="card">

    <div class="card-header bg-info" id="ttl_form">
        <div class="card-actions text-white">
            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
            </a>
        </div>
        <h4 class="card-title m-b-0 text-white">Informaci&oacute;n del solicitante</h4>
    </div>

    <div class="card-body b-t" style="padding-top: 7px;">
        <span class="label label-success" style="font-size: 16px;">Expediente</span>
        <blockquote class="m-t-0">
            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    N&uacute;mero del expediente:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->numexpediente_expedientert ?></h5>
                </div>
            </div>

            <?php
                if ($reglamento->numeroexpediente_anterior != null) {
            ?>
                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        N&uacute;mero del expediente anterior:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= $reglamento->numeroexpediente_anterior ?></h5>
                    </div>
                </div>
            <?php
                }
            ?>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Datos del establecimiento</span>
        <blockquote class="m-t-0">
            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Nombre establecimiento:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->nombre_empresa ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Abreviatura del establecimiento:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->abreviatura_empresa ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Direcci&oacute;n:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->direccion_empresa ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Tel&eacute;fono:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->telefono_empresa ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Actividad Econ&oacute;mica:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->actividad_catalogociiu ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Municipio:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->municipio ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Representante Legal:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->nombres_representantert . ' ' . $reglamento->apellidos_representantert ?></h5>
                </div>
            </div>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Detalles del expediente</span>
        <blockquote class="m-t-0">

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Tipo de Solicitante:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->nombre_tipo_solicitante ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Tipo Solicitud:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= $reglamento->nombre_tipo_solicitud ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-5" style="height: 20px;">
                    Fecha y Hora de Creaci&oacute;n del expediente:
                </div>
                <div class="form-group col-lg-5" style="height: 20px;">
                    <h5><?= date("d-M-Y g:i:s A", strtotime($reglamento->fechacrea_expedientert)) ?></h5>
                </div>
            </div>

            <?php
                if (strtotime($reglamento->fecharesolucion_expedientert) != null) {
            ?>

                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        Resolución del expediente:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= $reglamento->resolucion_expedientert ?></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        Fecha y Hora de resolución del expediente:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= date("d-M-Y g:i:s A", strtotime($reglamento->fecharesolucion_expedientert)) ?></h5>
                    </div>
                </div>

            <?php
                }
            ?>

            <?php
                if (strtotime($reglamento->fecha_entrega) != null) {
            ?>

                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        Persona quien recibe:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= $reglamento->persona_recibe ?></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                        Fecha entrega de resolución:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= date("d-M-Y", strtotime($reglamento->fecha_entrega)) ?></h5>
                    </div>
                </div>
            <?php
                }
            ?>
        </blockquote>
    </div>
</div>
