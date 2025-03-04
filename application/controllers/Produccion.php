<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Produccion extends CI_Controller {

 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Produccion_Model",'Producir');
 		$this->load->library('Cart');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
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
				$Value = 0;
					// $this->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'left');
					// $this->db->where('Estado', $Value);
					$Moneda = $this->db->get('Moneda')->result();

					$data	= array (	
						// "Alerta" => $this->Producto->get_alert(),
						'Moneda' => $Moneda,
						// 'Banco' =>$this->db->get('Gestor_Bancos')->result(),
						'lotes' =>$this->db->get('Numeros_Lote')->result()
					);
					$datacss = array(
                        'jquery.dataTables' => 'content/datatables/DataTables/css/',
                        // 'jasny-bootstrap' => 'bower_components/jasny-bootstrap/css/',
						'select2'           =>'bower_components/select2/dist/css/',
											'toastem' =>'bower_components/jQueryToastem/',
						'select2-bootstrap' =>'bower_components/select2/dist/css/'

					);
					$this->mi_css_js->init_css_js($datacss,'css');
					$datajs = array(
						'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
						// 'dataTables.bootstrap'  =>'content/datatables/DataTables/js/',
						// 'jquery.inputmask'      =>'content/plugins/inputmask/',
						'select2'               =>'bower_components/select2/dist/js/',
						'es'                    =>'bower_components/select2/dist/js//i18n/',
                        'toastem' =>'bower_components/jQueryToastem/',
						// 'validate-bootstrap.jquery'                    =>'bower_components/validate-bootstrap.jquery/'
	
					);
					$this->mi_css_js->init_css_js($datajs,'js');

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php',FALSE);
						    $this->load->view('Produccion/Produccion_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Produccion/script_produccion.php');

				} else {

		
					$variable =  $this->Model_Menu->octenerMenu(22,$this->session->userdata('Permiso_idPermiso'));

					if (!empty($variable)) {
							$_data = array(
								'data_view' => $variable,
								"Alerta" => $this->Producto->get_alert(),
								'Moneda' => $Moneda,
								'Banco' =>$this->db->get('Gestor_Bancos')->result(),
								// 'Proveedor'=> $this->db->get('Proveedor')->result()
								);
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$_data,FALSE);
						    $this->load->view('Produccion/Produccion_vista.php', FALSE); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Produccion/script_produccion.php');

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
			$list = $this->Producir->get_Producto();
			// echo var_dump($list);
			$data = array();
			$no = $_POST['start'];
           $dato = 'detalleproduccion';
			foreach ($list as $Producto) 
			{
					$no++;
					$row   = array();
					$Precio_Costo = str_replace($this->config->item('caracteres'),"",$Producto->Precio_Costo);


					$row[] = $this->mi_libreria->getSubString($Producto->Nombre,30);
					$row[] = $this->mi_libreria->getSubString($Producto->Razon_Social.' ('.$Producto->Ruc.')',30);
					if (is_null($Producto->Estado_d)) {
						$esta = 1;
						$row[] = '<div class="progress active">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      ...Produciendo...
                    </div>
                  </div>';
                  		$row[] ='0';
						$row[] = $Producto->Fecha_Produccion;
     					$row[] = '
							<div class="pull-left hidden-phone">
							    <button " class="btn btn-danger btn-xs" href="javascript:void(0);" title="Terminar y agregar productos" data-toggle="tooltip" data-placement="top" onclick="terminar('."'".$Producto->Producto_idProducto."'".','."'".$Producto->Proveedor_idProveedor."'".','."'".$esta."'".','."'".$Precio_Costo."'".','."'".$Producto->CantidadProduccion."'".','."'".$Producto->idDetale_Produccion."'".','."'".$Producto->Monto_Total."'".')">
							    <i class="fa fa-stop-circle-o" aria-hidden="true"></i>
							        Terminar 
							     </button>
							</div>
							<div class="pull-right hidden-phone">
								<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$dato."'".','."'".$Producto->idDetale_Produccion."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/detalleproduccion/'.$Producto->idDetale_Produccion.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>

							    <button "  class="btn btn-primary btn-xs" href="javascript:void(0);" title="Editar"  data-toggle="tooltip" data-placement="top" onclick="editar_datos('."'".$Producto->idProducto."'".','."'".$Producto->id_Prove."'".','."'".$esta."'".','."'".$Producto->Fecha_Produccion."'".','."'".$Producto->CantidadProduccion."'".','."'".$Producto->idDetale_Produccion."'".')">
							        <i class="fa fa-pencil-square"></i>
							     </button>
							</div>

							';

					}elseif (!is_null($Producto->Estado_d)) {
							$esta = 2;
						$row[]= '<div class="progress">
                    <div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      Producro Producido
                    </div>
                  </div>';
 						$row[] = $Producto->CantidadProduccion.'&nbsp;&nbsp;Productos Producido';
						$row[] = $Producto->Fecha_Produccion;

							$row[] = '
							<div class="pull-left hidden-phone">
							    <button " class="btn btn-info btn-xs" href="javascript:void(0);" title="Editar y volver a producir" data-toggle="tooltip" data-placement="top" onclick="volver_producir('."'".$Producto->idProducto."'".','."'".$Producto->id_Prove."'".','."'".$esta."'".','."'".$Producto->Fecha_Produccion."'".','."'".$Producto->CantidadProduccion."'".','."'".$Producto->idDetale_Produccion."'".')">
							    <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
							        Volver a Producir
							     </button>
							 </div>
							 <div class="pull-right hidden-phone">
								<a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$dato."'".','."'".$Producto->idDetale_Produccion."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> </a>
					<a class="btn btn-success btn-xs" href="Reporte_exel/detalleproduccion/'.$Producto->idDetale_Produccion.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"> </i></a>

							    <button class="btn btn-danger btn-xs" href="javascript:void(0);" title="Eliminar" data-toggle="tooltip" data-placement="top"  onclick="_delete('."'".$Producto->idProducto."'".','."'".$Producto->Fecha_Produccion."'".','."'".$Producto->idDetale_Produccion."'".')">
							        <i class="fa fa-trash-o"></i>
							    </button>
							</div> 
							';		


					}


					$row[] = $Producto->idDetale_Produccion;
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Producir->count_todas(),
				"recordsFiltered" => $this->Producir->count_filtro(),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	public function load($id)
	{
		if ($this->input->is_ajax_request()) {
			if ($id) {
			 $this->cart->destroy();
			 $this->load->view('Produccion/cart_get.php');
			}else{
             $this->load->view('Produccion/cart_get.php');
			}

		} else {
			$this->load->view('errors/404.php');
		}

	}

	public function agregar_item($val)
	{
		$item = 0;
	
		if ($this->input->is_ajax_request()) {
			if ($this->input->post('maximo') > 1) {
				$opciones = array(
					'Unidad_Medida' => $this->security->xss_clean($this->input->post('unidad', FALSE) . '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->mi_libreria->medida($this->input->post('medida', FALSE))),
					'Cantidad_max' => $this->security->xss_clean($this->input->post('maximo', FALSE)),
					'iva' => $this->security->xss_clean($this->input->post('iva', FALSE))
				);
				
				$name = str_replace('_', ' ', ucfirst(strtolower($this->input->post('name'))));
				$marca = str_replace('_', ' ', ucfirst(strtolower($this->input->post('marca'))));
				$data = array(
					'id' => $this->security->xss_clean($this->input->post('id', FALSE)),
					'qty' => '1',
					'price' => $this->security->xss_clean(intval(preg_replace('/[^0-9]+/', '', $this->input->post('precio', FALSE)), 10)),
					'name' => $name . "          :" . $marca . "",
					'descuento' => '',
					'options' => $opciones
				);
				
				$this->cart->insert($data);
				$item++;
	
				// Cargar la vista del carrito actualizada
				$this->load->view('Produccion/cart_get');
				// $res = array(
				// 	'ress' => '',
				// 	'cart_view' => $this->load->view('Produccion/cart_get', NULL, TRUE),
				// );

				// echo json_encode($res);
			} else {
				$res = array(
					'ress' => 'error',
					'max' => "Articulo no Agregado... Cantidad maxima en stock:" . '  ' . $this->input->post('maximo', FALSE),
				);
				echo json_encode($res);
			}
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function add_allIngrediente($id_receta)
	{
		// Obtener los ingredientes de la tabla "ingrediente" para la receta especificada
		$this->db->select('idProducto,Nombre,replace(Precio_Costo,"_","") as Precio_Costo,replace(Unidad,"_","") as Unidad,Medida,Img,Iva,Cantidad_A,Cantidad');
		$this->db->from('Ingredientes');
		$this->db->join('Producto', 'Ingredientes.Producto_idProducto = Producto.idProducto');

		$this->db->where('Ingredientes.RecetasidRecetas', $id_receta);
		$ingredientes = $this->db->get();
		 // Insertar los ingredientes en el carrito de la sesión de CodeIgniter
		$this->cart->destroy();

		
		foreach ($ingredientes->result_array() as $ingrediente) {
			$opciones = array(
				'Unidad_Medida' => $ingrediente['Unidad'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $ingrediente['Medida'],
				'Cantidad_max' => $ingrediente['Cantidad_A'],
				'iva' => $ingrediente['Iva']
			);
			

			$data = array(
				'id' => $ingrediente['idProducto'],
				'qty' => $ingrediente['Cantidad'],
				'price' => $ingrediente['Precio_Costo'],
				'name' => $ingrediente['Nombre'],
				'descuento' => '',
				'options' => $opciones
			);
		
			
			$this->cart->insert($data);
		}



		$this->load->view('Produccion/cart_get');

	}

	public function list_productos()
	{
		if(!$this->input->is_ajax_request()){
			$this->load->view('errors/404.php');
			return;
		}
		$id = $this->input->get('id', TRUE);
		$q = $this->input->get('q', TRUE);
		$control = $this->input->get('control', TRUE);
		$limit = $id > 0 ? 1 : 1;
		$this->db->select('idProducto as id,Codigo,Nombre as full_name,Marca,replace(Precio_Costo,"_","") as precio,replace(Unidad,"_","") as Unidad,Medida,Img,Iva,Cantidad_A,Descuento');
		$this->db->from('Producto');
		$this->db->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'left');
		$this->db->where('Produccion', $control ? 2 : 1);
		$this->db->like('Codigo', $this->db->escape_like_str($q), 'BOTH');
		$this->db->or_like('Nombre', $this->db->escape_like_str($q), 'BOTH');
		$this->db->group_by('idProducto');
		$this->db->limit($limit);
		$resultado_esperado = $this->db->get();
		$data = array(
			'total_count'        => $resultado_esperado->num_rows(),
			'incomplete_results' => false,
			'items'              => $resultado_esperado->result_array()
		);
		echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	}

	public function listRecetas()
	{
		if (! $this->input->is_ajax_request()) {
			$this->load->view('errors/404.php');
			return;
		}
		
		$id = intval($this->input->get('id'));
		$q = $this->input->get('q');
		
		$limit = $id > 0 ? $id : 10; // Ajusta el límite según el valor de $id
		
		$this->db->select('idRecetas as id, concat(nombre, " || ", descripcion) as full_name,CantidadProduccion as Cantidad_A');
		$this->db->join('Detale_Produccion', 'Recetas.idRecetas = Detale_Produccion.Recetas_idRecetas',left);

		$this->db->from('Recetas');
		$this->db->like('nombre', $this->db->escape_like_str($q), 'BOTH');
		$this->db->or_like('descripcion', $this->db->escape_like_str($q), 'BOTH');
		$this->db->limit($limit);
		
		$resultado_esperado = $this->db->get();
		
		if ($resultado_esperado->num_rows() > 0) {
			$data = array(
				'total_count' => $resultado_esperado->num_rows(),
				'incomplete_results' => false,
				'items' => $resultado_esperado->result()
			);
		} else {
			$data = array(
				'total_count' => 0,
				'incomplete_results' => false,
				'items' => array()
			);
		}
		
		echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

	}

	public function produc($id=null)
	{
		if ($this->input->is_ajax_request()) {
			$this->load->model("Venta_Model",'Venta');
			// $this->output->enable_profiler(TRUE);
			   $data =  $this->Venta->list_productos(array('idProducto' => $id, ),1);
				if ($data != null) {
				echo $data;
			}else{
				echo $options='<option value=""></option>';
			}

		}else{
			$this->load->view('errors/404.php');
		}
	}

	public function delete_item($rowid)
	{
		if ($this->input->is_ajax_request()) {
		$this->cart->remove($rowid);
		$this->load->view('Produccion/cart_get');
        }else{
			$this->load->view('errors/404.php');
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

	public function ajax_add()
	{
				if ($this->input->is_ajax_request()) {
				$ticket =  Ticket();
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('produccion') == FALSE)
				{
						$data = array(
							'proveedor' => form_error('proveedor'),
							'idProduct'     => form_error('idProduct'),
							'recetas'     => form_error('recetas'),
							'cantidad'  => form_error('cantidad_producir'),
							'fecha'   => form_error('fecha_produccion'),
							'estado'   => form_error('estado_produccion'),
							'responsable'   => form_error('responsable_produccion'),
							'tiempo'   => form_error('tiempo_produccion'),
							'lotes'   => form_error('lotes'),

							'res'         => 'error');
					echo json_encode($data);		
				}else{
					$proveedor = $this->input->post('proveedor', FALSE);
					$idProduct = $this->input->post('idProduct', FALSE);
					$recetas = $this->input->post('recetas', FALSE);
					$cantidad = $this->input->post('cantidad_producir', FALSE);
					$fecha = $this->input->post('fecha_produccion', FALSE);
					$estado = $this->input->post('estado_produccion', FALSE);
					$responsible = $this->input->post('responsable_produccion', FALSE);
					$tiempo = $this->input->post('tiempo_produccion', FALSE);
					$lote = $this->input->post('lotes', FALSE);
					$lesiva = $this->input->post('lesiva', FALSE);

					$this->db->trans_begin();
					$cartcontents = $this->Producir->insert($proveedor, $idProduct, $recetas, $cantidad, $fecha, $estado, $responsible, $tiempo, $lote, $lesiva);
if ($proveedor > 1) {
    $monto_total = $this->security->xss_clean($this->input->post('cart_total', FALSE));
    $object = array(
        'Fecha_expedicion'  => date("d-m-Y"),
        'Hora'              => strftime( "%H:%M", time() ),
        'Concepto'          => 'Venta Credito',
        'Estado'            => '2',
        'Ticket'            => $ticket,
        'Tipo_Venta'        =>'0',
        'Monto_Total'       => $monto_total,
        'Contado_Credito'   =>'2',
        'Insert'            => 1,
        'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
        'Caja_idCaja'       => $this->session->userdata('idcaja'),
    );
    $this->db->insert('Factura_Venta', $object);
    $id = $this->db->insert_id();
    $descuento = 0;
    $data = array();
    foreach ($cartcontents as $items) {
        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
            $iva =  $option_value;
        }
        $data[] = array(
            'Cantidad'                        => $items['qty'], 
            'Descripcion'                     => '',
            'Precio'                          => str_replace($this->config->item('caracteres'),"",$items['price']),
            'Iva'                             => $iva,
            'Descuento'                       => $items['descuento'],
            'Factura_Venta_idFactura_Venta' => $id,
            'Producto_idProducto'             => $items['id'], 
        );
        // $descuento +=$items['descuento'];
        // $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
        // $this->db->where('idProducto', $items['id']);
        // $this->db->update('Producto');
    }
    $this->db->insert_batch('Detalle_Factura', $data);
    $Fecha_Ven = date('d-m-Y',strtotime("+1 month"));
    $data = array(
        'Num_Recibo'                    => $ticket + 1,
        'Importe'                       =>  $monto_total,
        'Fecha_Ven'                     => $Fecha_Ven,
        'Fecha_Pago'                    => '',
        'Estado'                        => '0',
        'Num_Cuota'                     => 1,
        'Factura_Venta_idFactura_Venta' => $id,
        'Proveedor_idProveedor'         => $proveedor,
    );
    $this->db->insert('Cuenta_Corriente_Cliente', $data);
}
					if ($this->db->trans_status() === FALSE)
					{
					        $this->db->trans_rollback();
					}
					else
					{
					        $this->db->trans_commit();
					       echo json_encode('1');
					}
					

				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

    public function add_flou($idAcientos)
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '<p class="text-danger">Venta</p>',
          'HaberDetalle'                => NULL,
          'Debe'                        => NULL,
          'Haber'                       => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
		public function ajax_update()
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('produccion') == FALSE)
				{
						$data = array(
							'proveedor' => form_error('proveedor'),
							'idProduct'     => form_error('idProduct'),
							'Estado_produccion'  => form_error('Estado_produccion'),
							'cantidad'   => form_error('cantidad'),
							'res'         => 'error');
					echo json_encode($data);		
				}else{
					$proveedor         = $this->security->xss_clean($this->input->post('proveedor',FALSE));
					$idProduct         = $this->security->xss_clean( $this->input->post('idProduct',FALSE));
					$Estado_produccion =  $this->security->xss_clean( $this->input->post('Estado_produccion',FALSE));
					$cantidad          =  $this->security->xss_clean( $this->input->post('cantidad',FALSE));
					$date              = $this->security->xss_clean( $this->input->post('date',FALSE));
					$newdate           = $this->security->xss_clean( $this->input->post('newdate',FALSE));
					$lesiva            = $this->security->xss_clean( $this->input->post('lesiva',FALSE));
					$iddp              = $this->security->xss_clean( $this->input->post('iddp',FALSE));
					$insert            = $this->Producir->ajax_update($proveedor,$idProduct,$Estado_produccion,$cantidad,$date,$newdate,$iddp);
					echo json_encode($insert);
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}


	public function detalle($value='')
	{
		if ($this->input->is_ajax_request()) {
			   $data = $this->Producir->detalle(array('idProduccion' => $value));
			      if ($data) {
				   	echo json_encode($data);
				   }
		}else{
          $this->load->view('errors/404.php');
		}
	}

 	public function ajax_edit($idProducto)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Producir->get_by_id($idProducto);
			echo json_encode($idProducto);
		} else {
			$this->load->view('errors/404.php');
		}

	}

	public function ajax_delete()
	{
		if ($this->input->is_ajax_request()) {
			$id         = $this->security->xss_clean($this->input->post('id',FALSE));
			$idp         = $this->security->xss_clean($this->input->post('idp',FALSE));
			$date         = $this->security->xss_clean($this->input->post('date',FALSE));
			$this->db->trans_begin();
				$this->db->where('idProduccion', $idp);
				$this->db->delete('Ingredientes');
				$this->db->where('idDetale_Produccion', $idp);
				$this->db->delete('Detale_Produccion');

                echo json_encode($this->db->affected_rows()); 
			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();
			}
			else
			{
			        $this->db->trans_commit();
			}
		}else{
          $this->load->view('errors/404.php');
		}
	}
	public function ajaxadd()
	{
				if ($this->input->is_ajax_request()) {
					// echo var_dump($this->input->post());
				$this->form_validation->set_error_delimiters('*','');
				$this->form_validation->set_rules('Razon_Social', 'Razon Social', 'trim|required|min_length[3]|max_length[40]|strip_tags');
				$this->form_validation->set_rules('Ruc', 'Ruc', 'trim|required|callback_check_ruc|min_length[5]|max_length[16]|strip_tags');
				$this->form_validation->set_rules('Vendedor', 'Vendedor', 'trim|required|min_length[5]|max_length[30]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'Ruc'          => form_error('Ruc'),
							'Razon_Social' => form_error('Razon_Social'),
							'Vendedor'     => form_error('Vendedor'),
							'res'          => 'error');
					echo json_encode($data);		
				}else{
					$data                 = array(
					'Ruc'          => $this->security->xss_clean( $this->input->post('Ruc',FALSE)),
					'Razon_Social' => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Razon_Social',FALSE)))),
					'Vendedor'     => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Vendedor',FALSE)))),
					);
					$this->load->model('Proveedor_Model');
					$id = $this->Proveedor_Model->insert($data);
					$this->db->select('idProveedor as id,Razon_Social as nom,Ruc as ruc');
					$this->db->where('idProveedor', $id);
					$query = $this->db->get('Proveedor')->row();
					echo json_encode($query);
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	function check_ruc($ruc_id)
	{
		$this->load->model('Proveedor_Model');
	if ($this->Proveedor_Model->check_ruc($ruc_id)) {
			$this->form_validation->set_message('check_ruc', "$ruc_id No Disponible Ruc duplicado");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}

function close_my_produc(){
	if ($this->input->is_ajax_request()) {
		$cantidadProducido = $this->security->xss_clean( $this->input->post('cantidadProducido', FALSE));
		$idProducto = $this->security->xss_clean( $this->input->post('idProducto', FALSE));
		$idDetalle = $this->security->xss_clean( $this->input->post('idDetalle', FALSE));
		$Monto_Total = $this->security->xss_clean( $this->input->post('Monto_Total', FALSE));

 		$this->db->trans_begin();
		 $operacion = 'suma'; // o 'resta'
		 $this->db->set('Cantidad_A', 'Cantidad_A+'.$cantidadProducido, FALSE);
		 $this->db->set('operacion', $operacion);
		 $this->db->where('idProducto', $idProducto);
		 $this->db->update('Producto');
 		$this->db->set('CantidadProduccion',$cantidadProducido, FALSE);
		$this->db->set('Estado_d', '1', FALSE);
		$this->db->where('idDetale_Produccion',  $idDetalle, false );
		$this->db->update('Detale_Produccion');
 		$data = array(
			'Diferencia' => $idDetalle,
			'Caja_idCaja' => $this->session->userdata('idcaja'),
		);
		$this->db->insert('Acientos', $data);
		$idAcientos = $this->db->insert_id();
 		$data = array(
			array(
				'PlandeCuenta_idPlandeCuenta' => '60',
				'Acientos_idAcientos' => $idAcientos,
				'DebeDetalle' => '(Ac +)',
				'HaberDetalle' => null,
				'Debe' => $Monto_Total ,
				'Haber' => null,
			),
			array(
				'PlandeCuenta_idPlandeCuenta' => '55',
				'Acientos_idAcientos' => $idAcientos,
				'DebeDetalle' => null,
				'HaberDetalle' => '(Ac -)',
				'Debe' => null,
				'Haber' => $Monto_Total
			)
		);
 		$this->db->insert_batch('PlandeCuenta_has_Acientos', $data);
 		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array("status" => FALSE));
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array("status" => TRUE));
		}
	} else {
		$this->load->view('errors/404.php');
	}
}
	function close_in_produc()
	{
		if ($this->input->is_ajax_request()) {
			// echo  var_dump($this->input->post());
			$val = $this->security->xss_clean( $this->input->post('val',FALSE));
			$Acheque_tercero = $this->security->xss_clean( $this->input->post('Acheque_tercero',FALSE));
			$pagoparcial1 = $this->security->xss_clean( $this->input->post('pagoparcial1',FALSE));
			$pagoparcial2 = $this->security->xss_clean( $this->input->post('pagoparcial2',FALSE));
			$pagoparcial3 = $this->security->xss_clean( $this->input->post('pagoparcial3',FALSE));
			$pagoparcial4 = $this->security->xss_clean( $this->input->post('pagoparcial4',FALSE));
			if (!empty($pagoparcial1)) {
				for ($i=1; $i <= $val; $i++) { 
				$this->form_validation->set_rules('EF'.$i, 'Moneda', 'trim|numeric|min_length[1]|max_length[15]|strip_tags');
				}
			}elseif (!empty($pagoparcial2)) {
				if (empty($Acheque_tercero)) {
					$this->form_validation->set_rules('numcheque', 'numerocheque', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
					$this->form_validation->set_rules('fecha_pago', 'fecha', 'trim|required|min_length[1]|max_length[14]|strip_tags');
					$this->form_validation->set_rules('efectivo', 'Efectivo', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');	
				}else{
					$this->form_validation->set_rules('Acheque_tercero', 'Cheque', 'trim|required|numeric|min_length[1]|max_length[50]|strip_tags');	
			}

			}elseif (!empty($pagoparcial3)) {
				$this->form_validation->set_rules('efectivoTarjeta', 'Tarjeta', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Tarjeta', 'tipo', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
			}elseif (!empty($pagoparcial4)) {
				$this->form_validation->set_rules('matriscuanta', 'Deuda', 'trim|required|min_length[1]|max_length[25]|strip_tags');
			}
			    $this->form_validation->set_rules('montototal', 'Monto Total', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
			    $this->form_validation->set_rules('cantidadProduc', 'Cantidad', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');

				if ($this->form_validation->run() == FALSE) {
					$data      = array(
					'EF1'             => form_error('EF1'),
					'EF2'             => form_error('EF2'),
					'EF3'             => form_error('EF3'),
					'EF4'             => form_error('EF4'),
					'EF5'             => form_error('EF5'),
					'EF6'             => form_error('EF6'),
					'Montoapagar'    => form_error('Montoapagar'),
					'numcheque'       => form_error('numcheque'),
					'fecha_pago'      => form_error('fecha_pago'),
					'efectivo'        => form_error('efectivo'),
					'efectivoTarjeta' => form_error('efectivoTarjeta'),
					'Tarjeta'         => form_error('Tarjeta'),
					'matriscuanta'           => form_error('matriscuanta'),
					'cantidadProduc'           => form_error('cantidadProduc'),

					'res'      => 'error');
					echo json_encode($data);
				}else{
							$tipoComprovante =	$this->security->xss_clean( $this->input->post('tipoComprovante',FALSE));
							$Montoapagar     =	$this->security->xss_clean( $this->input->post('Montoapagar',FALSE));
							$deudapagar      =	$this->security->xss_clean( $this->input->post('montototal',FALSE));
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
							
							$cantidadProduc  = $this->security->xss_clean( $this->input->post('cantidadProduc',FALSE));
							$idProducto      = $this->security->xss_clean( $this->input->post('id_Producto',FALSE));
							$id_Detalle      = $this->security->xss_clean( $this->input->post('id_Detalle',FALSE));
							$idproveedore    = $this->security->xss_clean( $this->input->post('idproveedore',FALSE));
							$efectivo        = $this->security->xss_clean( $this->input->post('efectivo',FALSE));
							$Acheque         =	$this->security->xss_clean( $this->input->post('Acheque',FALSE));

						$moneda = array();
						if (!empty($pagoparcial1)) {
						for ($i=1; $i <= $val ; $i++) {
								$Moneda   =	$this->security->xss_clean( $this->input->post('Moneda'.$i,FALSE));
								$cambio   =	$this->security->xss_clean( $this->input->post('cam'.$i,FALSE));
								$cambiado =	$this->security->xss_clean( $this->input->post('ex'.$i,FALSE));
								$signo    =	$this->security->xss_clean( $this->input->post('signo'.$i,FALSE));
								$EF       =	$this->security->xss_clean( $this->input->post('EF'.$i,FALSE));
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


					$this->Producir->close_in_produc($tipoComprovante,$Montoapagar,$idproveedore ,$efectivo ,
						$pagoparcial1,
						$deudapagar,
						$vueltototal,
						$moneda,

						$pagoparcial2,
						$numcheque,
						$fecha_pago,
						$cuenta_bancaria ,
						$Acheque_tercero,$Acheque,


						$pagoparcial3,
						$efectivoTarjeta,
						$Tarjeta,$idProducto,$id_Detalle,

						$pagoparcial4,
						$matriscuanta,$matris,$cantidadProduc);
					 echo json_encode(1);
				}
		}else{
			 $this->load->view('errors/404.php');
	   }
	}

function ListarTodas($id) {
    if ($this->input->is_ajax_request()) {
        $this->db->where('Proveedor_idProveedor', $id);
        $this->db->where('Estado', 0);
        $this->db->or_where('Estado', 3);
        $query = $this->db->get('Cuenta_Corriente_Cliente');
        
        if ($query->num_rows() > 0) {
            $options = array();
            
            foreach ($query->result() as $key => $value) {
                $options[] = '<option value="' . $value->idCuenta_Corriente_Cliente . ',' . str_replace(",", "", $value->Importe) . '">' .
                            'Monto: ' . $value->Importe . '  Recibo Nº: ' . $value->Num_Recibo . '</option>';
            }
            
            echo implode('', $options);
        } else {
            echo '<option value=""></option>';
        }
    } else {
        $this->load->view('errors/404.php');
    }
}


}

/* End of file Produccion.php */
/* Location: ./application/controllers/Produccion.php */