<?php

// Incluir el autoloader para escpos-php
require_once(APPPATH . 'third_party/escpos/autoload.php');  // Ajusta la ruta si es necesario

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Escpos {

    public function __construct() {
        // Constructor vacío
    }

    public function printTicket($printerName, $ticketText) {
        try {
            // Conexión con la impresora
            $connector = new WindowsPrintConnector($printerName);  // Conexión local
            // Si es impresora de red, usa NetworkPrintConnector:
            // $connector = new NetworkPrintConnector("192.168.1.100", 9100);

            $printer = new Printer($connector);
            $printer->text($ticketText);  // Imprimir el ticket
            $printer->cut();  // Cortar el papel
            $printer->close();  // Cerrar la conexión
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
