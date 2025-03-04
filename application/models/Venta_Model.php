<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Venta_Model extends CI_Model {
    public $column = array('Num_Factura_Venta', 'Nombres', 'Apellidos', 'Ruc', 'Fecha_expedicion', 'Estado', 'Monto_Total', 'idFactura_Venta');
    public $order = array('idFactura_Venta' => 'desc');
    const WHERE = 'cr.Estado = 0 or cr.Estado = 3'; // no pagado
    const FACTURA = 'Factura_Venta fa';
    const DETALLE = 'Detalle_Factura de';
    const ID = 'idProducto';
    const O_HAS_P = 'Orden_has_Produccion or_has';
    const SELECT = 'Ticket,idFactura_Venta,Fecha_expedicion,Hora,Concepto,Estado,Num_Factura_Venta,Tipo_Venta,Monto_Total,Contado_Credito,idCliente,Insert,Ruc,Nombres,Apellidos,Flete';
    public $plandata = [];
   
        public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Asuncion");
        $this->load->database();
    }
    // DEUDAS
    private function _get_datatables_query($estatus, $ruc, $factura, $anho)
    {
        $i = 0;
        $this->db->select(self::SELECT);
        $this->db->from(self::FACTURA);
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        // Filtrar por estatus
        if ($estatus) {
            $this->db->where('fa.Estado', $estatus); // Cambia 'Estado' por el nombre correcto si es necesario
        }else{
            $this->db->where('fa.Estado !=', 4);

        }

        // Filtrar por RUC
        if ($ruc) {
            $this->db->like('cl.Ruc', $ruc); // Filtrar por RUC
        }

        // Filtrar por factura
        if ($factura) {
            $this->db->like('fa.Num_factura_Compra', $factura); // Filtrar por ID de factura
            $this->db->or_like('fa.Ticket', $factura); // Filtrar por ID de factura
        }

        // Filtrar por año
        if ($anho) {
            // Convertir la variable $anho de MM-AAAA a un objeto DateTime
            $fecha = DateTime::createFromFormat('m-Y', $anho);
            // Extraer el año y el mes del objeto DateTime en el formato AAAA-MM
            $yearMonth = $fecha->format('Y-m');
            
            // Usar el año y el mes en la consulta
            $this->db->like("fa.Fecha_expedicion", $yearMonth);
        }

        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            $this->db->like('Num_Factura_Venta', $_POST['search']['value']);
            $this->db->or_like('Nombres', $_POST['search']['value']);
            $this->db->or_like('Fecha_expedicion', $_POST['search']['value']);
            $this->db->or_like('Estado', $_POST['search']['value']);
            $this->db->or_like('Monto_Total', $_POST['search']['value']);
            $this->db->or_like('idFactura_Venta', $_POST['search']['value']);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_Venta($estatus, $ruc, $factura, $anho)
    {
        $this->_get_datatables_query($estatus, $ruc, $factura, $anho);
        if ($this->session->userdata('idUsuario') != 1) {
            $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
        $this->db->limit($_POST['length'], (int)$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    

        function getVenta()
    {
        $this->db->select(self::SELECT);
        $this->db->from(self::FACTURA);
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        // $this->db->where('Insert != 2');
        if ($this->session->userdata('idUsuario') != 1) {
            $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
            }
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtro($estatus, $ruc, $factura, $anho)
    {
        $this->_get_datatables_query($estatus, $ruc, $factura, $anho);
        if ($this->session->userdata('idUsuario') != 1) {
            $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
            }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas()
    {
      $this->db->select(self::SELECT);
      $this->db->from(self::FACTURA);
      $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
    //   $this->db->where('Insert != 2');
      if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
        }
        $this->db->where('fa.Estado != 4');
      return $this->db->count_all_results();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function detale($where = NULL) {
      $this->db->select('idDetalle_Factura as id,Cantidad as can,Precio,de.Descuento as Descuentoapp,pdc.Descuento,Nombre,Precio_Costo ,Precio_Venta,pdc.Iva');
        $this->db->from(self::DETALLE);
        $this->db->join('Producto pdc', 'de.Producto_idProducto = pdc.idProducto', 'left');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where($where);
            }
        }
         $query = $this->db->get();
         return $query->result();
    }

    public function list_productos($where = NULL,$id = NULL) {
        $this->db->select('idProducto,Marca,Nombre,Codigo,Precio_Venta,Unidad,Medida,Img,Iva,Cantidad_A,Descuento');
        $this->db->from('Producto');
        $this->db->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'left');
        if ($where !== NULL) {
          if (is_array($where)) {
              foreach ($where as $field=>$value) {
                  $this->db->where($field, $value);
              }
          } else {
              $this->db->where($where);
          }
        }
        $query = $this->db->get();
        if ($id == NULL) {
           if ($query->num_rows() > 0) {
              $options='<option value=""></option>';
              foreach ($query->result() as $key => $value) {
                $precio = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Precio_Venta), 10));
                $unidad = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Unidad), 10));
                $nombre = url_title(convert_accented_characters($value->Nombre),'_',TRUE);
                $marca = url_title(convert_accented_characters($value->Marca),'_',TRUE);
                $options.='<option 
                value='.$value->idProducto.','. $nombre.','.$precio.','.$value->Img.','.$value->Iva.','.$value->Cantidad_A.','.$unidad.','.$value->Medida.','.$marca.','.$value->Descuento.'>
                Codigo: ['.$value->Codigo.'] &nbsp;
                Nombre: '.$value->Nombre.'&nbsp;
                 ['.intval(preg_replace('/[^0-9]+/', '', $value->Unidad), 10).': '.$value->Medida.']
                </option>';
              }
           return $options; 
           }
        }else{
             if ($query->num_rows() > 0) {
                $options='<option value=""></option>';
                foreach ($query->result() as $key => $value) {
                  $precio = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Precio_Venta), 10));
                  $unidad = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Unidad), 10));
                  $nombre = url_title(convert_accented_characters($value->Nombre),'_',TRUE);
                  $options.='<option id='.$value->idProducto.'
                  value='.$value->idProducto.','. $nombre.','.$precio.','.$value->Img.','.$value->Iva.','.$value->Cantidad_A.','.$unidad.','.$value->Medida.'>
                  Codigo: ['.$value->Codigo.'] &nbsp;
                  Nombre: '.$value->Nombre.'&nbsp;
                  </option>';
                }
             return $options; 
             }

        }


    }
