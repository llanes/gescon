<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Deuda_cliente.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: christian <christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/08/30 13:04:01 by christian         #+#    #+#             */
/*   Updated: 2016/08/30 13:07:19 by christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Deuda_Cliente extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Deuda_cliente_Model",'Cuenta');
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
                $this->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'left');
                $this->db->where('Estado', '0');
                $Moneda = $this->db->get('Moneda')->result();
            $array = array(
                'jquery.dataTables' =>'content/datatables/DataTables/css/',
                // 'bootstrap-datetimepicker.min'   =>'content/plugins/pikear/css/',
                'select2'           =>'bower_components/select2/dist/css/',
                'select2-bootstrap' =>'bower_components/select2/dist/css/'
                //  'ventacss'       =>'bower_components/script_vista/'
            );
            $this->mi_css_js->init_css_js($array,'css');


            $array = array(
                'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
                // 'jquery.mask'         =>'content/plugins/jQuery-Mask-Plugin-master/dist/',
                'select2'                  =>'bower_components/select2/dist/js/',
                //  'es'                    =>'bower_components/select2/dist/js/i18n/',
                 'toastem'            	   =>'bower_components/jQueryToastem/',
                //  'jquery.inputmask'      =>'content/plugins/input-mask/',
                //  'moment'       =>'content/plugins/pikear/js/',
                //  'es'       =>'content/plugins/pikear/',
                //  'bootstrap-datetimepicker'       =>'content/plugins/pikear/js/',
                  'scriptDeudaCliente'       =>'bower_components/script_vista/',
            );
            $this->mi_css_js->init_css_js($array,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'Moneda' => $Moneda,
															'Cliente' =>$this->getcliente());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Venta/DeudaC_vista.php');
									// $this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/footer.php');
									$this->load->view('Venta/script_D_C.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(17);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
     			        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
								$data       = array (	"Alerta" => $this->Producto->get_alert(),
								'data_view' => $variable,
								'Moneda'    => $Moneda,
								'Cliente'   =>$this->getcliente());
							 	$this->load->view('Home/head.php',$data,FALSE);
						        $this->load->view('Home/header.php',FALSE);
								$this->load->view('Home/aside2.php',FALSE);
								$this->load->view('Venta/DeudaC_vista.php');
								// $this->load->view('Home/sidebar.php',FALSE);
								$this->load->view('Home/footer.php');
								$this->load->view('Venta/script_D_C.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}
				}

			}
	}

	    public function getcliente()
    {
        $this->db->where('idCliente != 1');
        $query = $this->db->get('Cliente');
        return $query->result();
    }

  
    public function ajax_list_deudas_clientes()
    {
        if ($this->input->is_ajax_request()) {
			$length = $_POST['length'];
			$start = $_POST['start'];
			$search_value = $_POST['search']['value'];
			$order_column = $_POST['order']['0']['column'];
			$order_dir = $_POST['order']['0']['dir'];
			
			// Captura los nuevos parámetros
			$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : '';
			$ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
			$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
			$anho = isset($_POST['anho']) ? $_POST['anho'] : '';

			// Llama al método que maneja la lógica de búsqueda
			$data = $this->Cuenta->get_Deuda($length, $start, $search_value, $order_column, $order_dir, $estatus, $ruc, $factura, $anho);
			
    
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->Cuenta->count_todas(),
                "recordsFiltered" => $this->Cuenta->count_filtro($search_value,$order_column,$order_dir,$estatus, $ruc, $factura, $anho),
                "data"            => array(),
            );
    
            foreach ($data as $Deuda) {
                // Verificar si Factura_ID no es nulo
				$option = '';
                if (!is_null($Deuda->Factura_ID)) {
                    $Parcial_todo = $Deuda->Total_Parcial;
                    $mpendiente = preg_replace("/[^0-9]/", "", $Deuda->Monto_Total_Factura);
            
                    // if (!empty($Deuda->crestado)) {
                        if ($Parcial_todo == 0 && $Deuda->esta != 2 && $Deuda->Cuotas_NoPagado == 0) {
                            $this->Cuenta->res_factura($Deuda->Factura_ID, 2);
                        } elseif ($Deuda->Cuotas_NoPagado > 0) {
							$option = '<div class="pull-right hidden-phone">
                                <a data-toggle="tooltip" data-placement="top" id="listadeuda" class="btn btn-primary btn-xs" href="javascript:void(0);" title="Listar" onclick="listar_deudas(' . "'" . $Deuda->Factura_ID . "'" . ')">
                                <i class="fa fa-list-ol"></i> Listar</a>
                            </div>';
                            $this->Cuenta->res_factura($Deuda->Factura_ID, 1);
                        }
                    // }
                    $mpagado = (($Parcial_todo + $Deuda->Total_Pagado) );
            
                    $row = array();
                    $row[] = $Deuda->Cuotas_Pagadas;
                    $row[] = $Deuda->Cuotas_NoPagado;
            
                    if (is_null($Deuda->idCliente)) {
                        $row[] = $this->mi_libreria->getSubString($Deuda->Razon_Social, 15) . ' (' . $this->mi_libreria->getSubString($Deuda->Vendedor, 15) . ')';
                    } else {
                        $row[] = $this->mi_libreria->getSubString($Deuda->Nombres, 15) . ' (' . $this->mi_libreria->getSubString($Deuda->Ruc, 15) . ')';
                    }
                    $row[] = $Deuda->Num_Factura_Venta;

                    $row[] = $Deuda->Monto_Total_Factura;
                    $row[] = number_format($Parcial_todo, 0, ',', '.') . '&nbsp; Gs.';
                    $row[] = number_format(($mpendiente - $mpagado), 0, ',', '.') . '&nbsp; Gs.';
                    $row[] = number_format(($mpagado), 0, ',', '.') . '&nbsp; ₲.';
                    $row[] = $option;
            
                    $output['data'][] = $row;
                }
            }
            
            // $this->output->enable_profiler(TRUE);
            
            echo json_encode($output);
        } else {
            $this->load->view('errors/404.php');
        }
    }
    
	public function ajax_list_pagadas()
	{
		if ($this->input->is_ajax_request()) 
		{
			// Captura los nuevos parámetros
			$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : '';
			$ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
			$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
			$anho = isset($_POST['anho']) ? $_POST['anho'] : '';


			$list = $this->Cuenta->get_Deuda_($estatus, $ruc, $factura, $anho);
			$data = array();
			$no = $_POST['start'];
        	// $this->output->enable_profiler(TRUE);
			foreach ($list as $Deuda) 
			{


			$Parcial_todo = $Deuda->Monto_Cobrado ;
     		$mpendiente =  $Deuda->Importe_Total_Cuota - $Parcial_todo;

			if (!empty($Deuda->crestado)) {
					if ($mpendiente == 0 && $Deuda->crestado != 1) {
						$this->Cuenta->Estado_1($Deuda->id,$Deuda->idcobro, 0);
					}elseif ($mpendiente > 0 && $Deuda->crestado != 3) {
						$this->Cuenta->Estado_3($Deuda->id,$Deuda->idcobro, 0);
					}
					elseif ($Parcial_todo == 0 && $Deuda->crestado != 0) {
						   $this->Cuenta->Estado_0($Deuda->id);
					}
			}



					$no++;
					$row = array();


					$row[] = $Deuda->Cuota_Numero;
					$row[] = 'Nº '. $Deuda->Num_Recibo;// comprovante
					$row[] = $Deuda->Fecha_Pago;// comprovante
					$row[] =  $this->mi_libreria->getSubString($Deuda->Nombres, 10).' ('.$this->mi_libreria->getSubString($Deuda->Ruc, 10).')';
					$row[] =  number_format($Deuda->Importe_Total_Cuota,0,',','.');
					$row[] =  number_format($Parcial_todo,0,',','.');
					$row[] = number_format($mpendiente,0,',','.');
					$row[] =  $Deuda->id;
					$data[] = $row;




			}

			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Cuenta->count_todas_(),
				"recordsFiltered" => $this->Cuenta->count_filtro_($estatus, $ruc, $factura, $anho),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function listar_deudas($idFactura_Venta)
	{
		if ($this->input->is_ajax_request()) 
		{
			$list = $this->Cuenta->get_Deuda_list($idFactura_Venta);
            $recordsTotal    = $this->Cuenta->count_todas_list($idFactura_Venta);
            $recordsFiltered = $this->Cuenta->count_filtro_list($idFactura_Venta);
			$fecha = date("Y-m-d");
			$data = array();
        	// $this->output->enable_profiler(TRUE);
			foreach ($list as $Deuda) 
			{
				// $deudaJson =  htmlspecialchars(json_encode($Deuda), ENT_QUOTES, 'UTF-8') ;
				$row = array(); 
				$total_caja_pagos = $Deuda->total_caja_pagos ; // total cobrado por una cuota
				$montoPendiente = $Deuda->inporte_total - $total_caja_pagos;
				$row[] = $Deuda->Num_cuota;
				$row[] = 'Recibo Nº ' . $Deuda->Num_Recibo;
				$row[] = $this->mi_libreria->getSubString($Deuda->Nombres, 10) . ' (' . $this->mi_libreria->getSubString($Deuda->Vendedor, 10) . ')';
				$row[] = number_format($Deuda->inporte_total, 0, ',', '.');
				$row[] = number_format($total_caja_pagos, 0, ',', '.');
				// Cambiar el estilo según la fecha de vencimiento
				$row[] = '<i class="badge ' . ($Deuda->Fecha_Ven >= $fecha ? 'bg-green' : 'bg-red') . '" style="text-align:left"><strong>' . $Deuda->Fecha_Ven . '</strong></i>';
				$row[] = '
				<div class="pull-right hidden-phone">
					<a class="btn btn-xs" href="javascript:void(0);" title="" id="" data-toggle="tooltip" onclick="" data-original-title="Estado">
						<input type="checkbox" name="seleccionar_cuota[]" 
						value="' . $Deuda->id . '" 
						data-monto="' . $montoPendiente . '"
						data-recordsTotal="' . $recordsTotal . '"


						>  
					</a>
					
					<a data-toggle="tooltip" data-placement="top" id="Atras" class="btn btn-xs btn-danger" href="#" title="Atras" onclick="atras()">
						<i class="fa fa-reply"></i> 
					</a>
					<a data-toggle="tooltip" data-placement="top"  class="btn btn-success btn-xs" href="#" title="Pagar" onclick="calculateIndividualQuota(\'' . $Deuda->id . '\', \'' . $montoPendiente . '\', \'' . $recordsTotal . '\', \'' . $Deuda->idCliente . '\')">
						<i class="fa fa-external-link"></i> Cobrar
					</a>
				</div>';
				$row[] = $Deuda;
				



				$data[] = $row;


			}

			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $recordsTotal,
				"recordsFiltered" => $recordsFiltered,
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function lis_deuda($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Cuenta->lis_deuda(array('idCuenta_Corriente_Cliente' => $id));
			echo json_encode($data);
		}else{
			$this->load->view('errors/404.php');
		}
	}
	public function afabor($value)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Cuenta->afabor(array('Cliente_idCliente' => $value));
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}

	public function pagar_todo()
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('pagar_todo') == FALSE)
				{
						$data = array(
							'efectivo'        => form_error('efectivo'),
							'cuenta'          => form_error('cuenta'),
							'numcheque'       => form_error('numcheque'),
							'importe'         => form_error('importe'),
							'fecha_pago'      => form_error('fecha_pago'),
							'inputmontopagar' => form_error('inputmontopagar'),
							'inputdiferencia' => form_error('inputdiferencia'),
							'agremicuenta'    => form_error('agremicuenta'),
							'agustar'         => form_error('agustar'),
							'res'         => 'error');
					echo json_encode($data);
				}else{
					$id              = $this->security->xss_clean( $this->input->post('id',FALSE));
					$idF             = $this->security->xss_clean( $this->input->post('idF',FALSE));
					$totalrous       = $this->security->xss_clean( $this->input->post('totalrous',FALSE));
					$crEstado        = $this->security->xss_clean( $this->input->post('crEstado',FALSE));
					$cfEstado        = $this->security->xss_clean( $this->input->post('cfEstado',FALSE));
					$idCliente       = $this->security->xss_clean( $this->input->post('idCliente',FALSE));
					$fecha_pago      = $this->security->xss_clean( $this->input->post('fecha_pago',FALSE));
					$agremicuenta    = $this->security->xss_clean( $this->input->post('agremicuenta',FALSE));
					$efectivo        = $this->security->xss_clean( $this->input->post('efectivo',FALSE));
					$cuenta          = $this->security->xss_clean( $this->input->post('cuenta',FALSE));
					$numcheque       = $this->security->xss_clean( $this->input->post('numcheque',FALSE));
					$cuenta_bancaria = $this->security->xss_clean( $this->input->post('cuenta_bancaria',FALSE));
					$ch_o            = $this->security->xss_clean( $this->input->post('ch_o',FALSE));
					$inputmontopagar = $this->security->xss_clean( $this->input->post('inputmontopagar',FALSE));
					$inputdiferencia = $this->security->xss_clean( $this->input->post('inputdiferencia',FALSE));
					$agustar         = $this->security->xss_clean( $this->input->post('agustar',FALSE));
					$importe         = $this->security->xss_clean( $this->input->post('importe',FALSE));
					$_data =  $this->Cuenta->pagar_todo(
					$crEstado,
					$cfEstado,
					$totalrous ,
					$idF,
					$id,
					$idCliente,
					$fecha_pago,
					$agremicuenta,
					$efectivo,
					$cuenta,
					$numcheque,
					$cuenta_bancaria,
					$inputmontopagar,
					$inputdiferencia,
					$agustar,
					$importe);
					// $this->output->enable_profiler(TRUE);
					echo json_encode($_data);

				}

		}
	}


	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
		   $data = $this->Cuenta->pagos_referentes($id);
		   if ($data) {
		   	echo json_encode($data);
		   }

		}
	}

	public function cuenta_bancaria()
	{
		if ($this->input->is_ajax_request()) {
			$data =  $this->Cuenta->che_ingreso();
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}


	//Delete one item
	public function ajax_delete()
	{
		if ($this->input->is_ajax_request()) {
		$id  =	$this->security->xss_clean( $this->input->post('id1',FALSE)); // idcuenta cooreinte cliente 
		$id2 =	$this->security->xss_clean( $this->input->post('id2',FALSE));// idCaja_Cobros
		$id3 =	$this->security->xss_clean( $this->input->post('id3',FALSE));// Factura_Venta_idFactura_Venta
		$id4 =	$this->security->xss_clean( $this->input->post('id4',FALSE));// CCC_idCuenta_Corriente_Cliente

		$cantidad =	$this->security->xss_clean( $this->input->post('cantidad',FALSE));// cantidad restante
		$tipopago =	$this->security->xss_clean( $this->input->post('tipopago',FALSE));// tipopago
		$monto =	$this->security->xss_clean( $this->input->post('monto',FALSE));// tipopago


		$data = $this->Cuenta->delete($id,$id2,$id3,$id4,$cantidad,$tipopago,$monto);

			// $this->output->enable_profiler(TRUE);
			
		echo json_encode($data);
		}
	}
}

/* End of file Deuda_cliente.php */
/* Location: ./application/controllers/Deuda_cliente.php */
