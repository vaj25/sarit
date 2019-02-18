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
		$data['reglamentos'] = $this->reglamento_model->obtener_reglamentos( $this->input->get('nr'), $this->input->get('tipo'), $this->input->get('letra'), $this->input->get('filtro') );
		$this->load->view('reglamento_ajax/tabla_reglamento', $data);
	}

	public function gestionar_reglamento() {

		$representante_empresa = $this->comisionado_model->obtener_comisionado($this->input->post('id_comisionado'));
		$dui = '';
		if ($representante_empresa) {
			$dui = $representante_empresa->dui_representantert;
		}
		

		if($this->input->post('band1') == "save"){

			if ($this->input->post('nuevo_expediente') == 1) {
				$id = $this->reglamento_model->obtener_expediente_cierre($this->input->post('establecimiento'));
				if ($id) {
					$this->expediente_estado_model->insertar_expediente_estado(
						array(
						'id_estadort' => 11,
						'id_expedientert' => $id->row()->id_solicitud,
						'fecha_exp_est' => date("Y-m-d H:i:s"),
						'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
						'etapa_exp_est' => 1
					));
				}
			}

			$comisionado = array(
				'id_representantert' => $this->input->post('id_comisionado'),
				'id_empresart' => $this->input->post('establecimiento'), 
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			$id_representante = $this->comisionado_model->insert_or_update_comisinado($comisionado, $dui);

			if ("fracaso" != $id_representante) {
				
				$expediente = array(
					'id_empresart' => $this->input->post('establecimiento'),
					'id_representante' => $id_representante,
					'numexpediente_expedientert' => 'N/A',
					'numeroexpediente_anterior' => null,
					'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
					'organizacionsocial_expedientert' => '',
					'contratocolectivo_expedientert' => '',
					'archivo_expedientert' => '',
					'contenidoTitulos_expedientert' => ''
				);

				$id_expediente = $this->reglamento_model->insertar_reglamento($expediente);
				
				$id_solicitud = $this->solicitud_model->insertar_solicitud(array(
					'id_expedientert' => $id_expediente,
					'id_tipo_solicitud' => $this->input->post('tipo_solicitud'),
					'notificacion_solicitud' => '',
					'fechanotificacion_solicitud' => '',
					'resolucion_solicud' => '',
					'fecharesolucion_solicitud' => '',
					'fechacrea_solicitud' => date("Y-m-d H:i:s"),
					'obsergenero_solicitud' => '',
					'fecha_entrega_solicitud' => '',
					'persona_recibe_solicitud' => '',
					'desistido_solicitud' => '',
				));
				
				$this->expediente_estado_model->insertar_expediente_estado(
					array(
					'id_estadort' => 1,
					'id_expedientert' => $id_solicitud,
					'fecha_exp_est' => date("Y-m-d H:i:s"),
					'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
					'etapa_exp_est' => 1
				));

				echo json_encode(array( 'expediente' => $id_expediente , 'solicitud' => $id_solicitud ));

			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band1') == "edit"){

			$representante = array(
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

			$id_representante = $this->comisionado_model->insert_or_update_comisinado($representante, $dui);

			if ("fracaso" != $id_representante) {
				
				$solicitud = $this->solicitud_model->obtener_solicitud( $this->input->post('id_solicitud') )->result_array()[0];
				
				$solicitud['id_tipo_solicitud'] = $this->input->post('tipo_solicitud');
				
				$reglamento = $this->reglamento_model->obtener_reglamento( $solicitud['id_expedientert'] )->result_array()[0];
	
				$reglamento['id_empresart'] = $this->input->post('establecimiento');
				$reglamento['tipopersona_expedientert'] = $this->input->post('tipo_solicitante');
				$reglamento['id_representante'] = $id_representante;

				echo json_encode(array( 'expediente' => $this->reglamento_model->editar_reglamento($reglamento) , 'solicitud' => $this->solicitud_model->editar_solitud($solicitud) ));
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band1') == "edit_new"){

			$reglamento = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];

			$reglamento['id_empresart'] = $this->input->post('establecimiento');
			$reglamento['tipopersona_expedientert'] = $this->input->post('tipo_solicitante');

			$representante = array(
				'id_representantert' => $this->input->post('id_comisionado'),
				'id_empresart' => $this->input->post('establecimiento'),
				'nombres_representantert' => $this->input->post('nombres'),
				'apellidos_representantert' => $this->input->post('apellidos'),
				'dui_representantert'  => $this->input->post('dui_comisionado'),
				'nit_representantert' => $this->input->post('nit'),
				'telefono_representantert' => $this->input->post('telefono'),
				'correo_representantert' => $this->input->post('correo'),
				'cargo_representantert' => $this->input->post('tipo_representante'),
				'sexo_representantert' => $this->input->post('sexo')
			);

			$id_representante = $this->comisionado_model->insert_or_update_comisinado($representante, $dui);

			if ("fracaso" != $id_representante) {
				echo json_encode(array( 'expediente' => $this->reglamento_model->editar_reglamento($reglamento), 'solicitud' => $this->input->post('id_solicitud') ));
			} else {
				echo "fracaso";
			}

		} else if($this->input->post('band') == "delete") {
			
			$solicitudes = $this->reglamento_model->obtener_reglamentos_numero($this->input->post('id_expedientert'));
			if ($solicitudes > 0) {
				$data = array(
					'id_expedientert' => $this->input->post('id_expedientert')
				);
				echo $this->reglamento_model->eliminar_documento($data);
	
			} else {
				echo 'fracaso';
			}
			
		} else if($this->input->post('band1') == "reforma_parcial" || $this->input->post('band1') == "reforma_total"
			|| $this->input->post('band1') == "subsanando_observaciones"){
			/** 
			 * Crea o actualiza al representante de la empresa
			*/
			$representante = array(
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
			
			$id_representante = $this->comisionado_model->insert_or_update_comisinado($representante, $dui);

			if ("fracaso" != $id_representante) {
				/** 
				 * Cambia representante del reglamento 
				*/
				$reglamento = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];
				$reglamento['id_representante'] = $id_representante;

				$this->reglamento_model->editar_reglamento($reglamento);

				/** 
				 * Crea una nueva solicitud
				*/
				$data = $this->solicitud_model->obtener_solicitud($this->input->post('id_solicitud'))->result_array()[0];

				$data['id_solicitud'] = null;
				$data['id_expedientert'] = $this->input->post('id_expediente');
				$data['resolucion_solicud'] = null;
				$data['fecharesolucion_solicitud'] = null;
				$data['fechacrea_solicitud'] = date("Y-m-d H:i:s");
				$data['desistido_solicitud'] = null;
				$data['fechasignacion_solicitud'] = null;
				$data['obsergenero_solicitud'] = null;
				$data['fecha_entrega_solicitud'] = null;
				$data['persona_recibe_solicitud'] = null;
				$data['notificacion_solicitud'] = null;
				$data['fechanotificacion_solicitud'] = 0;
				$data['id_tipo_solicitud'] = $this->input->post('tipo_solicitud');
					
				$id_solicitud = $this->solicitud_model->insertar_solicitud($data);
			
				$this->expediente_estado_model->insertar_expediente_estado(
					array(
					'id_estadort' => 1,
					'id_expedientert' => $id_solicitud,
					'fecha_exp_est' => date("Y-m-d H:i:s"),
					'fecha_ingresar_exp_est' => date("Y-m-d H:i:s"),
					'etapa_exp_est' => 1
				));

				echo json_encode(array( 'expediente' => $this->input->post('id_expediente'), 'solicitud' => $id_solicitud ));

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
				($this->input->post('bandera') == 'edit') ? TRUE : FALSE
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

		$estado = 6;
		if ($data['resolucion_solicud'] == 'Aprobado') {
			$estado = 3;
		}

		if ("fracaso" == $this->solicitud_model->editar_solitud($data)) {
			echo "fracaso";
		} else {
			$this->expediente_estado_model->insertar_expediente_estado(
				array(
				'id_estadort' => $estado,
				'id_expedientert' => $data['id_solicitud'],
				'fecha_exp_est' => $data['fecharesolucion_solicitud'],
				'fecha_ingresar_exp_est' => $data['fecharesolucion_solicitud'],
				'etapa_exp_est' => 1
			));
			echo "exito";
		}
		
	}

	public function notificacion_reglamento() {
		$this->load->view('reglamento_ajax/notificacion_reglamento', array('id' => $this->input->post('id') ));
	}

	public function gestionar_notificacion_reglamento() {

		$data = $this->solicitud_model->obtener_solicitud($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['notificacion_solicitud'] = $this->input->post('notificacion');
		$data['fechanotificacion_solicitud'] = date("Y-m-d H:i:s", strtotime($this->input->post('fecha')));

		if ("fracaso" == $this->solicitud_model->editar_solitud($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function gestionar_desistir_reglamento() {

		$data = $this->solicitud_model->obtener_solicitud($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['desistido_solicitud'] = $this->input->post('mov_disistir');

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
			$this->solicitud_model->editar_solitud($data);
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
				'id_solicitud' => $this->input->post('id'),
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
		$data = $this->solicitud_model->obtener_solicitud_detallada($this->input->post('id_solicitud'));
		
		if ($data->id_empleado != $this->input->post('id_personal_copia')) {
			
			if ("fracaso" == $this->expediente_empleado_model->
				insertar_expediente_empleado(
					array(
						'id_expedientert' => $this->input->post('id_solicitud'),
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
		$this->load->view('reglamento_ajax/modal_entrega', array('id_solicitud' => $this->input->post('id') ));
	}

	public function gestionar_entrega_resolucion() {
		$data = $this->solicitud_model->obtener_solicitud($this->input->post('id_reglamento_resolucion'))->result_array()[0];
		$data['fecha_entrega_solicitud'] = date('Y-m-d', strtotime($this->input->post('fecha_entrega')));
		$data['persona_recibe_solicitud'] = $this->input->post('recibe');

		if ("fracaso" == $this->solicitud_model->editar_solitud($data)) {
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
