<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Deuda_empresa.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: christian <christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/08/30 13:04:01 by christian         #+#    #+#             */
/*   Updated: 2016/08/30 13:07:19 by christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Deuda_Empresa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Deuda_empresa_Model",'Deuda');
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
					// 'ventacss'       =>'bower_components/script_vista/'
				);
				$this->mi_css_js->init_css_js($array,'css');


				$array = array(
                    'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					'select2'                  =>'bower_components/select2/dist/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
                      'scriptDeudaEmpresa'       =>'bower_components/script_vista/',
		    	);
				$this->mi_css_js->init_css_js($array,'js');

		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
						$data       = array (	"Alerta" => $this->Producto->get_alert(),
												'Moneda' => $Moneda,
												'Banco' =>$this->db->get('Gestor_Bancos')->result(),
												);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Compra/DeudaE_vista.php');
									$this->load->view('Home/footer.php');
									// $this->load->view('Compra/script_D_E.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(13);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
     			        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
						$data       = array (	"Alerta" => $this->Producto->get_alert(),
												'data_view' => $variable,
												'Moneda' => $Moneda,
												'Banco' =>$this->db->get('Gestor_Bancos')->result(),
												);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Compra/DeudaE_vista.php');
												$this->load->view('Home/footer.php');
												// $this->load->view('Compra/script_D_E.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}
				}

			}

	}

	public function ajax_list_deuda_empresa()
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

			$data = $this->Deuda->get_Deuda($length, $start, $search_value, $order_column, $order_dir,$estatus, $ruc, $factura, $anho);

	
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Deuda->count_todas(),
				"recordsFiltered" => $this->Deuda->count_filtro($search_value,$order_column,$order_dir,$estatus, $ruc, $factura, $anho),
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
                            $this->Deuda->res_factura($Deuda->Factura_ID, 2);
                        } elseif ($Deuda->Cuotas_NoPagado > 0) {
							$option = '<div class="pull-right hidden-phone">
                                <a data-toggle="tooltip" data-placement="top" id="listadeuda" class="btn btn-primary btn-xs" href="javascript:void(0);" title="Listar" onclick="listar_deudas(' . "'" . $Deuda->Factura_ID . "'" . ')">
                                <i class="fa fa-list-ol"></i> Listar</a>
                            </div>';
                            if ($Parcial_todo > 0 && $Deuda->esta !=1) {
                               $this->Deuda->res_factura($Deuda->Factura_ID, 1);
                            }
							
                        }
                    // }
                    $mpagado = (($Parcial_todo + $Deuda->Total_Pagado));
            
                    $row = array();
                    $row[] = $Deuda->Cuotas_Pagadas;
                    $row[] = $Deuda->Cuotas_NoPagado;
                    $row[] = $this->mi_libreria->getSubString($Deuda->Razon_Social, 15) . ' (' . $this->mi_libreria->getSubString($Deuda->Ruc, 15) . ')';
                    $row[] = $Deuda->Num_factura_Compra.$Deuda->Ticket;
                    $row[] = $Deuda->Monto_Total_Factura. '&nbsp; Gs.';
					
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


			$list = $this->Deuda->get_Deuda_($estatus, $ruc, $factura, $anho);
			$data = array();
			$no = $_POST['start'];
        	// $this->output->enable_profiler(TRUE);
			foreach ($list as $Deuda) 
			{


				$Parcial_todo = $Deuda->Monto_Pagado ;
				$mpendiente =  $Deuda->Importe_Total_Cuota - $Parcial_todo;

			if (!empty($Deuda->crestado)) {
					if ($mpendiente == 0 && $Deuda->crestado != 1) {
						$this->Cuenta->Estado_1($Deuda->id,$Deuda->idCaja_Pagos, 0);
					}elseif ($mpendiente > 0 && $Deuda->crestado != 3) {
						$this->Cuenta->Estado_3($Deuda->id,$Deuda->idCaja_Pagos, 0);
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
				"recordsTotal"    => $this->Deuda->count_todas_(),
				"recordsFiltered" => $this->Deuda->count_filtro_($estatus, $ruc, $factura, $anho),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

    public function listar_deudas($idFactura_Compra)
{
    if ($this->input->is_ajax_request()) 
    {
        // Obtén la lista de deudas con la suma de pagos
        $list = $this->Deuda->get_Deuda_list($idFactura_Compra);
        $recordsTotal    = $this->Deuda->count_todas_list($idFactura_Compra);
        $recordsFiltered = $this->Deuda->count_filtro_list($idFactura_Compra);
        // $this->output->enable_profiler(TRUE);

        // exit;
        
        $fechaActual = new DateTime(); // Obtiene la fecha actual
        $data = array();
        
        foreach ($list as $Deuda) 
        {
            $row = array(); 
            $total_caja_pagos = $Deuda->total_caja_pagos;
            $montoPendiente = $Deuda->inporte_total - $total_caja_pagos;
        
            // Formatea la fecha de vencimiento
            $fechaVencimiento = DateTime::createFromFormat('d-m-Y', $Deuda->Fecha_Ven);
        
            // Compara la fecha actual con la fecha de vencimiento
            $badgeClass = ($fechaActual < $fechaVencimiento) ? 'bg-green' : 'bg-red';
            
            // Construye la fila de datos
            $row[] = $Deuda->Num_cuota;
            $row[] = 'Recibo Nº ' . $Deuda->Num_Recibo;
            $row[] = $this->mi_libreria->getSubString($Deuda->Razon_Social, 10) . ' (' . $this->mi_libreria->getSubString($Deuda->Vendedor, 10) . ')';
            $row[] = number_format($Deuda->inporte_total, 0, ',', '.');
            $row[] = number_format($total_caja_pagos, 0, ',', '.');
            
            // Estilo según la fecha de vencimiento
            $row[] = '<i class="badge ' . $badgeClass . '" style="text-align:left"><strong>' . $Deuda->Fecha_Ven .  '</strong></i>';
            $row[] = '
            <div class="pull-right hidden-phone">
                <a class="btn btn-xs" href="javascript:void(0);" title="" id="" data-toggle="tooltip" onclick="" data-original-title="Estado">
                   <input type="checkbox" name="seleccionar_cuota[]" value="'. $Deuda->idCuenta_Corriente_Empresa . '">  
                </a>
                
                <a data-toggle="tooltip" data-placement="top" id="Atras" class="btn btn-xs btn-danger" href="#" title="Atras" onclick="atras()">
                    <i class="fa fa-reply"></i>
                </a>
                <a data-toggle="tooltip" data-placement="top" class="btn btn-success btn-xs" href="#" title="Pagar" onclick="item_cobrar(\''.$Deuda->idCuenta_Corriente_Empresa.'\', \''.$montoPendiente.'\', \''.$recordsTotal.'\', \''.$Deuda->idProveedor.'\')">
                    <i class="fa fa-external-link"></i> Pagar
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

        // Salida en formato JSON
        echo json_encode($output);
    } else {
        $this->load->view('errors/404.php');
    }
}
	public function lis_deuda($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Deuda->lis_deuda(array('idCuenta_Corriente_Empresa' => $id));
			echo json_encode($data);
		}else{
			$this->load->view('errors/404.php');
		}
	}
	public function afabor($value)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Deuda->afabor(array('Proveedor_idProveedor' => $value));
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}

	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
		   $data = $this->Deuda->pagos_referentes($id);
		   if ($data) {
		   	echo json_encode($data);
		   }

		}
	}

	public function cuenta_bancaria()
	{

			echo  $this->Deuda->che_ingreso();

	}


	//Delete one item
	public function ajax_delete()
	{
		if ($this->input->is_ajax_request()) {
			$params = array(
				'id1' => $this->security->xss_clean($this->input->post('id1', FALSE)), // idCCE idCuenta_Corriente_Empresa
				'id2' => $this->security->xss_clean($this->input->post('id2', FALSE)), // idCA idCaja_Pagos
				'id3' => $this->security->xss_clean($this->input->post('id3', FALSE)), // idFC idFactura_Compra
				'id4' => $this->security->xss_clean($this->input->post('id4', FALSE)), // idCF CCE_idCuenta_Corriente_Empresa
				'id5' => $this->security->xss_clean($this->input->post('id5', FALSE)), // idM idMovimientos
				'tipopago' => $this->security->xss_clean($this->input->post('tipopago', FALSE)), // idT idTarjeta
				'cantidad' => $this->security->xss_clean($this->input->post('cantidad', FALSE)) ,// si es 1 es el último registro del pago
				'monto' => $this->security->xss_clean($this->input->post('monto', FALSE)), // idT idTarjeta
			);
			$data = $this->Deuda->delete($params);
			echo json_encode($data);
		}
	}




}

/* End of file Deuda_Empresa.php */
/* Location: ./application/controllers/Deuda_Empresa.php */
