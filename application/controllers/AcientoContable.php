<?php defined('BASEPATH') OR exit('No direct script access allowed');
class AcientoContable extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Acientos_Model",'Acientos');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			}

	}
	
	public function index( $offset = 0 )
	{
			if ($this->session->userdata('Empresa') == 0) {
	              redirect('Home','refresh');
			} else {
				$array = array(
					'bootstrap-datetimepicker.min' =>'content/plugins/pikear/css/',
					'jquery.dataTables' =>'content/datatables/DataTables/css/',


					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',
					'toastem'        			   =>'bower_components/jQueryToastem/',


				);
				$this->mi_css_js->init_css_js($array,'css');
				$array = array(
                    'jquery.dataTables.min' =>'content/datatables/DataTables/js/',

					// 'jquery.mask'         =>'content/plugins/jQuery-Mask-Plugin-master/dist/',
					'select2'                  =>'bower_components/select2/dist/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
					 'moment' =>'content/plugins/pikear/js/',

					 'bootstrap-datetimepicker.min' =>'content/plugins/pikear/js/',

                    //  'jquery.inputmask'      =>'content/plugins/input-mask/',
                    //  'script_compra'       =>'bower_components/script_vista/',
		    	);
				$this->mi_css_js->init_css_js($array,'js');

		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'AC' =>$this->db->get('Acientos')->result(),
															'caja' =>$this->db->get('Caja')->result(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Acientos/Aciento_vista.php');
									// $this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/footer.php');
									$this->load->view('Acientos/scriptAciento.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(29);
					if (!empty($variable)) {
								$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
								$this->db->where('Usuario_idUsuario', $this->session->userdata('idUsuario'));
								$query    = $this->db->get('Caja');
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
												$data       = array (
												"Alerta"    => $this->Producto->get_alert(),
												'AC' =>$this->db->get('Acientos')->result(),
												'caja' =>$query->result(),
												'data_view' => $variable

												);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Acientos/Aciento_vista.php');
												// $this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/footer.php');
												$this->load->view('Acientos/scriptAciento.php');
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

			$list = $this->Acientos->get_Aciento();
			// $this->output->enable_profiler(TRUE);
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $Acientos) 
			{
						$row   = array();
				    	$row[] =  $Acientos->Fecha;
				    	if (!is_null($Acientos->DebeDetalle)) {
						$row[] =  $Acientos->Balance_General.' '.$Acientos->DebeDetalle;
				    	}else{
				    	 $row[] =  '';
				    	}

						if (!is_null($Acientos->HaberDetalle)) {
						$row[] =  $Acientos->Balance_General.' '.$Acientos->HaberDetalle;
					   }else{
					   	 $row[] =  '';
					   }

						if (!is_null($Acientos->Debe)) {
					    $row[] =  number_format($Acientos->Debe,0,'.',',').' ₲.';
						}else{
							 $row[] =  '';
						}

					    if (!is_null($Acientos->Haber)) {
					    $row[] =  number_format($Acientos->Haber,0,'.',',').' ₲.';
						}else{
							 $row[] =  '';
						}
						$no++;
					$data[] = $row;
			}

			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Acientos->count_todas(),
				"recordsFiltered" => $this->Acientos->count_filtro(),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}


	public function ajax()
	{
		if ($this->input->is_ajax_request()) 
		{
			$fecha = $this->input->post('fecha');
			$caja = $this->input->post('caja');
			$con = $this->input->post('con');
			$list = $this->Acientos->get_Aciento_sear($fecha,$caja,$con );

			$data = array();
			$no = $_POST['start'];
			foreach ($list as $Acientos) 
			{
				$row   = array();
				    	$row[] =  $Acientos->Fecha;
				    	if (!is_null($Acientos->DebeDetalle)) {
						$row[] =  $Acientos->Balance_General.' '.$Acientos->DebeDetalle;
				    	}else{
				    	 $row[] =  '';
				    	}

						if (!is_null($Acientos->HaberDetalle)) {
						$row[] =  $Acientos->Balance_General.' '.$Acientos->HaberDetalle;
					   }else{
					   	 $row[] =  '';
					   }

						if (!is_null($Acientos->Debe)) {
					    $row[] =  number_format($Acientos->Debe,0,'.',',').' ₲.';
						}else{
							 $row[] =  '';
						}

					    if (!is_null($Acientos->Haber)) {
					    $row[] =  number_format($Acientos->Haber,0,'.',',').' ₲.';
						}else{
							 $row[] =  '';
						}

					    $row[] =  '';
						$no++;
					$data[] = $row;

			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Acientos->count_todas_($fecha,$caja,$con ),
				"recordsFiltered" => $this->Acientos->count_filtro_($fecha,$caja,$con ),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
			
		} else {
			$this->load->view('errors/404.php');
		}
	}


	public function load_mayor($value='')
	{
		if ($this->input->is_ajax_request()) {
			$fecha = $this->input->post('fecha');
			$caja = $this->input->post('caja');
			$plan = $this->input->post('plan');
			$list = $this->Acientos->load_mayor($fecha,$caja,$plan );
			//      $this->output->enable_profiler(TRUE);
			$data = array('datos' => $list, );
			$this->load->view('Acientos/loadesbusqueda.php',$data,FALSE);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function balance()
	{
		if ($this->input->is_ajax_request()) {
			$mes = $this->input->post('mes');
			$ano = $this->input->post('ano');
			// $this->output->enable_profiler(TRUE);
			// echo json_encode($this->input->post());
			$list = $this->Acientos->load_balance($mes,$ano);
			$recordsFiltered = $this->Acientos->count_filtro_balance($mes,$ano);
            $hb = 0;
			$db = 0;
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $Acientos) 
			{
				$row   = array();
				    	$row[] = $Acientos->Nombre;
						$row[] =  $Acientos->Balance_General;
						if (!is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
							if ($Acientos->debe > $Acientos->haber) {
								$row1 = $Acientos->debe - $Acientos->haber;
								$row[] = '<p style="text-align:center"><strong>'.number_format($row1 ,0,'.',',').' ₲.</strong></p>';
								$row[] =  '';
								$db          +=$Acientos->debe;
								$hb           +=$Acientos->haber;
							}else {
								$row[] =  '';
                                $row2 =  $Acientos->haber - $Acientos->debe;
                                $row[] = '<p style="text-align:center"><strong>'.number_format($row2 ,0,'.',',').' ₲.</strong></p>';
								$db          +=$Acientos->debe;
								$hb           +=$Acientos->haber;
							}
						}elseif (is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
							    $row[] =  '';
						        $row[] = '<p style="text-align:center"><strong>'.number_format($Acientos->haber ,0,'.',',').' ₲.</strong></p>';
								$db          +=$Acientos->debe;
								$hb           +=$Acientos->haber;
						}elseif (!is_null($Acientos->debe) &&  is_null($Acientos->haber)) {
						        $row[] = '<p style="text-align:center"><strong>'.number_format($Acientos->debe ,0,'.',',').' ₲.</strong></p>';
								$db          +=$Acientos->debe;
								$hb           +=$Acientos->haber;
							    $row[] =  '';
						}


						$no++;
					$data[] = $row;
					if ($no == $recordsFiltered) {
								$row     = array();

								$row[] = '<p class="text-danger "><big></big></p>';
								$row[] = '<p class="text-danger  "><big>Resultados</big></p>';
								$row[] = '<p class="text-danger text-center "><big>'.$db.' ₲.</big></p>';
								$row[] = '<p class="text-danger text-center "><big>'.$hb.' ₲.</big></p>';
								$data[] = $row;
					}
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Acientos->count_todas_balance($mes,$ano),
				"recordsFiltered" => $recordsFiltered,
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
	public function ajax_add_Pago($value='')
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
				 $this->form_validation->set_rules('razon', 'Razon', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			     $this->form_validation->set_rules('Descripcion', 'Descripcion', 'trim|min_length[1]|max_length[50]|strip_tags');
			     $this->form_validation->set_rules('Totalparclal', 'Toal', 'trim|required|numeric|min_length[1]|max_length[25]|strip_tags');
			     $this->form_validation->set_rules('tipoComprovante', 'Comprovante', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			     $this->form_validation->set_rules('comprobante', 'Numero', 'trim|required|min_length[1]|max_length[15]|strip_tags'   );
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
					'razon'           => form_error('razon'),
					'Descripcion'     => form_error('Descripcion'),
					'fecha_pago'      => form_error('fecha_pago'),
					'efectivo'        => form_error('efectivo'),
					'efectivoTarjeta' => form_error('efectivoTarjeta'),
					'Tarjeta'         => form_error('Tarjeta'),
						'comprobante'     => form_error('comprobante'),
					'res'      => 'error');
					echo json_encode($data);
				}else{
						$Totalparclal    =	$this->security->xss_clean( $this->input->post('Totalparclal',FALSE));
						$numcheque       =	$this->security->xss_clean( $this->input->post('numcheque',FALSE));
						$fecha_pago      =	$this->security->xss_clean( $this->input->post('fecha_pago',FALSE));
						$Razon           =	$this->security->xss_clean( $this->input->post('razon',FALSE));
						$Descripcion     =	$this->security->xss_clean( $this->input->post('Descripcion',FALSE));
						$efectivoTarjeta =	$this->security->xss_clean( $this->input->post('efectivoTarjeta',FALSE));
						$Tarjeta         =	$this->security->xss_clean( $this->input->post('Tarjeta',FALSE));
						$tipoComprovante =	$this->security->xss_clean( $this->input->post('tipoComprovante',FALSE));
						$comprobante =	$this->security->xss_clean( $this->input->post('comprobante',FALSE));
						$Ticket =	$this->security->xss_clean( $this->input->post('Ticket',FALSE));
						$R_H =	$this->security->xss_clean( $this->input->post('R_H',FALSE));
						$cuenta_bancaria =	$this->security->xss_clean( $this->input->post('cuenta_bancaria',FALSE));
						$Nombre_Tipo =	$this->security->xss_clean( $this->input->post('Nombre_Tipo',FALSE));


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
					$_data = $this->Pago->Pago(
						$Totalparclal,
						$parcial1,
						$moneda,
						$parcial2,
						$numcheque,
						$fecha_pago,
						$Razon,$Descripcion ,$tipoComprovante,$comprobante ,$Ticket,$R_H ,$cuenta_bancaria,$Nombre_Tipo,
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
	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
		   $data = $this->Pago->get_by_id($id);
		   if ($data) {
		   	echo json_encode($data);
		   }

		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function Ticket()
	{
		// $this->output->enable_profiler(TRUE);
		if ($this->input->is_ajax_request()) {
				$this->db->select_max('Ticket', 'num');
				$query = $this->db->get('Factura_Compra');
				$row = $query->row();
				if ($row->num > 0) {
					foreach ($query->result() as $key => $value) {
						echo json_encode(++$value->num);
					}
				}else{
				$this->db->select_max('Comprovante', 'num');
				$query = $this->db->get('Empresa');
					foreach ($query->result() as $key => $value) {
						echo json_encode(++$value->num);
					}
		}
		}else{
				$this->db->select_max('Ticket', 'num');
				$query = $this->db->get('Factura_Compra');
				$row = $query->row();
				if ($row->num >  0) {
					foreach ($query->result() as $key => $value) {
						return ++$value->num;
					}
				}else{
				$this->db->select_max('Comprovante', 'num');
				$query = $this->db->get('Empresa');
					foreach ($query->result() as $key => $value) {
						return ++$value->num;
					}
				}
		}
	}

	public function check_num($check_num)
	{
		$this->load->model("Compra_Model",'Compra');
	if ($this->Compra->check_num($check_num)) {
			$this->form_validation->set_message('check_num', "$check_num No Disponible..");
			return FALSE;
        }
        else
        {
            return TRUE;
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
					'SubPagoCuenta_idSubPagoCuenta'            => $this->security->xss_clean(ucfirst(strtolower($this->input->post('Categorias',FALSE))))
					);
					$this->Pago->update(array('idPagodeCuenta' => $this->input->post('id')), $data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
 	}


 		public function ajax_addtipo($value='')
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				$this->form_validation->set_rules('Descripcion', 'Descripcion', 'trim|required|callback_check_nom|min_length[1]|max_length[44]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'Descripcion' => form_error('Descripcion'),
							'res' => 'error');
					echo json_encode($data);
				}
				else
				{
					$data                 = array(
					'NombreTipo'   => $this->security->xss_clean( $this->input->post('Descripcion',FALSE)),
					);
					$_data = $this->Pago->insert_tipo($data);
					echo json_encode($_data);

				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	public function check_nom($check_nom)
	{
	if ($this->Pago->check_nom($check_nom)) {
			$this->form_validation->set_message('check_nom', "$check_nom ya fue registrado debes canbiar!!");
			return FALSE;
        }
        else
        {
            return TRUE;
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
			            	$this->db->where('idFactura_Compra', $id);
			            	$this->db->delete('Factura_Compra');
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


			            	$this->db->where('idMovimientos', $id3);
			            	$this->db->delete('Movimientos');

			            }
			            if (!empty($id4)) {

			            	$this->db->where('idTarjeta', $id4);
			            	$this->db->delete('Tarjeta');
			            }
			            if (!empty($id5)) {
			          	    $this->db->where('Factura_Compra_idFactura_Compra', $id);
			            	$this->db->delete('Caja_Pagos');
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

/* End of file Pago.php */
/* Location: ./application/controllers/Pago.php */
