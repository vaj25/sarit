<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reglamento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("reglamento_model", "documento_model", "comisionado_model" ));
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reglamento');
		$this->load->view('templates/footer');
	}

	public function tabla_reglamento(){
		$data['reglamentos'] = $this->reglamento_model->obtener_reglamentos();
		$this->load->view('reglamento_ajax/tabla_reglamento', $data);
	}

	public function gestionar_reglamento() {

		if($this->input->post('band1') == "save"){

			$data = array(
				'id_empresart' => $this->input->post('establecimiento'),
				'id_personal' => '',
				'id_estadort' => 1,
				'numexpediente_expedientert' => '1/2018 SS',
				'tipopersona_expedientert' => $this->input->post('tipo_solicitante'),
				'tiposolicitud_expedientert' => 'Registro',
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
				echo $this->reglamento_model->insertar_reglamento($data);
			} else {
				echo "fracaso";
			}

		}else if($this->input->post('band1') == "edit"){

			$data = $this->reglamento_model->obtener_reglamento($this->input->post('id_expediente'))->result_array()[0];

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
				echo $this->reglamento_model->editar_reglamento($data);
			} else {
				echo "fracaso";
			}

		}else if($this->input->post('band') == "delete"){
			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert')
			);
			echo $this->reglamento_model->eliminar_documento($data);

		}

	}

	public function registros_reglamentos_documentos() {

		print json_encode(
			$this->reglamento_model->obtener_reglamentos_documentos($this->input->post('id'))->result()
		);
		
	}

	public function combo_establecimiento() {
		
		$this->load->view('reglamento_ajax/combo_establecimiento', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);

	}

	public function combo_delegado() {
		
		$this->load->view('reglamento_ajax/combo_delegado', 
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $this->db->get('lista_empleados_estado')
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
		$data = $this->reglamento_model->obtener_reglamento(25)->result_array()[0];
		$data['resolucion_expedientert'] = $this->input->post('resolucion');
		$data['obsergenero_expedientrt'] = $this->input->post('ob_genero');
		$data['fecharesolucion_expedientert'] = date("Y-m-d H:i:s");

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

	public function inhabilitar_reglamento() {
		$this->load->view('templates/header');
		$this->load->view('reglamento_ajax/inhabilitar_reglamento');
		$this->load->view('templates/footer');
	}

	public function gestionar_inhabilitar_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento(1)->result_array()[0];
		$data['id_estadort'] = 2;
		$data['inhabilitado_expedientert'] = $this->input->post('mov_inhabilitar');

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function gestionar_habilitar_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento(1)->result_array()[0];
		$data['id_estadort'] = 1;
		$data['inhabilitado_expedientert'] = null;

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function estado_reglamento() {
		
		$this->load->view('templates/header');
		$this->load->view('reglamento_ajax/estado_reglamento',
			array(
				'id' => 0,
				'estado' => $this->db->get('sri_estadort')
			)
		);
		$this->load->view('templates/footer');

	}

	public function gestionar_estado_reglamento() {

		$data = $this->reglamento_model->obtener_reglamento(1)->result_array()[0];
		$data['id_estadort'] = $this->input->post('estado');

		if ("fracaso" == $this->reglamento_model->editar_reglamento($data)) {
			echo "fracaso";
		} else {
			echo "exito";
		}
	}

	public function adjuntar_reglamento() {
		$this->load->view('templates/header');
		$this->load->view('reglamento_ajax/adjuntar_reglamento');
		$this->load->view('templates/footer');
	}

	public function gestionar_adjuntar_reglamento() {
		
		$data = $this->reglamento_model->obtener_reglamento(25)->result_array()[0];
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
	
	public function modal_establecimiento() {
		$this->load->view('reglamento_ajax/modal_establecimiento');
	}

}
?>
