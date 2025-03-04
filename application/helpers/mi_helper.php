<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('get_users'))
{
	function get_acientos()
	{
		 date_default_timezone_set("America/Asuncion");
        //asignamos a $ci el super objeto de codeigniter
		$ci =& get_instance();
		$ci->db->join('PlandeCuenta', 'Acientos.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'inner');
		$ci->db->where('Fecha',date("Y-m-d"));
		$query = $ci->db->get('Acientos');
		return $query->result();

	}
}

if (!function_exists('Ticket')) {
	function Ticket()
	{


        $CI =& get_instance();
     

            $NumeroTicket =  $CI->session->userdata('NumeroTicket');

            if ($NumeroTicket) {
                
                $data = array(
                            'NumeroTicket' => $NumeroTicket,
                            // 'idNumeracion' => $CI->session->userdata('idNumeracion')
                    );
                return $data;
            } else {
                try {
                    $CI->db->trans_start(); // Iniciar transacción
                    $CI->db->query("CALL ConsultaTiket()");
                    $query = $CI->db->query("SELECT numero_actual as NumeroTicket, idNumeracion FROM NumeracionDocumentos WHERE TipoDocumento = 'TiCket' AND numero_actual <= numero_final LIMIT 1");
                    if ($query) {
                        // Process the results
                        $row = $query->row();

                        $data = array(
                            'NumeroTicket' => $row->NumeroTicket,

                        );
                        $CI->session->set_userdata($data); // Usar set_flashdata en lugar de set_userdata
                        // Close the result set
                        $query->free_result();
                    } else {
                        echo "Query failed: " . $CI->db->error();
                    }


                  


                    $CI->db->trans_complete(); // Completar transacción

                    if ($CI->db->trans_status() === FALSE) {
                        throw new Exception('Error al actualizar la numeración de la factura');
                    }

                    return $data;
                } catch (Exception $e) {
                    return false; // No se encontraron números de factura disponibles.
                }
            }



    // }
	}
}
if (!function_exists('comprobante')) {
	function comprobante()
	{

            $CI =& get_instance();
                $numero_factura = false;
                $TerceraSeccion =  $CI->session->userdata('TerceraSeccion');

                if ($TerceraSeccion) {

                    $data = array(
                                'PrimeraSeccion' => $CI->session->userdata('PrimeraSeccion'),
                                'SegundaSeccion' => $CI->session->userdata('SegundaSeccion'),
                                'TerceraSeccion' => $TerceraSeccion,
                                // 'idNumeracion' => $CI->session->userdata('idNumeracion')

                        );
                    return $data;
                } else {
                    try {


                        $CI->db->trans_start(); // Iniciar transacción

                        $CI->db->query("CALL ConsultaFactura()");
                        $query = $CI->db->query("SELECT PrimeraSeccion, SegundaSeccion, numero_actual AS TerceraSeccion, idNumeracion, numero_final
                                                FROM NumeracionDocumentos
                                                WHERE TipoDocumento = 'Factura' AND numero_actual <= numero_final
                                                LIMIT 1");
  
                        if ($query) {
                            // Process the results
                            $row = $query->row();
                                $data = array(
                                    'PrimeraSeccion' => $row->PrimeraSeccion,
                                    'SegundaSeccion' => $row->SegundaSeccion,
                                    'TerceraSeccion' => $row->TerceraSeccion,
                                    'idNumeracion' => $row->idNumeracion
                            );
                            $CI->session->set_userdata($data); // Usar set_flashdata en lugar de set_userdata
                            // Close the result set
                            $query->free_result();
                        } else {
                            echo "Query failed: " . $CI->db->error();
                        }



                        $CI->db->trans_complete(); // Completar transacción

                        if ($CI->db->trans_status() === FALSE) {
                            throw new Exception('Error al actualizar la numeración de la factura');
                        }

                        return $data;
                    } catch (Exception $e) {
                        return false; // No se encontraron números de factura disponibles.
                    }
                }
   




	}

}

if (!function_exists('')) {
	function PlandeCuenta()
	{
		$CI =& get_instance();
		return $CI->db->get('PlandeCuenta')->result();
	}
}

if (!function_exists('lssub_pla')) {
	function lssub_pla()
	{
		$CI =& get_instance();
		return $CI->db->get('SubPlanCuenta')->result();
	}
}

if (!function_exists('loadmayor')) {
	function loadmayor($Value='',$Fecha='')
	{
		$CI =& get_instance();
		$CI->db->where('PlandeCuenta_idPlandeCuenta', $Value);
		$CI->db->where('DATE(Fecha_Plan)', $Fecha);
		return $CI->db->get('PlandeCuenta_has_Acientos')->result();
	}
}

if (!function_exists('nombremes')) {
	function nombremes($mes){
		 setlocale(LC_TIME, 'spanish');  
		 $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
		 return ucwords($nombre);
	} 
}

if (!function_exists('numtoletras')) {
	function numtoletras($xcifra)
{
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO GUARANIEZ        ";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN GUARANIEZ         ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " GUARANIEZ        "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
  }


}

if (!function_exists('subfijo')) {
	function subfijo($xx)
	{ // esta función regresa un subfijo para la cifra
	    $xx = trim($xx);
	    $xstrlen = strlen($xx);
	    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
	        $xsub = "";
	    //
	    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
	        $xsub = "MIL";
	    //
	    return $xsub;
	}
}

if (!function_exists('returnnum')) {
    function returnnum($var){
        $return = '';
    for($i=0; $i<strlen($var); $i++){
    $part_var = substr($var, $i, 1);
        if(is_numeric( $part_var )){
        $return .= $part_var;
        }
    }
    return $return;
    }
}

if (!function_exists('returnletra')) {
    function returnletra($value='')
    {
       return preg_replace('/[0-9]+/', '', $value);
    }
}

if (!function_exists('loadcaja')) {
    function loadcaja($value='')
    {
       $CI =& get_instance();
       $CI->db->order_by('idCaja', 'desc');
       return $CI->db->get('Caja')->result();
    }
}

if (!function_exists('LoadIva')) {
    function LoadIva($value='')
    {
        $CI =& get_instance();
        if (is_numeric($value) && $value > 0) {
             $CI->db->where('Num_Iva', $value);
        }   
        $query = $CI->db->get('Iva');
            if ($query->num_rows()>0) {
                return $query->result();
            }else{
                echo json_encode(FALSE);
            }
    }
}
if (!function_exists('monedas')) { 
     function monedase($value='')
     {
        $CI =& get_instance();
        $CI->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'left');
        $CI->db->where('Estado', $value);
        return $CI->db->get('Moneda')->result();
     }
}

if (!function_exists('get_proveedor')) {
  function get_proveedor($value='')
  {
    $CI =& get_instance();
    return  $CI->db->get('Proveedor')->result();
  }
}

if (! function_exists('Banco') ) {
   function Banco()
   {   
       $CI =& get_instance();
       return  $CI->db->get('Gestor_Bancos')->result();
   }
}




//end application/helpers/ayuda_helper.php