<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Venta.php                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: Christian <Christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/09/12 15:47:44 by Christian         #+#    #+#             */
/*   Updated: 2016/09/12 15:48:25 by Christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Venta extends CI_Controller {
    private $campos = [
        'Cliente', 'comprobante', 'orden', 'montofinal', 'fecha', 'inicial',
        'tipoComprovante', 'cuotas', 'condicion', 'fletes', 'observaciones','Direccion',
        'finalcarrito', 'Estado','finaldescuento', 'descuento', 'cart_total', 'lesiva', 'checControl', 'iva_diez',
        'iva_cinco','Ticket'];
	private $data_ajaxdata = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Venta_Model",'Venta');
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
                $this->cart->destroy();
				$arraycss = array(
					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',
					'toastem'        			   =>'bower_components/jQueryToastem/',
                    'ventacss'       =>'bower_components/script_vista/',

				);
				$arrayjs = array(
					'jquery.mask'         =>'content/plugins/jQuery-Mask-Plugin-master/dist/',
					'select2'                  =>'bower_components/select2/dist/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
                     'jquery.inputmask'      =>'content/plugins/input-mask/',
                    //  'script_compra'       =>'bower_components/script_vista/',
		    	);
				$this->mi_css_js->init_css_js($arraycss,'css');

				$this->mi_css_js->init_css_js($arrayjs,'js');

		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		    		// $this->output->enable_profiler(TRUE);
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////


									$data       = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Venta/Venta.php');
									$this->load->view('Venta/modal_pago.php');

									$this->load->view('Home/footerlite.php',FALSE); 
									$this->load->view('Venta/scriptVenta.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(18);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
											$data       = array (	"Alerta" => $this->Producto->get_alert(),
																	'data_view' => $variable);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Venta/Venta.php');
									           $this->load->view('Venta/modal_pago.php');

												$this->load->view('Home/footerlite.php',FALSE);
												$this->load->view('Venta/scriptVenta.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}

				}

			}

	}
	public function Listar()
	{ 
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$Value = 0;
				$arraycss = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
                		'bootstrap-datetimepicker.min' =>'content/plugins/pikear/css/');
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array(
					'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
                    'moment'                   =>'content/plugins/pikear/js/',
                		'bootstrap-datetimepicker' =>'content/plugins/pikear/js/',);
				$this->mi_css_js->init_css_js($arrayjs,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Venta/ListadodeVentas.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Venta/scriptlistado.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {

					$variable =  $this->Model_Menu->octener(18);
                  
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
						$data       = array (	"Alerta" => $this->Producto->get_alert(),'data_view' => $variable);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Venta/ListadodeVentas.php');
												$this->load->view('Home/footer.php');
												$this->load->view('Venta/scriptlistado.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						// $this->load->view('errors/404.php');
					}

				}

			}

	}

public function select2remote($value='')
{
    if ($this->input->is_ajax_request()) {
        $q = $this->input->post('q', TRUE);
        $peso = $this->input->post('peso', TRUE);
        $code = $this->input->post('code', TRUE);
        $cantidad = $this->input->post('cantidad', TRUE);


        $cache_key = 'select2remote_' . md5($q);
        $data = $this->cache->file->get($cache_key);
        
        if (!$data) {
            $query = $this->db->query("CALL BuscarProductos(?)", array($q));
            
            if ($query->num_rows() > 0) {
                $data = array(
                    'total_count' => $query->num_rows(),
                    'incomplete_results' => false,
                    'items' => $query->result(),
                    'query' => 'BuscarProductos'

                );
                
                $this->cache->file->save($cache_key, $data, 60 * 5); // Guardar en caché por 5 minutos
            }
        }
        $name;
        if ($data && $data['total_count'] > 0) {
            $producto = $data['items'][0];
			$precio = $producto->precio; // Precio por defecto
			$Cantidad_max = $producto->Cantidad_A;
			$peso_formateado = $cantidad;

            if ($peso) {
                // Si se proporciona el peso, calcula el precio basado en el peso
				$peso_num = floatval($peso) / 1000; // Divide por 1000 para obtener el formato "99.999"
				$peso_formateado = number_format($peso_num, 3, '.', '');
				
	        }
			if ($producto->Medida == 'kg') {
				$Cantidad_max = $producto->Cantidad_A * 1000 ;
			}
            $opciones =  array(
                'iva'   => $producto->Iva,
                'descuento' =>  '',
                'poriginal' => $producto->precio,
                'predesc'   => 0,
                'Cantidad_max'       =>  $producto->Cantidad_A,
                't_f'       => 0,
                'Medida'       => $producto->Medida,
				'id'       => $producto->id


            );

            $datacart = array(
                'id'      => $code ,
                'qty'     => $peso_formateado,
				'price' => $producto->precio,
                'name'    => $producto->full_name,
                'descuento'    => $producto->Descuento,
                'options' => $opciones
            );
            
            $this->cart->insert($datacart);
        }

        echo json_encode($data);
    } else {
        show_404();
    }
}
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) {
			$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : '';
			$ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
			$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
			$anho = isset($_POST['anho']) ? $_POST['anho'] : '';

		$list = $this->Venta->get_Venta($estatus, $ruc, $factura, $anho);
        // $this->output->enable_profiler(TRUE);
        
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $Venta) {
			    $datos='venta';
				$no++;
				$row   = array();
				if ($Venta->Estado == 0) {
						if ($Venta->Tipo_Venta == 0 ) { // voleta
						$row[] = 'Recibo Contado Nº '.$Venta->Ticket;// comprovante
						}elseif ($Venta->Tipo_Venta == 1 ) { // factura
							$row[] = 'Factura Contado Nº '. $Venta->Num_Factura_Venta;// comprovante
						}
						$row[] =  $this->mi_libreria->getSubString($Venta->Nombres.' '.$Venta->Apellidos, 15);
						$row[] = $Venta->Fecha_expedicion.'  '.$Venta->Hora;
						$row[] = '<i class="badge bg-success" style="text-align:left"><strong> Pagado</strong></i>';
						$row[] = $this->mi_libreria->getSubString(number_format( $Venta->Monto_Total,0,'.',','),20 ). '&nbsp; ₲.';
						if ($Venta->Flete == 1) {
							$row[] = '<i class="badge bg-red" style="text-align:left"><strong>Si</strong></i>';
						}else{
						     $row[] = 'No';
						}
						if ($Venta->Contado_Credito == 1) {
							$row[] = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Venta->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/venta/'.$Venta->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
						<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Anular" onclick="anular('."'".$Venta->idFactura_Venta."'".','."'".$Venta->Insert."'".')">
						<i class="fa fa-ban"></i></a>
							</div>';
						} else{
							$row[] = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Venta->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/venta/'.$Venta->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							</div>';
						}
				}elseif ($Venta->Estado == 1) {
							if ($Venta->Tipo_Venta == 0 ) { // voleta
							$row[] = 'Recibo Credito Nº '.$Venta->Ticket;;// comprovante
							}elseif ($Venta->Tipo_Venta == 1 ) { // factura
							$row[] = 'Factura Credito Nº '. $Venta->Num_Factura_Venta;// comprovante
							}
						$row[] =  $this->mi_libreria->getSubString($Venta->Nombres.' '.$Venta->Apellidos, 15);
						$row[] = $Venta->Fecha_expedicion.'  '.$Venta->Hora;
						$row[] = '<i class="badge bg-green" style="text-align:left"><strong> Pago Parcial</strong></i>';
						$row[] = $this->mi_libreria->getSubString(number_format( $Venta->Monto_Total,0,'.',','),20 ). '&nbsp; ₲.';
							if ($Venta->Flete == 1) {
								$row[] = '<i class="badge bg-red" style="text-align:left"><strong>Si</strong></i>';
							}else{
							     $row[] = 'No';
							}
						$row[] = '<div class="pull-right hidden-phone">
											<a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Venta->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" href="Reporte_exel/venta/'.$Venta->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
						</div>';
				}elseif ($Venta->Estado == 2) {
											if ($Venta->Tipo_Venta == 0 ) { // voleta
						$row[] = 'Recibo Nº '.$Venta->Ticket;// comprovante
						}elseif ($Venta->Tipo_Venta == 1 ) { // factura
						$row[] = 'Factura Nº '. $Venta->Num_Factura_Venta;// comprovante
						}
						if ($Venta->Contado_Credito == 1) {
							$row[] =  $this->mi_libreria->getSubString($Venta->Nombres.' '.$Venta->Apellidos, 15);
							$row[] = $Venta->Fecha_expedicion.'  '.$Venta->Hora;
							$row[] = '<i class="badge bg-red" style="text-align:left"><strong> No Pagado</strong></i>';
							$row[] = $this->mi_libreria->getSubString(number_format( $Venta->Monto_Total,0,'.',','),20 ). '&nbsp; ₲.';
							if ($Venta->Flete == 1) {
								$row[] = '<i class="badge bg-red" style="text-align:left"><strong>Si</strong></i>';
							}else{
							     $row[] = 'No';
							}
							$row[] = '<div class="pull-right hidden-phone">
											<a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Venta->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" href="Reporte_exel/venta/'.$Venta->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Anular" onclick="anular('."'".$Venta->idFactura_Venta."'".','."'".$Venta->Insert."'".')">
							<i class="fa fa-ban"></i></a>
							<div class="btn-group ">
		                      <button class="btn btn-info btn-xs bg-yellow color-palette" data-toggle="dropdown" title="Estado"  aria-expanded="false"><i class="fa fa-bars"></i></button>
		                      <ul class="dropdown-menu pull-right  " role="menu">
		                          <li class="text-red"> <a class="text-red" href="javascript:void(0);" onclick="_pagado('."'".$Venta->idFactura_Venta."'".')">Cambiar a Pagado</a></li>
		                      </ul>
		                    </div>
							</div>';
						}else{
							$row[] =  $this->mi_libreria->getSubString($Venta->Nombres.' '.$Venta->Apellidos, 15);
							$row[] = $Venta->Fecha_expedicion.'  '.$Venta->Hora;
							$row[] = '<i class="badge bg-red" style="text-align:left"><strong> No Pagado</strong></i>';
							$row[] = $this->mi_libreria->getSubString(number_format( $Venta->Monto_Total,0,'.',','),20 ). '&nbsp; ₲.';
							if ($Venta->Flete == 1) {
								$row[] = '<i class="badge bg-red" style="text-align:left"><strong>Si</strong></i>';
							}else{
							     $row[] = 'No';
							}
							$row[] = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$Venta->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" href="Reporte_exel/venta/'.$Venta->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
							<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Anular" onclick="anular('."'".$Venta->idFactura_Venta."'".','."'".$Venta->Insert."'".')">
							<i class="fa fa-ban"></i></a>
							</div>';
						}
				}
				$row[] = $Venta->idFactura_Venta;
				$data[] = $row;
		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Venta->count_todas(),
			"recordsFiltered" => $this->Venta->count_filtro($estatus, $ruc, $factura, $anho),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}

	}
	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
		   $data = $this->Venta->detale(array('Factura_Venta_idFactura_Venta' => $id));
		   if ($data) {
		   	echo json_encode($data);
		   }

		}
	}

    public function getcliente()
    {
        $this->db->where('idCliente != 1');
        $query = $this->db->get('Cliente');
        return $query->result();
    }

	public function list_productos($id=null)
	{
		if ($this->input->is_ajax_request()) {
			   $data =  $this->Venta->list_productos();
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}
	//Update one item
	public function list_orden( $id )
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			echo $this->Venta->list_orden(array('Cliente_idCliente' => $id));



		}else{
			$this->load->view('errors/404.php');
		}
	}


	public function loader($id = NULL)
	{
			if ($id==NULL) {
			 $this->load->view('Venta/cart_get.php');
			}else{
             $this->cart->destroy();
             $this->load->view('Venta/cart_get.php');
			}


	}
	/**
	 * [agregar_item description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function agregar_item($value = null)
	{
		$item = 0;
		if ($this->input->is_ajax_request()) {
				if ($value == null) {
					if ($this->input->post('max',FALSE)>$this->input->post('val',FALSE)) {
							$name = str_replace('_',' ', ucfirst(strtolower($this->input->post('name'))));
                            $id = $this->input->post('id', TRUE);
                            $iva = $this->input->post('iva', TRUE);
                            $precio = $this->input->post('precio', TRUE);
                            $nombre = $this->input->post('nombre', TRUE);
                            $Descuento = $this->input->post('descuento', TRUE);

                            $opciones =  array(
                                'iva'   => $iva,
                                'descuento' => '',
                                'poriginal' => $precio,
                                'predesc'   => 0,
                                'Cantidad_max'       => $this->input->post('max',FALSE),
                                't_f'   => 0,
								'Medida'       =>'',
								'id'      => $id ,
                            );


							$data = array(
							'id'      => $id ,
							'qty'     => '1',
							'price'   => $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$precio), 10)),
							'name'    => $name,
							'descuento'    => $Descuento,
							'options' => $opciones
							);
							$this->cart->insert($data);
			   				$item++;
							echo json_encode($item );
					}else{
				    $res = array(
				    	'ress' => 'error',
				    	'max' => "Articulo no Agregado... Cantidad maxima en stock:".'  '.$this->input->post('max',FALSE),
				    	 );
				    echo json_encode($res);
					}

				}else{  /// agregar orden
					$data =   $this->Venta->item_orden(array('Orden_idOrden' => $value));
					echo json_encode($data);
				}
		} else {
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [delete_item description]
	 * @param  [type] $rowid [description]
	 * @return [type]        [description]
	 */
	public function delete_item($rowid)
	{
		if ($this->input->is_ajax_request()) {
		$this->cart->remove($rowid);
        }else{
			$this->load->view('errors/404.php');
		}
	}
	
	/**
	 * [update_rowid description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function update_rowid( $id = NULL )
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_error_delimiters('','');
			if ($this->form_validation->run('update_rowid') == FALSE)
			{
					$data = array(
						'qty'   => form_error('qty'),
						'res'         => 'error');
					echo json_encode($data);
			}else{
				if ($id !== NULL) {
					$data = array(
					'rowid' => $this->security->xss_clean($id),
					'qty'   => $this->security->xss_clean( $this->input->post('qty',FALSE)),
					);
					$datta = $this->cart->update($data);
					echo json_encode($datta);
				}
			}

        }else{
			show_404();
		}
	}

	/**
	 * [update2_rowid description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function update2_rowid( $id = NULL )
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_error_delimiters('','');
			if ($this->form_validation->run('update2_rowid') == FALSE)
			{
					$data = array(
						'qty'   => form_error('qty'),
						'price' => form_error('price'),
						'res'   => 'error');
				echo json_encode($data);
			}else{
				if ($id !== NULL) {
					$data   = array(
						'rowid' => $this->security->xss_clean($id),
						'qty'   => $this->security->xss_clean( $this->input->post('qty',FALSE)),
						'price' => $this->security->xss_clean( $this->input->post('price',FALSE)),
					);
					$datta  = $this->cart->update($data);
					echo json_encode($datta);
				}
			}

        }else{
			show_404();
		}
	}
	/**
	 * [_pagado cambiar estado de factura]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function pagado($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Venta->_pagado($id);
			echo json_encode($data);
		}
	}
	/**
	 * [_pagado cambiar estado de factura]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function no_pagado($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Venta->no_pagado($id);
			echo json_encode($data);
		}
	}

	/**
	 * [no_pagado description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function anular($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$condicion = $this->input->post('num', TRUE);
			$data = $this->Venta->anular($id,$condicion);
			echo json_encode($data);
		}
	}

	public function ajax_add()
	{
		if ($this->input->is_ajax_request()) {
			    $this->data_ajaxdata = $this->security->xss_clean($this->input->post(null, FALSE));
				// $tipoComprovante =	$this->security->xss_clean( $this->input->post('tipoComprovante',FALSE));
				$this->form_validation->set_rules('Cliente', 'Cliente', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
				// if ( $tipoComprovante == 1) {
				// $this->form_validation->set_rules('comprobante', 'Numero', 'trim|callback_check_num|required|min_length[1]|max_length[15]|strip_tags'   );
				// }else{
				// $this->form_validation->set_rules('comprobante', 'Numero', 'trim|min_length[1]|max_length[15]|strip_tags'   );
				// }
				$this->form_validation->set_rules('orden', 'Orden', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('tipoComprovante', 'Comprovante', 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('fecha', 'Fecha', 'trim|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('condicion', 'Pago', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('checControl', 'Flete', 'trim|numeric|min_length[1]|max_length[2]|strip_tags');
				$this->form_validation->set_rules('cuotas', 'Cuotas', 'trim|numeric|required|min_length[1]|max_length[2]|strip_tags');
				$this->form_validation->set_rules('fletes', 'Fletes', 'trim|numeric|min_length[1]|max_length[15]|strip_tags|limite['.$this->input->post('cart_total').']');
				$this->form_validation->set_rules('observaciones', 'Descripcion', 'trim|min_length[1]|max_length[50|strip_tags');
				$this->form_validation->set_rules('Direccion', 'Direccion', 'trim|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('finalcarrito', 'Final', 'trim|numeric|required|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('finaldescuento', 'Descuento', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('cart_total', 'Totalmonto', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
						'Cliente'       => form_error('Cliente'),
						'comprobante'     => form_error('comprobante'),
						'orden'           => form_error('orden'),
						'tipoComprovante' => form_error('tipoComprovante'),
						'fecha'           => form_error('fecha'),
						'condicion'       => form_error('condicion'),
						'cuotas'          => form_error('cuotas'),
						'fletes'          => form_error('fletes'),
						'observaciones'   => form_error('observaciones'),
						'Direccion'   => form_error('Direccion'),
						'finalcarrito'    => form_error('finalcarrito'),
						'finaldescuento'       => form_error('finaldescuento'),
						'cart_total'      => form_error('cart_total'),
						'checControl'     => form_error('checControl'),
						'res'             => 'error');
					echo json_encode($data);
				}else{

					   $cartVenta = $this->registrarVenta();

					$_data = $this->Venta->venta($cartVenta);
                    echo json_encode($_data);

				}

		}
	}
    public function registrarVenta() {
        $newdada_compra = [];
        foreach ($this->campos as $campo) {
            $newdada_compra[$campo] = $this->obtener_input($campo);
        }
        return $newdada_compra;
    }

    public function obtener_input($campo) {
        return $this->data_ajaxdata[$campo];
    }

	public function ajax_add_pago($value='')
	{
		if ($this->input->is_ajax_request()) {
            $val =  $this->security->xss_clean($this->input->post(null, FALSE));
            // var_dump($this->security->xss_clean($this->input->post(null, FALSE)));
            $this->data_ajaxdata = $val;
            $parcial1 = $val['parcial1'];
            $parcial2 = $val['parcial2'];
            $parcial3 = $val['parcial3'];
            $parcial4 = $val['parcial4'];
            switch (true) {
                case !empty($parcial1):
                    for ($i=1; $i <= $val['val']; $i++) { 
                        $this->form_validation->set_rules('EF'.$i, 'Moneda', 'trim|min_length[1]|max_length[15]|strip_tags');
                        }
                    break;
                case !empty($parcial2):
                    if ($val['numcheque'] > 0 || $val['efectivotxt'] > 0) {
                            $this->form_validation->set_rules('numcheque', 'numerocheque', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
                            $this->form_validation->set_rules('fecha_pago', 'fecha', 'trim|required|min_length[1]|max_length[14]|strip_tags');
                            $this->form_validation->set_rules('efectivotxt', 'Efectivo', 'trim|required|min_length[1]|max_length[15]|strip_tags');
                        }
                    break;
                case !empty($parcial3):
                        $this->form_validation->set_rules('efectivoTarjeta', 'Tarjeta', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
                        $this->form_validation->set_rules('Tarjeta', 'tipo', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
                    break;
                case !empty($parcial4):
                        $this->form_validation->set_rules('multi', 'Fabor', 'trim|required|min_length[1]|max_length[15]|strip_tags');
                    break;
            }

			    $this->form_validation->set_rules('Totalparclal', 'Toal', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
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
					'Clproveedoriente'         => form_error('proveedor'),
					'fecha_pago'      => form_error('fecha_pago'),
					'efectivotxt'        => form_error('efectivotxt'),
					'efectivoTarjeta' => form_error('efectivoTarjeta'),
					'Tarjeta'         => form_error('Tarjeta'),
					'multi'           => form_error('multi'),
					'res'      => 'error');
					echo json_encode($data);
				}else{
                    // $this->output->enable_profiler(TRUE);
					$data = $this->Venta->pago_($val,$parcial1,$parcial2,$parcial3,$parcial4 );
                   
						echo json_encode($data);
						         
          
				}
			}else {
				$this->load->view('errors/404.php');
			}
	}

	public function check_num($check_num)
	{
	if ($this->Venta->check_num($check_num)) {
			$this->form_validation->set_message('check_num', "$check_num ya fue registrado debes canbiar!!");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}



	public function venta_null( $offset = 0 )
	{
			if ($this->db->count_all_results('Empresa') == 0) {
				$data  = $this->session->userdata('usuario');
				$this->load->view('Home/head.php',$data, FALSE);
				$this->load->view('Home/header.php',$data, FALSE);
				$this->load->view('Home/section2.php',$data, FALSE);
				$this->load->view('Home/sidebar.php',$data, FALSE);
				$this->load->view('Home/pie_js.php');
				$this->load->view('Home/script.php');

			} else {

		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista null Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'Proveedor' =>$this->db->get('Proveedor')->result());
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Venta/Anul.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Venta/script_tabla_nul.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(19);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
												$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		'Proveedor' =>$this->db->get('Proveedor')->result());
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Venta/Anul.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Venta/script_tabla_nul.php');
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
	public function anulados()
	{
		if ($this->input->is_ajax_request()) {
			
		$list = $this->Venta->get_anul();
		$data = array();
		$no = $_POST['start'];
		$datos='lisventanul';
		foreach ($list as $ress) {
				$no++;
				$Monto =  number_format( $ress->Monto_Total,0,'.',',');
				$row   = array();
				if ($ress->Tipo_Venta == 0 ) { // voleta
					$row[] = 'Recibo Nº ' . $ress->Ticket;;// comprovante
				}elseif ($ress->Tipo_Venta == 1 ) { // factura
					$row[] = 'Factura Nº '. $ress->Num_Factura_Venta;// comprovante
				}
				$row[] =  $this->mi_libreria->getSubString($ress->Nombres.' '.$ress->Apellidos, 15);
				$row[] = $this->mi_libreria->getSubString($Monto,20 ). '&nbsp; ₲.';
				$row[] = '<div class="pull-right hidden-phone">
												<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$ress->idFactura_Venta."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top"  href="Reporte_exel/lisventanul/'.$ress->idFactura_Venta.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
						</div>';
				$row[] = $ress->idFactura_Venta;
				$data[] = $row;
		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Venta->count_todasanul(),
			"recordsFiltered" => $this->Venta->count_filtroanul(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}

	}

	public function formapago($value='',$id = '')
	{

                switch ($value) {
                    case '3': // cuenta
					$this->db->select('cf.idCuenta_Fabor, cf.Monto');
					$this->db->from('Cuenta_Fabor cf');
					$this->db->where('cf.Estado', 1);
					$this->db->where('cf.Cliente_Empresa', 1);
					$this->db->where('Cliente_idCliente', $id);
					$query = $this->db->get();
					$options = '<option value=""></option>';
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $key => $value) {
							$formattedMonto = number_format($value->Monto, 0, '.', ',');
							$options .= '<option value="' . $value->Monto . ',' . $value->idCuenta_Fabor . '"> ' . $formattedMonto . ' ₲S.</option>';
						}
					}
					echo $options;
                break;
				// case '5': // ch
				// 	$this->load->view('Venta/Formapago/1.php',FALSE);
				// 	$this->load->view('Venta/Formapago/forscript.php',FALSE);
				// 	break;
			}
	
	}
	function obtenerMinimo($numero) {
		if (strpos($numero, '.') !== false) {
			// Si el número tiene un punto decimal
			$partes = explode('.', $numero);
			$parteEntera = $partes[0];
			if ($parteEntera > 0) {
				// Si la parte entera es mayor a 0, devolver 1
				return 1;
			} else {
				// Si la parte entera es igual o menor a 0, devolver 0.01
				return 0.01;
			}
		} else {
			// Si el número no tiene un punto decimal, devolver 1
			return 1;
		}
	}
	function Additem() {
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id', TRUE);
			$iva = $this->input->post('iva', TRUE);
			$precio = $this->input->post('precio', TRUE);
			$nombre = $this->input->post('nombre', TRUE);
			$Descuento = $this->input->post('descuento', TRUE);
			$stock = $this->input->post('stock', TRUE);
			$code = $this->input->post('code', TRUE);
			$medida = $this->input->post('medida', TRUE);

				$opciones =  array(
					'iva'   => $iva,
					'descuento' => '', // solo descuento aplicado 
					'poriginal' => $precio,
					'predesc'   => 0,
					'Cantidad_max'       =>$stock,
					't_f'       => 0,
					'Medida'       => $medida,
					'id'      => $id,
				);
				

				$datacart = array(
					'id'      => $code ,
					'qty'     => $this->obtenerMinimo($stock),
					'price'   => $precio,
					'name'    => $nombre,
					'descuento'    => $Descuento,
					'options' => $opciones
				);
				$this->cart->insert($datacart);

				$this->load->view('Venta/cart_get.php');

		} else {
			show_404();
		}
	}

	public function campoSearch() {
		$searchTerm = $this->input->post('searchTerm');
		$cache_Search = 'campoSearch_' . md5($searchTerm);
		$data = $this->cache->file->get($cache_Search);

		if (!$data) {
			$query = $this->db->query("CALL BuscarProductosAll(?)", array($searchTerm));

			if ($query->num_rows() > 0) {
				$data = $query->result();
				$this->cache->file->save($cache_Search, $data, 60 * 5);
			}
		}

		echo json_encode($data);
	}


}


/* End of file Venta.php */
/* Location: ./application/controllers/Venta.php */
