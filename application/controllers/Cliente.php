<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Cliente extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Cliente_Model");
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
						$this->load->view('Home/aside.php');
						$this->load->view('Cliente/Cliente_vista.php'); // este seria todo el contenido central
						$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
						$this->load->view('Home/pie_js.php'); // pie con los js
						$this->load->view('Cliente/script.php'); // pie con los js
				} else {


					$variable =  $this->Model_Menu->octener(88);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Cliente/Cliente_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Cliente/script.php'); // pie con los js
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
		if ($this->input->is_ajax_request()) 
		{
			$list = $this->Cliente_Model->get_CLiente();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $Cliente) 
			{
					$no++;
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Cliente->Limite_max_Credito), 10); 
					$Limite_max_Credito =  number_format($resultado,0,'.',',');
					$row   = array();
					$row[] =  $no;
					$row[] = $this->mi_libreria->getSubString($Cliente->Nombres, 15);
					$row[] = $this->mi_libreria->getSubString($Cliente->Apellidos, 15);
					$row[] = $this->mi_libreria->getSubString($Cliente->Direccion, 15);
					$row[] = $Cliente->Telefono;
					$row[] = $Cliente->Correo;
					$row[] = $Limite_max_Credito.'&nbsp; ₲.';
					//$row[] = $sueldo.'&nbsp; ₲.';
					//$row[] = $Cliente->Cargo ;
					//add html for action
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Cliente->idCliente."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Cliente->idCliente."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Cliente_Model->count_todas(),
				"recordsFiltered" => $this->Cliente_Model->count_filtro(),
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
			$query = $this->db->select('Ruc as ruc,Nombres as full_name,Apellidos as Vendedor,idCliente  as id,Limite_max_Credito as lmc,TotalCredito as tc')
							  ->like('Ruc', $id, 'BOTH')
			                  ->or_like('Nombres', $id, 'BOTH')
			                  ->or_like('Apellidos', $id, 'BOTH')
			                  ->limit(10)
			                  ->get('Cliente');

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

	public function select2()
	{

			$id = $this->security->xss_clean($this->input->get('q'));
			$query = $this->db->select('Ruc as ruc,Nombres as full_name,Apellidos as Vendedor,idCliente  as id')
							  ->like('Ruc', $id, 'BOTH')
			                  ->limit(1)
			                  ->get('Cliente');

			echo json_encode($query->result());


	}
	/**
	 * [ajax_add description]
	 * @return [type] [description]
	 */
	public function ajax_add()
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('registro_Cliente') == FALSE)
				{
						$data = array(
							'Nombres'   => form_error('Nombres'),
							'Apellidos' => form_error('Apellidos'),
							'ruc'     => form_error('ruc'),
							'Direccion' => form_error('Direccion'),
							'Telefono'  => form_error('Telefono'),
							'Correo'     => form_error('Email'),
							'Limite_max_Credito'     => form_error('Limite_max_Credito'),
							'res'            => 'error');
					echo json_encode($data);
				}
				else
				{
					$data                 = array(
					'Nombres'   => $this->security->xss_clean( $this->input->post('Nombres',FALSE)),
					'Ruc' => $this->security->xss_clean( $this->input->post('ruc',FALSE)),
					'Apellidos' => $this->security->xss_clean( $this->input->post('Apellidos',FALSE)),
					'Direccion' => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Telefono'  => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Correo'    => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'Limite_max_Credito'    => $this->security->xss_clean( $this->input->post('Limite_max_Credito',FALSE)),
					);
					$this->Cliente_Model->insert($data);
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
 	public function ajax_edit($idCliente)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Cliente_Model->get_by_id($idCliente);
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
				if ($this->form_validation->run('ajax_update_Cliente') == FALSE)
				{
					$data = array(
							'Nombres'            => form_error('Nombres'),
							'Apellidos'          => form_error('Apellidos'),
							'Ruc'                => form_error('ruc'),
							'Direccion'          => form_error('Direccion'),
							'Telefono'           => form_error('Telefono'),
							'Email'              => form_error('Email'),
							'Limite_max_Credito' => form_error('Limite_max_Credito'),
							'res'                => 'error');
					echo json_encode($data);
				}else{
					$data                 = array(
					'Nombres'          => $this->security->xss_clean( $this->input->post('Nombres',FALSE)),
					'Ruc' => $this->security->xss_clean( $this->input->post('ruc',FALSE)),
					'Apellidos' => $this->security->xss_clean( $this->input->post('Apellidos',FALSE)),
					'Direccion'    => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Telefono'     => $this->security->xss_clean( $this->input->post('Telefono',FALSE)),
					'Correo'       => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'Limite_max_Credito'   => $this->security->xss_clean( $this->input->post('Limite_max_Credito',FALSE)),
					);
					$this->Cliente_Model->update(array('idCliente' => $this->input->post('idCliente')), $data);
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
		$this->Cliente_Model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	// comprovar si existe nobre de usuario para registro empleado
	function check_User($user_id)
	{		
	$this->load->model("Logeo_model");
	if ($this->Logeo_model->check_User($user_id)) {
			$this->form_validation->set_message('check_User', "$user_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	function check_ruc($ruc_id)
	{
	if ($this->Cliente_Model->check_ruc($ruc_id)) {
			$this->form_validation->set_message('check_ruc', "$ruc_id ya registrado");
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
