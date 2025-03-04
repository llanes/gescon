<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Load Dependencies
		$this->load->model('User_Model');
		if(!$this->session->userdata('idUsuario')) { // si la seccion no existe me quedo en el homo
			redirect('Login');
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
				$datacss = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
					// 'toastem' =>'bower_components/jQueryToastem/'

				);
				$this->mi_css_js->init_css_js($datacss,'css');
				$datajs = array(
					'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					// 'toastem'  =>'bower_components/jQueryToastem/',
                    'script_user'       =>'bower_components/script_vista/',
					
				);
                $this->mi_css_js->init_css_js($datajs,'js');

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
				            $this->load->view('Home/aside.php',FALSE);
							$this->load->view('Usuario/User_vista.php',$data,FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php',FALSE); // este seria todo el contenido central
							// $this->load->view('Home/pie_js.php'); // pie con los js
							// $this->load->view('Usuario/script.php'); // pie con los js
				} else {
					$variable =  $this->Model_Menu->octener(27);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$_data = array('data_view' => $variable);
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$_data,FALSE);
							$this->load->view('Usuario/User_vista.php',$data,FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php',FALSE); // este seria todo el contenido central
							// $this->load->view('Home/pie_js.php'); // pie con los js
							// $this->load->view('Usuario/script.php'); // pie con los js
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}
				}

			}


	}
	public function ajax_list($value='')
	{
			// $this->output->enable_profiler(TRUE);
			$lista = $this->User_Model->get_usuario();
			$data = array();
			$i = $_POST['start'];
			foreach ($lista as $key => $user) {
				$i++;
				$row = array();
				$row[] =  $i;
				$row[] =  $user->Usuario;
				$row[] =  $user->Descripcion;
				$row[] =  $user->Oservacion;
				if ($this->db->count_all_results('Usuario') == 1) {
					$row[] = '<div class="pull-right hidden-phone">
						<a class="btn btn-primary btn-xs" href="javascript:void(0);" title="Edit" onclick="edit_User('."'".$user->idUsuario."'".')">
						<i class="fa fa-pencil-square"></i></a>
						</div>';
				} else {
					$row[] = '<div class="pull-right hidden-phone">
						<a class="btn btn-primary btn-xs" href="javascript:void(0);" title="Edit" onclick="edit_User('."'".$user->idUsuario."'".')">
						<i class="fa fa-pencil-square"></i></a>
						<a class="btn btn-danger btn-xs" href="javascript:void(0);" title="Hapus" onclick="delete_User('."'".$user->idUsuario."'".')">
						<i class="fa fa-trash-o"></i></a></div>';
				}

				$data[] = $row; 
			}
				$salida = array(
						"draw"            => $_POST['draw'],
						"recordsTotal"    => $this->User_Model->count_todas(),
						"recordsFiltered" => $this->User_Model->count_filtro(),
						"data"            => $data,
				);
		//salida to json format
		echo json_encode($salida);

	}
    public function ajax_add()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('*', '');
            $this->form_validation->set_rules('cargo', 'Tipo de Cargo', 'trim|required|min_length[1]|max_length[12]|strip_tags');
            $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|callback_check_User|strip_tags');
            $this->form_validation->set_rules('password', 'Contraseña', 'trim|required|strip_tags');
            $this->form_validation->set_rules('passconf', 'Confirmar Contraseña', 'trim|required|matches[password]|strip_tags');
    
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'cargo'     => form_error('cargo'),
                    'usuario'   => form_error('usuario'),
                    'password'  => form_error('password'),
                    'passconf'  => form_error('passconf'),
                    'res'       => 'error'
                );
                echo json_encode($data);
            } else {
                // Las contraseñas coinciden, proceder con el registro.
                $data = array(
                    'usuario'             => $this->security->xss_clean($this->input->post('usuario', FALSE)),
                    'password'            => sha1($this->security->xss_clean($this->input->post('password', FALSE))),
                    'Empleado_idEmpleado' => NULL,
                    'Permiso_idPermiso'   =>$this->security->xss_clean($this->input->post('cargo', FALSE)),
                );
                $insert = $this->User_Model->insert($data);
                echo json_encode(array("status" => TRUE));
            }
        } else {
            $this->load->view('errors/404.php');
        }
    }
    

	/**
	 * [ajax_edit description]
	 * @param  [type] $id [description]
	 * @return [type]            [description]
	 */
	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->User_Model->get_by_id($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [update description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function ajax_update( $id = NULL )
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('','');
				$pasantigua = sha1($this->security->xss_clean($this->input->post('pasantigua',FALSE)));
				if (!empty($pasantigua)) {
					$tes = 'user_update';
				}else{
					$tes = 'user_update2';
				}
				if ($this->form_validation->run($tes) == FALSE)
				{
						$data = array(
							'cargo'   => form_error('cargo'),
							'usuario'   => form_error('usuario'),
							'password'  => form_error('password'),
							'passconf'  => form_error('passconf'),
							'passanterior'  => form_error('pasantigua'),
							'res'		=> 'error');
					echo json_encode($data);		
				}else{
					if (!empty($pasantigua)) {
						$_data                = array(
						'usuario'             => $this->security->xss_clean($this->input->post('usuario',FALSE)),
						'password'            => sha1($this->security->xss_clean($this->input->post('password',FALSE))),
						'Empleado_idEmpleado' => NULL,
						'Permiso_idPermiso'   =>$this->security->xss_clean($this->input->post('cargo',FALSE)),
						);
					}else{
						$_data                = array(
						'usuario'             => $this->security->xss_clean($this->input->post('usuario',FALSE)),
						'Permiso_idPermiso'   =>$this->security->xss_clean($this->input->post('cargo',FALSE)),
						);
					}

					$this->User_Model->update(array('idUsuario' => $this->input->post('idUsuario')), $_data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [ajax_delete eliminar usuario]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function ajax_delete($id)
	{
		// $this->output->enable_profiler(TRUE);
		if ($this->input->is_ajax_request()) {
			$this->User_Model->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	// comprovar si existe nobre de usuario para registro 
	function check_User($user_id)
	{

	if ($this->User_Model->check_User($user_id)) {
			$this->form_validation->set_message('check_User', "$user_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

	public function verificarpass()
	{
		if ($this->input->is_ajax_request()) {
			$pass = sha1($this->security->xss_clean($this->input->post('val',FALSE)));
			$this->db->select('Password');
			$this->db->like('Password', $pass, 'both');
			$query = $this->db->get('Usuario');
			if ($query->num_rows()>0) {
				echo json_encode(TRUE);
			}else{
				echo json_encode(FALSE);
			}

		}
	}

}

/* End of file Admin_User.php */
/* Location: ./application/controllers/Admin_User.php */