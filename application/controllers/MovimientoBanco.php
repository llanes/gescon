<?php  defined('BASEPATH') OR exit('No direct script access allowed');
 
 class MovimientoBanco extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		//Load Dependencies
 		$this->load->model('MovimientoBanco_Model', 'Movi');
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
				$this->db->where('Moneda', 'PYG');
				$Moneda = $this->db->get('Moneda')->result();
				$data = array //arreglo para mandar datos a la vista
				(
					"Alerta" => $this->Producto->get_alert(),
					'Banco' =>$this->db->get('Gestor_Bancos')->result(),
					'Moneda' => $Moneda,
				);

				if ($this->session->userdata('Permiso_idPermiso') == 1) {
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
							$this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside.php');
							$this->load->view('MovimientoBanco/MovimientoBanco_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('MovimientoBanco/script_.php'); // pie con los js
				} else {



					$variable =  $this->Model_Menu->octener(28);
					if (!empty($variable)) {

						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
							$_data = array('data_view' => $variable,);
							$this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
				            $this->load->view('Home/header.php',$data,FALSE); // esta seria la barra de navegacion horizontal
							$this->load->view('Home/aside2.php',$_data,FALSE);
							$this->load->view('MovimientoBanco/MovimientoBanco_vista.php'); // este seria todo el contenido central
							$this->load->view('Home/sidebar.php',FALSE); // este seria todo el contenido central
							$this->load->view('Home/pie_js.php'); // pie con los js
							$this->load->view('MovimientoBanco/script_.php'); // pie con los js
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
	public function ajax_list()
	{
		if ($this->input->is_ajax_request()) 
		{
			// $this->output->enable_profiler(TRUE);
			$list = $this->Movi->get_Movi();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $Movi) 
			{
					$no++;
					$row   = array();
					if (!empty($Movi->NumeroCheque)) {
						$row[] =  $Movi->NumeroCheque;
					}else{
						$row[] =  'Efectivo';
					}

			    	if (!empty($Movi->PlandeCuenta_idPlandeCuenta)) {
			    		$row[] =  $Movi->Balance_General;
			    	}else{
				    	if ($Movi->Entrada_Salida == 'Entrada') {
				    	$row[] =  $Movi->ConceptoEntrada;
				    	}else{
				    	 $row[] =  $Movi->ConceptoSalida;
				    	}
			    	}


					$row[] = $Movi->FechaExpedicion;
					$row[] = number_format($Movi->Importe,0,'.',',').'&nbsp; ₲S.';
					$row[] = $Movi->Nombre;
					$row[] =  $Movi->Entrada_Salida;
					if ($Movi->Control == 0) {
					$row[] = '';
					}else{
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Hapus" onclick="_delete('."'".$Movi->idMovimientos."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					}

					$row[] =  $Movi->idMovimientos;
					$data[] = $row;

			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Movi->count_todas(),
				"recordsFiltered" => $this->Movi->count_filtro(),
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
				if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
				$this->form_validation->set_error_delimiters('*','');
				$this->form_validation->set_rules('Cheques', 'Cheques', 'trim|required|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('PlandeCuenta', 'Plan de Cuenta', 'trim|required|min_length[1]|max_length[14]|strip_tags');
				$this->form_validation->set_rules('cuenta_bancaria', 'Banco', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('movi', 'Movimiento', 'trim|required|min_length[1]|max_length[15]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'Cheques'         => form_error('Cheques'),
							'PlandeCuenta'    => form_error('PlandeCuenta'),
							'cuenta_bancaria' => form_error('cuenta_bancaria'),
							'movi'            => form_error('movi'),
							'res'		=> 'error');
					echo json_encode($data);
				}else{
					// $this->output->enable_profiler(TRUE);
					$Acheque_tercero = $this->security->xss_clean($this->input->post('Acheque_tercero',FALSE));
					$Acheque_m       = $this->security->xss_clean($this->input->post('Acheque_m',FALSE));
					$PlandeCuenta    = $this->security->xss_clean($this->input->post('PlandeCuenta',FALSE));
					$cuenta_bancaria = $this->security->xss_clean($this->input->post('cuenta_bancaria',FALSE));
					$movi            = $this->security->xss_clean($this->input->post('movi',FALSE));
					$insert          = $this->Movi->insert($Acheque_tercero ,$PlandeCuenta,$cuenta_bancaria,$movi,$Acheque_m     );
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}
 	/**
 	 * [ajax_add description]
 	 * @return [type] [description]
 	 */
	public function ajax_add_()
	{
				if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
				$this->form_validation->set_error_delimiters('*','');
				$this->form_validation->set_rules('Numeru', 'Numeru', 'trim|required|callback_username_check|numeric|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('PlandeCuenta', 'Plan de Cuenta', 'trim|required|min_length[1]|max_length[14]|strip_tags');
				$this->form_validation->set_rules('cuenta_bancaria', 'Banco', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Importe', 'Importe', 'trim|required|min_length[1]|max_length[25]|strip_tags');
				$this->form_validation->set_rules('fecha', 'Fecha', 'trim|required|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('movi', 'Movimiento', 'trim|required|min_length[1]|max_length[15]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
								'Numeru'         => form_error('Numeru'),
								'PlandeCuenta'    => form_error('PlandeCuenta'),
								'cuenta_bancaria' => form_error('cuenta_bancaria'),
								'Importe'         => form_error('Importe'),
								'fecha'           => form_error('fecha'),
								'movi'            => form_error('movi'),
								'res'             => 'error');
					echo json_encode($data);
				}else{
					$Numeru         = $this->security->xss_clean($this->input->post('Numeru',FALSE));
					$PlandeCuenta    = $this->security->xss_clean($this->input->post('PlandeCuenta',FALSE));
					$cuenta_bancaria = $this->security->xss_clean($this->input->post('cuenta_bancaria',FALSE));
					$Importe    = $this->security->xss_clean($this->input->post('Importe',FALSE));
					$fecha = $this->security->xss_clean($this->input->post('fecha',FALSE));
					$movi         = $this->security->xss_clean($this->input->post('movi',FALSE));
					$insert = $this->Movi->insert2($Numeru ,$PlandeCuenta,$cuenta_bancaria,$Importe,$fecha,$movi   );
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}


	public function ajax_add_e()
	{
				if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
				$this->form_validation->set_error_delimiters('*','');
			    $val = $this->security->xss_clean( $this->input->post('val',FALSE));
			    $parcial1 = $this->security->xss_clean( $this->input->post('parcial1',FALSE));
				for ($i=1; $i <= $val; $i++) { 
				$this->form_validation->set_rules('EF'.$i, 'Moneda', 'trim|numeric|min_length[1]|max_length[25]|strip_tags');

				}
				$this->form_validation->set_rules('PlandeCuenta', 'Plan de Cuenta', 'trim|required|min_length[1]|max_length[25]|strip_tags');
				$this->form_validation->set_rules('movi', 'Movimiento', 'trim|required|min_length[1]|max_length[25]|strip_tags');
		    	$this->form_validation->set_rules('parcial1', 'Moneda', 'trim|required|min_length[1]|max_length[30]|strip_tags',array('required' => 'Es necesario completar algun campo es obligatorio'));
		    	$this->form_validation->set_rules('cuenta_bancaria', 'Banco', 'trim|required|numeric|min_length[1]|max_length[25]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
								'EF1'           => form_error('EF1'),
								'EF2'          => form_error('EF2'),
								'EF3'           => form_error('EF3'),
								'EF4'          => form_error('EF4'),
								'EF5'           => form_error('EF5'),
								'EF6'          => form_error('EF6'),
								'EF7'           => form_error('EF7'),
								'EF8'          => form_error('EF8'),
								'PlandeCuenta'    => form_error('PlandeCuenta'),
								'movi'            => form_error('movi'),
								'parcial1'        => form_error('parcial1'),
								'cuenta_bancaria' => form_error('cuenta_bancaria'),
								'res'             => 'error');
				echo json_encode($data);


				}else{
							$moneda = array();
						for ($i=1; $i <= $val ; $i++) {
						 	$Moneda  =	$this->security->xss_clean( $this->input->post('Moneda'.$i,FALSE));
						 	$importe  =	$this->security->xss_clean( $this->input->post('EF'.$i,FALSE));
						 	$Signo  =	$this->security->xss_clean( $this->input->post('Signo'.$i,FALSE));

						 	if ($importe>0) {
						 		$moneda[ ] = array(
						 			'Moneda' => $Moneda, 
						 			'importe' => $importe, 
						 			'Signo'=> $Signo, 
						 			);
						 	}

						 }
						 // $this->output->enable_profiler(TRUE);
					$cuenta_bancaria = $this->security->xss_clean($this->input->post('cuenta_bancaria',FALSE));
					$movi         = $this->security->xss_clean($this->input->post('movi',FALSE));
					$PlandeCuenta    = $this->security->xss_clean($this->input->post('PlandeCuenta',FALSE));
					$insert = $this->Movi->insert3($movi ,$PlandeCuenta,$moneda,$cuenta_bancaria);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}
 	/**
	 * [ajax_edit description]
	 * @param  [type] $idEmpleado [description]
	 * @return [type]            [description]
	 */
 	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Movi->get_by_id($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
		
	}

	/**
	 * [ajax_update description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
 	public function ajax_update_()
	{
				if ($this->input->is_ajax_request()) { // una forma  de controlar o validar las peticiones de ajax 
				$this->form_validation->set_error_delimiters('*','');

				$this->form_validation->set_rules('Numeru', 'Numeru', 'trim|required|callback_username_checkk['.$this->input->post('id',FALSE).']|numeric|min_length[1]|max_length[50]|strip_tags');
				$this->form_validation->set_rules('PlandeCuenta', 'Plan de Cuenta', 'trim|required|min_length[1]|max_length[14]|strip_tags');
				$this->form_validation->set_rules('cuenta_bancaria', 'Banco', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags');
				$this->form_validation->set_rules('Importe', 'Importe', 'trim|required|min_length[1]|max_length[25]|strip_tags');
				$this->form_validation->set_rules('fecha', 'Fecha', 'trim|required|min_length[1]|max_length[15]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
								'Numeru'         => form_error('Numeru'),
								'PlandeCuenta'    => form_error('PlandeCuenta'),
								'cuenta_bancaria' => form_error('cuenta_bancaria'),
								'Importe'         => form_error('Importe'),
								'fecha'           => form_error('fecha'),
								'res'             => 'error');
					echo json_encode($data);
				}else{
					$id         = $this->input->post('id');
					$Numeru         = $this->security->xss_clean($this->input->post('Numeru',FALSE));
					$PlandeCuenta    = $this->security->xss_clean($this->input->post('PlandeCuenta',FALSE));
					$cuenta_bancaria = $this->security->xss_clean($this->input->post('cuenta_bancaria',FALSE));
					$Importe    = $this->security->xss_clean($this->input->post('Importe',FALSE));
					$fecha = $this->security->xss_clean($this->input->post('fecha',FALSE));
					$data = array(
						'NumeroCheque' => $Numeru ,
						'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
						'Gestor_Bancos_idGestor_Bancos' => $cuenta_bancaria ,
						'Importe' => $Importe ,
						'FechaPago' => $fecha,
						 );

					$this->Movi->update(array('idMovimientos' => $this->input->post('id')), $data);
					echo json_encode(array("status" => TRUE));
				}
        }else{
			$this->load->view('errors/404.php');
		}
	}


 
 	/**
 	 * [ajax_delete description]
 	 * @param  [type] $id [description]
 	 * @return [type]     [description]
 	 */
 	public function ajax_delete( $id = NULL )
 	{
 		if ($this->input->is_ajax_request()) {
 			$this->Movi->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
 		} else {
 			$this->load->view('errors/404.php');
 		}

 	}

 	/**
 	 * [check_marca description]
 	 * @param  [type] $marca_id [description]
 	 * @return [type]           [description]
 	 */
 	function username_check($check)
	{
		// $this->output->enable_profiler(TRUE);
	if ($this->Movi->username_check($check)) {
			$this->form_validation->set_message('username_check', "$check no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}



 	function username_checkk($checkk,$id='')
	{
		// $this->output->enable_profiler(TRUE);
	if ($this->Movi->username_checkk($checkk,$id)) {
			$this->form_validation->set_message('username_checkk', "$checkk no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}





	public function Cheques($value='')
	{
		if ($this->input->is_ajax_request()) {
			// $this->output->enable_profiler(TRUE);
		    $this->db->select('idMovimientos as id, NumeroCheque as numch,Importe as im ');
		    $this->db->where('Gestor_Bancos_idGestor_Bancos IS NULL');
		    $this->db->where('Entrada_Salida',$value);
		    $this->db->where('Activo_Inactivo = 1');
		     $query = $this->db->get('Movimientos');
		    if ($query->num_rows() > 0) {
		        $options='<option value=""></option>';
		        foreach ($query->result() as $key => $value) {
		            $options.='<option value='.$value->id.','.$value->im.'>['.$value->numch.'] '.number_format($value->im,0,'.',',').' ₲S.</option>';
		        }
		     echo $options; 
		     }else{
              echo $options='<option value=""></option>';
		     }
		} else {
 			$this->load->view('errors/404.php');
 		}
	}
 }
 
 /* End of file MovimientoBanco.php */
 /* Location: ./application/controllers/MovimientoBanco.php */
