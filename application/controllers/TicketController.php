<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TicketController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Cargar la librería Escpos (si la necesitas en el futuro)
        $this->load->library('Escpos');
    
        // Verificar si la librería Escpos fue cargada correctamente
        if (!isset($this->escpos)) {
            echo 'Librería Escpos no cargada correctamente';
            exit;
        }
    }

    // Mostrar página de inicio
    public function index() {
        $this->load->view('copos');
    }

    // Función para generar el contenido del ticket
    public function generate_ticket() {
        $ticketText = "¡Hola, Mundo!\nEste es un ticket de prueba.\n\nGracias por su compra!";
        
        // Enviar los datos del ticket a la vista
        echo json_encode(['ticket' => $ticketText]);
    }

    // Función para verificar la conexión con la impresora (en caso de que esté conectada)
    private function check_printer_connection($printerName) {
        try {
            // Intentamos conectarnos a la impresora
            $connector = new WindowsPrintConnector($printerName);  // Conexión con impresora local
            $printer = new Printer($connector);  // Crear el objeto Printer

            // Si no lanza excepciones, la conexión fue exitosa
            $printer->close();
            return true;
        } catch (Exception $e) {
            // Si hay un error, la impresora no está disponible
            return false;
        }
    }
}
