<?php  defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Banco extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		//Load Dependencies
 		$this->load->model('Banco_Model', 'Banco');
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
					// "Categoria" => $this->db->get('SubBancoCuenta')->result(),
				);

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php');
							$this->load->view('Banco/Banco_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Banco/script_.php'); // pie con los js
				} else {

					$variable =  $this->Model_Menu->octener(27);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Banco/Banco_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('Banco/script_.php'); // pie con los j
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
			$list = $this->Banco->get_Banco();
			$data = array();
			$no = $_POST['start'];
			$datos = 'movimiwnto';
			foreach ($list as $Banco) 
			{

					$no++;
					$row   = array();
					$row[] = $this->mi_libreria->getSubString($Banco->Nombre,40 );
					$row[] = $this->mi_libreria->getSubString($Banco->Numero,40 );
						if (!empty($Banco->MontoActivo)) {
							$row[] =  number_format($Banco->MontoActivo,0,'.',',').'&nbsp; ₲S.';
						}else{
							$row[] = '0 &nbsp; ₲S.';
						}

					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Banco->idGestor_Bancos."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/movimiwnto/'.$Banco->idGestor_Bancos.'" title="Exportar a EXEL">
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Banco->idGestor_Bancos."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Banco->idGestor_Bancos."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$row[] = $Banco->idGestor_Bancos;
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Banco->count_todas(),
				"recordsFiltered" => $this->Banco->count_filtro(),
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
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('numero', 'Numero', 'trim|required|min_length[1]|max_length[45]|strip_tags');
				$this->form_validation->set_rules('monto', 'Monto', 'trim|min_length[1]|max_length[45]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'nombre'   => form_error('nombre'),
							'numero'  => form_error('numero'),
							'monto'  => form_error('monto'),

							'res'		=> 'error');
					echo json_encode($data);
				}else{
					$MontoActivo = str_replace($this->config->item('caracteres'),"",$this->input->post('monto',FALSE));
					$data                = array(
					'Nombre'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('nombre',FALSE)))),
					'Numero'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('numero',FALSE)))),
					'MontoActivo'            => $this->security->xss_clean(ucfirst(strtolower($MontoActivo))),
					);
					$insert = $this->Banco->insert($data);
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
			$data = $this->Banco->get_by_id($id);
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
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('numero', 'Numero', 'trim|required|min_length[1]|max_length[45]|strip_tags');
				$this->form_validation->set_rules('monto', 'Monto', 'trim|min_length[1]|max_length[45]|strip_tags');

				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
			                'nombre'   => form_error('nombre'),
							'numero'  => form_error('numero'),
							'monto'  => form_error('monto'),
							'res'		=> 'error');
					echo json_encode($data);
				}else{
					$MontoActivo = str_replace($this->config->item('caracteres'),"",$this->input->post('monto',FALSE));
					$data                = array(
					'Nombre'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('nombre',FALSE)))),
					'Numero'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('numero',FALSE)))),
					'MontoActivo'            => $this->security->xss_clean(ucfirst(strtolower($MontoActivo))),
					);
					$this->Banco->update(array('idGestor_Bancos' => $this->input->post('id')), $data);
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
 			$this->Banco->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
 		} else {
 			$this->load->view('errors/404.php');
 		}

 	}


	public function detale($id='')
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$res = $this->Banco->detale($id);
			// echo var_dump($res);
			echo json_encode($res);
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
	if ($this->Banco->check_Codigo($marca_id,$id)) {
			$this->form_validation->set_message('check_Codigo', "$marca_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
 }
 
 /* End of file Banco.php */
 /* Location: ./application/controllers/Banco.php */
