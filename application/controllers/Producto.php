<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Producto extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Producto_Model");
        
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
 
 	}
	/**
	 * [index description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function index()
	{
        $empresa_count = $this->db->count_all_results('Empresa');
        if ($empresa_count == 0) {
            redirect('Home', 'refresh');
        } else {
          // Se utiliza una técnica de caché para almacenar los resultados de las funciones "get_Marca()", "get_Categoria()" y "get_alert()"
            $marca_data = $this->cache->get('marca_data');
            if (!$marca_data) {
                $marca_data = $this->Producto_Model->get_Marca();
                $this->cache->save('marca_data', $marca_data, 3600); // Se almacena en caché durante 1 hora
            }
            $categoria_data = $this->cache->get('categoria_data');
            if (!$categoria_data) {
                $categoria_data = $this->Producto_Model->get_Categoria();
                $this->cache->save('categoria_data', $categoria_data, 3600); // Se almacena en caché durante 1 hora
            }
            $alerta_data = $this->cache->get('alerta_data');
            if (!$alerta_data) {
                $alerta_data = $this->Producto_Model->get_alert();
                $this->cache->save('alerta_data', $alerta_data, 3600); // Se almacena en caché durante 1 hora
            }
            $data = array(
                "Marca" => $marca_data,
                "Categoria" => $categoria_data,
                "Alerta" => $alerta_data
            );
            $datacss = array(
                'jquery.dataTables' => 'content/datatables/DataTables/css/',
                // 'jasny-bootstrap' => 'bower_components/jasny-bootstrap/css/',
                'select2' => 'bower_components/select2/dist/css/',
                'select2-bootstrap' => 'bower_components/select2/dist/css/'
            );
            $this->mi_css_js->init_css_js($datacss, 'css');
            $datajs = array(
                'jquery.dataTables.min' => 'content/datatables/DataTables/js/',
                'jasny-bootstrap' => 'bower_components/jasny-bootstrap/js/',
                'select2' => 'bower_components/select2/dist/js/',
                'es' => 'bower_components/select2/dist/js//i18n/',
               'script_producto' => 'bower_components/script_vista/'
   
            );
            $this->mi_css_js->init_css_js($datajs, 'js');
            $permiso_id = $this->session->userdata('Permiso_idPermiso');
            // Cargar vistas comunes a todas las ramas del condicional
            $this->load->view('Home/head.php', FALSE); // carga todos las url de estilo i js home	
            $this->load->view('Home/header.php', $data, FALSE); // esta seria la barra de navegacion horizontal
            $this->load->view('Producto/Producto_vista.php', FALSE); // este seria todo el contenido central
            $this->load->view('Home/footer.php'); // pie con los js
            if ($permiso_id == 1) {
                // Cargar vistas específicas para el permiso 1
                $this->load->view('Home/aside.php', FALSE);
            } else {
                // Cargar vistas específicas para otros permisos
                $variable = $this->Model_Menu->obtener(6);
                if (!empty($variable)) {
                    $variable = $this->Model_Menu->obtenerMenu($permiso_id);
                    $data_aside = array(
                        'data_view' => $variable,
                        'Alerta' => $alerta_data,
                    );
                    $this->load->view('Home/aside2.php', $data_aside, FALSE);
                } else {
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

			$data = array();
			$no = $_POST['start'];
            $list = $this->cache->get('get_Producto');
            if (!$list) {
                $list = $this->Producto_Model->get_Producto();
                $this->cache->save('get_Producto', $list, 3600); // Se almacena en caché durante 1 hora
            } 
			foreach ($list as $Producto) 
			{
					$no++;
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
					$Precio_Venta =  number_format($resultado,0,'.',',');
					$Marca  = $this->mi_libreria->getSubString($Producto->Marca,15);
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 15);
					$row   = array();
		
						$row[] = $Producto->Img;
				
					$row[] = $this->mi_libreria->getSubString($Producto->Codigo, 12);
					$row[] = $Marca." (".$Nombre.")";
					$row[] = $Precio_Venta.'&nbsp; ₲S.';
					$row[] = $Producto->Cantidad_A += $Producto->Cantidad_D;
					if ($Producto->Iva=="0")
					{
						$row[] = "Exenta";
					}
					else
					{
						$row[] = $Producto->Iva."%";
					}
					// $hass = $this->Producto_Model->proveedor_has($Producto->idProducto);
					if (empty($Producto->Si_No)) {
						$val = '1';
						$row[] = '<a href="javascript:void(0);" onclick="status('.$Producto->idProducto.','.$val.');"><span class="label label-success">Activo</span></a>';
					}else{
						$val = '0';
						$row[] = '<a href="javascript:void(0);" onclick="status('.$Producto->idProducto.','.$val.');"><span class="label label-danger">Inactivo</span></a>';
					} 
					$row[] = '
					    <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar" onclick="_edit('."'".$Producto->idProducto."'".')">
					        <i class="fa fa-pencil-square"></i>
					     </a>
					    <a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$Producto->idProducto."'".')">
					        <i class="fa fa-trash-o"></i>
					    </a>


					';
					$row[] = $Producto->idProducto;
					$data[] = $row;
			}
			
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Producto_Model->count_todas(),
				"recordsFiltered" => $this->Producto_Model->count_filtro(),
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
	public function ajax_add()
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('registro_Producto') == FALSE)
				{
						$data              = array(
						'Codigo'           => form_error('Codigo'),
						'Nombre'           => form_error('Nombre'),
						'Marca'            => form_error('Marca'),
						'Categoria'        => form_error('Categoria'),
						'Cantidad_D'       => form_error('Cantidad_D'),
						'Cantidad_D'       => form_error('Cantidad_D'),
						'Stock_Min'        => form_error('Stock_Min'),
						'Precio_Costo'     => form_error('Precio_Costo'),
						'Porcentaje_Venta' => form_error('Porcentaje'),
						'Precio_Venta'     => form_error('Precio_Venta'),
						'iva'              => form_error('iva'),
						'multi'            => form_error('multi'),
						'Unidad'           => form_error('Unidad'),
						'Medida'           => form_error('Medida'),
						'Descuento'        => form_error('Descuento'),
						'res'              => 'error');
					echo json_encode($data);		
				}
				else
				{
					$seleccionados = explode(',',$this->input->post('multi')); // convierto el string a un array.
					$this->mi_libreria->upload_file($this->input->post('Nombre'));
					if (!$this->upload->do_upload('file')) {
						$data              = array(
						'img'              => $this->upload->display_errors(),
						'res'              => 'error');
					echo json_encode($data);		
					} else 
					{			$file_info = $this->upload->data();// ObTENGO ELL NOMBRE DE LA IMAGEN
								 if (!$file_info['file_name']) {
								 	$nombre_modifi  = '';
								 } else {
									$nombre_modifi  = $this->mi_libreria->create($file_info['file_name']);
									$data = array('upload_data' => $this->upload->data());
								 }
							$Precio_Venta = $this->security->xss_clean( $this->input->post('Precio_Venta',FALSE));
							$Cantidad_A   = intval(preg_replace('/[^0-9]+/', '',$this->security->xss_clean( $this->input->post('Cantidad_A',FALSE))), 10); 
							$Cantidad_D   = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Cantidad_D',FALSE))), 10); 
							$Stock_Min    = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Stock_Min',FALSE))), 10);
							$Precio_Costo = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Precio_Costo',FALSE))), 10);
							$Precio_Venta = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Precio_Venta',FALSE))), 10);


							$_data                 = array(
								'Codigo'                => $this->security->xss_clean( $this->input->post('Codigo',FALSE)),
								'Nombre'                => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Nombre',FALSE)))),
								'Precio_Costo'          => $Precio_Costo,
								'Produccion'            => $this->security->xss_clean( $this->input->post('Porcentaje',FALSE)),
								'Precio_Venta'          => $Precio_Venta,
								'Cantidad_A '           => $Cantidad_A,
								'Cantidad_D '           => $Cantidad_D,
								'Stock_Min '            => $Stock_Min,
								'Descuento'             => $this->security->xss_clean( $this->input->post('Descuento',FALSE)),
								'Iva'                   => $this->security->xss_clean( $this->input->post('iva',FALSE)),
								'Img'                   => $nombre_modifi,
								'Unidad'                => $this->security->xss_clean( $this->input->post('Unidad',FALSE)),
								'Medida'                => $this->security->xss_clean( $this->input->post('Medida',FALSE)),
								'Categoria_idCategoria' => $this->security->xss_clean( $this->input->post('Categoria',FALSE)),
								'Marca_idMarca'         => $this->security->xss_clean( $this->input->post('Marca',FALSE)),
							);
								$id =$this->Producto_Model->insert($_data);
								if ($this->input->post('multi') !== 'null') {
									$seleccionados = explode(',',$this->input->post('multi')); // convierto el string a un array.
										for ($i=0;$i<count($seleccionados);$i++)
										{
												$_data         = array(
												'Producto_idProducto'  => $id ,
												'Proveedor_idProveedor' => $seleccionados[$i],
												);
											$this->Producto_Model->add_producto_x_pro($_data);
										}	
								} 
						echo json_encode(array("status" => TRUE));
					}

				}

        }else{
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [ajax_edit description]
	 * @param  [type] $idProducto [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($idProducto)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Producto_Model->get_by_id($idProducto);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}


	/**
	 * [ajax_update description]
	 * @return [type] [description]
	 */
		public function ajax_update()
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('ajax_update_Producto') == FALSE)
				{
						$data              = array(
						'Codigo'           => form_error('Codigo'),
						'Nombre'           => form_error('Nombre'),
						'Marca'            => form_error('Marca'),
						'Categoria'        => form_error('Categoria'),
						'Cantidad_D'       => form_error('Cantidad_D'),
						'Cantidad_D'       => form_error('Cantidad_D'),
						'Stock_Min'        => form_error('Stock_Min'),
						'Precio_Costo'     => form_error('Precio_Costo'),
						'Produccion' => form_error('Porcentaje'),
						'Precio_Venta'     => form_error('Precio_Venta'),
						'iva'              => form_error('iva'),
						'multi'            => form_error('multi'),
						'Unidad'           => form_error('Unidad'),
						'Medida'           => form_error('Medida'),
						'Descuento'        => form_error('Descuento'),
						'res'              => 'error');
					echo json_encode($data);		
				}
				else
				{
					$this->mi_libreria->upload_file($this->input->post('Nombre'));
					if (!$this->upload->do_upload('file')) {
						$data              = array(
						'img'              => $this->upload->display_errors(),
						'res'              => 'error');
					echo json_encode($data);
					} else 
					{
							if (!$this->input->post('imageneditado')) {
								$file_info = $this->upload->data();// ObTENGO ELL NOMBRE DE LA IMAGEN
								 if (!$file_info['file_name']) {
								 	$nombre_modifi  = '';
								 } else {
									$nombre_modifi  = $this->mi_libreria->create($file_info['file_name']);
									$data = array('upload_data' => $this->upload->data());
								 }

							} else {
								$nombre_modifi = $this->input->post('imageneditado');
							}

							$Cantidad_A = intval(preg_replace('/[^0-9]+/', '',$this->security->xss_clean( $this->input->post('Cantidad_A',FALSE))), 10); 
							$Cantidad_D = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Cantidad_D',FALSE))), 10); 
							$Stock_Min = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean( $this->input->post('Stock_Min',FALSE))), 10);
							$_data                 = array(
								'Codigo'                => $this->security->xss_clean( $this->input->post('Codigo',FALSE)),
								'Nombre'                => $this->security->xss_clean( ucfirst(strtolower($this->input->post('Nombre',FALSE)))),
								'Precio_Costo'          => $this->security->xss_clean( $this->input->post('Precio_Costo',FALSE)),
								'Produccion'      => $this->security->xss_clean( $this->input->post('Porcentaje',FALSE)),
								'Precio_Venta'          => $this->security->xss_clean( $this->input->post('Precio_Venta',FALSE)),
								'Cantidad_A '           => $Cantidad_A,
								'Cantidad_D '           => $Cantidad_D,
								'Stock_Min '            => $Stock_Min,
								'Descuento'             => $this->security->xss_clean( $this->input->post('Descuento',FALSE)),
								'Iva'                   => $this->security->xss_clean( $this->input->post('iva',FALSE)),
								'Img'                   => $nombre_modifi,
								'Unidad'                => $this->security->xss_clean( $this->input->post('Unidad',FALSE)),
								'Medida'                => $this->security->xss_clean( $this->input->post('Medida',FALSE)),
								'Categoria_idCategoria' => $this->security->xss_clean( $this->input->post('Categoria',FALSE)),
								'Marca_idMarca'         => $this->security->xss_clean( $this->input->post('Marca',FALSE)),
							);
					$this->Producto_Model->update(array('idProducto' => $this->input->post('idProducto')), $_data);
					$seleccionados = explode(',',$this->input->post('multi')); // convierto el string a un array.
					if ($this->input->post('multi') == 'null') {
						// echo var_dump($this->input->post('multi'));
						$this->Producto_Model->delete_by_id_has(array('Producto_idProducto' => $this->input->post('idProducto')));
					} else {
						// echo var_dump($this->input->post('multi'));
						$this->Producto_Model->delete_by_id_has(array('Producto_idProducto' => $this->input->post('idProducto')));
								for ($i=0;$i<count($seleccionados);$i++)
								{
										$_data                  = array(
										'Producto_idProducto'   => $this->input->post('idProducto') ,
										'Proveedor_idProveedor' => $seleccionados[$i],
										'Fecha'                 => '',
										'Cantidad'              => ''
										);
										$this->Producto_Model->add_producto_x_pro($_data);
								}
					}

							echo json_encode(array("status" => TRUE));
					}

				}

        }else{
			$this->load->view('errors/404.php');
		}
	}

	public function guardarCambios() {
            $codigo = $this->security->xss_clean($this->input->post('codigo', FALSE));
            $nombre = ucfirst(strtolower($this->security->xss_clean($this->input->post('nombre', FALSE))));
            $precioVenta = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean($this->input->post('precio', FALSE))), 10);
            $precioMayor = intval(preg_replace('/[^0-9]+/', '', $this->security->xss_clean($this->input->post('mayor', FALSE))), 10);

            $_data = array(
                'Codigo' => $codigo,
                'Nombre' => $nombre,
                'Precio_Venta' => $precioVenta,
                'Precio_Mayor' => $precioMayor,
            );
            $this->Producto_Model->update(array('idProducto' => $this->input->post('id')), $_data);
            echo json_encode(array("ok" => TRUE));
       

	}

	public function ajax_delete($id = NULL)
	{
		if ($this->input->is_ajax_request()) {
			$this->Producto_Model->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		} else {
		   $this->load->view('errors/404.php');
		}

	}

	function updateStatud($value='')
	{
       if ($this->input->is_ajax_request()) {
       		$id = $this->security->xss_clean($this->input->post('id', TRUE));
       		$val = $this->security->xss_clean($this->input->post('val', TRUE));
 				$this->db->set('Si_No', $val, FALSE);
				$this->db->where('idProducto', $id);
				$this->db->update('Producto');
				echo json_encode($this->db->affected_rows()); // Has keys 'code' and 'message'

		} else {
		   $this->load->view('errors/404.php');
		}
	}

	public function proveedor_has($id = NULL)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Producto_Model->proveedor_has($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}
    // comprovar si existe nobre de usuario para registro Producto
	function check_codigo($codigo_id)
	{	
	if ($this->Producto_Model->check_codigo($codigo_id)) {
			$this->form_validation->set_message('check_codigo', "$codigo_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	public function uriswg()
	{
		$this->session->set_userdata('urisegment',$this->input->post('val'));
		echo json_encode(true);
	}
	///////////////////////////////////////////////////////Modulo Stock Vista/////////////////////////////////////////////////////////////////////////

	/**
	 * [index_stock description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function index_stock( $offset = 0 )
	{
		$data    = array //arreglo para mandar datos a la vista
		(
		"Alerta" => $this->Producto->get_alert(),
		);
				$array = array(
					'jquery.dataTables' =>'content/datatables/DataTables/css/',
				);
				$this->mi_css_js->init_css_js($array,'css');
				$array = array(
					'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
					'script_stock'       =>'bower_components/script_vista/',
				);
				$this->mi_css_js->init_css_js($array,'js');

		if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php');
							$this->load->view('Stock/Stock_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Stock/sctk.php'); // pie con los js
		} else {

					$variable =  $this->Model_Menu->octener(7);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$data = array('data_view' => $variable,"Alerta"    => $this->Producto->get_alert(),);
					        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$data,FALSE);
							$this->load->view('Stock/Stock_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/footer.php'); // pie con los js
							$this->load->view('Stock/sctk.php'); // pie con los js
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}

		}

	}

	/**
	 * [ajax_list_stock description]
	 * @return [type] [description]
	 */
	public function ajax_list_stock($id = null)
	{
		if ($this->input->is_ajax_request()) 
		{
			$val  = $this->input->post('val');
			$id   = (isset($val)) ? $val : null;
			// var_dump($id);
			$list = $this->Producto_Model->get_Producto($id);
			$data = array();
			$no   = $_POST['start'];

			foreach ($list as $Producto) 
			{
					$no++;
					$Marca  = $this->mi_libreria->getSubString($Producto->Marca,22);
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 22);
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
					$Unidad = intval(preg_replace('/[^0-9]+/', '', $Producto->Unidad), 10); 
					$Precio_Venta =  number_format($resultado,0,'.',',');
					$row   = array();
					$row[] = $no;
					$row[] = $Nombre." (". $Marca.")";
						$cantidad = ($Producto->Cantidad_A + $Producto->Cantidad_D);
					if ($cantidad <= 10 ) {
						$row[] ='<span class="badge bg-red">'.$cantidad.'</span>';
						} else if($cantidad > 100) {
							$row[] ='<span class="badge bg-green">'.$cantidad.'</span>';
						} else if ($cantidad <= 500) {
							$row[] ='<span class="badge bg-blue">'.$cantidad.'</span>';
						}
						if ($Producto->Cantidad_D <= $Producto->Stock_Min ) {
						$row[] ='<span class="badge bg-red">'.$Producto->Cantidad_D.'</span>';
						} else if($Producto->Cantidad_D > 100) {
							$row[] ='<span class="badge bg-green">'.$Producto->Cantidad_D.'</span>';
						} else if ($Producto->Cantidad_D <= 500) {
							$row[] ='<span class="badge bg-blue">'.$Producto->Cantidad_D.'</span>';
						}
						//////////////////////////////////////
						if ($Producto->Cantidad_A <= $Producto->Stock_Min ) {
						$row[] ='<span class="badge bg-red">'.$Producto->Cantidad_A.'</span>';
						} else if($Producto->Cantidad_A > 100) {
							$row[] ='<span class="badge bg-green">'.$Producto->Cantidad_A.'</span>';
						} else if ($Producto->Cantidad_A <= 500) {
							$row[] ='<span class="badge bg-blue">'.$Producto->Cantidad_A.'</span>';
						}
					$row[] = $Precio_Venta.'&nbsp; ₲S.';
					$row[] = $Unidad."&nbsp;".$Producto->Medida;
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Producto_Model->count_todas($id),
				"recordsFiltered" => $this->Producto_Model->count_filtro($id),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [detale Listado d detales de productos]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	function detale($idProducto='')
	{
		if ($this->input->is_ajax_request()) {
			$this->db->select('REPLACE(Precio_Costo,"_","") as A1, REPLACE(Precio_Venta,"_","") as A2, Stock_Min as A3, REPLACE(Unidad,"_","") as A4, Medida as A5,  GROUP_CONCAT(CONCAT("<li>",prv.Razon_Social,"</i>") SEPARATOR ",") Proveedores');
			$this->db->join('Producto_has_Proveedor pp', 'pp.Producto_idProducto=pro.idProducto', 'left');
			$this->db->join('Proveedor prv', 'pp.Proveedor_idProveedor=prv.idProveedor', 'left');
			$this->db->where('pro.idProducto', $idProducto);
			$this->db->group_by('pro.idProducto');
			$this->db->from('Producto pro');
			$query = $this->db->get();
			echo json_encode($query->result());
		}
	}
 }


 
 /* End of file Producto.php */
 /* Location: ./application/controllers/Producto.php */
