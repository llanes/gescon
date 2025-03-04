<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cobro extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Cobro_Model",'Cobro');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			}

	}

	public function index( $offset = 0 )
	{
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
											   $this->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'left');
											   $this->db->where('Estado', '0');
				  							   $Moneda = $this->db->get('Moneda')->result();
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'Moneda' => $Moneda,
															'Razon' =>$this->db->get('Cliente')->result(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Pagos_Cobros/Cobro/Cobros_vista.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Pagos_Cobros/Cobro/scriptCobros.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(23);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
				        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
												$data       = array (
												"Alerta"    => $this->Producto->get_alert(),
												'Moneda'    => $Moneda,
												'data_view' => $variable,
												'Razon'     =>$this->db->get('Cliente')->result(),
												);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Pagos_Cobros/Cobro/Cobros_vista.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Pagos_Cobros/Cobro/scriptCobros.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}
				}

			}

	}


	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) 
		{
			// $this->output->enable_profiler(TRUE);
		$list = $this->Cobro->get_Cobro();
		// echo var_dump($list);
			$data = array();
			$no = $_POST['start'];
			$datos ='cob_ro';
			$dato ='cobro';

			foreach ($list as $Cobro) 
			{
	           if (is_null($Cobro->idcce)) { ///  cobros
	               /////////////////////////////////////////////////////////////////////////
					$row   = array();
			    	$row[] =  $this->mi_libreria->getSubString($Cobro->Concepto,40);;
					$row[] =$this->mi_libreria->getSubString( number_format( $Cobro->Monto,0,'.',','),20 ). '&nbsp; ₲.';
						if ($Cobro->Tipo_Venta == 1) {
							$row[] = 'Factura N° '.$Cobro->Num_Factura_Venta;
						}else {
							$row[] = 'Recibo N° '.$Cobro->Ticket;
						}
						if (!empty($Cobro->Razon_Social)) {
							$row[] = $this->mi_libreria->getSubString($Cobro->Razon_Social,40 );
						}else{
							$row[] = $this->mi_libreria->getSubString($Cobro->Nombres. ' (' .$Cobro->Ruc.')',40 );
						}

					$row[] = $this->mi_libreria->getSubString($Cobro->Fecha.' - '.$Cobro->Hora,40 );
					if (is_null($Cobro->idcce)) {
							$row[]   = '<div class="pull-right hidden-phone">
	<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$dato."'".','."'".$Cobro->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/cobro/'.$Cobro->idFactura_Venta.'" title="Exportar a EXEL" onclick=>
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							<a class ="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Eliminar" onclick="delete_('."'".$Cobro->idFactura_Venta."'".','."'".$Cobro->idcce."'".','."'".$Cobro->idm."'".','."'".$Cobro->idtj."'".','."'".$Cobro->idCaja_Cobros."'".')"><i class="fa fa-trash-o"></i></a></div>';
					}else{
							$row[]   = '<div class="pull-right hidden-phone">
	<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$dato."'".','."'".$Cobro->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a EXEL" onclick="exel_exporte('."'".$dato."'".','."'".$Cobro->idFactura_Venta."'".')">
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							</div>';
					}
                   	$row[] =  $Cobro->idFactura_Venta;


               ////////////////////////////////////////////////////////////////////
	           }
	           else  /// cuotas
	           {
	           	////////////////////////////////////////////////////////////////////
					$row   = array();
			    	$row[] =  'Cobros de Cuotas';
					$row[] =$this->mi_libreria->getSubString( number_format( $Cobro->total1,0,'.',','),30 ). '&nbsp; ₲.';
					$row[] = 'Recibo Cuota N° '.$Cobro->Num_Recibo;
						if (!empty($Cobro->Razon_Social)) {
							$row[] = $this->mi_libreria->getSubString($Cobro->Razon_Social,40 );
						}else{
							$row[] = $this->mi_libreria->getSubString($Cobro->Nombres. ' (' .$Cobro->Ruc.')',40 );
						}
					$row[] = $this->mi_libreria->getSubString($Cobro->Fecha_Pago,40 );
							$row[]   = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Cobro->idcce."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/cob_ro/'.$Cobro->idcce.'" title="Exportar a EXEL">
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							</div>';

                   	$row[] =  $Cobro->idcce;
	           	////////////////////////////////////////////////////////////////////
	           }
					$no++;
					$data[] = $row;

			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Cobro->count_todas(),
				"recordsFiltered" => $this->Cobro->count_filtro(),
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
	public function ajax_add_cobro($value='')
	{
		if ($this->input->is_ajax_request()) {
			$val = $this->security->xss_clean( $this->input->post('val',FALSE));
			$parcial1 = $this->security->xss_clean( $this->input->post('parcial1',FALSE));
			$parcial2 = $this->security->xss_clean( $this->input->post('parcial2',FALSE));
			$parcial3 = $this->security->xss_clean( $this->input->post('parcial3',FALSE));
			$parcial4 = $this->security->xss_clean( $this->input->post('parcial4',FALSE));
			if (!empty($parcial1)) {
				for ($i=1; $i <= $val; $i++) { 
				$this->form_validation->set_rules('EF'.$i, 'Moneda', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');
				}
			}elseif (!empty($parcial2)) {
				 $this->form_validation->set_rules('numcheque', 'numerocheque', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				   $this->form_validation->set_rules('fecha_pago', 'fecha', 'trim|required|min_length[1]|max_length[14]|strip_tags');
				    $this->form_validation->set_rules('efectivo', 'Efectivo', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			}elseif (!empty($parcial3)) {
				$this->form_validation->set_rules('efectivoTarjeta', 'Tarjeta', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Tarjeta', 'tipo', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
			}
				 $this->form_validation->set_rules('Razon', 'Razon', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			     $this->form_validation->set_rules('Descripcion', 'Descripcion', 'trim|required|min_length[1]|max_length[50]|strip_tags');
			     $this->form_validation->set_rules('Totalparclal', 'Toal', 'trim|required|numeric|min_length[1]|max_length[25]|strip_tags');
			     $this->form_validation->set_rules('tipoComprovante', 'Comprovante', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			     $this->form_validation->set_rules('PlandeCuenta', 'PlandeCuenta', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				if ($this->form_validation->run() == FALSE) {
					$data      = array(
					'EF1'             => form_error('EF1'),
					'EF2'             => form_error('EF2'),
					'EF3'             => form_error('EF3'),
					'EF4'             => form_error('EF4'),
					'EF5'             => form_error('EF5'),
					'EF6'             => form_error('EF6'),
					'Totalparclal'    => form_error('Totalparclal'),
					'numcheque'       => form_error('numcheque'),
					'Razon'           => form_error('Razon'),
					'Descripcion'     => form_error('Descripcion'),
					'fecha_pago'      => form_error('fecha_pago'),
					'efectivo'        => form_error('efectivo'),
					'efectivoTarjeta' => form_error('efectivoTarjeta'),
					'PlandeCuenta' => form_error('PlandeCuenta'),
					'Tarjeta'         => form_error('Tarjeta'),
					'res'      => 'error');
					echo json_encode($data);
				}else{
						$Totalparclal    =	$this->security->xss_clean( $this->input->post('Totalparclal',FALSE));
						$numcheque       =	$this->security->xss_clean( $this->input->post('numcheque',FALSE));
						$fecha_pago      =	$this->security->xss_clean( $this->input->post('fecha_pago',FALSE));
						$Razon           =	$this->security->xss_clean( $this->input->post('Razon',FALSE));
						$Descripcion     =	$this->security->xss_clean( $this->input->post('Descripcion',FALSE));
						$efectivoTarjeta =	$this->security->xss_clean( $this->input->post('efectivoTarjeta',FALSE));
						$Tarjeta         =	$this->security->xss_clean( $this->input->post('Tarjeta',FALSE));
						$tipoComprovante =	$this->security->xss_clean( $this->input->post('tipoComprovante',FALSE));
						$PlandeCuenta =	$this->security->xss_clean( $this->input->post('PlandeCuenta',FALSE));
						$comprobante =	$this->security->xss_clean( $this->input->post('comprobante',FALSE));
						$Ticket =	$this->security->xss_clean( $this->input->post('Ticket',FALSE));

						$moneda = array();
						if (!empty($parcial1)) {
						for ($i=1; $i <= $val ; $i++) {
						 	$Moneda  =	$this->security->xss_clean( $this->input->post('Moneda'.$i,FALSE));
						 	$cambio  =	$this->security->xss_clean( $this->input->post('cam'.$i,FALSE));
						 	$cambiado  =	$this->security->xss_clean( $this->input->post('ex'.$i,FALSE));
						 	$signo  =	$this->security->xss_clean( $this->input->post('signo'.$i,FALSE));
						 	$EF  =	$this->security->xss_clean( $this->input->post('EF'.$i,FALSE));
						 	if ($Moneda>0) {
						 		$moneda[ ] = array(
						 			'Moneda' => $Moneda, 
						 			'cambio' => $cambio, 
						 			'cambiado' => $cambiado, 
						 			'signo' => $signo, 
						 			'EF' => $EF, 
						 			);
						 	}

						 }
						}

// $this->output->enable_profiler(TRUE);
					$_data = $this->Cobro->cobro(
						$Totalparclal,
						$parcial1,
						$moneda,

						$parcial2,
						$numcheque,
						$fecha_pago,
						$Razon,$Descripcion ,$tipoComprovante,$comprobante ,$Ticket,$PlandeCuenta,

						$parcial3,
						$efectivoTarjeta,
						$Tarjeta);
					echo json_encode($_data);
				}
		}else {
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
			// $data = $this->Cobro->get_by_id($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}
 	/**
	 * [ajax_edit description]
	 * @param  [type] $idEmpleado [description]
	 * @return [type]            [description]
	 */
	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
		   $data = $this->Cobro->get_by_id($id);
		   if ($data) {
		   	echo json_encode($data);
		   }

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
				$this->form_validation->set_rules('Codigo', 'Codigo', 'trim|required|callback_check_Codigo['.$this->input->post('id',FALSE).']|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[1]|max_length[44]|strip_tags');
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
					'NombreCuenta'             => $this->security->xss_clean(ucfirst(strtolower($this->input->post('nombre',FALSE)))),
					'CodigoCuenta'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Codigo',FALSE)))),
					'SubCobroCuenta_idSubCobroCuenta'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Categorias',FALSE))))
					);
					$this->Cobro->update(array('idCobrodeCuenta' => $this->input->post('id')), $data);
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
 	public function ajax_delete()
 	{
 		if ($this->input->is_ajax_request()) {
			$id  =	$this->security->xss_clean( $this->input->post('id',FALSE)); // idfactura
			$id2  =	$this->security->xss_clean( $this->input->post('id2',FALSE)); // id cuenta corriente
			$id3  =	$this->security->xss_clean( $this->input->post('id3',FALSE)); // id movimiento
			$id4  =	$this->security->xss_clean( $this->input->post('id4',FALSE)); // id tarjeta
            $id5  =	$this->security->xss_clean( $this->input->post('id5',FALSE)); // id pagos
            	$this->db->trans_begin();
            	// $this->output->enable_profiler(TRUE);
            		    if (!empty($id)) {
            		    	$this->db->where('Factura_Venta_idFactura_Venta', $id);
							$this->db->delete('Acientos');	
			            	$this->db->where('idFactura_Venta', $id);
			            	$this->db->delete('Factura_Venta');
			            }
			            if (!empty($id3)) {

			            	$this->db->select('Importe,Gestor_Bancos_idGestor_Bancos');
			            	$this->db->where('idMovimientos', $id3);
			            	$query = $this->db->get('Movimientos');
			                $row = $query->row();
			                if (!empty($row->Gestor_Bancos_idGestor_Bancos)) {
			                $this->db->set('MontoActivo', 'MontoActivo+'.$row->Importe, FALSE);
			                $this->db->where('idGestor_Bancos', $row->Gestor_Bancos_idGestor_Bancos);
			                $this->db->update('Gestor_Bancos');
			                }
            		    	$this->db->where('Factura_Venta_idFactura_Venta', $id);
							$this->db->delete('Acientos');	
			            	$this->db->where('idMovimientos', $id3);
			            	$this->db->delete('Movimientos');

			            }
			            if (!empty($id4)) {
            		    	$this->db->where('Factura_Venta_idFactura_Venta', $id);
							$this->db->delete('Acientos');	
			            	$this->db->where('idTarjeta', $id4);
			            	$this->db->delete('Tarjeta');
			            }
			            if (!empty($id5)) {
            		    	$this->db->where('Factura_Venta_idFactura_Venta', $id);
							$this->db->delete('Acientos');	
			          	    $this->db->where('Factura_Venta_idFactura_Venta', $id);
			            	$this->db->delete('Caja_Cobros');
			            }
            	if ($this->db->trans_status() === FALSE)
            	{
            	        $this->db->trans_rollback();
            	}
            	else
            	{
            	       $this->db->trans_commit();
            	       echo json_encode(array("status" => TRUE));
            	}
			// echo json_encode(array("status" => TRUE));
 		} else {
 			$this->load->view('errors/404.php');
 		}

 	}


}

/* End of file Cobro.php */
/* Location: ./application/controllers/Cobro.php */
