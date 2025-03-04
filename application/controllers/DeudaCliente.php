<?php defined('BASEPATH') OR exit('No direct script access allowed');
class DeudaCliente extends CI_Controller {

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
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'Moneda' => $Moneda,
															'Cliente' =>$this->getcliente());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Pagos_Cobros/Cobro/DeudaC_vista.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Pagos_Cobros/Cobro/script_D_C.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(24);
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
								$this->load->view('Pagos_Cobros/Cobro/DeudaC_vista.php');
								$this->load->view('Home/sidebar.php',FALSE);
								$this->load->view('Home/pie_js.php');
								$this->load->view('Pagos_Cobros/Cobro/script_D_C.php');
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
				$list = $this->Cuenta->get_Deuda();
				$data = array();
				$no = $_POST['start'];
				foreach ($list as $Deuda) 
				{
				$no++;
				$Parcial_todo = $this->Cuenta->sum_pagos_tods($Deuda->idCuenta_Corriente_Cliente);
				$xx =  round($Deuda->inporte_total) ;
			    $mpendiente = round( $xx - $Parcial_todo) ;
			    		// if ($Parcial_todo == 0 && $Deuda->esta != 2 && $Deuda->Num_cuota > 0) {
			    		// 	$this->Cuenta->res_factura($Deuda->idFactura_Venta,2);
			    		// }elseif ($Parcial_todo > 0 && $Deuda->esta != 1 && $Deuda->Num_cuota > 0) {
			    		// 	$this->Cuenta->res_factura($Deuda->idFactura_Venta,1);
			    		// }
						if ($Deuda->Num_cuota == 1 ) {
							if ($mpendiente > 0)
							{
							$row = array();
							$row[] = $Deuda->Num_cuota;
							if (is_null($Deuda->idCliente)) {
							$row[] =  $this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')';
							}else{
							$row[] =  $this->mi_libreria->getSubString($Deuda->Nombres, 15).' ('.$this->mi_libreria->getSubString($Deuda->Apellidos, 15).')';
							}
							$row[] =  number_format($xx,0,',','.').'&nbsp; ₲.';
							$row[] =  number_format($Parcial_todo,0,',','.').'&nbsp; ₲.';
							$row[] =  number_format($mpendiente,0,',','.').'&nbsp; ₲.';
									$row[] = '<div class="pull-right hidden-phone">
									<a data-toggle="tooltip" data-placement="top" id="listadeuda" class="btn btn-primary btn-xs" href="javascript:void(0);" title="Listar" onclick="listar_deudas('."'".$Deuda->idFactura_Venta."'".','."'".$Deuda->Num_cuota."'".')">
									<i class="fa fa-list-ol"></i> Listar</a>
									</div>';
							$data[] = $row;
							}
						}else{
							$row = array();
							if ($Deuda->Num_cuota == 0) {
							  $row[] = 1;
							}else{
							  $row[] = $Deuda->Num_cuota;
							}

							if (is_null($Deuda->idCliente)) {
							$row[] =  $this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')';
							}else{
							$row[] =  $this->mi_libreria->getSubString($Deuda->Nombres, 15).' ('.$this->mi_libreria->getSubString($Deuda->Apellidos, 15).')';
							}
						    $row[] =   number_format($xx,0,',','.').'&nbsp; ₲.';
						    $row[] =  number_format($Parcial_todo,0,',','.').'&nbsp; ₲.';
							$row[] =  number_format($mpendiente,0,',','.').'&nbsp; ₲.';
									$row[] = '<div class="pull-right hidden-phone">
									<a data-toggle="tooltip" data-placement="top" id="listadeuda" class="btn btn-primary btn-xs" href="javascript:void(0);" title="Listar" onclick="listar_deudas('."'".$Deuda->idFactura_Venta."'".','."'".$Deuda->Num_cuota."'".')">
									<i class="fa fa-list-ol"></i> Listar</a>
									</div>';

							$data[] = $row;
						}
				}

				$output = array(
					"draw"            => $_POST['draw'],
					"recordsTotal"    => $this->Cuenta->count_todas(),
					"recordsFiltered" => $this->Cuenta->count_filtro(),
					"data"            => $data,
				);
				//output to json format

		        echo json_encode($output);

		} else {
			$this->load->view('errors/404.php');
		}
	}

    public function getcliente()
    {
        $this->db->where('idCliente != 1');
        $query = $this->db->get('Cliente');
        return $query->result();
    }

    	public function formapago($value='',$id = '')
	{
		if ($this->input->is_ajax_request()) {
			switch ($value) {
				case '4': // cuenta
							$this->db->select('idCuenta_Fabor,cf.Monto');
							$this->db->from('Cuenta_Fabor cf');
							$this->db->where('cf.Estado = 1 AND cf.Cliente_Empresa = 1');
							$this->db->where('Cliente_idCliente', $id);
							$query = $this->db->get()->result();
				    $data = array(
				    	'cuenta' => $query,
				    );
					$this->load->view('Pagos_Cobros/Cobro/Formapago/4.php',$data ,FALSE);
					break;
				case '5': // ch
					$this->load->view('Pagos_Cobros/Cobro/Formapago/1.php',FALSE);
					$this->load->view('Pagos_Cobros/Cobro/Formapago/forscript.php',FALSE);
					break;
			}
		}else{
			$this->load->view('errors/404.php');
		}
	}
	
	public function lis_deuda($id)
	{
		if ($this->input->is_ajax_request()) {
			$this->output->enable_profiler(TRUE);
			$data =  $this->Cuenta->lis_deuda(array('idCuenta_Corriente_Cliente' => $id));
			echo json_encode($data);
		}else{
			$this->load->view('errors/404.php');
		}
	}

	public function cobroDeuda($id)
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
                    // var_dump($this->input->post());
                    // exit();
					$i_d              =  $this->input->post('id',FALSE);
					$idF             = $this->security->xss_clean( $this->input->post('idF',FALSE));
					$totalrous       = $this->security->xss_clean( $this->input->post('totalrous',FALSE));
					$crEstado        = $this->security->xss_clean( $this->input->post('crEstado',FALSE));
					$cfEstado        = $this->security->xss_clean( $this->input->post('cfEstado',FALSE));
					$idCliente       = $this->security->xss_clean( $this->input->post('idCliente',FALSE));

					$Totalparclal    =	$this->security->xss_clean( $this->input->post('Totalparclal',FALSE));
					$vueltototal     =	$this->security->xss_clean( $this->input->post('vueltototal',FALSE));
					$si_no           =	$this->security->xss_clean( $this->input->post('si_no',FALSE));
					$ajustado        =	$this->security->xss_clean( $this->input->post('ajustado',FALSE));

					$numcheque       =	$this->security->xss_clean( $this->input->post('numcheque',FALSE));
					$fecha_pago      =	$this->security->xss_clean( $this->input->post('fecha_pago',FALSE));
                    $cuenta_bancaria =	$this->security->xss_clean( $this->input->post('cuenta_bancaria',FALSE));

					$efectivoTarjeta =	$this->security->xss_clean( $this->input->post('efectivoTarjeta',FALSE));
					$Tarjeta         =	$this->security->xss_clean( $this->input->post('Tarjeta',FALSE));
					$matris          =	$this->security->xss_clean( $this->input->post('matris',FALSE));
					$matriscuanta    =	$this->security->xss_clean( $this->input->post('matriscuanta',FALSE));
					$Acheque_tercero =	$this->security->xss_clean( $this->input->post('Acheque_tercero',FALSE));
					$Acheque    =	$this->security->xss_clean( $this->input->post('Acheque',FALSE));

					$parcial1 = $this->security->xss_clean( $this->input->post('parcial1',FALSE));
					$parcial2 = $this->security->xss_clean( $this->input->post('parcial2',FALSE));
					$parcial3 = $this->security->xss_clean( $this->input->post('parcial3',FALSE));
					$parcial4 = $this->security->xss_clean( $this->input->post('parcial4',FALSE));
			        $val = $this->security->xss_clean( $this->input->post('val',FALSE));
 					$monto = $this->security->xss_clean( $this->input->post('monto',FALSE));
 					$cuotaN = $this->security->xss_clean( $this->input->post('cuotaN',FALSE));
 					$cuotaPrecio = $this->security->xss_clean( $this->input->post('cuotaPrecio',FALSE));


						$moneda = array();
						if (!empty($parcial1)) {
						for ($i=1; $i <= $val ; $i++) {
						 	$Moneda  =	$this->security->xss_clean( $this->input->post('Moneda'.$i,FALSE));
						 	$cambio  =	$this->security->xss_clean( $this->input->post('cam'.$i,FALSE));
						 	$cambiado  =	$this->security->xss_clean( $this->input->post('montoCambiado'.$i,FALSE));
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

					$_data =  $this->Cuenta->cobroDeuda(
					$i_d 
					,$idF   
					,$totalrous 
					,$crEstado   
					,$cfEstado
					,$idCliente    
					,$Totalparclal   
					,$vueltototal 
					,$si_no 
					,$ajustado 
					,$numcheque 
					,$fecha_pago 
					,$efectivoTarjeta
					,$Tarjeta 
					,$matriscuanta 
					,$parcial1
					,$parcial2
					,$parcial3
					,$parcial4 
					,$moneda, 
                    $matris,
                    $Acheque_tercero,
                    $Acheque,
                    $monto,
                    $cuotaN,
                    $id,
                    $cuotaPrecio
					);
					// $this->output->enable_profiler(TRUE);
					echo json_encode(array("status" => TRUE));

				}

		}
	}

	public function pagar_parcial()
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('pagar_parcial') == FALSE)
				{
						$data = array(
							'efectivo'        => form_error('efectivo'),
							'cuenta'          => form_error('cuenta'),
							'numcheque'       => form_error('numcheque'),
							'cuenta_bancaria' => form_error('cuenta_bancaria'), 
							'importe'         => form_error('importe'),
							'fecha_pago'      => form_error('fecha_pago'),
							'inputmontopagar' => form_error('inputmontopagar'),
							'inputdiferencia' => form_error('inputdiferencia'),
							'agremicuenta'    => form_error('agremicuenta'),
							'agustar'         => form_error('agustar'),
							'res'         => 'error');
					echo json_encode($data);
				}else{
					$i_d              =  $this->input->post('id',FALSE);
					$idF             = $this->security->xss_clean( $this->input->post('idF',FALSE));
					$totalrous       = $this->security->xss_clean( $this->input->post('totalrous',FALSE));
					$crEstado        = $this->security->xss_clean( $this->input->post('crEstado',FALSE));
					$cfEstado        = $this->security->xss_clean( $this->input->post('cfEstado',FALSE));
					$idCliente       = $this->security->xss_clean( $this->input->post('idCliente',FALSE));

					$Totalparclal    =	$this->security->xss_clean( $this->input->post('Totalparclal',FALSE));
					$vueltototal     =	$this->security->xss_clean( $this->input->post('vueltototal',FALSE));
					$si_no           =	$this->security->xss_clean( $this->input->post('si_no',FALSE));
					$ajustado        =	$this->security->xss_clean( $this->input->post('ajustado',FALSE));

					$numcheque       =	$this->security->xss_clean( $this->input->post('numcheque',FALSE));
					$fecha_pago      =	$this->security->xss_clean( $this->input->post('fecha_pago',FALSE));

					$efectivoTarjeta =	$this->security->xss_clean( $this->input->post('efectivoTarjeta',FALSE));
					$Tarjeta         =	$this->security->xss_clean( $this->input->post('Tarjeta',FALSE));
					$matris          =	$this->security->xss_clean( $this->input->post('matris',FALSE));
					$matriscuanta    =	$this->security->xss_clean( $this->input->post('matriscuanta',FALSE));

					$parcial1 = $this->security->xss_clean( $this->input->post('parcial1',FALSE));
					$parcial2 = $this->security->xss_clean( $this->input->post('parcial2',FALSE));
					$parcial3 = $this->security->xss_clean( $this->input->post('parcial3',FALSE));
					$parcial4 = $this->security->xss_clean( $this->input->post('parcial4',FALSE));
			        $val = $this->security->xss_clean( $this->input->post('val',FALSE));



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
						$this->output->enable_profiler(TRUE);
					$_data =  $this->Cuenta->pagar_parcial(
					$i_d 
					,$idF   
					,$totalrous 
					,$crEstado   
					,$cfEstado
					,$idCliente    
					,$Totalparclal   
					,$vueltototal 
					,$si_no 
					,$ajustado 
					,$numcheque 
					,$fecha_pago 
					,$efectivoTarjeta
					,$Tarjeta 
					,$matris 
					,$matriscuanta 
					,$parcial1
					,$parcial2
					,$parcial3
					,$parcial4 
					,$moneda, $matris  );
							echo json_encode(array("status" => TRUE));

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
		$id  =	$this->security->xss_clean( $this->input->post('id',FALSE));
		$id2 =	$this->security->xss_clean( $this->input->post('id2',FALSE));//verificar si es efectivo cheque o cuesnta
		$id3 =	$this->security->xss_clean( $this->input->post('id3',FALSE));//solo para cheque
		$id4 =	$this->security->xss_clean( $this->input->post('id4',FALSE));//si es el ultimo pago
		$data = $this->Cuenta->delete($id,$id2,$id3,$id4);
		echo json_encode($data);
		}
	}
}

/* End of file Deuda_cliente.php */
/* Location: ./application/controllers/Deuda_cliente.php */
