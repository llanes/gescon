<?php defined('BASEPATH') OR exit('No direct script access allowed');
class CajaActiva extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Caja_Model",'Caja');
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
	{        $fecha = date("Y-m-d");
	       if ($this->db->count_all_results('Empresa') == 0) {
	            redirect('Home','refresh');
			} else {
				$data = array('dataTables.bootstrap' =>'content/datatables/DataTables/css/');
				$this->mi_css_js->init_css_js($data,'css');
				
				$data = array('jquery.dataTables.min' =>'content/datatables/DataTables/js/',
								'dataTables.bootstrap'  =>'content/datatables/DataTables/js/',
							   'script_CajaActiva'  =>'bower_components/script_vista/'
							   );
				$this->mi_css_js->init_css_js($data,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista caja Solo admin///////////////////////////////////////////////////////
		        							   $this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'left');
											   $this->db->where('Cierre = 0');
				  							   $Open = $this->db->get('Caja')->result();
		        $data = array (	
		        	"Alerta" => $this->Producto->get_alert(),
		        	"Open" => $Open
		        	);
				 	$this->load->view('Home/head.php',FALSE);
			        $this->load->view('Home/header.php',$data,FALSE);
					$this->load->view('Home/aside.php',FALSE);
					$this->load->view('Caja/CajaActiva_view.php',FALSE);
					$this->load->view('Home/footer.php',FALSE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else
				{
					$this->load->view('errors/404.php');
				}

			}

	}

	public function loader($value)
	{
		if ($this->input->is_ajax_request()) {
			$this->load->view('Caja/loader.php',$value);
		}else {
					$this->load->view('errors/404.php');
		}
	}

	/**
	 * [verificar_caja description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function verificar_caja($value='')
	{
			$fecha = $this->fecha();
			$inicio_caja  = $this->Caja->inicio_caja($fecha);
			if ($inicio_caja == 1 || $inicio_caja == '') 
			{
				echo json_encode('cerrada');

			} 
			else 
			{
				echo json_encode('abierto');

			}

	}

	/**
	 * [abrir_Cerrar_Caja description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
   public function abrir_Cerrar_Caja($id ='')
    {
		if($this->input->is_ajax_request()){
								// $this->output->enable_profiler(TRUE);
						$hora = strftime( "%H:%M", time() );
						$fecha = date("Y-m-d");
						$impor           = $this->security->xss_clean( $this->input->post('Importe'));
						$Importe         =  str_replace($this->config->item('caracteres'),"",$impor);
						if ($id == 1) {
							$this->form_validation->set_error_delimiters('*','');
							if ($this->form_validation->run('abrir_Cerrar_Caja') == FALSE)
							{
									$data = array(
										'inicio'     => form_error('inicio'),
										'res'         => 'error');
								echo json_encode($data);
							}
							else
							{
								$data               = array(
								'Monto_inicial'     => $impor,
								'Monto_final'       => '',
								'Fecha_apertura'    =>  $fecha ,
								'Hora_apertura'     =>  $hora ,
								'Fecha_cierre'      => '',
								'Hora_cierre'       => '',
								'Apertura'          => $id,
								'Cierre'            => '',
								'Usuario_idUsuario' => $this->session->userdata('idUsuario')
								);
								$data1           = $this->Caja->add_caja($data);
								echo json_encode(array("status" => TRUE));
							}
						};
						if ($id == 0) {
							$fecha1 = $this->fecha();
							if ($fecha1 != $fecha) {
							$data1           = $this->Caja->set_caja($Importe);
							} else {
							$data               = array(
							'Monto_final'       => $impor,
							'Fecha_cierre'      => $fecha ,
							'Hora_cierre'       => $hora,
							'Cierre'            => '1',
							'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
							);
							$data1           = $this->Caja->add_set_caja($data);
							}
						echo json_encode(array("status" => TRUE));
						};

		}else{
			$this->load->view('errors/404.php');
		}
    }

    /**
     * [ajax_list description]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    public function ajax_list($id = '')
	{
		if ($this->input->is_ajax_request()) {
				$fecha = $this->fecha();
				// $this->output->enable_profiler(TRUE);
				$list = $this->Caja->get_caja($fecha);
				$recordsFiltered = $this->Caja->count_filter($fecha);
				$inicial = $this->inicial($fecha);
				$monto_inicial  = $this->monto_inicial();
				$data = array();
				$haber = 0;
				$debe = 0;
				$total = 0;
				$as = '';
				$monto_inicial =  str_replace($this->config->item('caracteres'),"",$monto_inicial);
				$no = $_POST['start'];
				foreach ($list as $caja) {
					$resultadohaber = str_replace($this->config->item('caracteres'),"",$caja->haber);
					$resultadodebe  = str_replace($this->config->item('caracteres'),"",$caja->debe);
					$haber          +=$resultadohaber;
					$debe           +=$resultadodebe;
					$no++;
					$row     = array();
					$Descrip = $this->mi_libreria->getSubString($caja->descripcion,40);
					$row[] = '<p style="text-align:left"><strong>'.$Descrip.'</strong></p>';
					$row[] = '<p style="text-align:left"><strong>'.$caja->fecha.'</strong></p>';
					$row[] = '<p style="text-align:center"><strong>'.$caja->debe.'</strong></p>';
					$row[] = '<p style="text-align:center"><strong>'.$caja->haber.'</strong></p>';
					$row[] = null;
					$data[] = $row;
					////////////////////////////
					if ($no == $recordsFiltered) {
						if ($haber < $debe) {
							$as = $debe - $haber;
							$total =  number_format($as,0,'.',',');
							for ($i = 0; $i <1 ; $i++) {
								$row     = array();
								$row[] = null;
								$row[] = null;
								$row[] = null;
								$row[] = null;
								$row[] = '<p style="text-align:right" class="text-danger total_debe_haber"><big>'.$total.' ₲.</big></p>';
								$data[] = $row;
							}
						} else {
							$as = $debe - $haber;
							$total =  number_format($as,0,'.',',');
							for ($i = 0; $i <1 ; $i++) {
								$row     = array();
								$row[] = null;
								$row[] = null;
								$row[] = null;
								$row[] = null;
								$row[] = '<p style="text-align:right" class="text-danger total_debe_haber"><big>'.$total.'&nbsp;₲. </big></p>';
								$data[] = $row;
							}

						} 
					}else{}
				}
				if ($monto_inicial < $as) {
					$monto_final =  number_format($as + $monto_inicial,0,'.',',');
				} else {
					$monto_final =  number_format($monto_inicial + $as,0,'.',',');
				}
				$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Caja->count_filter($fecha),
						"recordsFiltered" => $this->Caja->count_todas($fecha),
						"data" => $data,
						"Importe" => $monto_final
				);
					echo json_encode($output);
					# code...
		} else {
			$this->load->view('errors/404.php');
		}
	}



	public function edit_caja($id)
	{
		if ($this->input->is_ajax_request()) {
			$this->output->enable_profiler(TRUE);
			$inicio_busca  = $this->Caja->inicio_busca();
			if ($inicio_busca == '') {
				$this->Caja->edit_caja($id);
			} else {
				$this->load->view('errors/404.php');
			}
		} else {
			$this->load->view('errors/404.php');
		}
	}

	private	function fecha()
	{
		$this->db->select('Caja.Fecha_apertura as fecha');
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$this->db->where("Cierre = 0");

		$query = $this->db->get('Caja');
		foreach($query->result_array() as $d)
	{
			return( $d['fecha']);
		}
	}


	private	function inicial($id)
	{
		$this->db->select('Monto_inicial');
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$query = $this->db->get('Caja');
		foreach($query->result_array() as $d)
		{
			return( $d['Monto_inicial']);
		}

	}

	private function monto_inicial()
	{
		$this->db->select('Monto_inicial');
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$this->db->where("Cierre = 0");
		$query = $this->db->get('Caja');
		foreach($query->result_array() as $d)
		{
			return( $d['Monto_inicial']);
		}
	}

	public function movimiento( $offset = 0 )
	{        $fecha = date("Y-m-d");
			if ($this->db->count_all_results('Empresa') == 0) {
				redirect('Home','refresh');

			} else {
				$data = array('dataTables.bootstrap' =>'content/datatables/css/');
				$this->mi_css_js->init_css_js($data,'css');
				
				$data = array('jquery.dataTables.min' =>'content/datatables/js/','dataTables.bootstrap'  =>'content/datatables/js/');
				$this->mi_css_js->init_css_js($data,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista movimiento Solo admin///////////////////////////////////////////////////////
		        $data = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',FALSE);
							        $this->load->view('Home/header.php',$data,FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Caja/Movimiento_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Caja/script_movimient.php');

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
					$no = 0; $nn = 0;
					$resultado = count($variable);
					foreach ($variable as $key) {
						$nn ++;
						if ($key->ID == 3 && $no == 0) {
							$_data = array('data_view' => $variable);
					        //////////////////////////////////////Vista caja Solo admin///////////////////////////////////////////////////////
					        $data = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',FALSE);
							        $this->load->view('Home/header.php',$data,FALSE);
									$this->load->view('Home/aside2.php',$_data ,FALSE);
									$this->load->view('Caja/Movimiento_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Caja/script_movimient.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					        $no ++;
						} else{
							if ( $nn == $resultado) {
								// $this->load->view('errors/404.php');
							}
						}

					}

				}

			}

	}

	/**
	 * [caja_list description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function caja_list($value='')
	{
		if ($this->input->is_ajax_request()) {
		$list = $this->Caja->get_caja_list();
		$inicio_busca  = $this->Caja->inicio_busca();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $caja) {
			$no++;
				$row   = array();
				$row[] =  $caja->idCaja;
				$row[] = $caja->Fecha_apertura.'     &nbsp;&nbsp;'.$caja->Hora_apertura;
				$row[] = $caja->Fecha_cierre.'     &nbsp;&nbsp;'.$caja->Hora_cierre;
				$row[] = $caja->Monto_inicial;
				$row[] = $caja->Monto_final;
				$row[] = $caja->Usuario;
				if ($inicio_busca == 1) {
					$row[] = '<div class="hidden-phone">
					<a class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf('."'".$caja->idCaja."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a EXEL" onclick="exel('."'".$caja->idCaja."'".')">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i>
					</div>';
				} else {
					$row[] = '<div class="hidden-phone">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Editar Caja" onclick="edit_caja('."'".$caja->idCaja."'".')">
					<i class="fa fa-folder-open"></i></a>
					<a class="btn btn-info btn-xs"data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Exportar a PDF" onclick="pdf('."'".$caja->idCaja."'".')">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
					<a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Exportar a EXEL" onclick="exel('."'".$caja->idCaja."'".')">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i>
					</div>';
				}
				


			$data[] = $row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Caja->get_count_todas(),
						"recordsFiltered" => $this->Caja->get_count_filtro(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}


	/**
	 * [movimiento description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function historial( $offset = 0 )
	{        $fecha = date("Y-m-d");
			if ($this->db->count_all_results('Empresa') == 0) {
				redirect('Home','refresh');

			} else {
				$data = array('dataTables.bootstrap' =>'content/datatables/css/');
				$this->mi_css_js->init_css_js($data,'css');
				
				$data = array('jquery.dataTables.min' =>'content/datatables/js/','dataTables.bootstrap'  =>'content/datatables/js/');
				$this->mi_css_js->init_css_js($data,'js');
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista movimiento Solo admin///////////////////////////////////////////////////////
		        $data = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',FALSE);
							        $this->load->view('Home/header.php',$data,FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Histori/Histori_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Histori/script.php');

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
					$no = 0; $nn = 0;
					$resultado = count($variable);
					foreach ($variable as $key) {
						$nn ++;
						if ($key->ID == 4 && $no == 0) {
							$_data = array('data_view' => $variable);
					        //////////////////////////////////////Vista caja Solo admin///////////////////////////////////////////////////////
					        $data = array (	"Alerta" => $this->Producto->get_alert());
								 	$this->load->view('Home/head.php',FALSE);
							        $this->load->view('Home/header.php',$data,FALSE);
									$this->load->view('Home/aside2.php',$_data ,FALSE);
									$this->load->view('Histori/Histori_vista.php');
									$this->load->view('Home/footer.php');
									$this->load->view('Histori/script.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					        $no ++;
						} else{
							if ( $nn == $resultado) {
								// $this->load->view('errors/404.php');
							}
						}

					}

				}

			}

	}


	/**
	 * [caja_list description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function histori_list($value='')
	{
		if ($this->input->is_ajax_request()) {
		$list = $this->Caja->get_hist_list();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $histori) {
			$no++;
				$row   = array();
				$row[] =  $no;
				$Descrip = $this->mi_libreria->getSubString($histori->Descripcion,30);
				$row[] = $Descrip;
				$row[] = $histori->A_Fecha.'     &nbsp;&nbsp;'.$histori->A_Hora;
				$row[] = $histori->Caja_idCaja;
				$row[] = $histori->Usuario;

			$data[] = $row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Caja->get_histcount_todas(),
						"recordsFiltered" => $this->Caja->get_histcount_filtro(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}






}

/* End of file Caja.php */
/* Location: ./application/controllers/Caja.php */
