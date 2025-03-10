<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CDevoluciones extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Cdevolver_Model",'Devolver');
		$this->load->library('Cart');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			}

	}

	public function index( $offset = 0 )
	{
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$array = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',
                    'toastem'        			   =>'bower_components/jQueryToastem/',
				);
				$this->mi_css_js->init_css_js($array,'css');


				$array = array(
                    'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					'select2'                  =>'bower_components/select2/dist/js/',
                      'scriptCdevolucion'       =>'bower_components/script_vista/',
                      'toastem'            	   =>'bower_components/jQueryToastem/',
		    	);
				$this->mi_css_js->init_css_js($array,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'motivos' => $this->Devolver->obtener_motivos(),
															'Proveedor' =>$this->db->get('Proveedor')->result());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Cdevolucion/Vista.php');
									$this->load->view('Home/footer.php');
									// $this->load->view('Cdevolucion/script.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(16);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
												$data       = array (	
																		'data_view' => $variable
                                                                    );
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Cdevolucion/Vista.php');
                                                $this->load->view('Home/footer.php');
												// $this->load->view('Cdevolucion/script.php');
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
		if ($this->input->is_ajax_request()) {
		// $this->output->enable_profiler(TRUE);
		$list = $this->Devolver->get_Devolver();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $Devolver) {
				$datos ='devolucion';
				$no++;
				$row   = array();
						// pagado
		   				if ($Devolver->Tipo_Compra == 0 ) { // voleta
						$row[] = 'Recibo Nº '. $Devolver->Ticket;;// comprovante
						}elseif ($Devolver->Tipo_Compra == 1 ) { // factura
						$row[] = 'Factura Nº '. $Devolver->Num_factura_Compra;// comprovante
						}
						$row[] =  $this->mi_libreria->getSubString($Devolver->Razon_Social.'-'.$Devolver->Vendedor, 35);
						$row[] = $Devolver->Fecha;
							$row[] = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Devolver->idDevoluciones."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/devolucion/'.$Devolver->idDevoluciones.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							</div>';
							$row[] = $Devolver->idDevoluciones;

				$data[] = $row;
		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Devolver->count_todas(),
			"recordsFiltered" => $this->Devolver->count_filtro(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}

	}

	/**
	 * [list_comprobante description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function list_comprobante($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Devolver->list_comprobante(array('Proveedor_idProveedor' => $id));
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [loader description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function loader($id = NULL)
	{
		if ($this->input->is_ajax_request()) {
			if ($id==NULL) {
			 $this->load->view('Cdevolucion/cart_get.php');
			}else{
             $this->cart->destroy();
             $this->load->view('Cdevolucion/cart_get.php');
			}

		} else {
			$this->load->view('errors/404.php');
		}

	}

		/**
	 * [agregar_item description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function agregar_item($value = null)
	{
		if ($this->input->is_ajax_request()) {
					$this->cart->destroy();
					$data =   $this->Devolver->item_Comprobante(array('Factura_Compra_idFactura_Compra' => $value));
					echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}

		public function ajax_add()
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_rules('proveedor', 'Proveedor', 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('id', 'Comprobante', 'trim|required|min_length[1]|max_length[15]|strip_tags'   );
				$this->form_validation->set_rules('tipooccion', 'Occiones', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('mov', 'Motivo', 'trim|required|min_length[1]|max_length[100]|strip_tags');
				$this->form_validation->set_rules('cart_total', 'Monto final', 'trim|numeric|required|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_error_delimiters('<i class ="fa fa-exclamation" aria-hidden="true"> ' , ' </i>');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
						'proveedor'  => form_error('proveedor'),
						'id'         => form_error('id'),
						'mov'         => form_error('mov'),
						'tipooccion' => form_error('tipooccion'),
						'cart_total' => form_error('cart_total'),

						'res'             => 'error');
					echo json_encode($data);
				}else{
					$data = $this->input->post();




					$_data = $this->Devolver->devolver($data);

					echo json_encode(["status" => true]);
				}

		}
	}
		public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
		   $data = $this->Devolver->detale(array('Devoluciones_idDevoluciones' => $id));
		   if ($data) {
		   	echo json_encode($data);
		   }
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function ajax_delete()
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$id  =	$this->security->xss_clean( $this->input->post('id',FALSE));//idDetalle_Devolucion
			$id2  =	$this->security->xss_clean( $this->input->post('id2',FALSE));//Detalle_Devolucion.Estado
			$id3  =	$this->security->xss_clean( $this->input->post('id3',FALSE));//Cantidad
			$id4  =	$this->security->xss_clean( $this->input->post('id4',FALSE));//val.Motivo
			$id5  =	$this->security->xss_clean( $this->input->post('id5',FALSE));//Producto_idProducto
			$del  =	$this->security->xss_clean( $this->input->post('del',FALSE));//Devoluciones_idDevoluciones
			$pre  =	$this->security->xss_clean( $this->input->post('pre',FALSE));//precio
			$id6  =	$this->security->xss_clean( $this->input->post('id6',FALSE));//precio
			switch ($id2) {
				case '1':
					if ($id4 == '3') {
						$this->db->trans_begin();
								$this->db->set('Devolucion', 'Devolucion-'.$id3, FALSE);
								$this->db->where('idDetalle_Compra', $id6);
								$this->db->update('Detalle_Compra');
								$this->db->where('idDetalle_Devolucion', $id);
								$this->db->delete('Detalle_Devolucion');
						if ($this->db->trans_status() === FALSE)
						{
						        $this->db->trans_rollback();
						}
						else
						{
						        $this->db->trans_commit();
						}
					}else{
						$this->db->trans_begin();
									$this->db->set('Devolucion', 'Devolucion-'.$id3, FALSE);
									$this->db->where('idDetalle_Compra', $id6);
									$this->db->update('Detalle_Compra');
									$this->db->where('idDetalle_Devolucion', $id);
									$this->db->delete('Detalle_Devolucion');
						if ($this->db->trans_status() === FALSE)
						{
						        $this->db->trans_rollback();
						}
						else
						{
						        $this->db->trans_commit();
						}
					}

					break;

				case '2':
						$this->db->trans_begin();
							$this->db->set('Cantidad_A', 'Cantidad_A+'.$id3, FALSE);
							$this->db->where('idProducto', $id5);
							$this->db->update('Producto');
							$this->db->set('Devolucion', 'Devolucion-'.$id3, FALSE);
							$this->db->where('idDetalle_Compra', $id6);
							$this->db->update('Detalle_Compra');
							$this->db->set('Monto', 'Monto-'.$pre, FALSE);
							$this->db->where('Devoluciones_idDevoluciones', $id);
							$this->db->update('Caja_Cobros');
							$this->db->where('idDetalle_Devolucion', $id);
							$this->db->delete('Detalle_Devolucion');
							$this->db->where('Compra_idDevoluciones', $id);
							$this->db->delete('Acientos');	
						if ($this->db->trans_status() === FALSE)
						{
						        $this->db->trans_rollback();
						}
						else
						{
						        $this->db->trans_commit();
						}
					break;

				case '3':
						$this->db->trans_begin();
								$this->db->set('Cantidad_A', 'Cantidad_A+'.$id3, FALSE);
								$this->db->where('idProducto', $id5);
								$this->db->update('Producto');
								$this->db->set('Devolucion', 'Devolucion-'.$id3, FALSE);
								$this->db->where('idDetalle_Compra', $id6);
								$this->db->update('Detalle_Compra');
								$this->db->set('Monto', 'Monto-'.$pre, FALSE);
								$this->db->where('Devoluciones_idDevoluciones', $id);
								$this->db->update('Cuenta_Fabor');
								$this->db->where('idDetalle_Devolucion', $id);
								$this->db->delete('Detalle_Devolucion');
							$this->db->where('Compra_idDevoluciones', $id);
							$this->db->delete('Acientos');	
						if ($this->db->trans_status() === FALSE)
						{
						        $this->db->trans_rollback();
						}
						else
						{
						        $this->db->trans_commit();
						}
					break;
			}
				$this->db->trans_begin();
					$this->db->where('Devoluciones_idDevoluciones', $del);
					$query = $this->db->count_all_results('Detalle_Devolucion');
					if (!$query) {
						$this->db->where('Devoluciones_idDevoluciones', $del);
						$this->db->delete('Caja_Cobros');
						$this->db->where('Devoluciones_idDevoluciones', $del);
						$this->db->delete('Cuenta_Fabor');
						$this->db->where('idDevoluciones', $del);
						$this->db->delete('Devoluciones');

					}
				if ($this->db->trans_status() === FALSE)
				{
				        $this->db->trans_rollback();
				}
				else
				{
				        $this->db->trans_commit();
				        echo json_encode($this->db->affected_rows());
				}


		} else {
			$this->load->view('errors/404.php');
		}
	}

}

/* End of file C_Devoluciones.php */
/* Location: ./application/controllers/C_Devoluciones.php */
