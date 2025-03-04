<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permiso extends CI_Controller {

 	public function __construct()
 	{
 		parent::__construct();
 		//Load Dependencies
 		$this->load->model('Permiso_Model', 'Permiso');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
 	}
	// List all your items
	public function index( $offset = 0 )
	{
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$query = $this->db->get('Menu');				
				$data = array //arreglo para mandar datos a la vista
				(
					"Alerta" => $this->Producto->get_alert(),
					'Menu' =>$query->result()
				);
				$datacss = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',

				);
				$this->mi_css_js->init_css_js($datacss,'css');
				$datajs = array(
					'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					'select2'                  =>'bower_components/select2/dist/js/',
                    'script_permiso'       =>'bower_components/script_vista/',
					
				);
                $this->mi_css_js->init_css_js($datajs,'js');

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Permiso/Permiso_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php',FALSE); // este seria todo el contenido central
							// $this->load->view('Home/pie_js.php'); // pie con los js
							// $this->load->view('Permiso/script.php'); // pie con los js
				} else {


					$variable =  $this->Model_Menu->octener(100);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Permiso/Permiso_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php',FALSE); // este seria todo el contenido central
							// $this->load->view('Home/pie_js.php'); // pie con los js
							// $this->load->view('Permiso/script.php'); // pie con los js
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
			$list = $this->Permiso->get_Permiso();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $Permiso) 
			{
					$no++;
					$row   = array();
			    	$row[] =  $no;
					$row[] = $this->mi_libreria->getSubString($Permiso->Descripcion,40 );
					$row[] = $this->mi_libreria->getSubString($Permiso->Oservacion,40 );
					$hass = $this->Permiso->permiso_has($Permiso->idPermiso);
					if ($hass == '') {
						$row[] = 'Sin Acceso';
					} else {
						$row[] = '<a class="btn btn-warning btn-xs"  data-toggle="tooltip" data-placement="top" title="Permiso" onclick="_permiso('."'".$Permiso->idPermiso."'".')">Permiso</a>
				';
					}
					switch ($Permiso->idPermiso) {
						case '1':
								$row[] = '<div class="pull-right hidden-phone">
								<a class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top" title="Editar" onclick="_edit('."'".$Permiso->idPermiso."'".')">
								<i class="fa fa-pencil-square"></i></a>
								</div>';
						$data[] = $row;
							break;
						case '2':
								$row[] = '<div class="pull-right hidden-phone">
								<a class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top" title="Editar" onclick="_edit('."'".$Permiso->idPermiso."'".')">
								<i class="fa fa-pencil-square"></i></a>
								</div>';
						$data[] = $row;
							break;
						case '3':
								$row[] = '<div class="pull-right hidden-phone">
								<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top"  title="Editar" onclick="_edit('."'".$Permiso->idPermiso."'".')">
								<i class="fa fa-pencil-square"></i></a>
								</div>';
						$data[] = $row;
							break;
						default:
								$row[] = '<div class="pull-right hidden-phone">
								<div class="pull-right hidden-phone"></div>
								<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top"  title="Editar" onclick="_edit('."'".$Permiso->idPermiso."'".')">
								<i class="fa fa-pencil-square"></i></a>
								<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  title="Eliminar" onclick="_delete('."'".$Permiso->idPermiso."'".')">
								<i class="fa fa-trash-o"></i></a></div>';
								$data[] = $row;
							break;
					}

			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Permiso->count_todas(),
				"recordsFiltered" => $this->Permiso->count_filtro(),
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
				if ($this->form_validation->run('registro_Permiso') == FALSE)
				{
						$data = array(
							'Nombre'   => form_error('Nombre'),
							'Oservacion'  => form_error('Oservacion'),
							'res'		=> 'error');
					echo json_encode($data);		
				}else{
					$data                = array(
					'Descripcion'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Nombre',FALSE)))),
					'Oservacion'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Oservacion',FALSE))))
					);
					$id = $this->Permiso->insert($data);
					if ($this->input->post('multi') !== 'null') {
								$seleccionados = $this->input->post('multi'); // convierto el string a un array.
									for ($i=0;$i<count($seleccionados);$i++)
									{
											$_data         = array(
											'Permiso_idPermiso'  => $id ,
											'Menu_idMenu' => $seleccionados[$i],
											);
										$this->Permiso->add_Permiso_has($_data);
									}	
							} else {
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
 	public function ajax_edit($idPermiso)
	{
		if ($this->input->is_ajax_request()) {
            // $this->output->enable_profiler(TRUE);
            
			$data = $this->Permiso->get_by_id($idPermiso);
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
				if ($this->form_validation->run('update_Permiso') == FALSE)
				{
						$data = array(
							'Nombre'   => form_error('Nombre'),
							'Oservacion'  => form_error('Oservacion'),
							'res'		=> 'error');
					echo json_encode($data);
				}else{
					$data                = array(
					'Descripcion'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Nombre',FALSE)))),
					'Oservacion'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Oservacion',FALSE))))
					);
					$this->Permiso->update(array('idPermiso' => $this->input->post('idPermiso')), $data);
					if ($this->input->post('multi') !== 'null') {
								$this->Permiso->delete_by_has($this->input->post('idPermiso'));
								$seleccionados = $this->input->post('multi'); // convierto el string a un array.
									for ($i=0;$i<count($seleccionados);$i++)
									{
											$_data         = array(
											'Permiso_idPermiso'  => $this->input->post('idPermiso') ,
											'Menu_idMenu' => $seleccionados[$i],
											);
										$this->Permiso->add_Permiso_has($_data);
									}	
							} else {
								$this->Permiso->delete_by_has($this->input->post('idPermiso'));
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
 	public function ajax_delete( $id = NULL )
 	{
 		if ($this->input->is_ajax_request()) {
 			$this->Permiso->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
 		} else {
 			$this->load->view('errors/404.php');
 		}

 	}

 	/**
 	 * [check_Permiso description]
 	 * @param  [type] $Permiso_id [description]
 	 * @return [type]           [description]
 	 */
 	function check_permiso($permiso_id)
	{		
	if ($this->Permiso->check_Permiso($permiso_id)) {
			$this->form_validation->set_message('check_permiso', "$permiso_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

	public function permiso_has($id='')
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data = $this->Permiso->permiso_has($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}
}

/* End of file Permiso.php */
/* Location: ./application/controllers/Permiso.php */
