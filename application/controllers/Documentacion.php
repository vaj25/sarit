<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "documento_model", "expediente_empleado_model"));
	}

	public function gestionar_documentacion() {

        if($this->input->post('band2') == "save" 
            || $this->input->post('band2') == "reforma_parcial"
            || $this->input->post('band2') == "reforma_total"){

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
                'nominayfuncion_documentort' => $this->input->post('nominacion'),
                'leycreacionescritura_documentort' => $this->input->post('creacion_escritura'),
                'acuerdoejecutivo_documentort' => $this->input->post('acuerdo_ejecutivo')
            );

            if ("fracaso" != $this->documento_model->insertar_documento($data2)) {
                
                $this->expediente_empleado_model->insertar_expediente_empleado(
					array(
					'id_expedientert' => $this->input->post('id_expedient'),
					'id_empleado' => $this->input->post('colaborador'),
					'fecha_exp_emp ' => date("Y-m-d H:i:s")
				));

                echo $this->input->post('id_expedient');
            } else {
                echo "fracaso";
            }

		} else if($this->input->post('band2') == "edit"){

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
                'nominayfuncion_documentort' => $this->input->post('nominacion'),
                'leycreacionescritura_documentort' => $this->input->post('creacion_escritura'),
                'acuerdoejecutivo_documentort' => $this->input->post('acuerdo_ejecutivo')
			);

			if ("fracaso" != $this->documento_model->editar_documento($data2)) {
				echo $this->input->post('id_expedient');
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band2') == "edit_new"){

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
                'nominayfuncion_documentort' => $this->input->post('nominacion'),
                'leycreacionescritura_documentort' => $this->input->post('creacion_escritura'),
                'acuerdoejecutivo_documentort' => $this->input->post('acuerdo_ejecutivo')
			);

			if ("fracaso" != $this->documento_model->insertar_documento($data2)) {
				echo $this->input->post('id_expedient');
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band') == "delete"){
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