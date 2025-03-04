<?php
// application/controllers/Configuracion.php

class Configuracion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Impresora_model');
    }

    public function index() {
        if ($this->db->count_all_results('Empresa') == 0) {
            $data = $this->session->userdata('Usuario');
            //redirecionamos a la vista o llamamos a la vista index
            $this->load->view('Home/head.php',$data, FALSE);	// carga todos las url de estilo i js home	
            $this->load->view('Home/header1.php',$data, FALSE); // esta seria la barra de navegacion horizontal
            $this->load->view('Home/section2.php',$data, FALSE); // este seria todo el contenido central
            $this->load->view('Home/footer.php'); // pie con los js
            $this->load->view('Home/script.php'); // pie con los js
    } else {
            if($this->session->userdata('Permiso_idPermiso') == 1) { // si la seccion no existe me quedo en el homo

                $data['Alerta'] =  $this->Producto->get_alert();

                $this->load->view('Home/head.php',FALSE);	// carga todos las url de estilo i js home	
                $this->load->view('Home/header.php',$data,false); // esta seria la barra de navegacion horizontal
                $this->load->view('Home/aside.php',FALSE); // este seria todo el contenido central
                $this->load->view('config/impresoras/configuracion_impresoras');

                $this->load->view('Home/footer.php'); // pie con los js
            }else{

            }  
    }



       
    }


    public function obtener_impresoras() {
        $data['impresoras'] = $this->Impresora_model->obtener_impresoras();
        $this->load->view('config/impresoras/impresoraTable', $data);
    }

        public function guardar_impresora() {
            $modelo_impresora = $this->input->post('modelo_impresora');
            list($nombre, $modelo) = explode('|', $modelo_impresora);

            $data = array(
                'nombre' => $nombre,
                'tamano_papel' => $this->input->post('tamano_papel'),
                'conexion' => $this->input->post('conexion'),
                'modelo' => $modelo,
            );

            $this->Impresora_model->guardar_impresora($data);

                redirect('configuracion');

        }

    public function editar_impresora($id) {
        // Lógica para editar la impresora
    }

        public function eliminar_impresora() {
            $id = $this->input->post('id');

            if (!empty($id)) {
                $success = $this->Impresora_model->eliminar_impresora($id);

                $response = $success
                    ? ['status' => 'success', 'message' => 'Impresora eliminada']
                    : ['status' => 'error', 'message' => 'No se pudo eliminar la impresora'];
            } else {
                $response = ['status' => 'error', 'message' => 'ID de impresora inválido'];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }

    public function obtenerImpresoras() {
        // $this->load->library('Thermal_printer');
        $data = "Contenido del ticket"; // El contenido del ticket que deseas imprimir
        // $this->thermal_printer->printTicket($data);
        

    }


}
