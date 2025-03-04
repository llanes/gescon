<?php  defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Plan_de_Cuenta extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		//Load Dependencies
 		$this->load->model('Plan_de_Cuenta_Model', 'Plan');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
 	}
 
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
							$this->load->view('Home/aside.php');
							$this->load->view('Plan_de_Cuenta/Plan_de_Cuenta_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Plan_de_Cuenta/script_.php'); // pie con los js
		
				} else {


					$variable =  $this->Model_Menu->octener(30);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Plan_de_Cuenta/Plan_de_Cuenta_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Plan_de_Cuenta/script_.php'); // pie con los js
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
			$list = $this->Plan->get_PlandeCuenta();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Plan) 
			{
					$no++;
					$row   = array();
					// $row[] =  $Plan->idPlandeCuenta;
			    	$row[] =  $Plan->Codificacion;
					$row[] = $this->mi_libreria->getSubString($Plan->Balance_General,100 );
					$row[] =  $Plan->Nombre;
						$row[] = '<div class="pull-right hidden-phone">
						<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Edit" onclick="_edit('."'".$Plan->idPlandeCuenta."'".')">
						<i class="fa fa-pencil-square"></i></a>
						<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Hapus" onclick="_delete('."'".$Plan->idPlandeCuenta."'".')">
						<i class="fa fa-trash-o"></i></a></div>';


					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Plan->count_todas(),
				"recordsFiltered" => $this->Plan->count_filtro(),
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
				if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('registro_Plan') == FALSE)
				{
						$data = array(
							'Codigo'   => form_error('Codigo'),
							'nombre'  => form_error('nombre'),
							'res'		=> 'error');
					echo json_encode($data);
				}else{
					$data                = array(
					'Balance_General'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('nombre',FALSE)))),
					'Codificacion '            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Codigo',FALSE)))),

					);
					$insert = $this->Plan->insert($data);
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
 	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Plan->get_by_id($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	/**
	 * [ajax_update description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
 	public function ajax_update( $id = NULL )
 	{
 		if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
 		        // $this->output->enable_profiler(TRUE);
				$this->form_validation->set_error_delimiters('*','');
				$this->form_validation->set_rules('Codigo', 'Codigo', 'trim|required|callback_check_Codigo['.$this->input->post('id',FALSE).']|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('Categorias', 'Categoria', 'trim|required|min_length[1]|max_length[15]|strip_tags');

				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'Codigo'   => form_error('Codigo'),
							'nombre'  => form_error('nombre'),
							'Categorias'  => form_error('Categorias'),
							'res'		=> 'error');
					echo json_encode($data);
				}else{
					$data                = array(
					'Balance_General'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('nombre',FALSE)))),
					'Codificacion'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Codigo',FALSE)))),
					'Control'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Categorias',FALSE))))
					);
					$this->Plan->update(array('idPlandeCuenta' => $this->input->post('id')), $data);
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
 	public function ajax_delete( $id = NULL )
 	{
 		if ($this->input->is_ajax_request()) {
 			$this->Plan->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
 		} else {
 			$this->load->view('errors/404.php');
 		}

 	}

 	/**
 	 * [check_marca description]
 	 * @param  [type] $marca_id [description]
 	 * @return [type]           [description]
 	 */
 	function check_Codigo($marca_id,$id='')
	{
	if ($this->Plan->check_Codigo($marca_id,$id)) {
			$this->form_validation->set_message('check_Codigo', "$marca_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
 }
 
 /* End of file Plan_de_Cuenta.php */
 /* Location: ./application/controllers/Plan_de_Cuenta.php */
