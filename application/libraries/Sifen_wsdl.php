<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sifen_wsdl {

    private $client;
    private $certificado;
    private $clave_privada;

    public function __construct() {
        // Cargar la configuración de la empresa al instanciar la librería
        $this->CI =& get_instance();
        $this->CI->load->helper('file');  // Para manejo de archivos
    }

    // Configurar los certificados y la conexión SOAP
    public function set_empresa($ruc) {
        // Obtener el certificado y la clave privada de la empresa (ruta configurada en la DB o archivo de configuración)
        $empresa = $this->CI->Empresa_model->get_empresa_by_ruc($ruc); // Asegúrate de tener la empresa en la base de datos

        $this->certificado = $empresa->certificado_path;  // Ruta al certificado
        $this->clave_privada = $empresa->clave_privada_path;  // Ruta a la clave privada

        // Configuración de SOAP Client
        $this->client = new SoapClient(
            'https://sifen.set.gov.py/de/ws/async/recibe-lote.wsdl', 
            [
                'trace' => 1, 
                'exceptions' => true,
                'local_cert' => $this->certificado, 
                'private_key' => $this->clave_privada, 
                'passphrase' => 'tu_clave_aqui', // Passphrase si está configurado
                'soap_version' => SOAP_1_2,
                'connection_timeout' => 60
            ]
        );
    }

    // Generar el XML del lote de documentos electrónicos
    public function generar_xml_lote($ruc) {
        // Aquí generas el XML con la estructura y los datos necesarios
        $lote = '<rLoteDE xmlns="http://ekuatia.set.gov.py/sifen/xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepDE_v150.xsd">';

        // Ejemplo de un DE, aquí deberías agregar la lógica de generación de cada documento dentro del lote
        $lote .= '<rDE>';
        $lote .= '<dRucEmisor>' . $ruc . '</dRucEmisor>';
        $lote .= '</rDE>';
        
        $lote .= '</rLoteDE>';

        // Comprimir y codificar en Base64
        $xml_comprimido = gzcompress($lote);
        return base64_encode($xml_comprimido);  // Devuelve el XML comprimido y codificado en Base64
    }

    // Enviar lote de documentos electrónicos
    public function enviar_lote($ruc, $xml_base64) {
        $params = [
            'dId' => '20240926',  // ID del lote
            'xDE' => $xml_base64   // El XML codificado en Base64
        ];

        try {
            // Llamar al servicio SOAP para enviar el lote
            $response = $this->client->__soapCall('recibe-lote', [$params]);
            return $response;
        } catch (SoapFault $fault) {
            log_message('error', 'Error en el servicio SOAP: ' . $fault->getMessage());
            return false;
        }
    }

    // Consultar el estado de un lote
    public function consultar_lote($ruc, $numero_lote) {
        $params = [
            'dId' => '1',  // ID de la consulta
            'dProtConsLote' => $numero_lote  // Número de lote
        ];

        try {
            // Llamar al servicio SOAP para consultar el estado del lote
            $response = $this->client->__soapCall('consulta-lote', [$params]);
            return $response;
        } catch (SoapFault $fault) {
            log_message('error', 'Error en el servicio SOAP: ' . $fault->getMessage());
            return false;
        }
    }

    // Consultar un Documento Electrónico por su CDC
    public function consultar_documento($ruc, $cdc) {
        $params = [
            'dId' => '12',
            'dCDC' => $cdc
        ];

        try {
            // Llamar al servicio SOAP para consultar el estado del documento
            $response = $this->client->__soapCall('consulta', [$params]);
            return $response;
        } catch (SoapFault $fault) {
            log_message('error', 'Error en el servicio SOAP: ' . $fault->getMessage());
            return false;
        }
    }
}
