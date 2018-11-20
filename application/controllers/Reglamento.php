<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "documento_model", "comisionado_model", "expediente_estado_model", 
									"expediente_empleado_model", "login_model", "solicitud_model"));
	}

	public function index(){
		$data['delegados'] = $this->expediente_empleado_model->obtener_delegados_seccion();
		$data['tipo_persona'] = $this->db->get('sri_tipo_solicitante');
		$this->load->view('templates/header');
		$this->load->view('reglamento', $data);
		$this->load->view('templates/footer');
	}

	public function tabla_reglamento(){
		$data['reglamentos'] = $this->reglamento_model->obtener_reglamentos( $this->input->get('nr'), $this->input->get('tipo'), $this->input->get('letra') );
		$this->load->view('reglamento_ajax/tabla_reglamento', $data);
	}

	public function gestionar_reglamento() {

		if($this->input->post('band1') == "save"){

			$data = array(
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => '',
				'numexpediente_expedientert' => 'N/A',
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
				'desistido_expedientert' => '',
				'archivo_expedientert' => '',
				'numeroexpediente_anterior' => null
			);

			$data2 = array(
				'id_empresart' =>$this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			$data3 = array(
				'id_personal' => '',
				'tiposolicitud_expedientert' => $this->input->post('tipo_solicitud'),
				'notificacion_solicitud' => '',
				'fechanotificacion_solicitud' => '',
				'resolucion_solicitud' => '',
				'fecharesolucion_solicitud' => '',
				'fechacrea_solicitud' => date("Y-m-d H:i:s"),
				'obsergenero_expedientrt' => '',
				'fecha_entrega_solicitud' => '',
				'persona_recibe_solicitud' => ''
			);

			if ("exito" == $this->comisionado_model->insertar_comisionado($data2)) {
				
				$id = $this->reglamento_model->insertar_reglamento($data);

				$id2 = $this->solicitud_model->insertar_solicitud($data3);
				
				$this->expediente_estado_model->insertar_expediente_estado(
					array(
					'id_estadort' => 1,
					'id_expedientert' => $id2,
					'fecha_exp_est' => date("Y-m-d H:i:s"),
					'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
					'etapa_exp_est' => 1
				));

				echo $id;

			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band1') == "edit"){

			$data3 = $this->solicitud_model->obtener_solicitud( $this->input->post('id_solicitud') )->result_array()[0];
			
			$data3['id_tipo_solicitud'] = $this->input->post('tipo_solicitud');
			
			$data = $this->reglamento_model->obtener_reglamento( $data['id_expedientert'] )->result_array()[0];

			$data['id_empresart'] = $this->input->post('establecimiento');
			$data['tipopersona_expedientert'] = $this->input->post('tipo_solicitante');

			$data2 = array(
				'id_representantert' => $this->input->post('id_comisionado'),
				'id_empresart' =>$this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			if ("exito" == $this->comisionado_model->editar_comisionado($data2)) {
				$this->solicitud_model->editar_solitud($data3);
				echo $this->reglamento_model->editar_reglamento($data);
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band1') == "edit_new"){

			$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];

			$data['id_empresart'] = $this->input->post('establecimiento');
			$data['tipopersona_expedientert'] = $this->input->post('tipo_solicitante');

			$data2 = array(
				'id_empresart' =>$this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			if ("exito" == $this->comisionado_model->insertar_comisionado($data2)) {
				echo $this->reglamento_model->editar_reglamento($data);
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band') == "delete"){
			
			$solicitudes = $this->reglamento_model->obtener_reglamentos_numero($this->input->post('id_expedientert'));
			if ($solicitudes > 0) {
				$data = array(
					'id_expedientert' => $this->input->post('id_expedientert')
				);
				echo $this->reglamento_model->eliminar_documento($data);
	
			} else {
				echo 'fracaso';
			}
			
		} else if($this->input->post('band1') == "reforma_parcial" || $this->input->post('band1') == "reforma_total"){
			
			$dui = $this->reglamento_model->obtener_reglamentos_documentos($this->input->post('id_expediente'))->result();
			$dui = $dui[0]->dui_representantert;

			$data2 = array(
				'id_representantert' => $this->input->post('id_comisionado'),
				'id_empresart' =>$this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			$res = "fracaso";

			if ($dui == $this->input->post('dui_comisionado')) {
				$res = $this->comisionado_model->editar_comisionado($data2);
			} else {
				$data2['id_representantert'] = null;
				$res = $this->comisionado_model->insertar_comisionado($data2);
			}
			
			if ("exito" == $res) {
				$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];

				$data['id_expedientert'] = null;
				$data['resolucion_expedientert'] = null;
				$data['fecharesolucion_expedientert'] = 0;
				$data['archivo_expedientert'] = null;
				$data['desistido_expedientert'] = null;
				$data['obsergenero_expedientrt'] = null;
				$data['contenidoTitulos_expedientert'] = null;
				$data['notificacion_expedientert'] = null;
				$data['fechanotificacion_expedientert'] = 0;
				$data['tiposolicitud_expedientert'] = $this->input->post('tipo_solicitud');
					
				$id = $this->reglamento_model->insertar_reglamento($data);
			
				$this->expediente_estado_model->insertar_expediente_estado(
					array(
					'id_estadort' => 1,
					'id_expedientert' => $id,
					'fecha_exp_est' => date("Y-m-d H:i:s"),
					'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
					'etapa_exp_est' => 1
				));

				echo $id;

			} else {
				echo "fracaso";
			}

		}

	}

	public function insertar_reglamentos_filtro() {
		
		$id = $this->reglamento_model->insertar_reglamento (
			array(
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => '',
				'numexpediente_expedientert' => 'N/A',
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
				'desistido_expedientert' => '',
				'archivo_expedientert' => ''
			)
		);

		if ($id != 'fracaso') {

			$id2 = $this->solicitud_model->insertar_solicitud(
				array(
				'id_personal' => '',
				'tiposolicitud_expedientert' => $this->input->post('tipo_solicitud'),
				'notificacion_solicitud' => '',
				'fechanotificacion_solicitud' => '',
				'resolucion_solicitud' => '',
				'fecharesolucion_solicitud' => '',
				'fechacrea_solicitud' => date("Y-m-d H:i:s"),
				'obsergenero_expedientrt' => '',
				'fecha_entrega_solicitud' => '',
				'persona_recibe_solicitud' => ''
				)
			);

			$this->expediente_estado_model->insertar_expediente_estado(
				array(
				'id_estadort' => 1,
				'id_expedientert' => $id2,
				'fecha_exp_est' => date("Y-m-d H:i:s"),
				'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
				'etapa_exp_est' => 1
			));
	
			$this->expediente_empleado_model->insertar_expediente_empleado(
				array(
				'id_expedientert' => $id2,
				'id_empleado' => $this->input->post('colaborador'),
				'fecha_exp_emp ' => date("Y-m-d H:i:s")
			));

			echo 'exito';

		} else {
			echo $id;
		}

	}

	public function registros_reglamentos_documentos() {
		
		print json_encode(
			$this->reglamento_model->obtener_reglamentos_documentos(
				$this->input->post('id'),
				($this->input->post('bandera') == 'edit_new') ? FALSE : TRUE
				)->result()
		);
		
	}

	public function combo_establecimiento() {
		
		$this->load->view('reglamento_ajax/combo_establecimiento', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa'),
				'disable' => $this->input->post('disable')
			)
		);

	}

	public function combo_delegado() {
		
		$this->load->view('reglamento_ajax/combo_delegado',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $this->expediente_empleado_model->obtener_delegados_seccion(),
				'disable' => $this->input->post('disable')
			)
		);

	}

	public function ver_reglamento() {

		$data['reglamento'] = $this->reglamento_model->obtener_reglamento_empresa( $this->input->post('id') );
		
		$this->load->view('reglamento_ajax/vista_reglamento', $data);
	}

	public function resolucion_reglamento() {
		$this->load->view('reglamento_ajax/resolucion_reglamento', array('id' => $this->input->post('id') ));
	}

	public function gestionar_resolucion_reglamento() {
		$data = $this->solicitud_model->obtener_solicitud($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['resolucion_solicud'] = $this->input->post('resolucion');
		$data['obsergenero_solicitud'] = $this->input->post('ob_genero');
		$data['fecharesolucion_solicitud'] = date("Y-m-d H:i:s");

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
		
	}

	public function notificacion_reglamento() {
		$this->load->view('reglamento_ajax/notificacion_reglamento', array('id' => $this->input->post('id') ));
	}

	public function gestionar_notificacion_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['notificacion_expedientert'] = $this->input->post('notificacion');
		$data['fechanotificacion_expedientert'] = date("Y-m-d H:i:s", strtotime($this->input->post('fecha')));

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function gestionar_desistir_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['desistido_expedientert'] = $this->input->post('mov_disistir');

		if ("fracaso" == $this->expediente_estado_model->insertar_expediente_estado(
			array(
			'id_estadort' => 9,
			'id_expedientert' => $this->input->post('id_reglamento_resolucion'),
			'fecha_exp_est' => date("Y-m-d H:i:s"),
			'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
			'etapa_exp_est' => 1
		))) {
			echo "fracaso";
		} else {
			$this->reglamento_model->editar_reglamento($data);
			echo "exito";
		}
	}

	public function gestionar_habilitar_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['id_estadort'] = 1;
		$data['desistido_expedientert'] = null;

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function estado_reglamento() {
		
		$this->load->view('reglamento_ajax/estado_reglamento',
			array(
				'id' => 0,
				'id_expediente' => $this->input->post('id'),
				'estado' => $this->db->get('sri_estadort')
			)
		);

	}

	public function gestionar_estado_reglamento() {

		$data = array(
			'id_estadort' => $this->input->post('estado'),
			'id_expedientert' => $this->input->post('id_reglamento_resolucion'),
			'fecha_exp_est' => date("Y-m-d H:i:s"),
			'etapa_exp_est' => $this->obtener_estado($this->input->post('id_reglamento_resolucion')),
			'fecha_ingresar_exp_est' => date('Y-m-d', strtotime($this->input->post('fecha_estado')))
		);

		if ("fracaso" == $this->expediente_estado_model->insertar_expediente_estado($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function adjuntar_reglamento() {
		$this->load->view('reglamento_ajax/adjuntar_reglamento', array('id' => $this->input->post('id') ));
	}

	public function gestionar_adjuntar_reglamento() {
		
		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['id_estadort'] = $this->input->post('estado');
		
		$config['upload_path'] = $this->directorio( str_replace( "/", "_", $data['numexpediente_expedientert'] ) );
		$config['allowed_types'] = "pdf";
		$config['max_size'] = "20480";

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('archivo_reglamento')) {
			
			$data['uploadError'] = $this->upload->display_errors();
			//echo $this->upload->display_errors();
			echo "fracaso";

		} else {

			$data['archivo_expedientert'] = $this->upload->data('full_path');
	
			if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
				echo "fracaso";
			} else {
				echo "exito";
			}

		}

	}

	public function descargar_reglamento($id_reglamento_resolucion) {

		$data = $this->reglamento_model->obtener_reglamento( $id_reglamento_resolucion )->result_array()[0];

		if(file_exists( $data['archivo_expedientert'] )) {
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-disposition: attachment; filename='.basename($data['archivo_expedientert']));
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: binary");
			readfile($data['archivo_expedientert']);
		} else {
			return redirect('/reglamento');
		}
	}

	private function directorio($expediente) {

        if(!is_dir("./files/pdfs/" . $expediente)) {

            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
            mkdir("./files/pdfs/" . $expediente, 0777);
		}
		
		return "./files/pdfs/" . $expediente;
	}

	public function modal_acciones() {
		$this->load->view('reglamento_ajax/modal_acciones', array('id' => $this->input->post('id') ));
	}
	
	public function modal_establecimiento() {
		$this->load->view('reglamento_ajax/modal_establecimiento');
	}

	public function delegado_reglamento() {
		$this->load->view('reglamento_ajax/modal_delegado', array('id' => $this->input->post('id') ));
	}

	public function gestionar_reglamento_delegado() {
		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento'))->result_array()[0];
		
		if ($data['id_empleado'] != $this->input->post('id_personal_copia')) {
			
			if ("fracaso" == $this->expediente_empleado_model->
				insertar_expediente_empleado(
					array(
						'id_expedientert' => $this->input->post('id_reglamento'),
						'id_empleado' => $this->input->post('id_personal_copia'),
						'fecha_exp_emp ' => date("Y-m-d H:i:s")
					)
				)
			) {
				echo "fracaso";
			} else {
				echo "exito";
			}
		} else {
			echo "fracaso";
		}
		
	}

	public function modal_acta_aprobada() {
		$this->load->view('reglamento_ajax/modal_acta_aprobado', array('id' => $this->input->post('id') ));
	}

	public function gestionar_acta_aprobada() {
		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento'))->result_array()[0];
		$data['contenidoTitulos_expedientert'] = $this->input->post('contenido');

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function entrega_resolucion() {
		$this->load->view('reglamento_ajax/modal_entrega', array('id_expediente' => $this->input->post('id') ));
	}

	public function gestionar_entrega_resolucion() {
		$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['fecha_entrega'] = date('Y-m-d', strtotime($this->input->post('fecha_entrega')));
		$data['persona_recibe'] = $this->input->post('recibe');

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function obtener_estado($id_expediente) {
		
		$res = $this->expediente_estado_model->obtener_ultimo_estado($id_expediente);

		if ($res) {
			$data = $res->result()[0];
			
			if ($data->id_estadort == 3 && $data->etapa_exp_est >= 4) {
				return $data->etapa_exp_est + 1;
			} else {
				return $data->etapa_exp_est;
			}
			
		}

	}

}
?>
