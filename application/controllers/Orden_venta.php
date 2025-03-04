<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Orden_venta.php                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: Christian <Christian@student.42.fr>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2016/08/19 23:29:43 by Christian         #+#    #+#             */
/*   Updated: 2016/08/19 23:31:02 by Christian        ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
class Orden_venta extends CI_Controller {

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
//////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
		$data = array(	
			"Alerta" => $this->Producto->get_alert(),
			'Cliente'  =>  $this->db->where('idCliente != 1')->get('Cliente')->result()
		);
		$this->load->view('Home/head.php',$data,FALSE);
		$this->load->view('Home/header.php',FALSE);
		$this->load->view('Home/aside.php');
		$this->load->view('Orden/Orden_venta_vista.php');
		$this->load->view('Home/footer.php');
		$this->load->view('Orden/script_O_V.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	} else {

		$variable =  $this->Model_Menu->octener(11);
		if (!empty($variable)) {
			$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
			$data = array(	
				"Alerta" => $this->Producto->get_alert(),
				'data_view' => $variable,
				'Cliente' => $this->db->where('idCliente != 1')->get('Cliente')->result(),
				'Products'  => $this->db->get('Producto')->result()
			);
			$this->load->view('Home/head.php',$data,FALSE);
			$this->load->view('Home/header.php',FALSE);
			$this->load->view('Home/aside2.php',FALSE);
			$this->load->view('Orden/Orden_venta_vista.php');
			$this->load->view('Home/footer.php');
			$this->load->view('Orden/script_O_V.php');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else {
			$this->load->view('errors/404.php');
		}


	}

}

}

public function select2remote($value='')
{
	if ($this->input->is_ajax_request()) {
		$id = $this->security->xss_clean($this->input->get('q'));
		$query = $this->db->select('idProducto as id, Codigo,Nombre as full_name,replace(Precio_Venta,"_","") as precio,Cantidad_A,Descuento,Iva,Img as avatar_url,Marca')
		                  ->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'inner')
						  ->like('Codigo', $id, 'BOTH')
		                  ->or_like('Nombre', $id, 'BOTH')
		                  ->where('Si_No', false)
		                  ->limit(10)
		                  ->get('Producto');

		$data = array(
			'total_count'        => $query->num_rows(),
			'incomplete_results' => false,
			'items'              => $query->result()
		);
		echo json_encode($data);





	}else{
		$this->load->view('errors/404.php');
	}
}


public function FunctionName($value='')
{
	# code...
}

/**
* [ajax_list Lista de ordenes dispponibles]
* @return [type] [json]
*/
public function ajax_list()
{
	if ($this->input->is_ajax_request()) {
		$list = $this->Orden_V->get_orden_v();
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
			$row[] = $orden_v->tel;
			$row[] = $orden_v->Entrega;
			$row[] = $orden_v->Estado;
			$row[] = $this->mi_libreria->getSubString($Monto,10 ). '&nbsp; ₲.';
			$orden = 'Ovent';
			if ($orden_v->Estado == 'Aprobado') {
				$row[] = '<div class="pull-right hidden-phone">
				<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" 
				data-monto="'.$resultado2.'"
				data-nombre="'.$orden_v->Nombres.'"
				data-apellido="'.$orden_v->Apellidos.'"
				data-user="'.$orden_v->user.'"
				data-fecha="'.$orden_v->Entrega.'"
				data-tel="'.$orden_v->tel.'"
				href="javascript:void(0);"
				id="'.$orden_v->idOrden.'"
				data-total="'.$Monto.'"
				title="Ver Detalles" onclick="ver_detalles('."'".$orden_v->idOrden."'".')">
				<i class="fa fa-eye" aria-hidden="true"></i></a>
				<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$orden."'".','."'".$orden_v->idOrden."'".')">
				<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
				<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/Ovent/'.$orden_v->idOrden.'" title="Exportar a EXEL" >
				<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
				<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar Orden" onclick="_edit('."'".$orden_v->idOrden."'".')">
				<i class="fa fa-pencil-square"></i></a>
				</div>';
				$data[] = $row;
			}else{
				$row[] = '<div class="pull-right hidden-phone">
				<a      data-monto="'.$resultado2.'"
				data-nombre="'.$orden_v->Nombres.'"
				data-apellido="'.$orden_v->Apellidos.'"
				data-user="'.$orden_v->user.'"
				data-fecha="'.$orden_v->Entrega.'"
				data-tel="'.$orden_v->tel.'"
				href="javascript:void(0);"
				id="'.$orden_v->idOrden.'"
				data-total="'.$Monto.'"
				class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Ver Detalles" onclick="ver_detalles('."'".$orden_v->idOrden."'".')">
				<i class="fa fa-eye" aria-hidden="true"></i></a>
				<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('."'".$orden."'".','."'".$orden_v->idOrden."'".')">
				<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
				<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="Reporte_exel/Ovent/'.$orden_v->idOrden.'" title="Exportar a EXEL" >
				<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
				<a class="btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Editar Orden" onclick="_edit('."'".$orden_v->idOrden."'".')">
				<i class="fa fa-pencil-square"></i></a>
				<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$orden_v->idOrden."'".')">
				<i class="fa fa-trash-o"></i></a></div>';
				$data[] = $row;
			}

		}
		$output = array(
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->Orden_V->count_todas(),
			"recordsFiltered" => $this->Orden_V->count_filtro(),
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
			$this->load->view('Orden/cart_get_v.php');
		}else{
			$this->cart->destroy();
			$this->load->view('Orden/cart_get_v.php');
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
* [agregar_item description]
* @param  [type] $value [description]
* @return [type]        [description]
*/
public function agregar_item($value)
{
	if ($this->input->is_ajax_request()) {
		$data = $this->Orden_V->agregar_item(array('idProducto' => $value));
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
		if ($this->form_validation->run('add_Orden_venta') == FALSE)
		{
			$data = array(
				'changecliente' => form_error('changecliente'),
				'Entrega'       => form_error('Entrega'),
				'Envio_m'       => form_error('Envio_m'),
				'Estado'        => form_error('Estado'),
				'observac'      => form_error('observac'),
				'res'           => 'error');
			echo json_encode($data);
		}else{
			$data                         = array(
				'Compra_Venta'          => $this->security->xss_clean('2'),
				'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
				'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
				'Devolucion'            => $this->security->xss_clean( $this->input->post('Devolucion',FALSE)),
				'Estado'                => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
				'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
				'Monto_envio'           => $this->security->xss_clean( $this->input->post('Envio_m',FALSE)),
				'Usuario_idUsuario'     => $this->security->xss_clean( $this->session->userdata('idUsuario')),
				'Cliente_idCliente'     => $this->security->xss_clean( $this->input->post('changecliente',FALSE)),
				'Proveedor_idProveedor' => NULL
			);
			$insert = $this->Orden_V->insert($data);
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
		if ($this->form_validation->run('add_Orden_venta') == FALSE)
		{
			$data = array(
				'changecliente' => form_error('changecliente'),
				'Entrega'       => form_error('Entrega'),
				'Envio_m'       => form_error('Envio_m'),
				'Estado'        => form_error('Estado'),
				'observac'      => form_error('observac'),
				'res'           => 'error');
			echo json_encode($data);		
		}else{
			$data                         = array(
				'Compra_Venta'          => $this->security->xss_clean('2'),
				'Observacion'           => $this->security->xss_clean( $this->input->post('observac',FALSE)),
				'Entrega'               => $this->security->xss_clean( $this->input->post('Entrega',FALSE)),
				'Devolucion'            => $this->security->xss_clean( $this->input->post('Devolucion',FALSE)),
				'Estado'                => $this->security->xss_clean( $this->input->post('Estado',FALSE)),
				'Monto_Estimado'        => $this->security->xss_clean( $this->cart->total()),
				'Monto_envio'           => $this->security->xss_clean( $this->input->post('Envio_m',FALSE)),
				'Usuario_idUsuario'     => $this->security->xss_clean( $this->session->userdata('idUsuario')),
				'Cliente_idCliente'     => $this->security->xss_clean( $this->input->post('changecliente',FALSE)),
				'Proveedor_idProveedor' => NULL
			);
			$insert = $this->Orden_V->update(array('idOrden' => $this->input->post('idOrden')), $data,$this->input->post('idOrden'));
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





/**
* [delete description]
* @param  [type] $id [description]
* @return [type]     [description]
*/
public function delete( $id = NULL )
{
	if ($this->input->is_ajax_request()) {
		if ($id != NULL) {
			$this->Orden_V->delete(array('idOrden' => $id ), $id );
			echo json_encode(array("status" => TRUE));
		}

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

		$this->db->select('Ruc');
		$this->db->where('Ruc',$ruc_id);
		$consulta = $this->db->get('Cliente');
		if ($consulta->num_rows()> 0) {
			$this->form_validation->set_message('check_ruc', "$ruc_id ya registrado");
			return FALSE;
		}
		else
		{
			return TRUE;
		}

}

public function insercliente($value='')
{
	if ($this->input->is_ajax_request()) {
		header('Content-Type: application/json');
		if ($this->form_validation->run('insercliente') == FALSE)
		{
			$data = array(
				'nombre' => form_error('nom'),
				'Telefono' => form_error('telefon'),
				'ruc' => form_error('ruc'),
				'limite_credito' => form_error('credito'),
				'res' => 'error');
			echo json_encode($data);
		} else {
			$dat = array(
				'Nombres'   => $this->input->post('nom', FALSE),
				'Ruc' => $this->input->post('ruc', FALSE),
				'Apellidos' => $this->input->post('ape', FALSE),
				'Direccion' => $this->input->post('Direccion', FALSE),
				'Telefono'  => $this->input->post('telefon', FALSE),
				'Correo'    => $this->input->post('Email', FALSE),
				'Limite_max_Credito'    => $this->input->post('credito', FALSE),
			);
			// $this->output->enable_profiler(TRUE);

			if ($this->db->insert('Cliente', $dat)) {
				$id = $this->db->insert_id();
			} else {
				$id = false;
			}
		
			echo json_encode( array('id' => $id));


		}


	}else{
		$this->load->view('errors/404.php');
	}
}
}

/* End of file Orden_venta.php */
/* Location: ./application/controllers/Orden_venta.php */
