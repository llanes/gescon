<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Remisiones extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Ordenventa_Model",'Orden_V');
		$this->load->library('Cart');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 


	}

	/**
	 * [index Carga vista principal de orden de Venta]
	 * @param  integer $offset [parametro]
	 * @return [type]          [description]
	 */
	public function index( $offset = 0 )
	{        $fecha = date("Y-m-d");
		     $this->cart->destroy();
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$arraycss = array(
					'jquery.dataTables'            =>'content/datatables/DataTables/css/',
					'select2'                      =>'bower_components/select2/dist/css/',
					'select2-bootstrap'            =>'bower_components/select2/dist/css/',
					'toastem'        			   =>'bower_components/jQueryToastem/',
					'bootstrap-datetimepicker.min' =>'content/plugins/pikear/css/'

				);
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array(

					'jquery.dataTables.min'    =>'content/datatables/DataTables/js/',
					'jquery.inputmask'         =>'content/plugins/inputmask/',
					'select2'                  =>'bower_components/select2/dist/js/',
					'es'                       =>'bower_components/select2/dist/js//i18n/',
					'moment'                   =>'content/plugins/pikear/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
					'bootstrap-datetimepicker' =>'content/plugins/pikear/js/',

				);
				$this->mi_css_js->init_css_js($arrayjs,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista Remision Solo admin///////////////////////////////////////////////////////
					$data = array(	
						"Alerta" => $this->Producto->get_alert(),
						'Cliente'  =>  $this->db->where('idCliente != 1')->get('Cliente')->result()
					);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Remision/Remisiones_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Remision/script_r_v.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(31);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
				        //////////////////////////////////////Vista ///////////////////////////////////////////////////////
											$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		'Cliente' => $this->db->where('idCliente != 1')->get('Cliente')->result()
																	);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Remision/Remisiones_vista.php');
												$this->load->view('Home/footer.php');
												$this->load->view('Remision/script_r_v.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


					}

				}



	}
	/**
	 * [select2remote sewlect2 data remote]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function select2remote($value='')
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->security->xss_clean($this->input->get('id'));
			$q = $this->security->xss_clean($this->input->get('q'));


			if ($id == 6) {
				$query = $this->db->select('idProducto as id, Codigo,Nombre as full_name,replace(Precio_Venta,"_","") as precio,Cantidad_A,Descuento,Iva,Img as avatar_url,Marca,Proveedor_idProveedor')
				                  ->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'inner')
				                  ->join('Producto_has_Proveedor ph', 'Producto.idProducto = ph.Producto_idProducto', 'inner')
								  ->like('Codigo', $q, 'BOTH')
				                  ->or_like('Nombre', $q, 'BOTH')
				                  ->where('Si_No', false)
					              ->where('Produccion', 2)
					              ->group_by('idProducto')
					              ->limit(10)
					              ->get('Producto');
			}else{
				$query = $this->db->select('idProducto as id, Codigo,Nombre as full_name,replace(Precio_Venta,"_","") as precio,Cantidad_A,Descuento,Iva,Img as avatar_url,Marca')
				                  ->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'inner')
								  ->like('Codigo', $q, 'BOTH')
				                  ->or_like('Nombre', $q, 'BOTH')
				                  ->where('Si_No', false)
				                  ->group_by('idProducto')
				                  ->limit(10)
				                  ->get('Producto');
			 }

			$data = array(
				'total_count' => $query->num_rows(),
				'incomplete_results' => false,
				'items' => $query->result()
			);
			echo json_encode($data);
			// $this->output->enable_profiler(TRUE);
		}else{
			$this->load->view('errors/404.php');
		}
	}
	/**
	 * [ajax_list Lista de ordenes dispponibles]
	 * @return [type] [json]
	 */
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) {
		$list = $this->Orden_V->get_remision();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $orden_v) {
				$no++;
				$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
				$resultado2 = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_envio), 10); 
				$Monto =  number_format($resultado+$resultado2,0,'.',',');
				$row   = array();
				$row[] =  $no;
				$mon = $this->mi_libreria->getSubString($orden_v->Nombres,20 );
				$row[] = $mon;
				$row[] = $orden_v->Entrega;
				switch ($orden_v->Compra_Venta) {
					case '3':
						$row[] = '<p class="text=danger"> Nota de Entrada Productos </p>';
						break;
					case '4':
						$row[] = '<p class="text=danger"> Nota de Salida Productos </p>';
						break;
					case '5':
						$row[] = '<p class="text=danger"> Nota de Devolucion Productos </p>';
						break;
					case '6':
						$row[] = '<p class="text=danger"> Entrada Productos En Produccion </p>';
						break;
					case '7':
						$row[] = '<p class="text=danger"> Sin Accion </p>';
						break;
				}
				$row[] = $this->mi_libreria->getSubString($Monto,10 ). '&nbsp; ₲.';
								$orden = 'lisremision';
					$row[] = '<div class="pull-right hidden-phone">
					<a      data-monto="'.$resultado2.'"
							data-nombre="'.$orden_v->Nombres.'"
							data-apellido="'.$orden_v->Apellidos.'"
							data-user="'.$orden_v->Compra_Venta.'"
							data-fecha="'.$orden_v->Entrega.'"
							data-tel="'.$orden_v->tel.'"
							href="javascript:void(0);"
                            id="'.$orden_v->idOrden.'"
                            data-total="'.$Monto.'"
					class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Ver Detalles" onclick="ver_detalles('."'".$orden_v->idOrden."'".')">
					<i class="fa fa-eye" aria-hidden="true"></i></a>
				<a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$orden."'".','."'".$orden_v->idOrden."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
					<a class="btn btn-success btn-xs" href="Reporte_exel/lisremision/'.$orden_v->idOrden.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
					<a class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Editar Orden" onclick="_edit('."'".$orden_v->idOrden."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$orden_v->idOrden."'".','."'".$orden_v->Compra_Venta."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;

		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Orden_V->count_todas_remision(),
			"recordsFiltered" => $this->Orden_V->count_filtro_remision(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}

	}

 	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$this->cart->destroy();
			$data = $this->Orden_V->get_by_id($id);
			echo json_encode($data);
		} else {
			show_404();
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
			 $this->load->view('Remision/cart_get.php');
			}else{
             $this->cart->destroy();
             $this->load->view('Remision/cart_get.php');
			}

		} else {
			$this->load->view('errors/404.php');
		}

	}


	public function loader_deta($id = NULL)
	{
		if ($this->input->is_ajax_request()) {
             $this->load->view('Orden/cart_load.php');
		} else {
			$this->load->view('errors/404.php');
		}

	}

	public function list_productos($id='')
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
			$this->load->model("Venta_Model",'Venta');
			if (empty($id)) {
			   $data =  $this->Orden_V->list_productos('Produccion != 1 OR Produccion IS NULL');
			}else{
			   $data =  $this->Orden_V->list_produccion('Estado_d IS NULL');
			}

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
	 * [agregar_item description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function agregar_item($value)
	{
		if ($this->input->is_ajax_request()) {
			$val = $this->security->xss_clean( $this->input->post('data',FALSE));
			$data = $this->Orden_V->agregar_item(array('idProducto' => $value),$val);
			echo json_encode($data);
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
			show_404();
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
	 * [ajax_add description]
	 * @return [type] [description]
	 */
	public function ajax_add()
	{
				if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
					$this->form_validation->set_rules('changecliente', 'changecliente', 'trim|numeric|min_length[1]|max_length[14]|strip_tags');
					$this->form_validation->set_rules('Entrega', 'fecha', 'trim|required|min_length[1]|max_length[15]|strip_tags');
					$this->form_validation->set_rules('Estado', 'nOTAS', 'trim|required|numeric|min_length[1]|max_length[14]|strip_tags');
					$this->form_validation->set_rules('observac', 'description', 'trim|min_length[1]|max_length[100]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'changecliente' => form_error('changecliente'),
							'Estado'        => form_error('Estado'),
							'Entrega'        => form_error('Entrega'),
							'observac'      => form_error('observac'),
							'res'           => 'error');
					echo json_encode($data);
				}else{
					$data                         = array(
					'Compra_Venta'          => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
					'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
					'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
					'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
					'Monto_envio'           => $this->security->xss_clean( $this->input->post('Envio_m',FALSE)),
					'Usuario_idUsuario'     => $this->session->userdata('idUsuario'),
					'Cliente_idCliente'     => $this->security->xss_clean( $this->input->post('changecliente',FALSE)),
					);
					$insert = $this->Orden_V->insert_remision($data,$this->security->xss_clean( $this->input->post('Estado',FALSE)));
					echo json_encode($insert);
				}
        }else{
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
					$this->form_validation->set_rules('changecliente', 'changecliente', 'trim|numeric|min_length[1]|max_length[14]|strip_tags');
					$this->form_validation->set_rules('Entrega', 'fecha', 'trim|required|min_length[1]|max_length[15]|strip_tags');
					$this->form_validation->set_rules('Estado', 'nOTAS', 'trim|required|numeric|min_length[1]|max_length[14]|strip_tags');
					$this->form_validation->set_rules('observac', 'description', 'trim|min_length[1]|max_length[100]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'changecliente' => form_error('changecliente'),
							'Estado'        => form_error('Estado'),
							'Entrega'        => form_error('Entrega'),
							'observac'      => form_error('observac'),
							'res'           => 'error');
					echo json_encode($data);
				}else{
					$controval =$this->security->xss_clean( $this->input->post('controval',FALSE));
					$Estado =$this->security->xss_clean( $this->input->post('Estado',FALSE));
					$idOrden =$this->security->xss_clean( $this->input->post('idOrden',FALSE));
					$data                         = array(
					'Compra_Venta'          => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
					'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
					'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
					'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
					'Monto_envio'           => $this->security->xss_clean( $this->input->post('Envio_m',FALSE)),
					'Usuario_idUsuario'     => $this->session->userdata('idUsuario'),
					'Cliente_idCliente'     => $this->security->xss_clean( $this->input->post('changecliente',FALSE)),
					);
						$insert = $this->Orden_V->update2(array('idOrden' => $idOrden), $data,$Estado,$idOrden,$controval);
					echo json_encode($insert);
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	public function ver_detalles($value='')
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Orden_V->ver_detalles(array('Orden_idOrden' => $value ),$value );
			echo json_encode($data);
		}else{
            $this->load->view('errors/404.php');
		}
	}


	public function insercliente($value='')
	{
		if ($this->input->is_ajax_request()) {
				$this->form_validation->set_error_delimiters('*','');
				if ($this->form_validation->run('insercliente') == FALSE)
				{
						$data = array(
							'nom' => form_error('nom'),
							'telefon' => form_error('telefon'),
							'ruc' => form_error('ruc'),
							'res' => 'error');
					echo json_encode($data);
				}
				else
				{
					$data                 = array(
					'Nombres'   => $this->security->xss_clean( $this->input->post('nom',FALSE)),
					'Ruc' => $this->security->xss_clean( $this->input->post('ruc',FALSE)),
					'Apellidos' => $this->security->xss_clean( $this->input->post('ape',FALSE)),
					'Direccion' => $this->security->xss_clean( $this->input->post('Direccion',FALSE)),
					'Telefono'  => $this->security->xss_clean( $this->input->post('telefon',FALSE)),
					'Correo'    => $this->security->xss_clean( $this->input->post('Email',FALSE)),
					'Limite_max_Credito'    => $this->security->xss_clean( $this->input->post('Limite_max_Credito',FALSE)),
					);
					$this->load->model('Cliente_Model');
					$id = $this->Cliente_Model->insert($data);
					$this->db->select('idCliente as id,Nombres as nom,Ruc as ruc');
					$this->db->where('idCliente', $id);
					$query = $this->db->get('Cliente')->row();
					echo json_encode($query);

				}
        }else{
			$this->load->view('errors/404.php');
		}
	}


	/**
	 * [delete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function delete(  )
	{
		if ($this->input->is_ajax_request()) {
			 $id = $this->security->xss_clean( $this->input->post('id',FALSE));
			 $id2 = $this->security->xss_clean( $this->input->post('id2',FALSE));
				$this->Orden_V->delete_item(array('idOrden' => $id ), $id,$id2);
				echo json_encode(array("status" => TRUE));

 		} else {
 			$this->load->view('errors/404.php');
 		}
	}

	/**
	 * [check_ruc description]
	 * @param  [type] $ruc_id [description]
	 * @return [type]         [description]
	 */
	function check_ruc($ruc_id)
	{
		$this->load->model('Cliente_Model');
	if ($this->Cliente_Model->check_ruc($ruc_id)) {
			$this->form_validation->set_message('check_ruc', "$ruc_id ya registrado");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
}

/* End of file Remisiones.php */
/* Location: ./application/controllers/Remisiones.php */
 