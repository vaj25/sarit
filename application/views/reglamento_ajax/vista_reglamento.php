<?php 
    $reglamento = $reglamento->result()[0];
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Aprobaci&oacute;n de Reglamentos</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="cnt-tabla">
                
                <div class="card">

                    <div class="card-header">
                        <div class="card-actions">

                        </div>
                        <h4 class="card-title m-b-0">Informaci&oacute;n del solicitante</h4>
                    </div>

                    <div class="card-body b-t"  style="padding-top: 7px;">
                        <span class="label label-success" style="font-size: 16px;">Expediente</span>
                        <blockquote class="m-t-0">
                            <table class="table no-border">
                                <tbody>
                                    <tr>
                                        <td>N&uacute;mero del expediente:</td>
                                        <td class="font-medium"> <?= $reglamento->numexpediente_expedientert ?> </td>
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
                                        <td class="font-medium"><?= $reglamento->nombre_empresa ?></td>
                                    </tr>
                                    <tr>
                                        <td>Abreviatura del establecimiento:</td>
                                        <td class="font-medium"><?= $reglamento->abreviatura_empresa ?></td>
                                    </tr>
                                    <tr>
                                        <td>Direcci&oacute;n:</td>
                                        <td class="font-medium"><?= $reglamento->direccion_empresa ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tel&eacute;fono:</td>
                                        <td class="font-medium"><?= $reglamento->telefono_empresa ?></td>
                                    </tr>
                                    <tr>
                                        <td>Actividad Econ&oacute;mica:</td>
                                        <td class="font-medium"><?= $reglamento->actividad_catalogociiu ?></td>
                                    </tr>
                                    <tr>
                                        <td>Municipio:</td>
                                        <td class="font-medium"><?= $reglamento->municipio ?></td>
                                    </tr>
                                    <tr>
                                        <td>Representante Legal:</td>
                                        <td class="font-medium"><?= $reglamento->nombres_representante ?></td>
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
                                        <td class="font-medium"><?= $reglamento->tipopersona_expedientert ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tipo Solicitud:</td>
                                        <td class="font-medium"><?= $reglamento->tiposolicitud_expedientert ?></td>
                                    </tr>
                                    <tr>
                                        <td>Fecha y Hora de Creaci&oacute;n del expediente:</td>
                                        <td class="font-medium"><?= $reglamento->fechacrea_expedientert ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>