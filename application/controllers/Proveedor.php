<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Proveedor extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Proveedor_Model");
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
				$data    = array //arreglo para mandar datos a la vista
				(
				"Alerta" => $this->Producto->get_alert(),
				);

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Proveedor/Proveedor_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Proveedor/script.php'); // pie con los js
				} else {


					$variable =  $this->Model_Menu->octener(89);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Proveedor/Proveedor_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Proveedor/script.php'); // pie con los js
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
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
				$list = $this->Proveedor_Model->get_Proveedor();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $Proveedor) {
				$no++;
				// $resultado = intval(preg_replace('/[^0-9]+/', '', $Proveedor->Limite_max_Credito), 10); 
				// $CREDITO =  number_format($resultado,0,'.',',');
				$row   = array();
				$row[] =  $no;
				$row[] = $this->mi_libreria->getSubString($Proveedor->Vendedor,10);
				$row[] = $this->mi_libreria->getSubString($Proveedor->Ruc,15);
				$row[] = $this->mi_libreria->getSubString($Proveedor->Razon_Social,15);
				$row[] = $this->mi_libreria->getSubString($Proveedor->Direccion,15);
				$row[] = $Proveedor->Telefono;
				$row[] = $this->mi_libreria->getSubString($Proveedor->Correo,15);
				//add html for action
				if ($Proveedor->Pagina_Web == '') {
					$row[] = '<div class="pull-right ">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Proveedor->idProveedor."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Proveedor->idProveedor."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
				$data[] = $row;
				} else {
					$row[] = '<div class="pull-right ">
					<a class="btn  " href="'.$Proveedor->Pagina_Web.'" title="Pagina web">
					<i class="fa fa-globe fa-lg" aria-hidden="true"></i></a>
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Proveedor->idProveedor."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Proveedor->idProveedor."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
				}

		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Proveedor_Model->count_todas(),
			"recordsFiltered" => $this->Proveedor_Model->count_filtro(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	
	public function select2remote($value='')
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->security->xss_clean($this->input->get('q'));
			$query = $this->db->select('Ruc as ruc,Razon_Social as full_name,Vendedor,idProveedor as id')
							  ->like('Ruc', $id, 'BOTH')
			                  ->or_like('Razon_Social', $id, 'BOTH')
			                  ->or_like('Vendedor', $id, 'BOTH')
			                  ->limit(10)
			                  ->get('Proveedor');

			$data = array(
				'total_count'        => $query->num_rows(),
				'incomplete_results' => false,
				'items'              => $query->result()
			);
			echo json_encode($data);





		}else{
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
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('registro_Proveedor') == FALSE)
				{
						$data = array(
							'Ruc'          => form_error('Ruc'),
							'Razon_Social' => form_error('Razon_Social'),
							'Direccion'    => form_error('Direccion'),
							'Telefono'     => form_error('Telefono'),
							'Correo'       => form_error('Correo'),
							'Pagina_Web'   => form_error('Pagina_Web'),
							'Vendedor'     => form_error('Vendedor'),
							'res'            => 'error');
					echo json_encode($data);		
				}else{
					$data                 = array(
					'Ruc'          => $this->security->xss_clean( $this->input->post('Ruc',FALSE)),
					'Razon_Social' => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Razon_Social',FALSE)))),
					'Direccion'    => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Direccion',FALSE)))),
					'Telefono'     => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Correo'       => $this->security->xss_clean( $this->input->post('Correo',FALSE)),
					'Pagina_Web'   => $this->security->xss_clean( $this->input->post('Pagina_Web',FALSE)),
					'Vendedor'     => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Vendedor',FALSE)))),
					);
					$insert = $this->Proveedor_Model->insert($data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [ajax_edit description]
	 * @param  [type] $idProveedor [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($idProveedor)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Proveedor_Model->get_by_id($idProveedor);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}
	/**
	 * [ajax_update description]
	 * @return [type] [description]
	 */
	public function ajax_update()
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('ajax_update_Proveedor') == FALSE)
				{
					$data = array(
							'Ruc'          => form_error('Ruc'),
							'Razon_Social' => form_error('Razon_Social'),
							'Direccion'    => form_error('Direccion'),
							'Telefono'     => form_error('Telefono'),
							'Correo'       => form_error('Correo'),
							'Pagina_Web'   => form_error('Pagina_Web'),
							'Vendedor'     => form_error('Vendedor'),
							'res'            => 'error');
					echo json_encode($data);
				}else{
					$data                 = array(
					'Ruc'          => $this->security->xss_clean( $this->input->post('Ruc',FALSE)),
					'Razon_Social' => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Razon_Social',FALSE)))),
					'Direccion'    => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Direccion',FALSE)))),
					'Telefono'     => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Correo'       => $this->security->xss_clean( $this->input->post('Correo',FALSE)),
					'Pagina_Web'   => $this->security->xss_clean( $this->input->post('Pagina_Web',FALSE)),
					'Vendedor'     => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Vendedor',FALSE)))),
					);
					$this->Proveedor_Model->update(array('idProveedor' => $this->input->post('idProveedor')), $data);
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
		$this->Proveedor_Model->delete_by_id(array('idProveedor' => $id));
		echo json_encode(array("status" => TRUE));
	}

		// comprovar si existe nobre de usuario para registro Proveedor
	function check_ruc($ruc_id)
	{
	if ($this->Proveedor_Model->check_ruc($ruc_id)) {
			$this->form_validation->set_message('check_ruc', "$ruc_id No Disponible Ruc duplicado");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

 }
 
 /* End of file Proveedor.php */
 /* Location: ./application/controllers/Proveedor.php */
