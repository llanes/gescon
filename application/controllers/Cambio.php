<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Cambio extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Cambio_Model");
 		if(!$this->session->userdata('idUsuario')) { // si la seccion no existe me quedo en el homo
			redirect('Ingresar');
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
					"Alerta" => $this->Producto->get_alert()
				);
				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Cambio/Cambio_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Cambio/script.php'); // pie con los js
				} else {
					$variable =  $this->Model_Menu->octener(91);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Cambio/Cambio_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Cambio/script.php'); // pie con los js
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
			$list = $this->Cambio_Model->get_Cambio();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Cambio) 
			{
					$no++;
					$row   = array();
					$row[] = $no;
					$row[] = $Cambio->Moneda.'  '. $Cambio->Nombre.' ('.$Cambio->Signo.' ) ';
					$row[] = $Cambio->Compra;
					if ($Cambio->Estado == 0) {
					$row[] = 'Activo';
						# code...
					}else{
					$row[] = 'Inactivo';

					}
					$row[] = $fecha = date("Y-m-d");
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Cambio->idMoneda."'".')">
					<i class="fa fa-pencil-square"></i></a>
				    </div>';
					$data[] = $row;
			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Cambio_Model->count_todas(),
				"recordsFiltered" => $this->Cambio_Model->count_filtro(),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			show_404();
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
				if ($this->form_validation->run('registro_Cambio') == FALSE)
				{
						$data = array(
							'idCambios' => form_error('idCambios'),
							'Cambio'   => form_error('Cambio'),
							'Descrip'     => form_error('Descrip'),
							'res'         => 'error');
					echo json_encode($data);		
				}
				else
				{
					$data                 = array(
					'idCambios' => $this->security->xss_clean( $this->input->post('idCambios',FALSE)),
					'Cambio'   => $this->security->xss_clean( $this->input->post('Cambio',FALSE)),
					'Descrip'     => $this->security->xss_clean( $this->input->post('Descrip',FALSE)),
					);

					$this->Cambio_Model->insert($data);
					echo json_encode(array("status" => TRUE));
				}

        }else{
			show_404();
		}
	}
	/**
	 * [ajax_edit description]
	 * @param  [type] $idEmpleado [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($idCambios)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Cambio_Model->get_by_id($idCambios);
			echo json_encode($data);
		} else {
			show_404();
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
				$this->form_validation->set_rules('Moneda', 'Moneda', 'trim|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('Cambio', 'Cambio', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Estado', 'Estado', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');

				if ($this->form_validation->run('update_Cambio') == FALSE)
				{
					$data = array(
							'Moneda'   => form_error('Moneda'),
							'Cambio' => form_error('Cambio'),
							'Estado' => form_error('Estado'),

							'res'            => 'error');
					echo json_encode($data);
				}else{

					$_data                 = array(
					'Nombre'          => $this->security->xss_clean( $this->input->post('Moneda',FALSE)),
					'Estado'          => $this->security->xss_clean( $this->input->post('Estado',FALSE)),

					);
					$data                 = array(
					'Compra'          => $this->security->xss_clean( $this->input->post('Cambio',FALSE)),
					);				
					$this->Cambio_Model->update('Cambios',array('idCambios' => $this->input->post('idCambio')), $data);
					$this->Cambio_Model->update('Moneda',array('Cambios_idCambios' => $this->input->post('idCambio')), $_data);
					// $this->output->enable_profiler(TRUE);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			show_404();
		}
	}

	/**
	 * [ajax_delete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */


	public function ajax_delete($id = NULL)
	{
		$this->Cambio_Model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	
		
	function check_Cate($cate_id)
	{		
	if ($this->Cambio_Model->check_Cate($cate_id)) {
			$this->form_validation->set_message('check_Cate', "$cate_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

	////////////////////////////
	




 }
 
