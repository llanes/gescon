<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Categoria extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Categoria_Model");
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
				$arraycss = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
				);
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array(
					'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					// 'script_stock'          =>'bower_components/script_vista/'
				);
				$this->mi_css_js->init_css_js($arrayjs,'js');
				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Categoria/Categoria_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Categoria/script.php'); // pie con los js
				} else {
					$variable =  $this->Model_Menu->octener(8);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Categoria/Categoria_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Categoria/script.php'); // pie con los js
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
			$list = $this->Categoria_Model->get_Categoria();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Categoria) 
			{
					$no++;
					$row   = array();
					$row[] = $no;
					$row[] = $Categoria->Categoria;
					$row[] = $Categoria->Descrip;
					
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-primary btn-xs" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Editar" onclick="_edit('."'".$Categoria->idCategoria."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="_delete('."'".$Categoria->idCategoria."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Categoria_Model->count_todas(),
				"recordsFiltered" => $this->Categoria_Model->count_filtro(),
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
				if ($this->form_validation->run('registro_Categoria') == FALSE)
				{
						$data = array(
							'idCategoria' => form_error('idCategoria'),
							'Categoria'   => form_error('Categoria'),
							'Descrip'     => form_error('Descrip'),
							'res'         => 'error');
					echo json_encode($data);		
				}
				else
				{
					$data                 = array(
					'idCategoria' => $this->security->xss_clean( $this->input->post('idCategoria',FALSE)),
					'Categoria'   => $this->security->xss_clean( $this->input->post('Categoria',FALSE)),
					'Descrip'     => $this->security->xss_clean( $this->input->post('Descrip',FALSE)),
					);

					$this->Categoria_Model->insert($data);
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
 	public function ajax_edit($idCategoria)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Categoria_Model->get_by_id($idCategoria);
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
				if ($this->form_validation->run('update_Categoria') == FALSE)
				{
					$data = array(
							'Categoria'   => form_error('Categoria'),
							'Descrip' => form_error('Descrip'),
							'res'            => 'error');
					echo json_encode($data);
				}else{
					$data                 = array(
					'Categoria'          => $this->security->xss_clean( $this->input->post('Categoria',FALSE)),
					'Descrip' => $this->security->xss_clean( $this->input->post('Descrip',FALSE)),
					
					
					);
					
					$this->Categoria_Model->update(array('idCategoria' => $this->input->post('idCategoria')), $data);
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
		$this->Categoria_Model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	
		
	function check_Cate($cate_id)
	{		
	if ($this->Categoria_Model->check_Cate($cate_id)) {
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
 
