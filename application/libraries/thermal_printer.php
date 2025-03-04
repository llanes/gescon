<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Asegúrate de incluir la librería de Mike42
require_once APPPATH."/third_party/Mike42/autoload.php";
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Thermal_printer {
    private $printer;

    public function __construct() {
        // No cierres la conexión aquí en el constructor
    }
// Método para abrir la conexión con la impresora
private function openPrinterConnection() {
    // Configura el conector de la impresora (ajusta el nombre de la impresora según sea necesario)
    $connector = new WindowsPrintConnector("Microsoft Print to PDF");
    // Crea una instancia de la impresora
    $this->printer = new Printer($connector);
}

    // Método para imprimir un ticket
    public function printTicket($data) {
        // Abre la conexión con la impresora si aún no está abierta
        if (!$this->printer) {
            $this->openPrinterConnection();
        }

        // Aquí agregarías el contenido del ticket usando los métodos de la librería Mike42
        $this->printer->text($data);
        // Cierra la conexión con la impresora
        $this->printer->close();
    }

    // Destructor para manejar la limpieza
    public function __destruct() {
        // Cierra la conexión con la impresora solo si está abierta
        if ($this->printer) {
            $this->printer->close();
        }
    }
}


/* End of file Thermal_printer.php */
/* Location: ./application/libraries/Thermal_printer.php */
