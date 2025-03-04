<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Producto_null extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Producto_null_Model",'P_null');
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
				$data = array("Alerta" => $this->Producto->get_alert());
				$arraycss = array('jquery.dataTables' =>'content/datatables/DataTables/css/');
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array('jquery.dataTables.min' =>'content/datatables/DataTables/js/');
				$this->mi_css_js->init_css_js($arrayjs,'js');
				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
						    $this->load->view('Producto_null/Producto_null_vista.php',$data, FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Producto_null/script.php'); // pie con los js
				} else {

					$variable =  $this->Model_Menu->octener(10);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Producto_null/Producto_null_vista.php',$data, FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Producto_null/script.php'); // pie con los js
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

			$value = $this->input->post('data', TRUE);
			$list = $this->P_null->get_Producto($value);
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Producto) 
			{
					$no++;
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 10);
					$row   = array();
					$row[] = $no;
					if ($Producto->Img != '') {
						$row[] = '
						<div class="card card-block">
								<img src="'.base_url('/bower_components/uploads/'.$Producto->Img.'').'" onmouseover="this.width=100;this.height=100;" onmouseout="this.width=60;this.height=35;" width="60" height="35" alt=""/>
						</div>
					';
					} else {
						$row[] = "Sin Imagen";
					}
					$row[] = $this->mi_libreria->getSubString($Producto->Codigo, 12);
					$row[] = $Nombre;
					$row[] = $Producto->Cantidad;
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->P_null->count_todas($value),
				"recordsFiltered" => $this->P_null->count_filtro($value),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

 }

 /* End of file Producto_null.php */
 /* Location: ./application/controllers/Producto_null.php */
