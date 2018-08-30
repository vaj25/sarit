<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "documento_model"));
	}

	public function gestionar_documentacion() {

		if($this->input->post('band2') == "save"){

            $data = $this->reglamento_model->obtener_reglamento($this->input->post('id_expedient'))->result_array()[0];

            $data['id_personal'] = $this->input->post('colaborador');

			$data2 = array(
                'id_expedientert' => $this->input->post('id_expedient'), 
                'docreglamento_documentort' => $this->input->post('reglamento_interno'),
                'escritura_documentort' => $this->input->post('constitucion_sociedad'),
                'credencial_documentort'  => $this->input->post('credencial_representante'),
                'poder_documentort' => $this->input->post('poder'),
                'dui_documentort' => $this->input->post('dui'),
                'matricula_documentort' => $this->input->post('matricula'),
                'estatutos_documentort' => $this->input->post('estatutos'),
                'acuerdoejec_documentort' => $this->input->post('acuerdo_creacion'),
                'nominayfuncion_documentort' => $this->input->post('nominacion')
            );

            if (
                "exito" == $this->reglamento_model->editar_reglamento($data) &&
                "exito" == $this->documento_model->insertar_documento($data2)
            ) {
                echo $this->input->post('id_expedient');
            } else {
                echo "fracaso";
            }

		} else if($this->input->post('band') == "edit"){

			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert'),
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => $this->input->post('colaborador'),
				'id_estadort' => 'ninguno',
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => '',
				'organizacionsocial_expedientert' => '',
				'contratocolectivo_expedientert' => '',
				'notificacion_expedientert' => '',
				'fechanotificacion_expedientert' => '',
				'resolucion_expedientert' => '',
				'fecharesolucion_expedientert' => '',
				'archivo_expedientert' => '',
				'obsergenero_expedientrt' => '',
				'contenidoTitulos_expedientert' => '',
				'inhabilitado_expedientert' => '',
				'archivo_expedientert' => ''
			);

			$data2 = array(
				'id_expedientert' => $this->input->post('id_expedientert'), 
				'docreglamento_documentort' => $this->input->post('reglamento_interno'),
				'escritura_documentort' => $this->input->post('constitucion_sociedad'),
				'credencial_documentort'  => $this->input->post('credencial_representante'),
				'poder_documentort' => $this->input->post('poder'),
				'dui_documentort' => $this->input->post('establecimiento'),
				'matricula_documentort' => $this->input->post('matricula'),
				'estatutos_documentort' => $this->input->post('estatutos'),
				'acuerdoejec_documentort' => $this->input->post('acuerdo_creacion'),
				'nominayfuncion_documentort' => $this->input->post('nominacion')
			);

			echo $this->documento_model->editar_documento($data2);

		}else if($this->input->post('band') == "delete"){
			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert')
			);
			echo $this->reglamento_model->eliminar_documento($data);

		}

    }
    
    public function obtener()
    {
        var_dump($this->reglamento_model->obtener_reglamento($this->input->post('id_expedient'))->result()[0]);
    }

}
?>