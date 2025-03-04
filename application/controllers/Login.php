<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Load Dependencies
		$this->load->model("Logeo_Model");

	}
	public function index()
	{
		if(!$this->session->userdata('idUsuario')) {
			$this->inicio();
		}else{
				redirect('Inicio');


		}
	}
	public function inicio( $offset = 0 )
	{
		if(!$this->session->userdata('idUsuario')) {
			$this->load->view('Login/Vista_login.php',FALSE); // este seria todo el contenido central
		}else{
			redirect('Inicio');
		}

	}
		// Funcion de logeo
	public function logeo()
	{
		// $this->output->enable_profiler(TRUE);
	if($this->input->is_ajax_request()){
				// la validacion esta en el la carpeta config
				$this->form_validation->set_error_delimiters(' ',' ');
				if ($this->form_validation->run('Login_validation') == FALSE)
				{
						$data = array(
							'usuario'   => form_error('usuario'),
							'password'  => form_error('password'),
							'res'		=> 'error');

				}else{
					
					$Usuario = $this->security->xss_clean(strip_tags( $this->input->post('usuario')));
					$Password  = sha1($this->security->xss_clean(strip_tags( $this->input->post('password'))));					// conecion con el modelo
					$fila = $this->Logeo_Model->logeo($Usuario, $Password);
						if (!is_null($fila)) {
							$idcaja = $this->ultimoCaja($fila->idUsuario);
							$data = array(
										'idUsuario'         => $fila->idUsuario,
										'Usuario'           => $fila->Usuario,
										'Permiso_idPermiso' => $fila->Permiso_idPermiso,
										'idCaja'            =>$idcaja,
										'Empresa'           =>$this->db->count_all_results('Empresa'),
										'alerSession'       => true

										);
									$this->session->set_userdata($data);

						}else{
							$data = array(
							'password'   => '<ul class="list-unstyled text-danger"><li>Contraseña Incorrecta</li></ul>',
							'res'		=> 'error');
						}
				}
			echo json_encode($data);
		}else{
			$this->load->view('errors/404.php');
		}
	}
	public function ultimoCaja($ID)
    {
            $this->db->select_max('idCaja');
            $this->db->where("Cierre = 0");
            $this->db->where('Usuario_idUsuario',$ID);
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }
	// cerrar seccion
	public function logout()
	{
		$this->session->sess_destroy();
        $this->cache->clean();
		redirect('Ingresar');
	}

// calback usuario chequo si el usuario ingresado para logearse es vlido
	public function check_nombre($usuario)
	{

	if ($this->Logeo_Model->check_nombre($usuario)) {
			return TRUE;
        }
        else
        {
              $this->form_validation->set_message('check_nombre', 'Nombre Incorrecto');
            return FALSE;
        }
	}
	// comprovar si existe nobre de usuario para registro cliente
	public function check_User($user_id)
	{

	if ($this->Logeo_Model->check_User($user_id)) {
			$this->form_validation->set_message('check_User', "$user_id no Disponible");
			return FALSE;
        }
        else
        {
            return TRUE;
        }
	}
	// calback password chequo si el password ingresado para logearse es vlido
	public function check_pass($password)
	{
	if ($this->Logeo_Model->check_pass(sha1($password))) {
			return TRUE;
		}else {
		$this->form_validation->set_message('check_pass', 'Contraseña Incorrecta');
		return FALSE;}

	}
}

/* End of file Login.PHP */
/* Location: ./application/controllers/Login.PHP */
