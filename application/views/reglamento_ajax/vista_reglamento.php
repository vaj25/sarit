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
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>N&uacute;mero del expediente:</td>
                        <td class="font-medium">
                            <?= $reglamento->numexpediente_expedientert ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Datos del establecimiento</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>Nombre establecimiento:</td>
                        <td class="font-medium">
                            <?= $reglamento->nombre_empresa ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Abreviatura del establecimiento:</td>
                        <td class="font-medium">
                            <?= $reglamento->abreviatura_empresa ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Direcci&oacute;n:</td>
                        <td class="font-medium">
                            <?= $reglamento->direccion_empresa ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td class="font-medium">
                            <?= $reglamento->telefono_empresa ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Actividad Econ&oacute;mica:</td>
                        <td class="font-medium">
                            <?= $reglamento->actividad_catalogociiu ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Municipio:</td>
                        <td class="font-medium">
                            <?= $reglamento->municipio ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Representante Legal:</td>
                        <td class="font-medium">
                            <?= $reglamento->nombres_representantert . ' ' . $reglamento->apellidos_representantert ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Detalles del expediente</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>Tipo de Solicitante:</td>
                        <td class="font-medium">
                            <?= $reglamento->tipopersona_expedientert ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tipo Solicitud:</td>
                        <td class="font-medium">
                            <?= $reglamento->tiposolicitud_expedientert ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Fecha y Hora de Creaci&oacute;n del expediente:</td>
                        <td class="font-medium">
                            <?= date("d-M-Y g:i:s A", strtotime($reglamento->fechacrea_expedientert)) ?>
                        </td>
                    </tr>

                    <?php
                        if (strtotime($reglamento->fecharesolucion_expedientert) != null) {
                    ?>
                    <tr>
                        <td>Resolución del expediente:</td>
                        <td class="font-medium">
                            <?= $reglamento->resolucion_expedientert ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Fecha y Hora de resolución del expediente:</td>
                        <td class="font-medium">
                            <?= date("d-M-Y g:i:s A", strtotime($reglamento->fecharesolucion_expedientert)) ?>
                        </td>
                    </tr>

                    <?php
                        }
                    ?>

                    <?php
                        if (strtotime($reglamento->fecha_entrega) != null) {
                    ?>

                    <tr>
                        <td>Persona quien recibe:</td>
                        <td class="font-medium">
                            <?= $reglamento->persona_recibe ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Fecha entrega de resolución:</td>
                        <td class="font-medium">
                            <?= date("d-M-Y", strtotime($reglamento->fecha_entrega)) ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                </tbody>
            </table>
        </blockquote>
    </div>
</div>
