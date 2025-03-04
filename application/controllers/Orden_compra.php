<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Orden_compra.php                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: Christian <Christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/08/19 23:29:43 by Christian         #+#    #+#             */
/*   Updated: 2016/08/19 23:31:02 by Christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Orden_compra extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Orden_Model",'Orden');
		$this->load->library('Cart');
        $this->load->driver('cache');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 
	}

	/**
	 * [index Carga vista principal de orden de compra]
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
					// 'bootstrap-datetimepicker.min' =>'content/plugins/pikear/css/'

				);
				$this->mi_css_js->init_css_js($arraycss,'css');
				$arrayjs = array(

					'jquery.dataTables.min'    =>'content/datatables/DataTables/js/',
					'select2'                  =>'bower_components/select2/dist/js/',
					// 'es'                       =>'bower_components/select2/dist/js//i18n/',
					// 'moment'                   =>'content/plugins/pikear/js/',
				     'toastem'            	   =>'bower_components/jQueryToastem/',
					// 'bootstrap-datetimepicker' =>'content/plugins/pikear/js/',

				);
				$this->mi_css_js->init_css_js($arrayjs,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															'Proveedor' =>$this->db->get('Proveedor')->result()
														);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Orden/Orden_compra_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Orden/script_O_C.php');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(12);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
						$data       = array (	"Alerta" => $this->Producto->get_alert(),
												'data_view' => $variable,
												'Proveedor' =>$this->db->get('Proveedor')->result()
											);
					 	$this->load->view('Home/head.php',$data,FALSE);
				        $this->load->view('Home/header.php',FALSE);
						$this->load->view('Home/aside2.php',FALSE);
						$this->load->view('Orden/Orden_compra_vista.php');
						$this->load->view('Home/footer.php');
						$this->load->view('Orden/script_O_C.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}

				}
				

			}

	}

	/**
	 * [ajax_list Lista de ordenes dispponibles]
	 * @return [type] [json]
	 */
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) {
		$list = $this->Orden->get_orden_c();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $orden_c) {
				$no++;
				$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_c->Monto_Estimado), 10); 
				$Monto_Estimado =  number_format($resultado,0,'.',',');
				$row   = array();
				$row[] =  $no;
				$row[] = $this->mi_libreria->getSubString($orden_c->Razon_Social,20 );
				$row[] = $orden_c->Entrega;
				$row[] = $orden_c->Devolucion;
				$row[] = $orden_c->Estado;
				$row[] = $this->mi_libreria->getSubString($Monto_Estimado,10 ). '&nbsp; ₲.';
				$orden = 'Orden';
				if ($orden_c->Estado == 'Aprobado') {
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-success btn-xs" 
							data-nombre="'.$orden_c->Razon_Social.'"
							data-user="'.$orden_c->user.'"
							data-fecha="'.$orden_c->Entrega.'"
							data-tel="'.$orden_c->tel.'"
							href="javascript:void(0);"
                            id="'.$orden_c->idOrden.'"
                            data-total="'.$Monto_Estimado.'"

					data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Ver Detalles" onclick="ver_detalles('."'".$orden_c->idOrden."'".')">
					<i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$orden."'".','."'".$orden_c->idOrden."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/Orden/'.$orden_c->idOrden.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar Orden" onclick="_edit('."'".$orden_c->idOrden."'".')">
					<i class="fa fa-pencil-square"></i></a>
					</div>';
					$data[] = $row;
				}else{
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-success btn-xs" 
							data-nombre="'.$orden_c->Razon_Social.'"
							data-user="'.$orden_c->user.'"
							data-fecha="'.$orden_c->Entrega.'"
							data-tel="'.$orden_c->tel.'"
							href="javascript:void(0);"
                            id="'.$orden_c->idOrden.'"
                            data-total="'.$Monto_Estimado.'"
					data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Ver Detalles" onclick="ver_detalles('."'".$orden_c->idOrden."'".')">
					<i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$orden."'".','."'".$orden_c->idOrden."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/Orden/'.$orden_c->idOrden.'" title="Exportar a EXEL" >
					<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
					<a class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Editar Orden" onclick="_edit('."'".$orden_c->idOrden."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$orden_c->idOrden."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
				}

		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Orden->count_todas(),
			"recordsFiltered" => $this->Orden->count_filtro(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
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
        $cache_key = 'select2remote_' . md5($id . '_' . $q);
        $data = $this->cache->get($cache_key);
        if (!$data) {
            $limit = $id > 0 ? 1 : 1;
            $this->db->select('idProducto as id, Codigo, Nombre as full_name, replace(Precio_Venta,"_","") as precio, Cantidad_A, Descuento, Iva');
            if ($id > 0) {
                $this->db->select('Proveedor_idProveedor');
                $this->db->join('Producto_has_Proveedor ph', 'Producto.idProducto = ph.Producto_idProducto', 'inner');
            }
            $this->db->from('Producto')
                 ->where('Si_No', false);
                if ($id > 0) {
                    $this->db->where('Proveedor_idProveedor', $id);
    
                }
            $this->db->like('Codigo', $q, 'BOTH')
                ->or_like('Nombre', $q, 'BOTH')
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
        echo json_encode($data);
    } else {
        show_404();
    }
}
public function remotecategoria()
{
    if(!$this->input->is_ajax_request()){
        $this->load->view('errors/404.php');
        return;
    }
    $id = $this->security->xss_clean($this->input->get('id'));
    $q = $this->security->xss_clean($this->input->get('q'));
    $categoria = $this->security->xss_clean($this->input->get('categoria'));
    // $this->output->enable_profiler(TRUE);
    
    $limit = $id > 0 ? 1 : 1;
    $this->db->select('idProducto as id, Codigo, Nombre as full_name, replace(Precio_Venta,"_","") as precio, Cantidad_A, Descuento, Iva,Categoria_idCategoria')
    ->from('Producto')
    ->where('Categoria_idCategoria ',$categoria)
    ->where('Si_No', false)
    ->like('Codigo', $q, 'BOTH')
    ->or_like('Nombre', $q, 'BOTH')
    ->group_by('idProducto')
    ->limit($limit);
    $query = $this->db->get();;

    $data = array(
        'total_count'        => $query->num_rows(),
        'incomplete_results' => false,
        'items'              => $query->result()
    );
    echo json_encode($data);
}



 	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$this->cart->destroy();
			$data = $this->Orden->get_by_id($id);
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
			 $this->load->view('Orden/cart_get.php');
			}else{
             $this->cart->destroy();
             $this->load->view('Orden/cart_get.php');
			}

		} else {
			$this->load->view('errors/404.php');
		}

	}

	/**
	 * [loader description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function loader_deta($id = NULL)
	{
		if ($this->input->is_ajax_request()) {
             $this->load->view('Orden/cart_load.php');
		} else {
			$this->load->view('errors/404.php');
		}

	}

	/**
	 * [listaTodos description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function listaTodos($value)
	{
		if ($this->input->is_ajax_request()) {
			$this->cart->destroy();
			$data = $this->Orden->listaTodos(array('Proveedor_idProveedor' => $value));
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	/**
	 * [listaAlertas description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function listaAlertas($value)
	{
		if ($this->input->is_ajax_request()) {
			$this->cart->destroy();
			$data = $this->Orden->listaTodos(array('Proveedor_idProveedor' => $value),$value1='1');
			echo json_encode($data);
		} else {
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
			$data = $this->Orden->agregar_item(array('idProducto' => $value));
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


                    $datacart = array(
                        'rowid' => $this->security->xss_clean($id),
                        'qty'     => $this->security->xss_clean( $this->input->post('qty',FALSE)),

                    );


					$datta = $this->cart->update($datacart);
					echo json_encode($datta);
				}
			}

        }else{
			show_404();
		}
	}

	public function update_descuento()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->security->xss_clean($this->input->post('id'));
			$val = $this->security->xss_clean($this->input->post('val'));
			$qty = $this->security->xss_clean($this->input->post('qty'));
			$price = $this->security->xss_clean($this->input->post('price'));
			$i = $this->security->xss_clean($this->input->post('i'));
			$stock = $this->security->xss_clean($this->input->post('stock'));
			$des = $this->security->xss_clean($this->input->post('descuento'));




			if ($id !== NULL) {
            $precio = 0;
            $descuento = 0;

            if ($val > 0) {
            	$descuento = $price*$val;
            	$precio = $price - $descuento;

            }else{
            	$precio = $price;
            }
 				$opciones =  array(
					'iva'   => $i,
					'descuento' => $descuento*$qty,
					'poriginal' => $price,
					'predesc'   => $precio,
                    'Cantidad_max' =>$stock,
					't_f'       => $val,

 				);
 				$data = array(
					'rowid'     => $id,
					'qty'       => $qty,
					'price'     => $price,
					'descuento' => $des,
					'options'   => $opciones
 				);
				$datta = $this->cart->update($data);
				// var_dump($data);
				echo json_encode($datta);
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
				$id    = $this->security->xss_clean($this->input->post('id'));
				if ($id !== NULL) {
					$price = $this->security->xss_clean($this->input->post('price'));
					$qty   = $this->security->xss_clean($this->input->post('qty'));
					$iva   = $this->security->xss_clean($this->input->post('iva'));
					$tyf   = $this->security->xss_clean($this->input->post('tyf'));

	 				$opciones =  array(
						'iva'   => $iva,
						'descuento' => 0,
						'poriginal' => $price,
						'predesc'   => 0,
						't_f'       => 0,

	 				);
	 				$data = array(
						'rowid'     => $id,
						'qty'       => $qty,
						'price'     => $price,
						'descuento' => $price,
						'options'   => $opciones
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
				if ($this->form_validation->run('add_orden_compra') == FALSE)
				{
						$data = array(
							'changeprove' => form_error('changeprove'),
							'Entrega'     => form_error('Entrega'),
							'Devolucion'  => form_error('Devolucion'),
							'Estado'   => form_error('Estado'),
							'observac'    => form_error('observac'),
							'res'         => 'error');
					echo json_encode($data);		
				}else{
					$data                         = array(
					'Compra_Venta'          => $this->security->xss_clean('1'),
					'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
					'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
					'Devolucion'            => $this->security->xss_clean( $this->input->post('Devolucion',FALSE)),
					'Estado'                => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
					'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
					'Monto_envio'           => NULL,
					'Usuario_idUsuario'     => $this->security->xss_clean( $this->session->userdata('idUsuario')),
					'Cliente_idCliente'     => NULL,
					'Proveedor_idProveedor' => $this->security->xss_clean( $this->input->post('changeprove',FALSE))
					);
					$insert = $this->Orden->insert($data);
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
				if ($this->form_validation->run('add_orden_compra') == FALSE)
				{
						$data = array(
							'changeprove' => form_error('changeprove'),
							'Entrega'     => form_error('Entrega'),
							'Devolucion'  => form_error('Devolucion'),
							'Estado'   => form_error('Estado'),
							'observac'    => form_error('observac'),
							'res'         => 'error');
					echo json_encode($data);		
				}else{
					$data                         = array(
					'Compra_Venta'          => $this->security->xss_clean('1'),
					'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
					'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
					'Devolucion'            => $this->security->xss_clean( $this->input->post('Devolucion',FALSE)),
					'Estado'                => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
					'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
					'Monto_envio'           => NULL,
					'Usuario_idUsuario'     => $this->security->xss_clean( $this->session->userdata('idUsuario')),
					'Cliente_idCliente'     => NULL,
					'Proveedor_idProveedor' => $this->security->xss_clean( $this->input->post('changeprove',FALSE))
					);
					$insert = $this->Orden->update(array('idOrden' => $this->input->post('idOrden')), $data,$this->input->post('idOrden'));
					echo json_encode($insert);
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}

	public function ver_detalles($value='')
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Orden->ver_detalles(array('Orden_idOrden' => $value ),$value );
			echo json_encode($data);
		}else{
            $this->load->view('errors/404.php');
		}
	}


	/**
	 * [delete description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function delete( $id = NULL )
	{
		if ($this->input->is_ajax_request()) {
			if ($id != NULL) {
				$this->Orden->delete(array('idOrden' => $id ), $id );
				echo json_encode(array("status" => TRUE));
			}

 		} else {
 			$this->load->view('errors/404.php');
 		}
	}
}

/* End of file Orden_compra.php */
/* Location: ./application/controllers/Orden_compra.php */
 