public function list_orden($where = NULL) {
    $this->db->select('idOrden, Observacion, Estado');
    $this->db->from('Orden');
    $this->db->where($where);
    $this->db->limit(10);
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        $options = '<option value=""></option>';

        foreach ($query->result_array() as $row) {
            $options .= '<option value="' . $row['idOrden'] . '">N: ' . $row['Observacion'] . ' ' . $row['Estado'] . '</option>';
        }

        return $options;
    }
}

public function item_orden($where = NULL) {
    $this->db->select('idProducto,Cantidad,Precio,Nombre,pr.Iva as i_v_a,Cantidad_A');
    $this->db->from(self::O_HAS_P);
    $this->db->join('Producto pr', 'or_has.Producto_idProducto = pr.idProducto', 'left');
    $this->db->where($where);
    $query = $this->db->get();
    $item = 0; // Mover la variable $item fuera del bucle foreach

    foreach ($query->result() as $row)
    {
        if ($row->Cantidad_A > $row->Cantidad) {
            $cantidad = $row->Cantidad;
        } else {
            $cantidad = $row->Cantidad_A ;
        }

        $opciones =  array(
            'iva'   => $row->i_v_a,
            'descuento' => '',
            'poriginal' => $row->Precio,
            'predesc'   => 0,
            'Cantidad_max'       => $row->Cantidad_A,
            't_f'   => 0,
            'id'      => $row->idProducto,
        );
        if ($cantidad > 0) {
            $data = array(
                'id'      => $row->idProducto,
                'qty'     => $cantidad,
                'price'   => $row->Precio,
                'name'    => $row->Nombre,
                'descuento' => '',
                'options' => $opciones
            );
            $this->cart->insert($data);
            $item++;
        }
    }

    return $item; // Mover el return fuera del bucle foreach
}

    public function check_num($id)
    {
        $this->db->select('Num_factura_Venta');
        $this->db->where('Num_factura_Venta',$id);
        $consulta = $this->db->get(self::FACTURA);
        if ($consulta->num_rows()> 0) {
            return true;
        }
    }
    public function pago_($cartCoompra,$parcial1,$parcial2,$parcial3,$parcial4 )
    {
        $this->db->trans_begin();

                    $vueltototal = $cartCoompra['vueltototal'];
                    $si_no = $cartCoompra['si_no'];
                    $ajustado = $cartCoompra['ajustado'];
                    $Cliente = isset($cartCompra['Cliente']) && !empty($cartCompra['Cliente']) ? $cartCompra['Cliente'] : (isset($cartCompra['Proveedor']) ? $cartCompra['Proveedor'] : null);
                    // metodo chewque
                    $numcheque = $cartCoompra['numcheque']; 
                    $efectivotxt = $cartCoompra['efectivo'];
                    $idDevoluciones = isset($cartCoompra['idDevoluciones']) ? $cartCoompra['idDevoluciones'] : null;
                    // $banco = $cartCoompra['banco'];
                    // $fecha_pago = $cartCoompra['fecha_pago']; 
                    // $firma = $cartCoompra['firma'];
                    // $cuenta = $cartCoompra['cuenta'];
                    // metodo tarjeta
                    $efectivoTarjeta = $cartCoompra['efectivoTarjeta']; 
                    $Tarjeta = $cartCoompra['Tarjeta'];
                    // metodo saldo cuenta corriente cliente
                    $matriscuanta = $cartCoompra['matriscuanta'];
                    $matris = $cartCoompra['matris'];
                    // datos del carrito y factura
                    $tipoComprovante = $cartCoompra['tipoComprovante'];
                    $finalcarrito    = $cartCoompra['finalcarrito'];
                    // $descuento = $cartCoompra['descuento'];
                    $lesiva = round($cartCoompra['lesiva']);
                    // datos de seccion
                    $session_data = $this->session->userdata();
                    $idCaja = $session_data['idCaja'];
                    $idUsuario = $session_data['idUsuario'];
                    $totalivadescon  = 0;
                    $hora = date("H:i:s");

                    $idventa       = $this->venta($cartCoompra,$vueltototal);
                   
                  $data                         = array(
                  'Factura_Venta_idFactura_Venta'                       => $idventa,
                  'Caja_idCaja'                 => $idCaja ,
                  'Compra_idDevoluciones' => $idDevoluciones
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();

            $Caja_Cobro = [];
            // pago en efectivo
           if (!empty($parcial1)) {
                for ($i = 1; $i <= $cartCoompra['val']; $i++) {
                    $Moneda = $cartCoompra['Moneda' . $i];
                    $montoCambiado = $cartCoompra['montoCambiado' . $i];
                    $MontoMoneda = $cartCoompra['MontoMoneda' . $i];
                
                    if ($MontoMoneda > 0) {
                            $data = [
                                'Descripcion' => 'Cobro por Venta de Mercaderia ' . $cartCoompra['signo'.$i],
                                'MontoRecibido' => $montoCambiado,
                                'Hora' => $hora,
                                'Caja_idCaja' => $idCaja,
                                // 'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
                                'Devoluciones_idDevoluciones' => $idDevoluciones,
                                'Factura_Venta_idFactura_Venta' => $idventa,
                                'Moneda_idMoneda' => $Moneda,
                            ];

                            $Caja_Cobro[] = $data;

                    }
                }
  

                  $this->addAsientoPlan('2', $idAcientos,'(Ac +)',NULL,$parcial1,NULL);
           }
                // pago en cheque
                if ($parcial2) {
                    $banco = $cartCoompra['banco'];
                    $fecha_pago = $cartCoompra['fecha_pago']; 
                    $firma = $cartCoompra['firma'];
                    $cuenta = $cartCoompra['cuenta'];

                    $movimientosBase = array(
                        'Control' => 0, // 0 es cheque ajeno
                        'ConceptoEntrada' => 'Cobro por venta',
                        'Pagos'                                               => null,
                        'Cobros'                                              => 1,
                        'Activo_Inactivo'                                     => 1,
                        'FechaPago'                                           => $fecha_pago ,
                        'Entrada_Salida'                                     => 'Entrada',
                        'Gestor_Bancos_idGestor_Bancos'                       => $banco,
                        'Proveedor_idProveedor'                               => null,
                        'Cliente_idCliente'                                   => $Cliente,
                        'Usuario_idUsuario' => $idUsuario,
                        'Caja_idCaja' => $idCaja,
                        'FirmaTitular' => $firma,
                        'NumeroCuenta' => $cuenta,
                        'Cobros_idCaja_Cobros' => $cuenta
                         
                    );

                    $movimientos = $movimientosBase;
                    $movimientos['NumeroCheque'] = $numcheque;
                    $movimientos['Importe'] = $parcial2;
                    $movimientos['Devoluciones_idDevoluciones'] = $idDevoluciones; // Agregar este campo
                    
                    $this->db->insert('Movimientos', $movimientos);
                        $idm = $this->db->insert_id();
                        $descripcion = 'Cobro con Cheque N° ' . $numcheque;
                        $data = [
                            'Descripcion' => $descripcion,
                            'MontoRecibido' => $parcial2,
                            'Hora' => $hora,
                            'Caja_idCaja' => $idCaja,
                            'Factura_Venta_idFactura_Venta' => $idventa,
                            'Devoluciones_idDevoluciones' => $idDevoluciones,
                            'Movimientos_idMovimientos' => $idm,
                        ];

                        $Caja_Cobro[] = $data;

                    $this->addAsientoPlan('4', $idAcientos,'(Ac +)',NULL,$parcial2,NULL);

                }
                // pago con tarjetas
                if ($parcial3) {
                    $tarjetas = array(
                        1 => 'Tarjetas de Crédito',
                        2 => 'Tarjetas de Débito'
                    );
                    $Tarje = $tarjetas[$Tarjeta];
                    $data = array(
                        'TipodeTarjeta' => $Tarje,
                        'MontodeTarjeta' => $parcial3,
                        'FechaTarjeta' => date("Y-m-d"),
                        'Devoluciones_idDevoluciones' => $idDevoluciones, // Nuevo campo
                        'Factura_Venta_idFactura_Venta' => $idventa,      // Nuevo campo
                    );
                    $this->db->insert('Tarjeta', $data);
                    $idt = $this->db->insert_id();
                    $data = [
                        'Descripcion' => 'Cobro Venta por '. $Tarje,
                        'MontoRecibido' => $parcial3,
                        'Hora' => $hora,
                        'Caja_idCaja' => $idCaja,
                        'Factura_Venta_idFactura_Venta' => $idventa,
                        'Devoluciones_idDevoluciones' => $idDevoluciones,
                        'Tarjeta_idTarjeta' =>$idt
                    ];
                    $Caja_Cobro[] = $data;

                    $this->addAsientoPlan('304', $idAcientos,'(Ac +)',NULL,$parcial3,NULL);
                }

                // pago con saldo a favor
                if ($parcial4) {
                    $seleccionados = explode(',', $matriscuanta);
                    $matri = explode(',', $matris);

                    $this->db->set('Estado',2, FALSE);
                    $this->db->where_in('idCuenta_Fabor', $seleccionados);
                    $this->db->update('Cuenta_Fabor');

                    $data = array();
                    for ($i = 0; $i < count($seleccionados); $i++) {
                        $data = [
                            'Descripcion' => 'Cobro Venta por Cuenta Fabor',
                            'MontoRecibido' => $matri[$i],
                            'Hora' => $hora,
                            'Caja_idCaja' => $idCaja,
                            // 'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                            'Devoluciones_idDevoluciones' => $idDevoluciones,
                            'Factura_Venta_idFactura_Venta' => $idventa,
                            'Cuenta_Fabor_idCuenta_Fabor' => $seleccionados[$i]
                        ];
                        $Caja_Cobro[] = $data;
                    }
                    $this->addAsientoPlan('482', $idAcientos,'(P -)',NULL,$parcial4,NULL);
                }
  
                // insertar saldo a favor si se requiere
                if ($si_no == 1) {
                    $data = array(
                        'Estado' => 1,
                        'MontoRecibido' => $ajustado,
                        'Cliente_Empresa' => 1,
                        'Cliente_idCliente' => $Cliente,
                        'Proveedor_idProveedor' => null,
                        'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
                        'Devoluciones_idDevoluciones' => $idDevoluciones,
                    );
                    $this->db->insert('Cuenta_Fabor', $data);

                    $this->addAsientoPlan('482', $idAcientos,'(P +)',NULL,$ajustado,NULL);
                }

                // Si Dispongo de Descuento
                $descuento = 0;
                if (isset($cartCoompra['descuento'])) {
                    $descuento = $cartCoompra['descuento'];
                    $this->addAsientoPlan('329', $idAcientos,'(P +)',NULL,$descuento,NULL);
                }

                $dataH = ($finalcarrito + $descuento) - ($tipoComprovante == 0 ? 0 : $lesiva);
                $this->addAsientoPlan('249', $idAcientos,null,'(Ac +)',null,$dataH);

                if ($tipoComprovante != 0) {

                    $this->addAsientoPlan('480', $idAcientos,null,'(Ac +)',null,$lesiva);
                }

                foreach ($Caja_Cobro as $pago) {
                    $this->db->insert('Caja_Cobros', $pago);
                }


                    // si existe vuelto
                    if (!empty($vueltototal)) {
                        $data                         = [
                            'PlandeCuenta_idPlandeCuenta' => '483',
                            'Acientos_idAcientos'         => $idAcientos,
                            'DebeDetalle'                 => null,
                            'HaberDetalle'                => '(Ac -)',
                            'Debe'                        => null,
                            'Haber'                       => $vueltototal,
                        ];
                        $this->addAsientoPlan('482', $idAcientos,null,'(Ac -)',null,$vueltototal);
                    
                        $Caja_Pagos = array(
                        'Descripcion'                                         =>  'Diferencia Vuelto',
                        'MontoRecibido'                                               => $vueltototal,
                        'Hora' => $hora,
                        'Caja_idCaja'                                         => $idCaja,
                        'Devoluciones_idDevoluciones ' => $idDevoluciones,
     
                        );
                   
                         $this->db->insert('Caja_Pagos', $Caja_Pagos);
                    }

    
              $this->db->insert_batch('PlandeCuenta_has_Acientos',$this->plandata);


              if ($tipoComprovante == 1) {
                $variables_a_eliminar = array('PrimeraSeccion', 'SegundaSeccion', 'TerceraSeccion');
                $this->session->unset_userdata($variables_a_eliminar);
                $idventa = comprobante(); // helper
                } else {
                    $this->session->unset_userdata('NumeroTicket');
                    $idventa = Ticket(); // helper
                }

           if ($this->db->trans_status() === FALSE)
           {
                   $this->db->trans_rollback();
           }
           else
           {

                   $this->db->trans_commit();
                   return  $idventa ;
           }

    }

    public function addAsientoPlan($idP,$idA,$DD,$HD,$D,$H)
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $idP,
          'Acientos_idAcientos'         => $idA,
          'DebeDetalle'                 => $DD,
          'HaberDetalle'                => $HD,
          'Debe'                        => $D,
          'Haber'                       => $H,
          );
          array_push($this->plandata, $data);
    }

    public function venta($cartdata,$vueltototal='')
    {    
        $hora = date("H:i:s");
        $Cliente = isset($cartdata['Cliente']) ? $cartdata['Cliente'] : null;
        $orden = isset($cartdata['orden']) ? $cartdata['orden'] : null;
        $fecha = isset($cartdata['fecha']) ? $cartdata['fecha'] : null;
        $fecha_inicio_cuota = isset($cartdata['fecha_inicio_cuota']) ? $cartdata['fecha_inicio_cuota'] : null;
        $tipoComprovante = isset($cartdata['tipoComprovante']) ? $cartdata['tipoComprovante'] : null;
        $cuotas = isset($cartdata['cuotas']) ? $cartdata['cuotas'] : null;
        $condicion = isset($cartdata['condicion']) ? $cartdata['condicion'] : null;
        $fletes = isset($cartdata['fletes']) ? $cartdata['fletes'] : null;
        $observaciones = isset($cartdata['observaciones']) ? $cartdata['observaciones'] : null;
        $Direccion = isset($cartdata['Direccion']) ? $cartdata['Direccion'] : null;
        $finalcarrito = isset($cartdata['finalcarrito']) ? $cartdata['finalcarrito'] : null;
        $Estado = isset($cartdata['Estado']) ? $cartdata['Estado'] : null;
        $descuento = isset($cartdata['descuento']) ? $cartdata['descuento'] : null;
        $cart_total = isset($cartdata['cart_total']) ? $cartdata['cart_total'] : null;
        $checControl = isset($cartdata['checControl']) ? $cartdata['checControl'] : null;
        $Ticket = isset($cartdata['Ticket']) ? $cartdata['Ticket'] : null;
        $lesiva = isset($cartdata['lesiva']) ? round($cartdata['lesiva']) : null;
        $i10 = isset($cartdata['i10']) ? round($cartdata['i10']) : null;
        $i5 = isset($cartdata['i5']) ? round($cartdata['i5']) : null;
        $inicial = isset($cartdata['inicial']) ? $cartdata['inicial'] : null;

        $ComprobanteAsociada = isset($cartdata['comprobanteA']) ? $cartdata['comprobanteA'] : null;
        $timbradoAsociada = isset($cartdata['timbradoA']) ? $cartdata['timbradoA'] : null;
        $operacion = isset($cxartdata['operacion']) ? $cartdata['operacion'] : null;
        $concepto = isset($cxartdata['concepto']) ? $cxartdata['concepto'] : 'Venta';	
        $idDevoluciones = isset($cartCoompra['idDevoluciones']) ? $cartCoompra['idDevoluciones'] : null;

        
        
        
         $this->db->trans_begin();
         $session_data = $this->session->userdata();
        //  var_dump($session_data);
        //  exit;
         $comprobante = $session_data['PrimeraSeccion'].'-'.$session_data['SegundaSeccion'].'-'.$session_data['TerceraSeccion'];
         if ($tipoComprovante == 1) {
             $tipo = $comprobante;
             $Ticket = '';
             $nunrecibo = $session_data['TerceraSeccion'];
         } else {
             $tipo = null;
             $Ticket = $session_data['NumeroTicket'];
             $nunrecibo = $Ticket;
         }

                if ($Cliente !='') {
                  $idCliente = $Cliente;
                }else{
                  $idCliente = 1; 
                }
                $hora = date("H:i:s");

                $object = array(
                    'Fecha_expedicion'  => $fecha,
                    'Hora'              => $hora,
                    'Concepto'          => $concepto,
                    'Odservaciones'     => $observaciones,
                    'Estado'            => $Estado,
                    'Num_factura_Venta' => $tipo,
                    'Ticket'            => $Ticket,
                    'Tipo_Venta'        => $tipoComprovante,
                    'Monto_Total'       => $finalcarrito,
                    'Monto_Total_Iva'   => $lesiva,
                    'Contado_Credito'   => $condicion,
                    'Flete'             => $checControl,
                    'Cargos_Envios'     => $fletes,
                    'Direccion_Envio'   => $Direccion,
                    'Insert'            => $inicial,
                    'Usuario_idUsuario' => $session_data['idUsuario'],
                    'Cliente_idCliente' => $idCliente,
                    'Caja_idCaja'       => $session_data['idCaja'],
                    'Vuelto'            => $vueltototal,
                );
                // Insertar en Factura_Venta
                $this->db->insert('Factura_Venta', $object);
                $id = $this->db->insert_id(); // Obtener el ID de la factura insertada


                if ($idDevoluciones) {
                    $this->db->set('Factura_Venta_idFactura_Venta', $id);
                    $this->db->where('idDevoluciones', $idDevoluciones);
                    $this->db->update('Devoluciones');
                }
                if ($tipoComprovante == 1) {
                
                // Insertar en SET_Comprobantes
                $setData = array(
                    'tipo_registro'        => '1', // 1 = Ventas según la SET
                    'factura_id'     => $id,
                    'tipo_identificacion'  => isset($cartdata['tipo_identificacion']) ? $cartdata['tipo_identificacion'] : 11, // RUC por defecto
                    'numero_identificacion'=> isset($cartdata['numero_identificacion']) ? $cartdata['numero_identificacion'] : 'X',
                    'razon_social'         => isset($cartdata['razon_social']) ? $cartdata['razon_social'] : 'SIN NOMBRE',
                    'tipo_comprobante'     => isset($cartdata['tipo_comprobante']) ? $cartdata['tipo_identificacion'] : 109,  // Factura, Nota de Crédito, etc.
                    'fecha_emision'        => $fecha,
                    'numero_timbrado'      => isset($cartdata['timbrado']) ? $cartdata['timbrado'] : '00000000',
                    'numero_comprobante'   => $tipo, // Número de la factura generada
                    'monto_gravado_10'     => $cartdata['grabadas10'],
                    'monto_gravado_5'      => $cartdata['grabadas5'],
                    'monto_no_gravado'     => $finalcarrito - ($i10 + $i5), // Total - IVA 10% - IVA 5%
                    'monto_total'          => $finalcarrito,
                    'condicion_venta'      => $condicion,
                    'operacion_moneda_extranjera' => isset($cartdata['moneda_extranjera']) ? $cartdata['moneda_extranjera'] : 'N',
                    'imputa_iva'           => 'S',
                    'imputa_ire'           => 'N',
                    'imputa_irp_rsp'       => 'N',
                    'comprobante_asociado' => $ComprobanteAsociada,
                    'timbrado_asociado'    => $timbradoAsociada,
                    'estado'               => 'Activo'
                );

                $this->db->insert('SET_Comprobantes', $setData);

                $insertData = array();
            }
            
                    $cartContents = $this->cart->contents();
      
                    foreach ($cartContents as $items) {

                        if ($this->cart->has_options($items['rowid']) == TRUE) {
                            $product_options = $this->cart->product_options($items['rowid']);
                        }
                        
                        $caracteres = implode('', $this->config->item('caracteres')); // Convierte el array en una cadena
                        $price = preg_replace('/[' . preg_quote($caracteres) . ']/', '', $items['price']);
                        
                        $data = array(
                            'Cantidad' => $items['qty'],
                            'Descripcion' => '',
                            'Precio' => $price,
                            'Iva' => $product_options['iva'],
                            'Descuento' => $items['descuento'],
                            'Factura_Venta_idFactura_Venta' => $id,
                            'Producto_idProducto' => isset($product_options['id']) 
                            ? $product_options['id'] 
                            : $product_options['Producto_idProducto'],

                        );
                        $insertData[] = $data;

                    }
                    $this->db->insert_batch('Detalle_Factura',$insertData);
       
                    if ($Estado == '2') { 
                            $importe = round($finalcarrito / $cuotas);
                            $insertData = array();
                            for ($j = 1; $j <= $cuotas; $j++) {
                                    // Suma un mes a la fecha
                                    $nueva_fecha = date('Y-m-d', strtotime($fecha_inicio_cuota . "+$j month"));
                                    $data                           = array(
                                    'Num_Recibo'                    => $nunrecibo + $j,
                                    'Importe'                       => $importe,
                                    'Fecha_Ven'                     => $nueva_fecha,
                                    'Fecha_Pago'                    => '',
                                    'Estado'                        => '0',
                                    'Num_Cuota'                     => $j,
                                    'Factura_Venta_idFactura_Venta' => $id,
                                    'Cliente_idCliente'             => $idCliente,
                                    );
                                    $insertData[] = $data;
                            }
                            $this->db->insert_batch('Cuenta_Corriente_Cliente', $insertData);
                            $data                           = array(
                                'Factura_Venta_idFactura_Venta' => $id,
                                'Caja_idCaja'                   => $session_data['idCaja'],
                                );
                                $this->db->insert('Acientos', $data);
                                $idAcientos = $this->db->insert_id();
                            
                            $insertData2 = array();
                            $data = array(
                                'PlandeCuenta_idPlandeCuenta' => ($tipoComprovante == 0) ? '34' : '34',
                                'Acientos_idAcientos' => $idAcientos,
                                'DebeDetalle' => '(Ac +)',
                                'HaberDetalle' => null,
                                'Debe' => ($tipoComprovante == 0) ? $finalcarrito : $finalcarrito - $cartdata['descuento'],
                                'Haber' => null,
                                'Descuento_Debe' => ($tipoComprovante == 0) ? null : NULL,
                                'Descuento_Haber' => ($tipoComprovante == 0) ? null : NULL,
                            );
                            $insertData2[] = $data;

                            $descuento = $cartdata['descuento'];
                            if (!empty($descuento)) {
                                $data = array(
                                    'PlandeCuenta_idPlandeCuenta' => ($tipoComprovante == 0) ? '329' : '329',
                                    'Acientos_idAcientos' => $idAcientos,
                                    'DebeDetalle' => '(P +)',
                                    'HaberDetalle' => NULL,
                                    'Debe' => $descuento,
                                    'Haber' => NULL,
                                );
                                $insertData2[] = $data;
                            }

                            $data = array(
                                'PlandeCuenta_idPlandeCuenta' => '249',
                                'Acientos_idAcientos' => $idAcientos,
                                'DebeDetalle' => null,
                                'HaberDetalle' => '(Ac +)',
                                'Debe' => null,
                                'Haber' => ($tipoComprovante == 0) ? $finalcarrito + $descuento : $finalcarrito - $lesiva,
                            );
                            $insertData2[] = $data;

                            if ($tipoComprovante != 0) {
                                $data = array(
                                    'PlandeCuenta_idPlandeCuenta' => '480',
                                    'Acientos_idAcientos' => $idAcientos,
                                    'DebeDetalle' => null,
                                    'HaberDetalle' => '(P +)',
                                    'Debe' => null,
                                    'Haber' => $lesiva,
                                    'Descuento_Debe' => NULL,
                                    'Descuento_Haber' => NULL,
                                );
                                $insertData2[] = $data;
                            }

                            foreach ($insertData2 as $pago) {
                                $this->db->insert('PlandeCuenta_has_Acientos', $pago);
                            }
                            if ($tipoComprovante == 1) {
                                $variables_a_eliminar = array('PrimeraSeccion', 'SegundaSeccion', 'TerceraSeccion');
                                $this->session->unset_userdata($variables_a_eliminar);
                                $id = comprobante(); // helper
                            } else {
                                $this->session->unset_userdata('NumeroTicket');
                                $id = Ticket(); // helper
                            }

                    }



        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
                $this->db->trans_commit();
                return $id ;
        }

    }

    public function _pagado($id)
    {
        if ($id) {
            $this->db->trans_begin();
                $this->db->set('Estado', '0');
                $this->db->where('idFactura_Venta', $id);
                $this->db->update('Factura_Venta');
                $this->db->set('Estado', '1');
                $this->db->where('Factura_Venta_idFactura_Venta', $id);
                $this->db->update('Cuenta_Corriente_Cliente');
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
            }
            else
            {
                    $this->db->trans_commit();
                    return $this->db->affected_rows();
            }

        }
    }

    public function no_pagado($id)
    {
        if ($id) {
            $this->db->trans_begin();
                $this->db->set('Estado', '2');
                $this->db->where('idFactura_Venta', $id);
                $this->db->update('Factura_Venta');
                $this->db->set('Estado', '0');
                $this->db->where('Factura_Venta_idFactura_Venta', $id);
                $this->db->update('Cuenta_Corriente_Cliente');

            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
            }
            else
            {
                    $this->db->trans_commit();
                   return $this->db->affected_rows();
            }
        }
    }

    public function anular($id,$condicion)
    {
        if ($id) {
            $this->db->trans_begin();
              $this->db->select('*');
                $this->db->where('Factura_Venta_idFactura_Venta', $id);
                $query = $this->db->get('Detalle_Factura');
                if ($query->num_rows() > 0) {
                  switch ($condicion) {
                    case '1':
                      foreach ($query->result() as $key => $value) {
                              $this->db->set('Cantidad_A', 'Cantidad_A+'.$value->Cantidad, FALSE);
                              $this->db->where('idProducto', $value->Producto_idProducto);
                              $this->db->update('Producto');
                      }
                      break;
                    case '2':
                      foreach ($query->result() as $key => $value) {
                              $this->db->set('Cantidad_D', 'Cantidad_D+'.$value->Cantidad, FALSE);
                              $this->db->where('idProducto', $value->Producto_idProducto);
                              $this->db->update('Producto');
                      }
                      break;
                  }
                }
                $this->db->set('Estado', '4');
                $this->db->where('idFactura_Venta', $id);
                $this->db->update('Factura_Venta');

                $this->db->select('*');
                $this->db->where('Factura_Venta_idFactura_Venta', $id);
                $query = $this->db->get('Caja_Cobros');
                if ($query->num_rows()>0) {
                  foreach ($query->result() as $key => $value) {
                    if ($value->Cuenta_Fabor_idCuenta_Fabor != null) {
                        $this->db->set('Estado', '0');
                        $this->db->where('Cuenta_Fabor_idCuenta_Fabor', $value->Cuenta_Fabor_idCuenta_Fabor);
                        $this->db->update('Cuenta_Fabor');
                    }
                    if ($value->Movimientos_idMovimientos != null) {
                      $this->db->set('Activo_Inactivo', '1', FALSE);
                      $this->db->set('Cobros', 'null', FALSE);
                      $this->db->where('idMovimientos', $value->Movimientos_idMovimientos);
                      $this->db->update('Movimientos');
                     $this->db->select('Gestor_Bancos_idGestor_Bancos, Importe');
                      $this->db->where('idMovimientos', $value->Movimientos_idMovimientos);
                      $query = $this->db->get('Movimientos');
                      $row = $query->row();
                      if (!empty($row->Gestor_Bancos_idGestor_Bancos)) {
                          $this->db->set('MontoActivo', 'MontoActivo-'.$row->Importe  , FALSE);
                          $this->db->where('idGestor_Bancos',$row->Gestor_Bancos_idGestor_Bancos);
                          $this->db->update('Gestor_Bancos');
                      }
                    }
                    if ($value->Tarjeta_idTarjeta != null) {
                      $this->db->set('Estado', '4', FALSE);
                      $this->db->where('idTarjeta', $value->Tarjeta_idTarjeta);
                      $this->db->update('Tarjeta');
                    }
                  }


                      $this->db->set('Estado', '4');
                      $this->db->where('Factura_Venta_idFactura_Venta', $id);
                      $this->db->update('Caja_Cobros');
                      

                      $this->db->where('Factura_Venta_idFactura_Venta', $id);
                      $this->db->delete('Acientos');

                }else{
                  $this->db->select('idCuenta_Corriente_Cliente,Caja_Cobros.Cuenta_Fabor_idCuenta_Fabor,Caja_Cobros.Movimientos_idMovimientos,Importe,Tarjeta_idTarjeta');
                  $this->db->join('Cuenta_Corriente_Cliente cce', 'Caja_Cobros.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = cce.idCuenta_Corriente_Cliente', 'inner');
                  $this->db->where('cce.Factura_Venta_idFactura_Venta', $id);
                  $query = $this->db->get('Caja_Cobros');
                    if ($query->num_rows()>0) {
                                foreach ($query->result() as $key => $value) {
                                if ($value->Cuenta_Fabor_idCuenta_Fabor != null) {
                                    $this->db->set('Estado', '0');
                                    $this->db->where('Cuenta_Fabor_idCuenta_Fabor', $value->Cuenta_Fabor_idCuenta_Fabor);
                                    $this->db->update('Cuenta_Fabor');
                                }
                                if ($value->Movimientos_idMovimientos != null) {
                                  $this->db->set('Activo_Inactivo', '1', FALSE);
                                  $this->db->set('Pagos', 'null', FALSE);
                                  $this->db->where('idMovimientos', $value->Movimientos_idMovimientos);
                                  $this->db->update('Movimientos');
                                  $this->db->select('Gestor_Bancos_idGestor_Bancos, Importe');
                                  $this->db->where('idMovimientos', $value->Movimientos_idMovimientos);
                                  $query = $this->db->get('Movimientos');
                                  $row = $query->row();
                                  if (!empty($row->Gestor_Bancos_idGestor_Bancos)) {
                                      $this->db->set('MontoActivo', 'MontoActivo-'.$row->Importe  , FALSE);
                                      $this->db->where('idGestor_Bancos',$row->Gestor_Bancos_idGestor_Bancos);
                                      $this->db->update('Gestor_Bancos');
                                  }
                                }
                                if ($value->Tarjeta_idTarjeta != null) {
                                  $this->db->set('Estado', '4', FALSE);
                                  $this->db->where('idTarjeta', $value->Tarjeta_idTarjeta);
                                  $this->db->update('Tarjeta');
                                }
                              }
                    $this->db->where('Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente', $value->idCuenta_Corriente_Cliente);
                    $this->db->delete('Caja_Cobros');

                    }
                    $this->db->where('Factura_Venta_idFactura_Venta', $id);
                    $this->db->delete('Acientos');
                    $this->db->where('Factura_Venta_idFactura_Venta', $id);
                    $this->db->delete('Cuenta_Corriente_Cliente');
                }





            if ($this->db->trans_status() === FALSE)
            {
                   $this->db->trans_rollback();
            }
            else
            {
                  return  $this->db->trans_commit();
            }
        }
    }
    public function ultimaCaja()
    {
            $this->db->select_max('idCaja');
            $this->db->where('Usuario_idUsuario',$this->session->userdata('idUsuario'));
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }

   ////////////////////////////////////////// anulados
    private function _get_anul_query()
    {
        // $this->output->enable_profiler(TRUE);
        $this->db->from(self::FACTURA);
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        $this->db->where('Estado = 4');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;

        }

        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_anul()
    {
        $this->_get_anul_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function getanul()
    {
        $this->db->from(self::FACTURA);
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        $this->db->where('Estado = 4');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtroanul()
    {
        $this->_get_anul_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todasanul()
    {

        $this->db->from(self::FACTURA);
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
                $this->db->where('Estado = 4');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        return $this->db->count_all_results();
    }

    public function add_aciento_debe($idAcientos,$tipoComprovante,$value='',$parcial,$signo='')
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => null,
          'HaberDetalle'                =>  $signo,
          'Debe'                        => null,
          'Haber'                       => $parcial,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }

}

/* End of file Deuda_empresa_Model.php */
/* Location: ./application/models/Deuda_empresa_Model.php */