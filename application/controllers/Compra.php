<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Compra.php                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: Christian <Christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/09/12 15:47:44 by Christian         #+#    #+#             */
/*   Updated: 2016/09/12 15:48:25 by Christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Compra extends CI_Controller {
    private $campos = [
        'proveedor', 'comprobante', 'orden', 'montofinal', 'fecha', 'inicial',
        'tipoComprovante', 'cuotas', 'fletes', 'observac',
        'finalcarrito', 'Estado', 'descuento', 'cartotal', 'lesiva', 'iva_diez','montofinal',
        'iva_cinco', 'timbrado', 'virtual'
    ];
	private $data_ajaxdata = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Compra_Model",'Compra');
		$this->load->library('Cart');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			}

	}




	public function index( $offset = 0 )
	{
			$count = $this->db->count_all_results('Empresa');
	       if ($count == 0) {
	            redirect('Home','refresh');
			} else {

				if ($this->session->userdata('idcaja') === NULL && $this->session->userdata('idUsuario') != 1) {
					redirect('CA1', 'refresh');
				}
				$Value = 0;
  				$this->cart->destroy();
				$arraycss = array(
					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',
					'toastem'        			   =>'bower_components/jQueryToastem/',


				);
				$arrayjs = array(
					'jquery.mask'         =>'content/plugins/jQuery-Mask-Plugin-master/dist/',
					'select2'                  =>'bower_components/select2/dist/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
                     'jquery.inputmask'      =>'content/plugins/input-mask/',
                     'script_compra'       =>'bower_components/script_vista/',
		    	);
				$this->mi_css_js->init_css_js($arraycss,'css');

				$this->mi_css_js->init_css_js($arrayjs,'js');


		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															// 'Banco' =>$this->db->get('Gestor_Bancos')->result(),
															// 'Proveedor' =>$this->db->get('Proveedor')->result()
														);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Compra/Compra.php');
									$this->load->view('Home/footer.php');
									// $this->load->view('Compra/script.php');
									// $this->load->view('Compra/Formapago/forscript.php',FALSE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {

					$variable =  $this->Model_Menu->octener(14);
 
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
						$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		// 'Banco' =>$this->db->get('Gestor_Bancos')->result(),
																		'data_view' => $variable,
																		// 'Proveedor' =>$this->db->get('Proveedor')->result()
																	);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Compra/Compra.php');
												$this->load->view('Home/footer.php');
												$this->load->view('Compra/script.php');
												// $this->load->view('Compra/Formapago/forscript.php',FALSE);
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
									$this->load->view('Compra/ListadodeCompras.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Compra/scriptlistado.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {

					$variable =  $this->Model_Menu->octener(14);
                  
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
						$data       = array (	"Alerta" => $this->Producto->get_alert(),'data_view' => $variable);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Compra/ListadodeCompras.php');
												$this->load->view('Home/footer.php');
												$this->load->view('Compra/scriptlistado.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						// $this->load->view('errors/404.php');
					}

				}

			}

	}
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) {
			$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : '';
			$ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
			$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
			$anho = isset($_POST['anho']) ? $_POST['anho'] : '';
	
			$list = $this->Compra->get_Compra($estatus, $ruc, $factura, $anho);
	
			$data = array();
			$no = $this->input->post('start');
			foreach ($list as $Compra) {
				$no++;
				$row = array();
				$datos = 'compra';
				$comprobante = $Compra->Tipo_Compra == 0 ? "Recibo: N.{$Compra->Ticket}" : "Factura Nº {$Compra->Num_factura_Compra}";
				$razon_vendedor = $this->mi_libreria->getSubString("{$Compra->Razon_Social}-{$Compra->Vendedor}", 25);
				$fecha_hora = "{$Compra->Fecha_expedicion} {$Compra->Hora}";
				$monto_total = "{$Compra->Monto_Total}&nbsp; ₲.";
				$acciones_comunes = "<a class='btn btn-info btn-xs' href='javascript:void(0);' title='Exportar a PDF' onclick='pdf_exporte(\"{$datos}\", \"{$Compra->idFactura_Compra}\")'>
					<i class='fa fa-file-pdf-o' aria-hidden='true'></i> </a>
					<a class='btn btn-success btn-xs' href='Reporte_exel/compra/{$Compra->idFactura_Compra}' title='Exportar a EXEL' >
					<i class='fa fa-file-excel-o' aria-hidden='true'> </i></a>";
				$Contado_Credito = ($Compra->Contado_Credito == 0) 
					? "<span class='label label-primary'>Contado</span>" 
					: "<span class='label label-warning'>Credito</span>";
	
				$boton_accion = "<a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='top'  href='javascript:void(0);' title='Eliminar' onclick='gestionCompra(\"{$Compra->idFactura_Compra}\", \"{$Compra->Insert}\", \"Eliminar\")'><i class='fa fa-trash'></i></a>";
				switch ($Compra->Estado) {
					case 0:
						$estado = "<i class='badge bg-success' style='text-align:left'><strong> Pagado</strong></i>";
						$acciones = $Compra->Contado_Credito == 0 
							? "{$acciones_comunes}<a class='btn btn-warning btn-xs' data-toggle='tooltip' data-placement='top'  href='javascript:void(0);' title='Anular' onclick='gestionCompra(\"{$Compra->idFactura_Compra}\", \"{$Compra->Insert}\", \"Anular\")'><i class='fa fa-ban'></i></a>{$boton_accion}</div>" 
							: "{$acciones_comunes}{$boton_accion}</div>";
						break;
					case 1:
						$estado = "<i class='badge bg-green' style='text-align:left'><strong> Pago Parcial</strong></i>";
						$acciones = "{$acciones_comunes}</div>";
						break;
					case 2:
						$estado = "<i class='badge bg-red' style='text-align:left'><strong> No Pagado</strong></i>";
						$acciones = $Compra->Contado_Credito == 1 
							? "{$acciones_comunes}<a class='btn btn-warning btn-xs' data-toggle='tooltip' data-placement='top'  href='javascript:void(0);' title='Anular' onclick='gestionCompra(\"{$Compra->idFactura_Compra}\", \"{$Compra->Insert}\", \"Anular\")'><i class='fa fa-ban'></i></a><div class='btn-group '><button class='btn btn-info btn-xs bg-yellow color-palette' data-toggle='dropdown' title='Estado'  aria-expanded='false'><i class='fa fa-bars'></i></button><ul class='dropdown-menu pull-right  ' role='menu'><li class='text-red'> <a class='text-red' data-toggle='tooltip' data-placement='top' href='javascript:void(0);' onclick='_pagado(\"{$Compra->idFactura_Compra}\")'>Cambiar a Pagado</a></li></ul></div>{$boton_accion}</div>" 
							: "{$acciones_comunes}<a class='btn btn-warning btn-xs' data-toggle='tooltip' data-placement='top'  href='javascript:void(0);' title='Anular' onclick='gestionCompra(\"{$Compra->idFactura_Compra}\", \"{$Compra->Insert}\", \"Anular\")'><i class='fa fa-ban'></i></a>{$boton_accion}</div>";
						break;
						case 4:
							$estado = "<i class='badge bg-danger' style='text-align:left'><strong>Anulados</strong></i>";
							$acciones = "{$acciones_comunes}</div>";
							break;

				}
				$row = array($comprobante, $razon_vendedor, $Contado_Credito, $fecha_hora, $estado, $monto_total, $acciones, $Compra->idFactura_Compra);
				array_push($data, $row);
			}
			$output = array(
				"draw" => $this->input->post('draw'),
				"recordsTotal" => $this->Compra->count_todas(),
				"recordsFiltered" => $this->Compra->count_filtro($estatus, $ruc, $factura, $anho),
				"data" => $data,
			);
	
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}
	
	
    

	public function detale($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
		   $data = $this->Compra->detale(array('Factura_Compra_idFactura_Compra' => $id));
		   if ($data) {
		   	echo json_encode($data);
		   }

		}
	}

	public function list_productos($id)
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$data =  $this->Compra->list_productos(array('Proveedor_idProveedor' => $id));
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
			$data =  $this->Compra->list_orden(array('Proveedor_idProveedor' => $id));
			if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}


	public function loader($id = NULL)
	{

			if ($id==NULL) {
			 $this->load->view('Compra/cart_get.php');
			}else{
             $this->cart->destroy();
             $this->load->view('Compra/cart_get.php');
			}

	}
	public function select2remote($value='')
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->get('id', TRUE);
			$q = $this->input->get('q', TRUE);
			$cache_key = 'select2remote_' . md5($id . '_' . $q);
			$data = $this->cache->get($cache_key);
			if (!$data) {
				$limit = $id > 0 ? 1 : 1;
				$this->db->select('idProducto as id, Codigo, Nombre as full_name, replace(Precio_Costo,"_","") as precio, Cantidad_A, Descuento, Iva');
				if ($id > 0) {
					$this->db->select('Proveedor_idProveedor');
					$this->db->join('Producto_has_Proveedor ph', 'Producto.idProducto = ph.Producto_idProducto', 'inner');
				}
				$this->db->from('Producto')
					->where('Si_No', false);
					if ($id > 0) {
						$this->db->where('Proveedor_idProveedor', $id);
					}
					$this->db->where('Codigo', $q)
					// ->or_where('Nombre', $q)

					
					->group_by('idProducto')
					->limit($limit);
				$query = $this->db->get();
				$data = array(
					'total_count' => $query->num_rows(),
					'incomplete_results' => false,
					'items' => $query->result()
				);
				$this->cache->save($cache_key, $data, 60 * 5); // Guardar en caché por 5 minutos
			}
			if ($data['total_count'] > 0) {
				$producto = $data['items'][0];
				$opciones =  array(
					'iva'   => $producto->Iva,
					'descuento' => 0,
					'poriginal' => $producto->precio,
					'predesc'   => 0,
					't_f'       => 0
				);
				$datacart = array(
					'id'      => $producto->id,
					'qty'     => '1',
					'price'   => $producto->precio,
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

	function Additem() {
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id', TRUE);
			$iva = $this->input->post('iva', TRUE);
			$precio = $this->input->post('precio', TRUE);
			$nombre = $this->input->post('nombre', TRUE);
			$Descuento = $this->input->post('descuento', TRUE);
			


				$opciones =  array(
					'iva'   => $iva,
					'descuento' => 0,
					'poriginal' => $precio,
					'predesc'   => 0,
					't_f'       => 0
				);
				$datacart = array(
					'id'      => $id,
					'qty'     => '1',
					'price'   => $precio,
					'name'    => $nombre,
					'descuento'    => $Descuento,
					'options' => $opciones
				);
				$this->cart->insert($datacart);

				$this->load->view('Compra/cart_get.php');

		} else {
			show_404();
		}
	}

	// Método separado para realizar la consulta a la base de datos
	private function obtenerResultados($searchTerm, $limit) {
		$this->db->select('idProducto as id, Codigo as CodigoBarra, Nombre as full_name, replace(Precio_Costo,"_","") as precio, Cantidad_A as stock, Descuento, Iva,Img');
		$this->db->from('Producto');
		$this->db->where('Si_No', false);
		$this->db->group_start();
		$this->db->like('Codigo', $searchTerm,'both');
		$this->db->or_like('Nombre', $searchTerm,'both');
		$this->db->group_end();
		$this->db->group_by('idProducto');
		$this->db->limit($limit);
		return $this->db->get()->result();
	}

	public function campoSearch() {
		// Obtén el término de búsqueda enviado desde la solicitud AJAX
		$searchTerm = $this->input->post('searchTerm');
		$limit = 15;
		
		// Verificar si los resultados ya han sido obtenidos previamente
		if (!isset($this->resultados)) {
			$this->resultados = $this->obtenerResultados($searchTerm, $limit);
		}
		
		
		echo json_encode($this->resultados);
	}


	public function agregar_item($value = null)
	{
		$item = 0;
		if ($this->input->is_ajax_request()) {
				if ($value == null) {

					$precie = intval(preg_replace('/[^0-9]+/', '',$this->input->post('precio',FALSE)), 10);
					$name = str_replace('_',' ', ucfirst(strtolower($this->input->post('name'))));

					$opciones =  array(
						'iva'   => $this->security->xss_clean( $this->input->post('iva',FALSE)),
						'descuento' => 0,
						'poriginal' => $precie,
						'predesc'   => 0,
						't_f'       => 0
					);
					$data = array(
						'id'      => $this->security->xss_clean( $this->input->post('id',FALSE)),
						'qty'     => '1',
						'price'   => $precie, 
						'name'    =>$name, 
						'descuento'    => $precie, 
						'options' => $opciones
					);
					$this->cart->insert($data);
	   				$item++;
					echo json_encode($item );
				}else{  /// agregar orden
					$data =   $this->Compra->item_orden(array('Orden_idOrden' => $value));
					echo json_encode($data);
				}
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function delete_item($rowid)
	{
		if ($this->input->is_ajax_request()) {
		$this->cart->remove($rowid);
        }else{
			show_404();
		}
	}
	
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

	public function pagado($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Compra->_pagado($id);
			echo json_encode($data);
		}
	}

	public function no_pagado($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Compra->no_pagado($id);
			echo json_encode($data);
		}
	}


	public function gestionCompra()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id', TRUE);
			$condicion = $this->input->post('num', TRUE);
			$stado = $this->input->post('stado', TRUE);

			$data = $this->Compra->gestionCompra($id,$condicion,$stado);
			echo json_encode($data);
		}
	}

	public function ajax_add()
	{
		if ($this->input->is_ajax_request()) {
			$this->data_ajaxdata = $this->security->xss_clean($this->input->post(null, FALSE));
			

				$this->form_validation->set_rules('proveedor', 'Proveedor', 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('comprobante', 'Numero', 'trim|required|min_length[1]|max_length[15]|strip_tags|callback_check_num[' . $this->input->post('proveedor',FALSE). ']');

				$this->form_validation->set_rules('idOrden', 'Orden', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('montofinal', 'Monto final', 'trim|required|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('tipoComprovante', 'Comprovante', 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('fecha', 'Fecha', 'trim|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('inicial', 'Inicio', 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('condicion', 'Pago', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
				$this->form_validation->set_rules('cuotas', 'Cuotas', 'trim|numeric|required|min_length[1]|max_length[2]|strip_tags');
				$this->form_validation->set_rules('fletes', 'Fletes', 'trim|numeric|min_length[1]|max_length[15]|strip_tags|limite['.$this->input->post('cartotal',FALSE).']');
				$this->form_validation->set_rules('observaciones', 'Descripcion', 'trim|min_length[1]|max_length[50|strip_tags');
				$this->form_validation->set_rules('finalcarrito', 'Final', 'trim|numeric|required|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric|min_length[1]|max_length[15]|strip_tags|limite['.$this->input->post('cartotal',FALSE).']');
				$this->form_validation->set_rules('cartotal', 'Totalmonto', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');

				$this->form_validation->set_rules('timbrado', 'Timbrado', 'trim|required|min_length[8]|max_length[8]|strip_tags');
				$this->form_validation->set_rules('vence', 'Vencimiento', 'trim|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('virtual', 'Virtual', 'trim|numeric|min_length[1]|max_length[1]|strip_tags');


				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
						'proveedor'       => form_error('proveedor'),
						'comprobante'     => form_error('comprobante'),
						'orden'           => form_error('orden'),
						'montofinal'      => form_error('montofinal'),
						'tipoComprovante' => form_error('tipoComprovante'),
						'fecha'           => form_error('fecha'),
						'inicial'         => form_error('inicial'),
						'condicion'       => form_error('condicion'),
						'cuotas'          => form_error('cuotas'),
						'fletes'          => form_error('fletes'),
						'observaciones'   => form_error('observaciones'),
						'finalcarrito'    => form_error('finalcarrito'),
						'descuento'       => form_error('descuento'),
						'cart_total'      => form_error('cart_total'),

						'timbrado'        => form_error('timbrado'),
						'vence'           => form_error('vence'),
						'virtual'         => form_error('cart_tovirtualtal'),

						'res'             => 'error');
					echo json_encode($data);
				}else{
                // var_dump($this->data_ajaxdata);
				// exit;
                $cartCoompra = $this->registrarCompra();

                
                // if ($newdada_compra['Estado'] == 2) {
                //     $this->session->set_userdata($newdada_compra);
				// 	var_dump(strlen(serialize($_SESSION)));
				// 	exit;


                    $_data = $this->Compra->comprar($cartCoompra);
                    echo json_encode($_data);
                // } else {
                //     $this->session->set_userdata($newdada_compra);
                //     echo json_encode('1');
                // }
				}

		}
	}
    public function registrarCompra() {
        $newdada_compra = [];
        foreach ($this->campos as $campo) {
            $newdada_compra[$campo] = $this->obtener_input($campo);
        }
        return $newdada_compra;
    }

    public function obtener_input($campo) {
        return $this->data_ajaxdata[$campo];
    }

	public function ajax_add_pago()
	{
		if ($this->input->is_ajax_request()) {
        $val = $this->security->xss_clean($this->input->post(null, FALSE));
		$this->data_ajaxdata = $val;
        $parcial1 = $val['parcial1'];
        $parcial2 = $val['parcial2'];
        $parcial3 = $val['parcial3'];
        $parcial4 = $val['parcial4'];
     switch (true) {
        case !empty($parcial1):
            for ($i = 1; $i <= $val['val']; $i++) {
                $this->form_validation->set_rules('EF' . $i, 'Moneda', 'trim|min_length[1]|max_length[15]|strip_tags');
				}
            break;
        case !empty($parcial2):
            if ($val['numcheque'] > 0 || $val['efectivo'] > 0) {
					$this->form_validation->set_rules('numcheque', 'numerocheque', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				    $this->form_validation->set_rules('fecha_pago', 'fecha', 'trim|required|min_length[1]|max_length[14]|strip_tags');
				    // $this->form_validation->set_rules('efectivo', 'Efectivo', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				}
            break;
        case !empty($parcial3):
				// $this->form_validation->set_rules('efectivoTarjeta', 'Tarjeta', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Tarjeta', 'tipo', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
            break;
        case !empty($parcial4):
				$this->form_validation->set_rules('multi', 'Fabor', 'trim|required|min_length[1]|max_length[15]|strip_tags');
            break;
	}
			    $this->form_validation->set_rules('Totalparclal', 'Toal', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'EF1'             => form_error('EF1'),
					'EF2'             => form_error('EF2'),
					'EF3'             => form_error('EF3'),
					'EF4'             => form_error('EF4'),
					'EF5'             => form_error('EF5'),
					'EF6'             => form_error('EF6'),
					'Totalparclal'    => form_error('Totalparclal'),
					'numcheque'       => form_error('numcheque'),
					'Cliente'         => form_error('Cliente'),
					'fecha_pago'      => form_error('fecha_pago'),
					// 'efectivo'        => form_error('efectivo'),
					// 'efectivoTarjeta' => form_error('efectivoTarjeta'),
					'Tarjeta'         => form_error('Tarjeta'),
					'multi'           => form_error('multi'),
					'res'             => 'error'
				);
				echo json_encode($data);
			} else {
				
                // $cartCoompra = $this->registrarCompra();

				// $input_fields = array(
				// 	'vueltototal', 'si_no', 'ajustado',
				// 	'numcheque', 'fecha_pago', 'efectivo', 'cuenta_bancaria', 'Acheque_tercero', 'Acheque',
				// 	'efectivoTarjeta', 'Tarjeta',
				// 	'matriscuanta', 'matris'
				// );
				// $cleaned_data = array();
				// foreach ($input_fields as $field) {
				// 	$cleaned_data[$field] = $val[$field];
				// }
				$moneda = array();
				if (!empty($parcial1)) {
					for ($i = 1; $i <=  $val['val']; $i++) {
						$MontoMoneda = $val['MontoMoneda' . $i];
						if ($MontoMoneda > 0) {
							$moneda[] = array(
								'Moneda' => $val['Moneda' . $i],
								'cambio' => $val['MCambio' . $i],
								'cambiado' => $val['montoCambiado' . $i],
								'signo' => $val['signo' . $i],
								'EF' => $MontoMoneda,
							);
						}
					}
				}
				// var_dump($val);

			

				$_data = $this->Compra->pago_($val,$moneda,$parcial1,$parcial2,$parcial3,$parcial4);
				echo json_encode($_data);
			}
		}
	}
	public function check_num($check_num,$proveedor)
	{
	if ($this->Compra->check_num($check_num,$proveedor)) {
			$this->form_validation->set_message('check_num', "$check_num No Disponible..");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}


	// public function compra_null( $offset = 0 )
	// {
	// 	$count = $this->db->count_all_results('Empresa');
	// 		if ($count == 0) {
	// 			redirect('Home','refresh');
	// 		} else {
	// 			$arraycss = array(
	// 				'jquery.dataTables' =>'content/datatables/DataTables/css/',
    //             		'bootstrap-datetimepicker.min' =>'content/plugins/pikear/css/');
	// 			$this->mi_css_js->init_css_js($arraycss,'css');
	// 			$arrayjs = array(
	// 				'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
    //                 'moment'                   =>'content/plugins/pikear/js/',
    //             		'bootstrap-datetimepicker' =>'content/plugins/pikear/js/',);
	// 			$this->mi_css_js->init_css_js($arrayjs,'js');
	// 	    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
	// 	        //////////////////////////////////////Vista null Solo admin///////////////////////////////////////////////////////
	// 								$data       = array (	"Alerta" => $this->Producto->get_alert(),
	// 														// 'Proveedor' =>$this->db->get('Proveedor')->result()
	// 													);
	// 							 	$this->load->view('Home/head.php',$data,FALSE);
	// 						        $this->load->view('Home/header.php',FALSE);
	// 								$this->load->view('Home/aside.php');
	// 								$this->load->view('Compra/Anul.php');
	// 								$this->load->view('Home/footer.php');
    //             /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 			} else {
	// 				$variable =  $this->Model_Menu->octener(15);
	// 				if (!empty($variable)) {
	// 					$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
	// 											$data       = array (	"Alerta" => $this->Producto->get_alert(),
	// 																	'data_view' => $variable,
	// 																	// 'Proveedor' =>$this->db->get('Proveedor')->result()
	// 																);
	// 										 	$this->load->view('Home/head.php',$data,FALSE);
	// 									        $this->load->view('Home/header.php',FALSE);
	// 											$this->load->view('Home/aside2.php',FALSE);
	// 											$this->load->view('Compra/Anul.php');
	// 											$this->load->view('Home/footer.php');
	// 					   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 				}else {
	// 					$this->load->view('errors/404.php');
	// 				}
	// 			}

	// 		}

	// }

	/**
	 * [ajax_list description]
	 * @return [type] [description]
	 */
	// public function anulados()
	// {
	// 	if ($this->input->is_ajax_request()) {
	// 		$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : '';
	// 		$ruc = isset($_POST['ruc']) ? $_POST['ruc'] : '';
	// 		$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
	// 		$anho = isset($_POST['anho']) ? $_POST['anho'] : '';


	// 	$list = $this->Compra->get_anul($estatus, $ruc, $factura, $anho);
	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $ress) {
	// 		    $datos = 'Lisnul';
	// 			$no++;
	// 			$Monto =  number_format( $ress->Monto_Total,0,'.',',');
	// 			$row   = array();
	// 			if ($ress->Tipo_Compra == 0 ) { // voleta
	// 				$row[] = 'Recibo Nº ' .$ress->Ticket;// comprovante
	// 			}elseif ($ress->Tipo_Compra == 1 ) { // factura
	// 				$row[] = 'Factura Nº '. $ress->Num_factura_Compra;// comprovante
	// 			}
	// 			$row[] =  $this->mi_libreria->getSubString($ress->Razon_Social, 10).' ('.$this->mi_libreria->getSubString($ress->Vendedor, 10);
	// 			$row[] = $this->mi_libreria->getSubString($Monto,20 ). '&nbsp; ₲.';
	// 			$row[] = '<div class="pull-right hidden-phone">
	// 				<a class="btn btn-info btn-xs"  data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$datos."'".','."'".$ress->idFactura_Compra."'".')">
	// 				<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
	// 				<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top"   href="Reporte_exel/Lisnul/'.$ress->idFactura_Compra.'" title="Exportar a EXEL" >
	// 				<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>
	// 					</div>';
	// 			$row[] = $ress->idFactura_Compra;
	// 			$data[] = $row;
	// 	}
	// 	$output = array(
	// 		"draw"            => $_POST['draw'],
	// 		"recordsTotal"    => $this->Compra->count_todasanul(),
	// 		"recordsFiltered" => $this->Compra->count_filtroanul($estatus, $ruc, $factura, $anho);
	// 		"data"            => $data,
	// 	);
	// 	//output to json format
	// 	echo json_encode($output);
	// 	} else {
	// 		$this->load->view('errors/404.php');
	// 	}

	// }

		public function formapago($value,$id)
	{

 
			switch ($value) {
				case '3': // cuenta
							$this->db->select('idCuenta_Fabor,cf.Monto');
							$this->db->from('Cuenta_Fabor cf');
							$this->db->where('cf.Estado = 1 AND cf.Cliente_Empresa = 2');
							$this->db->where('Proveedor_idProveedor', $id);
					        $query = $this->db->get();
					        $options='<option value=""></option>';
					         if ($query->num_rows() > 0) {					           
					            foreach ($query->result() as $key => $value) {
					                $options.='<option value="'.$value->Monto.','.$value->idCuenta_Fabor.'"> '.number_format($value->Monto,0,'.',',').' ₲S.</option>';
					            }
					         }
                             echo $options; 
					break;

				case '5': // ch
					$this->load->view('Compra/Formapago/forscript.php',FALSE);
					break;
			}

	}
	public function verificar_comprobante() {
        $input_data = json_decode(trim(file_get_contents('php://input')), true);
        $comprobante = $input_data['comprobante'];
        $tipoComprovante = $input_data['tipoComprovante'];
        $proveedor = $input_data['proveedor'];


        if ($tipoComprovante == 1) {
            $this->db->where('Num_factura_Compra', $comprobante);
        } else {
            $this->db->where('Ticket', $comprobante);
        }
		$this->db->where('Proveedor_idProveedor ', $proveedor);
        $query = $this->db->get('Factura_Compra');  // Cambia 'tu_tabla' por el nombre de tu tabla

        $existe = $query->num_rows() > 0;
		

        // Devuelve una respuesta adecuada en formato JSON
        $response = array('status' => $existe ? 'exists' : 'not_exists');
        echo json_encode($response);
    }

}

/* End of file Compra.php */
/* Location: ./application/controllers/Compra.php */
