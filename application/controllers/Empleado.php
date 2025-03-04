<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Empleado extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Empleado_Model");
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
				$query = $this->db->get('Permiso');
				$data = array //arreglo para mandar datos a la vista
				(
					"Alerta" => $this->Producto->get_alert(),
					'Acceso' =>$query->result()
				);
				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Empleado/Empleado_vista.php',$data, FALSE); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Empleado/script.php'); // pie con los js
				} else {

					$variable =  $this->Model_Menu->octener(90);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Empleado/Empleado_vista.php',$data, FALSE); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Empleado/script.php'); // pie con los js
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
				$list = $this->Empleado_Model->get_Empleado();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $Empleado) {
			
				$no++;
				$resultado = intval(preg_replace('/[^0-9]+/', '', $Empleado->Sueldo), 10); 
				$sueldo =  number_format($resultado,0,'.',',');
				$row   = array();
				$row[] =  $no;
				$row[] = $this->mi_libreria->getSubString($Empleado->Nombres,10).'&nbsp;'.$this->mi_libreria->getSubString($Empleado->Apellidos,10);
				$row[] = $this->mi_libreria->getSubString($Empleado->Direccion,15);
				$row[] = $Empleado->Telefono;
				$row[] = $this->mi_libreria->getSubString($Empleado->Correo,20);
				$row[] = $sueldo.'&nbsp; ₲.';
				$row[] = $Empleado->Descripcion ;
				//add html for action
				$row[] = '<div class="pull-right hidden-phone">
				<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Empleado->idEmpleado."'".')">
				<i class="fa fa-pencil-square"></i></a>
				<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Empleado->idEmpleado."'".')">
				<i class="fa fa-trash-o"></i></a></div>';
				$data[] = $row;
		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Empleado_Model->count_todas(),
			"recordsFiltered" => $this->Empleado_Model->count_filtro(),
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
				$this->form_validation->set_error_delimiters('*','');
				if ($this->input->post('Usuario',FALSE) == '') {
					$user = 'registro_Empleado';# code...
					$_cargo = '';
				} else {
					$user = 'registro_Empleado_user';# code...
					$_cargo = $this->security->xss_clean( $this->input->post('Cargo',FALSE));
				}
				if ($this->form_validation->run($user) == FALSE)
				{
						$data = array(
							'Nombres'   => form_error('Nombres'),
							'Apellidos' => form_error('Apellidos'),
							'Direccion' => form_error('Direccion'),
							'Telefono'  => form_error('Telefono'),
							'Sueldo'    => form_error('Sueldo'),
							'Email'     => form_error('Email'),
							'Cargo'     => form_error('Cargo'),
							'Usuario'   => form_error('Usuario'),
							'Password'  => form_error('Password'),
							'passconf'  => form_error('passconf'),
							'res'            => 'error');
					echo json_encode($data);		
				}else{
					$data                 = array(
					'Nombres'   => $this->security->xss_clean( $this->input->post('Nombres',FALSE)),
					'Apellidos' => $this->security->xss_clean( $this->input->post('Apellidos',FALSE)),
					'Correo'    => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'Telefono'  => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Direccion' => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Sueldo'    => $this->security->xss_clean( $this->input->post('Sueldo',FALSE)),
					'Cargo'               => $_cargo 
					);
					$id               = $this->Empleado_Model->insert($data);
					if ($user == 'registro_Empleado_user') {
							$data                 = array(
							'Usuario'             => $this->security->xss_clean($this->input->post('Usuario',FALSE)),
							'Password'            => sha1($this->security->xss_clean($this->input->post('Password',FALSE))),
							'Empleado_idEmpleado' => $this->security->xss_clean($id),
							'Permiso_idPermiso'   =>$this->security->xss_clean($_cargo),
							);
							$insert = $this->Empleado_Model->insert2($data);
					} else {
						# code...
					}
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [ajax_edit description]
	 * @param  [type] $idEmpleado [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($idEmpleado)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Empleado_Model->get_by_id($idEmpleado);
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
				$pasantigua = $this->security->xss_clean($this->input->post('pasantigua'));
				if ($this->input->post('idUsuario')== '') {
					$user = 'ajax_update_Empleado';# code...
					$_cargo = '';
				} else {
					if (!$pasantigua ) {
					  $user = 'ajax_update_Empleado';# code...
					  $_cargo = $this->security->xss_clean( $this->input->post('Cargo',FALSE));
					}else{
					  $user = 'ajax_update_Empleado_user';# code...
					  $_cargo = $this->security->xss_clean( $this->input->post('Cargo',FALSE));
					}

				}
				
				if ($this->form_validation->run($user) == FALSE)
				{
					$data = array(
							'Nombres'   => form_error('Nombres'),
							'Apellidos' => form_error('Apellidos'),
							'Direccion' => form_error('Direccion'),
							'Telefono'  => form_error('Telefono'),
							'Sueldo'    => form_error('Sueldo'),
							'Email'     => form_error('Email'),
							'Cargo'     => form_error('Cargo'),
							'Usuario'   => form_error('Usuario'),
							'Password'  => form_error('Password'),
							'passconf'  => form_error('passconf'),
							'res'            => 'error');
					echo json_encode($data);		
				}else{
					$data                 = array(
					'Nombres'   => $this->security->xss_clean( $this->input->post('Nombres',FALSE)),
					'Apellidos' => $this->security->xss_clean( $this->input->post('Apellidos',FALSE)),
					'Correo'    => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'Telefono'  => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Direccion' => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Sueldo'    => $this->security->xss_clean( $this->input->post('Sueldo',FALSE)),
					'Cargo'               => $_cargo 
					);
					$this->Empleado_Model->update(array('idEmpleado' => $this->input->post('idEmpleado')), $data);

					if (!empty($this->input->post('idUsuario'))) {
	
						if ($pasantigua) {

							$_data                 = array(
							'Usuario'             => $this->security->xss_clean($this->input->post('Usuario',FALSE)),
							'Password'            => sha1($this->security->xss_clean($this->input->post('Password',FALSE))),
							'Empleado_idEmpleado' => $this->security->xss_clean($this->input->post('idEmpleado')),
							'Permiso_idPermiso'   =>$this->security->xss_clean( $this->input->post('Cargo',FALSE)),
							);

							$this->Empleado_Model->update2(array('idUsuario' => $this->input->post('idUsuario')), $_data);

						}else{
							$_data                 = array(
							'Usuario'             => $this->security->xss_clean($this->input->post('Usuario',FALSE)),
							'Empleado_idEmpleado' => $this->security->xss_clean($this->input->post('idEmpleado')),
							'Permiso_idPermiso'   =>$this->security->xss_clean( $this->input->post('Cargo',FALSE)),
							);
							$this->Empleado_Model->update2(array('idUsuario' => $this->input->post('idUsuario')), $_data);
						}
					} else {
						if (!empty($this->security->xss_clean($this->input->post('Usuario',FALSE)))  && empty($this->input->post('idUsuario'))) {
					         $_data                 = array(
							'Usuario'             => $this->security->xss_clean($this->input->post('Usuario',FALSE)),
							'Password'            => sha1($this->security->xss_clean($this->input->post('Password',FALSE))),
							'Empleado_idEmpleado' => $this->security->xss_clean($this->input->post('idEmpleado')),
							'Permiso_idPermiso'   =>$this->security->xss_clean( $this->input->post('Cargo',FALSE)),
							);
							$this->Empleado_Model->insert2($_data);
						}
					}

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
		$this->Empleado_Model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	// comprovar si existe nobre de usuario para registro empleado
	function check_User($user_id)
	{
	$this->load->model("Logeo_Model");
	if ($this->Logeo_Model->check_User($user_id)) {
			$this->form_validation->set_message('check_User', "No Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

 }
 
 /* End of file Empleado.php */
 /* Location: ./application/controllers/Empleado.php */
