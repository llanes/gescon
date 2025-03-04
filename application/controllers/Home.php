<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 


	}

	public function index()
	{

			if ($this->db->count_all_results('Empresa') == 0) {
					$data = $this->session->userdata('Usuario');
					//redirecionamos a la vista o llamamos a la vista index
					$this->load->view('Home/head.php',$data, FALSE);	// carga todos las url de estilo i js home	
					$this->load->view('Home/header1.php',$data, FALSE); // esta seria la barra de navegacion horizontal
					$this->load->view('Home/section2.php',$data, FALSE); // este seria todo el contenido central
					$this->load->view('Home/footer.php'); // pie con los js
					$this->load->view('Home/script.php'); // pie con los js
			} else {
					if($this->session->userdata('Permiso_idPermiso') == 1) { // si la seccion no existe me quedo en el homo

						$id = $this->session->userdata('Permiso_idPermiso');
						$data = array //arreglo para mandar datos a la vista
						(
							"Alerta"  => $this->Producto->get_alert(),
							'Ordenc'  => $this->db->where('Compra_Venta = 1')->count_all_results('Orden'),
							'Compra'  => $this->db->where('Estado != 4')->count_all_results('Factura_Compra'),
							'Cliente' => $this->db->count_all_results('Cliente'),
							'Prove'   => $this->db->count_all_results('Proveedor'),
							'Venta'   => $this->db->where('Estado != 4')->count_all_results('Factura_Venta'),
							'Ordenv'  => $this->db->where('Compra_Venta = 2')->count_all_results('Orden'),
						);

						$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
						$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
						$this->load->view('Home/aside.php',FALSE); // este seria todo el contenido central
						$this->load->view('Home/section.php',FALSE); // este seria todo el contenido central
						$this->load->view('Home/footer.php'); // pie con los js
					}else{
						$data = array //arreglo para mandar datos a la vista
						(
							"Alerta"  => $this->Producto->get_alert(),
							'Ordenc'  => $this->db->where('Compra_Venta = 1')->count_all_results('Orden'),
							'Compra'  => $this->db->where('Estado != 4')->where('Caja_idCaja', $this->session->userdata('idcaja'))->count_all_results('Factura_Compra'),
							'Cliente' => $this->db->count_all_results('Cliente'),
							'Prove'   => $this->db->count_all_results('Proveedor'),
							'Venta'   => $this->db->where('Estado != 4')->where('Caja_idCaja', $this->session->userdata('idcaja'))->count_all_results('Factura_Venta'),
							'Ordenv'  => $this->db->where('Compra_Venta = 2')->count_all_results('Orden'),
							'data_view' => $this->Model_Menu->octenerMenu(0),
						);
                        // var_dump($data);
						$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
						$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
						$this->load->view('Home/aside2.php',$data,FALSE); // este seria todo el contenido central
						$this->load->view('Home/section2.php',$data,FALSE); // este seria todo el contenido central
						$this->load->view('Home/footer.php'); // pie con los js
					}  
			}


	} 

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */