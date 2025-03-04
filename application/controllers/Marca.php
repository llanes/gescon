<?php  defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Marca extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		//Load Dependencies
 		$this->load->model('Marca_Model', 'Marca');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
 	}
 
	public function index( $offset = 0 )
	{
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$data = array("Alerta" => $this->Producto->get_alert());
				$arraycss = array('jquery.dataTables' =>'content/datatables/DataTables/css/');
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array('jquery.dataTables.min' =>'content/datatables/DataTables/js/');
				$this->mi_css_js->init_css_js($arrayjs,'js');

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php');
							$this->load->view('Marca/Marca_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Marca/script.php'); // pie con los js
				} else {
					$variable =  $this->Model_Menu->octener(9);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Marca/Marca_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Marca/script.php'); // pie con los js
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
			$list = $this->Marca->get_Marca();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Marca) 
			{
					$no++;
					$row   = array();
			    	$row[] =  $no;
					$row[] = $this->mi_libreria->getSubString($Marca->Marca,40 );
					$row[] = $this->mi_libreria->getSubString($Marca->Descripcion,40 );
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Marca->idMarca."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Marca->idMarca."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Marca->count_todas(),
				"recordsFiltered" => $this->Marca->count_filtro(),
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
				if ($this->form_validation->run('registro_marca') == FALSE)
				{
						$data = array(
							'Marca'   => form_error('Marca'),
							'Descripcion'  => form_error('Descripcion'),
							'res'		=> 'error');
					echo json_encode($data);		
				}else{
					$data                = array(
					'Marca'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Marca',FALSE)))),
					'Descripcion'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Descripcion',FALSE))))
					);
					$insert = $this->Marca->insert($data);
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
 	public function ajax_edit($idMarca)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Marca->get_by_id($idMarca);
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
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('update_marca') == FALSE)
				{
						$data = array(
							'Marca'   => form_error('Marca'),
							'Descripcion'  => form_error('Descripcion'),
							'res'		=> 'error');
					echo json_encode($data);		
				}else{
					$data                = array(
					'Marca'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Marca',FALSE)))),
					'Descripcion'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Descripcion',FALSE))))
					);
					$this->Marca->update(array('idMarca' => $this->input->post('idMarca')), $data);
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
 			$this->Marca->delete_by_id($id);
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
 	function check_marca($marca_id)
	{		
	if ($this->Marca->check_marca($marca_id)) {
			$this->form_validation->set_message('check_marca', "$marca_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
 }
 
 /* End of file Marca.php */
 /* Location: ./application/controllers/Marca.php */
