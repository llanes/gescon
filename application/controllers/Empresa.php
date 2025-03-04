<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Empresa extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Empresa_Model");
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
 
 	}
	/**
	 * [index description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function index( $offset = 0 )
	{
			if ($this->db->count_all_results('Empresa') == 0) {
				redirect('Home','refresh');
			} else {
					$data = array //arreglo para mandar datos a la vista
					(
						"Alerta" => $this->Producto->get_alert(),
					);
					if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Empresa/Empresa_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido  // 
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Empresa/script.php'); // pie con los js
					} else {
					$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
					$no = 0; $nn = 0;
					$resultado = count($variable);
					foreach ($variable as $key) {
						$nn ++;
						if ($key->ID == 18 && $no == 0) {
							$_data = array('data_view' => $variable);
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$_data,FALSE);
							$this->load->view('Empresa/Empresa_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido  // 
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Empresa/script.php'); // pie con los js
					        $no ++;
						} else{
							if ( $nn == $resultado) {
								// $this->load->view('errors/404.php');
							}
						}

					}
					}

			}

	}
	/**
	 * [ajax_list description]
	 * @return [type] [description]
	 */
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) {
				$list = $this->Empresa_Model->get_empresa();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $empresa) {
				$no++;
				$row   = array();
				$row[] =  $no;
				$row[] = $this->mi_libreria->getSubString($empresa->Nombre,20 );
				$row[] = $this->mi_libreria->getSubString($empresa->Direccion,25 );
				$row[] = $empresa->Telefono;
				$row[] = $this->mi_libreria->getSubString($empresa->Email,25 );
				//add html for action
				$row[] = '<div class="pull-right hidden-phone">
				<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="edit_empresa('."'".$empresa->idEmpresa."'".')">
				<i class="fa fa-pencil-square"></i></a>
				<a class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="top"href="javascript:void(0);" title="Eliminar" onclick="delete_empresa('."'".$empresa->idEmpresa."'".')">
				<i class="fa fa-trash-o"></i></a></div>';
				$data[] = $row;
		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Empresa_Model->count_todas(),
			"recordsFiltered" => $this->Empresa_Model->count_filtro(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	/**
	 * [ajax_add description]
	 * @return [type] [description]
	 */
	public function ajax_add()
	{
				if ($this->input->is_ajax_request()) {
					// echo var_dump($this->input->post());
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('registro_empresa') == FALSE)
				{
					$data = array(
					'Nombre'      => form_error('Nombre'),
					'Direccion'   => form_error('Direccion'),
					'Telefono'    => form_error('Telefono'),
					'Email'       => form_error('Email'),
					'ruc'         => form_error('ruc'),
					'Timbrado'    => form_error('Timbrado'),
					'Series'      => form_error('Series'),
					'Comprovante'     => form_error('Comprovante'),
					'res'            => 'error');
					echo json_encode($data);
					}else{
					$data                         = array(
					'Nombre'             => $this->security->xss_clean( $this->input->post('Nombre',FALSE)),
					'Descripcion'        => $this->security->xss_clean( $this->input->post('Descripcion',FALSE)),
					'Direccion'          => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Telefono'           => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Email'              => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'R_U_C'              => $this->security->xss_clean( $this->input->post('ruc',FALSE)),
					'Timbrado'           => $this->security->xss_clean( $this->input->post('Timbrado',FALSE)),
					'Series'             => $this->security->xss_clean( $this->input->post('Series',FALSE)),
					'Comprovante' => $this->security->xss_clean( $this->input->post('Comprovante',FALSE))
					);
					$insert = $this->Empresa_Model->insert($data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [ajax_edit description]
	 * @param  [type] $idEmpresa [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($idEmpresa)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Empresa_Model->get_by_id($idEmpresa);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}
	/**
	 * [ajax_update description]
	 * @return [type] [description]
	 */
	public function ajax_update($id = NULL)
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('ajax_update_empresa') == FALSE)
				{
					$data       = array(
					'Nombre'      => form_error('Nombre'),
					'Direccion'   => form_error('Direccion'),
					'Telefono'    => form_error('Telefono'),
					'Email'       => form_error('Email'),
					'ruc'         => form_error('ruc'),
					'Timbrado'    => form_error('Timbrado'),
					'Series'      => form_error('Series'),
					'Comprovante'     => form_error('Comprovante'),
					'res'         => 'error');
					echo json_encode($data);
					}else{
					$data                         = array(
					'Nombre'             => $this->security->xss_clean( $this->input->post('Nombre',FALSE)),
					'Descripcion'        => $this->security->xss_clean( $this->input->post('Descripcion',FALSE)),
					'Direccion'          => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Telefono'           => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Email'              => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'R_U_C'              => $this->security->xss_clean( $this->input->post('ruc',FALSE)),
					'Timbrado'           => $this->security->xss_clean( $this->input->post('Timbrado',FALSE)),
					'Series'             => $this->security->xss_clean( $this->input->post('Series',FALSE)),
		            'Comprovante' => $this->security->xss_clean( $this->input->post('Comprobante',FALSE))
					);

					$this->Empresa_Model->update(array('idEmpresa' => $this->input->post('idEmpresa')), $data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [ajax_delete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function ajax_delete($id = NULL)
	{
		$this->Empresa_Model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

 }
 
 /* End of file Empresa.php */
 /* Location: ./application/controllers/Empresa.php */
