<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Inventario extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Producto_Model");
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
				$data = array("Alerta" => $this->Producto->get_alert());
				$arraycss = array('jquery.dataTables' =>'content/datatables/DataTables/css/');
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array('jquery.dataTables.min' =>'content/datatables/DataTables/js/');
				$this->mi_css_js->init_css_js($arrayjs,'js');
				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
							$this->load->view('Producto/Inventario_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Producto/scriptinvenario.php'); // pie con los js
				} else {
					$variable =  $this->Model_Menu->octener(91);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Producto/Inventario_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Producto/scriptinvenario.php'); // pie con los js
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
			$list = $this->Producto_Model->get_Producto();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Producto) 
			{
					$no++;
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
					$Precio_Venta =  number_format($resultado,0,'.',',');
					$Marca  = $this->mi_libreria->getSubString($Producto->Marca,10);
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 10);
					$row   = array();
					$row[] = $no;
					$row[] = $this->mi_libreria->getSubString($Producto->Codigo, 12);
					$row[] = $Marca." (".$Nombre.")";
					$row[] = $Precio_Venta.'&nbsp; ₲S.';
					if ($Producto->Iva=="0")
					{
						$row[] = "Exenta";
					}
					else
					{
						$row[] = $Producto->Iva."%";
					}
					$row[] = $Producto->Cantidad_A += $Producto->Cantidad_D;
					$data[] = $row;
			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Producto_Model->count_todas(),
				"recordsFiltered" => $this->Producto_Model->count_filtro(),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	/**
	 * [ajax_list description]
	 * @return [type] [description]
	 */
	public function bajostock()
	{
		if ($this->input->is_ajax_request()) 
		{
			$list = $this->Producto_Model->get_Producto('0');
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Producto) 
			{
				if (10>($Producto->Cantidad_A + $Producto->Cantidad_D)) {
					$no++;
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 25); 
					$Precio_Venta =  number_format($resultado,0,'.',',');
					$Marca  = $this->mi_libreria->getSubString($Producto->Marca,25);
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 25);
					$row   = array();
					$row[] = $no;
					$row[] = $this->mi_libreria->getSubString($Producto->Codigo, 12);
					$row[] = $Marca." (".$Nombre.")";
					$row[] = $Precio_Venta.'&nbsp; ₲S.';
					if ($Producto->Iva=="0")
					{
						$row[] = "Exenta";
					}
					else
					{
						$row[] = $Producto->Iva."%";
					}
					$row[] = '<span class="badge bg-red">'.$Producto->Cantidad_A += $Producto->Cantidad_D.'</span>';
					$data[] = $row;	
				}

			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Producto_Model->count_todas(),
				"recordsFiltered" => $this->Producto_Model->count_filtro('0'),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}


 }
 
