<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class Descuentos extends CI_Controller {
 
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model("Descuento_Model",'Descuento');
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
        if ($this->session->userdata('Empresa') == 0) {
            redirect('Home','refresh');
        } else {
            $cate = $this->db->where('activo', 1)->limit(10)->get('Categoria')->result();
            $arraycss = array(
                'jquery.dataTables' =>'content/datatables/DataTables/css/',
                'select2'           =>'bower_components/select2/dist/css/',
                'select2-bootstrap' =>'bower_components/select2/dist/css/',
                'toastem' =>'bower_components/jQueryToastem/'
            );
            $this->mi_css_js->init_css_js($arraycss,'css');
            $arrayjs = array(

                'jquery.dataTables.min' =>'content/datatables/DataTables/js/',
                'select2'               =>'bower_components/select2/dist/js/',
                'es'                    =>'bower_components/select2/dist/js//i18n/',
                'script_descuento'       =>'bower_components/script_vista/',
                'toastem'  =>'bower_components/jQueryToastem/'
    
            );
            $this->mi_css_js->init_css_js($arrayjs,'js');

            $data = [
                'Alerta' => $this->Producto->get_alert(),
                'cate' => $cate,

            ];
            if ($this->session->userdata('Permiso_idPermiso') == 1) {
                $this->load->view('Home/head.php', FALSE);
                $this->load->view('Home/header.php', $data, FALSE);
                $this->load->view('Home/aside.php', FALSE);
                $this->load->view('Descuento/Descuento_vista.php', FALSE);
                $this->load->view('Home/footer.php');
            } else {
                    $variable = $this->Model_Menu->octenerMenu(35,$this->session->userdata('Permiso_idPermiso'));
                    // $variable = $this->Model_Menu->octener(32, $this->session->userdata('Permiso_idPermiso'));
                if (!empty($variable)) {
                    $data = [
                        'data_view' => $variable,
                        'Alerta' => $this->Producto->get_alert(),
                        'cate' => $cate,

                    ];
                    $this->load->view('Home/head.php', FALSE);
                    $this->load->view('Home/header.php', $data, FALSE);
                    $this->load->view('Home/aside2.php', FALSE);
                    $this->load->view('Descuento/Descuento_vista.php', FALSE);
                    $this->load->view('Home/footer.php');
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

			$value = $this->input->post('data', TRUE);
			$list = $this->Descuento->get_descuento();
			$data = array();
			$no = $_POST['start'];

			foreach ($list as $descuento) 
			{
					$no++;
					$Nombre = $this->mi_libreria->getSubString($descuento->Nombre, 10);
					$row   = array();
					$row[] = $no;
					$row[] =  $descuento->Categoria;
					$row[] = $descuento->Marca;
					$row[] = $descuento->Descuento.' %';
					$row[] = '<div class="pull-right hidden-phone">
					<a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="	Editar" onclick="edit('."'".$descuento->Categoria_idCategoria."'".')">
					<i class="fa fa-pencil-square"></i></a>
					<a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="_delete('."'".$descuento->Categoria_idCategoria."'".')">
					<i class="fa fa-trash-o"></i></a></div>';
					$data[] = $row;
			}
			$output = array(
				"draw"            => $_POST['draw'],
				"recordsTotal"    => $this->Descuento->count_todas(),
				"recordsFiltered" => $this->Descuento->count_filtro(),
				"data"            => $data,
			);
			//output to json format
			echo json_encode($output);
		} else {
			$this->load->view('errors/404.php');
		}
	}

	public function add($value='')
	{
		if ($this->input->is_ajax_request()) {

				$this->form_validation->set_error_delimiters('*','');
				$categoria = $this->security->xss_clean( $this->input->post('categoria',FALSE));
				$marca     = $this->security->xss_clean( $this->input->post('marca',FALSE));
				$Descuento = $this->security->xss_clean( $this->input->post('Descuento',FALSE));
				if (empty($categoria) And empty($marca)) {
						$this->form_validation->set_rules('categoria', 'Categoria', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
						$this->form_validation->set_rules('marca', 'Marca', 'trim|required|numeric|min_length[1]|max_length[15]|strip_tags'   );
				}else{
						$this->form_validation->set_rules('categoria', 'Categoria', 'trim|numeric|min_length[1]|max_length[11]|strip_tags');
						$this->form_validation->set_rules('marca', 'Producto', 'trim|numeric|min_length[1]|max_length[15]|strip_tags'   );
				}

				$this->form_validation->set_rules('Descuento', 'Descuento', 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags');
				if ($this->form_validation->run() == FALSE)
				{
						$data = array(
							'categoria' => form_error('categoria'),
							'marca'     => form_error('marca'),
							'Descuento' => form_error('Descuento'),
							'res'       => 'error');
					echo json_encode($data);		
				}
				else
				{

					$res = $this->Descuento->insert($categoria,$marca ,$Descuento);
					echo json_encode($res);
				}
			
		}else{
			$this->load->view('errors/404.php');
		}
	}

	public function ajax_edit($id)
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->Descuento->get_by_id($id);
			echo json_encode($data);
		} else {
			$this->load->view('errors/404.php');
		}
	}


	public function ajax_delete($id='')
	{
		if ($this->input->is_ajax_request()) {
               $res = $this->Descuento->insert($id,$marca='' ,$Descuento='NULL');
			   echo json_encode($res);

		}else{
			$this->load->view('errors/404.php');
		}
	}

 }

 /* End of file Descuento.php */
 /* Location: ./application/controllers/Descuento.php */
