<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sifen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Sifen_wsdl');  // Cargar la librería de integración con SIFEN
        // $this->load->model('Empresa_model'); // Modelo para gestionar empresas
    }

    // Cargar la vista principal de SIFEN
    public function index() {
        $this->load->view('sifen_view');  // Cargar la vista "sifen_view.php"
    }

    // Métodos para las acciones (generar, enviar, consultar)
    public function generar_documento_electronico() {
        $ruc = $this->input->post('ruc');
        if ($ruc) {
            $xml_base64 = $this->sifen_wsdl->generar_xml_lote($ruc);
            $data['response'] = "Documento generado y codificado en Base64.";
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'RUC no válido']);
        }
    }

    public function enviar_lote() {
        $ruc = $this->input->post('ruc');
        $xml_base64 = $this->sifen_wsdl->generar_xml_lote($ruc);
        $response = $this->sifen_wsdl->enviar_lote($ruc, $xml_base64);
        echo json_encode($response);  // Retornar la respuesta de SIFEN
    }

    public function consultar_lote() {
        $numero_lote = $this->input->post('numero_lote');
        $response = $this->sifen_wsdl->consultar_lote($ruc, $numero_lote);
        echo json_encode($response);
    }

    public function consultar_documento() {
        $cdc = $this->input->post('cdc');
        $response = $this->sifen_wsdl->consultar_documento($ruc, $cdc);
        echo json_encode($response);
    }
}
