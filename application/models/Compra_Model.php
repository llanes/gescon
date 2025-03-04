<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Compra_Model extends CI_Model {
        VAR $column   = array('Num_factura_Compra','Razon_Social','Fecha_expedicion','Estado','Monto_Total','idFactura_Compra');
        var $order    = array('idFactura_Compra' => 'desc');
        const WHERE   = 'cr.Estado = 0 or cr.Estado = 3'; // no pagado
        const FACTURA = 'Factura_Compra fa';
        const DETALLE = 'Detalle_Compra de';
        const ID      = 'idProducto';
        const O_HAS_P = 'Orden_has_Produccion or_has';
        const SELECT ='Ticket,idFactura_Compra,Fecha_expedicion,Hora,Concepto,Estado,Num_factura_Compra,Tipo_Compra,Monto_Total,Contado_Credito,idProveedor,Insert,Ruc,Razon_Social,Vendedor';
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Asuncion");
        $this->load->database();
    }
 private function _get_datatables_query($estatus, $ruc, $factura, $anho)
{
    $this->db->select(self::SELECT);
    $this->db->from(self::FACTURA);
    $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        // Filtrar por estatus
        if ($estatus) {
            $this->db->where('fa.Estado', $estatus); // Cambia 'Estado' por el nombre correcto si es necesario
        }else{
            $this->db->where('fa.Estado !=', 4);
        }

        // Filtrar por RUC
        if ($ruc) {
            $this->db->like('pr.Ruc', $ruc); // Filtrar por RUC
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
        $this->db->like('Num_factura_Compra', $_POST['search']['value']);
        $this->db->or_like('Razon_Social', $_POST['search']['value']);
        $this->db->or_like('Fecha_expedicion', $_POST['search']['value']);
        $this->db->or_like('Estado', $_POST['search']['value']);
        $this->db->or_like('Monto_Total', $_POST['search']['value']);
        $this->db->or_like('idFactura_Compra', $_POST['search']['value']);
        $this->db->group_end();
    }
    if (isset($_POST['order'])) {
        $this->db->order_by($this->column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
    }
}
 function get_Compra($estatus, $ruc, $factura, $anho)
{
    $this->_get_datatables_query($estatus, $ruc, $factura, $anho);
    if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario'));
    }
    $this->db->limit($_POST['length'], (int)$_POST['start']);
    $query = $this->db->get();
    return $query->result();
}

    public function getCompra($value='')
    {
        $this->db->select(self::SELECT);
        $this->db->from(self::FACTURA);
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
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
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
        }
        $this->db->where('fa.Estado != 4');
        return $this->db->count_all_results();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function detale($where = NULL) {
      $this->db->select('idDetalle_Compra as id,Cantidad as can,Precio,de.Descuento as Descuentoapp, Nombre,Precio_Costo,pdc.Descuento,pdc.Iva ');
        $this->db->from(self::DETALLE);
        $this->db->join('Producto pdc', 'de.Producto_idProducto = pdc.idProducto', 'left');
        if ($where !== NULL) {
                $this->db->where($where);
        }
         $query = $this->db->get();
         return $query->result();
    }

    public function list_productos($where = NULL) {
        $this->db->select('idProducto,Nombre,Codigo,Precio_Costo,Unidad,Medida,Img,Iva,Precio_Venta');
        $this->db->from('Producto_has_Proveedor has');
        $this->db->join('Producto pr', 'has.Producto_idProducto = pr.idProducto', 'left');
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

         if ($query->num_rows() > 0) {
            $options='<option value=""></option>';
            foreach ($query->result() as $key => $value) {
                   if ($value->Precio_Costo == 0) {
                       $precioo = $value->Precio_Venta;
                    }else{
                       $precioo = $value->Precio_Costo;
                    }
              $precio = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$precioo), 10));
              $nombre = url_title(convert_accented_characters($value->Nombre),'_',TRUE);
              $options.='<option 
              value='.$value->idProducto.','. $nombre.','.$precio.','.$value->Img.','.$value->Iva.'>
              Codigo: ['.$value->Codigo.'] &nbsp;
              Nombre: '.$value->Nombre.'&nbsp;
               ['.intval(preg_replace('/[^0-9]+/', '', $value->Unidad), 10).': '.$value->Medida.']
              </option>';
            }
         return $options; 
         }


    }

    public function list_orden($where = NULL) {
        $this->db->select('idOrden,Observacion,Estado');
        $this->db->from('Orden');
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where($where);
            }
        }
        $this->db->limit(100);
         $query = $this->db->get();

         if ($query->num_rows() > 0) {
            $options='<option value=""></option>';
            foreach ($query->result() as $key => $value) {
                $options.='<option 
                value='.$value->idOrden.'>
                '.$value->Observacion.'</option>';
            }
         return $options; 
         }


    }

    public function item_orden($where = NULL) {
    $this->db->select('idProducto,Cantidad,Precio,Nombre,or_has.Iva as i_v_a');
    $this->db->from(self::O_HAS_P);
      $this->db->join('Producto pr', 'or_has.Producto_idProducto = pr.idProducto', 'left');
    if ($where !== NULL) {
            $this->db->where($where);
    }
    $query = $this->db->get();
    foreach ($query->result() as $row)
    {
            $opciones =  array(
                'iva'   => $row->i_v_a,
                'Importe' => $row->i_v_a,
                'descuento' => 0,
                'poriginal' => $row->Precio,
                'predesc'   => 0,
                't_f'       => 0
            );

            $data = array(
                        'id'      =>$row->idProducto,
                        'qty'     => $row->Cantidad,
                        'price'   => $row->Precio,
                        'name'    =>$row->Nombre,
                        'descuento'    =>$row->Precio,
                        'options' => $opciones
                    );
            $this->cart->insert($data);
    }
    return $query->num_rows();
    }

    public function check_num($id,$proveedor)
    {
        $this->db->select('Num_factura_Compra');
        $this->db->where('Num_factura_Compra',$id);
        $this->db->where('Proveedor_idProveedor',$proveedor);

        $consulta = $this->db->get(self::FACTURA);
        if ($consulta->num_rows()> 0) {
            return true;
        }
    }
    
    public function pago_($cartCompra, $moneda, $parcial1, $parcial2, $parcial3, $parcial4) {
        $this->db->trans_begin();

        // var_dump($cartCompra);
        // exit;
    
        // Extract variables from $cartCompra
        $vueltototal = $cartCompra['vueltototal'];
        $si_no = $cartCompra['si_no'];
        $ajustado = $cartCompra['ajustado'];
        $numcheque = $cartCompra['numcheque'];
        $fecha_pago = $cartCompra['fecha'];
        $efectivo = $cartCompra['efectivo'];
        $cuenta_bancaria = (isset($cartCompra['cuenta_bancaria'])) ? $cartCompra['cuenta_bancaria'] : null ;



        $Acheque_tercero = $cartCompra['Acheque_tercero'];
        $Acheque = $cartCompra['Acheque'];
        $Tarjeta = $cartCompra['Tarjeta'];
        $matriscuanta = $cartCompra['matriscuanta'];
        $matris = $cartCompra['matris'];
        $tipoComprovante = $cartCompra['tipoComprovante'];
        $finalcarrito = $cartCompra['finalcarrito'];
        $descuento = $cartCompra['descuento'];
        $lesiva = round($cartCompra['lesiva']);
        $proveedor = $cartCompra['proveedor'];
        $session_data = $this->session->userdata();
        $idCaja = $session_data['idCaja'];
        $idUsuario = $session_data['idUsuario'];
        $montofinal = $cartCompra['deudapagar'];
        $Totalparclal = $cartCompra['Totalparclal'];
    
        $hora = date("H:i:s");
        $plandata = [];

        $idcomprar       = $this->comprar($cartCompra,$vueltototal);
        if ($si_no == 1) {
            $Totalparclal = $Totalparclal - $ajustado;
        }
        // Inserción en la tabla 'Caja_Pagos'
        $dataPagos = [
            'Descripcion' => 'Compras',
            'MontoRecibido' => $Totalparclal,
            'MontoTotal' => $montofinal,
            'MontoVuelto' => $vueltototal,
            'Caja_idCaja' => $idCaja,
            'Factura_Compra_idFactura_Compra' => $idcomprar
        ];
        $idPago = $this->insertCajaPagos($dataPagos);
        if (!empty($vueltototal)) {
                   $Caja_Cobros = array(
                   'Descripcion' => 'Diferencia Vuelto' ,
                   'MontoRecibido' => $vueltototal,
                   'Caja_idCaja' => $idCaja,
                   'Tipos_de_Pago_idTipos_de_Pago ' => 5,
                   'idCompraVuelto '                                         => $idPago
                   );
              
                    $this->db->insert('Caja_Cobros', $Caja_Cobros);
        }
    
        // Inserción en la tabla 'Acientos'
        $dataAcientos = [
            'Factura_Compra_idFactura_Compra' => $idcomprar,
            'Caja_idCaja' => $idCaja,
            'idCaja_Pagos' => $idPago,
        ];
        $idAcientos = $this->insertAcientos($dataAcientos);
    
        // Insertar datos en 'PlandeCuenta_has_Acientos'
        $plandata = $this->preparePlanData($tipoComprovante, $finalcarrito, $descuento, $lesiva, $vueltototal, $idAcientos);
    
        // Pagos parciales
        $this->handlePartialPayments($cuenta_bancaria,$parcial1, $parcial2, $parcial3, $parcial4, $moneda, $tipoComprovante, $idAcientos, $idPago, $fecha_pago, $numcheque, $efectivo, $Acheque_tercero, $Acheque, $matriscuanta, $matris,$proveedor,$Tarjeta);
    
        // Insertar saldo a favor si se requiere
        if ($si_no == 1) {
            $this->handleSaldoAFavor($ajustado, $proveedor, $idPago, $idAcientos);
        }
    
        // Si Dispongo de Descuento
        if ($descuento) {
            $plandata[] = $this->prepareDescuentoData($idAcientos, $descuento);
        }
    
        // Inserción en 'PlandeCuenta_has_Acientos'
        $this->db->insert_batch('PlandeCuenta_has_Acientos', $plandata);
    
        // Manejo de la transacción
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
    // $this->output->enable_profiler(TRUE);

            return $idcomprar;
        }
    }
    
    // Funciones auxiliares
    private function insertCajaPagos($dataPagos) {
        $this->db->insert('Caja_Pagos', $dataPagos);
        return $this->db->insert_id();
    }
    
    private function insertAcientos($dataAcientos) {
        $this->db->insert('Acientos', $dataAcientos);
        return $this->db->insert_id();
    }
    
    private function preparePlanData($tipoComprovante, $finalcarrito, $descuento, $lesiva, $vueltototal, $idAcientos) {
        $plandata = [];
        $plandata[] = [
            'PlandeCuenta_idPlandeCuenta' => '58',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => '(Ac +)',
            'HaberDetalle' => null,
            'Debe' => ($finalcarrito + $descuento) - ($tipoComprovante == 0 ? 0 : $lesiva),
            'Haber' => null,
        ];
    
        if ($tipoComprovante != 0) {
            $plandata[] = [
                'PlandeCuenta_idPlandeCuenta' => '479',
                'Acientos_idAcientos' => $idAcientos,
                'DebeDetalle' => '(Ac +)',
                'HaberDetalle' => null,
                'Debe' => $lesiva,
                'Haber' => null,
            ];
        }
    
        if (!empty($vueltototal)) {
            $plandata[] = [
                'PlandeCuenta_idPlandeCuenta' => '483',
                'Acientos_idAcientos' => $idAcientos,
                'DebeDetalle' => '(Ac +)',
                'HaberDetalle' => null,
                'Debe' => $vueltototal,
                'Haber' => null,
            ];




        }
    
        return $plandata;
    }
    
    private function handlePartialPayments($cuenta_bancaria,$parcial1, $parcial2, $parcial3, $parcial4, $moneda, $tipoComprovante, $idAcientos, $idPago, $fecha_pago, $numcheque, $efectivo, $Acheque_tercero, $Acheque, $matriscuanta, $matris,$proveedor,$Tarjeta) {
        if ($parcial1) {
            foreach ($moneda as $key => $value) {
                if ($value['cambiado'] > 0) {
                    $data = [
                        'Pagos_idCaja_Pagos' => $idPago,
                        'Descripcion' => 'Cobro por compra de Mercadería ' . $value['EF'] . ' ' . $value['signo'],
                        'Monto' => $value['cambiado'],
                        'Moneda_idMoneda' => $value['Moneda'],
                    ];
                    $this->db->insert('MetodoPago', $data);
                }
            }
            $this->add_aciento_plan($idAcientos, $tipoComprovante, '2', $parcial1, '(Ac -)');
        }
    
        if ($parcial2) {
            $movimientosBase = [
                'Control' => 1, // 1 es mi cheque
                'ConceptoSalida' => 'Pago por compra',
                'Pagos' => 1,
                'Cobros' => null,
                'Activo_Inactivo' => 2,
                'FechaPago' => $fecha_pago,
                'Entrada_Salida' => 'Salida',
                'Proveedor_idProveedor' => $proveedor,
                'Cliente_idCliente' => null,
                'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
            ];
    
            if ($numcheque > 0 && $efectivo > 0) {
                $movimientos = $movimientosBase;
                $movimientos['NumeroCheque'] = $numcheque;
                $movimientos['Importe'] = $efectivo;
                $movimientos['Cobros_idCaja_Pagos'] = $idPago;
    
                if ($cuenta_bancaria) {
                    $this->db->set('MontoActivo', 'MontoActivo-' . str_replace(",", "", $efectivo), FALSE);
                    $this->db->where('idGestor_Bancos', $cuenta_bancaria);
                    $this->db->update('Gestor_Bancos');
                    $this->add_aciento_plan($idAcientos, $tipoComprovante, '4', $efectivo);
                    $movimientos['Gestor_Bancos_idGestor_Bancos'] = $cuenta_bancaria;
                }
                $this->db->insert('Movimientos', $movimientos);
                $idm = $this->db->insert_id();
    
                $_data = [
                    'Pagos_idCaja_Pagos' => $idPago,
                    'Descripcion' => 'Pago Compra con Cheque N° ' . $numcheque,
                    'Monto' => $parcial2,
                    'Movimientos_idMovimientos' => $idm,
                ];
                $this->db->insert('MetodoPago', $_data);
            }
    
            if ($Acheque_tercero) {
                $seleccionados = explode(',', $Acheque_tercero);
                $selecc = explode(',', $Acheque);
                $parcialtodo = 0;
    
                foreach ($seleccionados as $seleccionado) {
                    foreach ($selecc as $monto) {
                        $this->db->set('Activo_Inactivo', '2', FALSE);
                        $this->db->set('Pagos', '1', FALSE);
                        $this->db->set('ConceptoSalida', "'Pago por compra'");
                        $this->db->where('idMovimientos', $seleccionado, FALSE);
                        $this->db->update('Movimientos');
    
                        $_data = [
                            'Pagos_idCaja_Pagos' => $idPago,
                            'Descripcion' => 'Pago Compra con Cheque Tercero',
                            'Monto' => $monto,
                            'Movimientos_idMovimientos' => $seleccionado,
                        ];
                        $this->db->insert('MetodoPago', $_data);
    
                        $parcialtodo += $monto;
                    }
                }
                $this->add_aciento_plan($idAcientos, $tipoComprovante, '33', $parcialtodo, '(Ac -)');
            }
        }
    
        if ($parcial3) {
            $tarjetas = [
                1 => 'Tarjetas de Crédito',
                2 => 'Tarjetas de Débito',
            ];
            $Tarje = $tarjetas[$Tarjeta];
            $data = [
                'TipodeTarjeta' => $Tarje,
                'MontodeTarjeta' => $parcial3,
                'FechaTarjeta' => date("Y-m-d"),
                'Caja_idCaja_Pagos ' => $idPago,
            ];
            $this->db->insert('Tarjeta', $data);
            $idt = $this->db->insert_id();
    
            $_data = [
                'Pagos_idCaja_Pagos' => $idPago,
                'Descripcion' => 'Pago Compra con ' . $Tarje,
                'Monto' => $parcial3,
                'Tarjeta_idTarjeta' => $idt,
            ];
            $this->db->insert('MetodoPago', $_data);
    
            $this->add_aciento_plan($idAcientos, $tipoComprovante, '304', $parcial3, '(Ac -)');
        }
    
        if ($parcial4) {
            $seleccionados = explode(',', $matriscuanta);
            $matri = explode(',', $matris);
    
            $this->db->set('Estado', 2, FALSE);
            $this->db->where_in('idCuenta_Fabor', $seleccionados);
            $this->db->update('Cuenta_Fabor');
    
            for ($i = 0; $i < count($seleccionados); $i++) {
                $data = [
                    'Pagos_idCaja_Pagos' => $idPago,
                    'Descripcion' => 'Pago por Cuenta Fabor',
                    'Monto' => $matri[$i],
                    'Cuenta_Fabor_idCuenta_Fabor' => $seleccionados[$i],
                ];
                $this->db->insert('MetodoPago', $data);
            }
            $this->add_aciento_plan($idAcientos, $tipoComprovante, '481', $parcial4, '(Ac -)');
        }
    }
    
    private function handleSaldoAFavor($ajustado, $proveedor, $idPago, $idAcientos) {
        $data = [
            'Estado' => 1,
            'Monto' => $ajustado,
            'Cliente_Empresa' => 2,
            'Cliente_idCliente' => null,
            'Proveedor_idProveedor' => $proveedor,
            'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
            'Devoluciones_idDevoluciones' => null,
            'Pagos_idCaja_Pagos' => $idPago,
        ];
        $this->db->insert('Cuenta_Fabor', $data);
    
        $data = [
            'PlandeCuenta_idPlandeCuenta' => '481',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => '(Ac +)',
            'HaberDetalle' => null,
            'Debe' => $ajustado,
            'Haber' => null,
        ];
        $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
    
    private function prepareDescuentoData($idAcientos, $descuento) {
        return [
            'PlandeCuenta_idPlandeCuenta' => '20',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => null,
            'HaberDetalle' => '(Ac +)',
            'Debe' => null,
            'Haber' => $descuento,
        ];
    }
    
    public function comprar($cartdata, $vueltototal = '')
    {
        // Obtener datos de $cartdata
        $proveedor = $cartdata['proveedor'];
        $comprobante = $cartdata['comprobante'];
        $orden = $cartdata['orden'];
        $montofinal = $cartdata['montofinal'];
        $fecha = $cartdata['fecha'];
        $inicial = $cartdata['inicial'];
        $tipoComprovante = $cartdata['tipoComprovante'];
        $cuotas = $cartdata['cuotas'];
        $fletes = $cartdata['fletes'];
        $finalcarrito = $cartdata['finalcarrito'];
        $Estado = $cartdata['Estado'];
        $cart_total = $cartdata['cartotal'];
        $lesiva = round($cartdata['lesiva']);
        $i10 = round($cartdata['iva_diez']);
        $i5 = round($cartdata['iva_cinco']);
        $timbrado = $cartdata['timbrado'];
        $virtual = $cartdata['virtual'];
        $vence = isset($cartdata['vence']) ? $cartdata['vence'] : '';
        $descuento = isset($cartdata['descuento']) ? $cartdata['descuento'] : "";
        // Determinar tipo de comprobante
        $tipo = $tipoComprovante == 1 ? $comprobante : '';
        $Ticket = $tipoComprovante == 1 ? '' : $comprobante;
        $nunrecibo = $tipoComprovante == 1 ? $comprobante : $Ticket;
    
        $session_data = $this->session->userdata();
        $idCaja = $session_data['idCaja'];
        $idUsuario =  $session_data['idUsuario'];

        $object = array(
            'Fecha_expedicion' => $fecha,
            'Hora' => date("H:i:s"),
            'Concepto' => 'Compras',
            'Estado' => $Estado,
            'Num_factura_Compra' => $tipo,
            'Ticket' => $Ticket,
            'Tipo_Compra' => $tipoComprovante,
            'Descuento_Total' => $descuento,
            'Monto_Total' => $montofinal,
            'Monto_envio' => $fletes,
            'Monto_Total_Iva' => $lesiva,
            'Contado_Credito' => $Estado,
            'Caja_idCaja' => $idCaja,
            'Usuario_idUsuario' => $idUsuario,
            'Proveedor_idProveedor' => $proveedor,
            'Insert' => $inicial,
            'Vuelto' => $vueltototal,
            'timbrado' => $timbrado,
        );
    
        // Iniciar transacción
        $this->db->trans_start();
        try {
            // Insertar en Factura_Compra
            $this->db->insert('Factura_Compra', $object);
            $idFactura_Compra = $this->db->insert_id();
    
            // Insertar detalles de compra
                $insertData = [];
                $casesA = [];
                $casesD = [];
                $ids = [];
            foreach ($this->cart->contents() as $items) {
                $iva = 0;
                $options = $this->cart->product_options($items['rowid']);
                if (!empty($options)) {
                    $iva = end($options);
                }
    
                $data = array(
                    'Cantidad' => $items['qty'],
                    'Descripcion' => '',
                    'Precio' => str_replace($this->config->item('caracteres'), "", $items['price']),
                    'Iva' => $iva,
                    'Descuento' => $items['descuento'],
                    'Factura_Compra_idFactura_Compra' => $idFactura_Compra,
                    'Producto_idProducto' => $items['id'],
                );
                $insertData[] = $data;
                
                $qty = $items['qty'];
                $idProducto = $items['id'];
                $ids[] = $idProducto;
                if ($inicial == '1') {
                    $casesA[] = "WHEN idProducto = $idProducto THEN Cantidad_A + $qty";
                } else {
                    $casesD[] = "WHEN idProducto = $idProducto THEN Cantidad_D + $qty";
                }

            }
            if (!empty($insertData)) {
                $this->db->insert_batch('Detalle_Compra', $insertData);
            }
            if ($inicial == '1') {
                $caseString = implode(' ', $casesA);
                $this->db->query("UPDATE Producto SET Cantidad_A = CASE $caseString END WHERE idProducto IN (" . implode(',', $ids) . ")");
            } else {
                $caseString = implode(' ', $casesD);
                $this->db->query("UPDATE Producto SET Cantidad_D = CASE $caseString END WHERE idProducto IN (" . implode(',', $ids) . ")");
            }
// Manejar créditos si el Estado es '2'
if ($Estado == '2') {
    // Calcular importe por cuota
    $importePorCuota = round($finalcarrito / $cuotas);
    $insertDatacce = [];

    // Generar datos para cada cuota
    for ($j = 1; $j <= $cuotas; $j++) {
        $fechaVencimiento = date('d-m-Y', strtotime("+$j month"));
        $data = [
            'Num_Recibo' => $nunrecibo + $j,
            'Importe' => $importePorCuota,
            'Fecha_Ven' => $fechaVencimiento,
            'Fecha_Pago' => '',
            'Estado' => '0',
            'Num_Cuotas' => $j,
            'Factura_Compra_idFactura_Compra' => $idFactura_Compra,
            'Proveedor_idProveedor' => $proveedor,
        ];
        $insertDatacce[] = $data;
    }

    // Insertar datos en Cuenta_Corriente_Empresa
    $this->db->trans_start();
    $this->db->insert_batch('Cuenta_Corriente_Empresa', $insertDatacce);
    $idCuentaCorrienteEmpresa = $this->db->insert_id();

    // Insertar en Acientos
    $acientoData = [
        'Factura_Compra_idFactura_Compra' => $idFactura_Compra, // ID de la factura de compra
        'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $idCuentaCorrienteEmpresa, // ID de la cuenta corriente empresa (si aplica)
        'Caja_idCaja' => $idCaja, // ID de la caja
        'Fecha_Factura' => $fecha,
        'Debe_Total' => ($tipoComprovante == 0) ? $finalcarrito + $descuento : ($finalcarrito + $descuento) - $lesiva,// Total en debe (si aplica)
        'Haber_Total' => $finalcarrito , // Total en haber (si aplica)
        'Iva10' => $i10, // IVA 10%
        'Iva5' => $i5, // IVA 5%
        // 'Exenta' => $exenta, // Exenta
        'Totaliva' => $lesiva, // Total IVA
        'Proveedor_idProveedor' => $proveedor, // ID del proveedor (si aplica)
        'documentos' => $tipoComprovante, // Tipo de documento (si aplica)
        'forma_pago' => $Estado, // Forma de pago (si aplica)
        'Timbrado' => $timbrado, // Número de timbrado (si aplica)
        'Cuotas' => $cuotas, // Número de cuotas (si aplica)
        'virtual' => $virtual, // Indica si es virtual (si aplica)
        'vencimiento' => $vence, // Fecha de vencimiento (si aplica)
    ];
    $this->db->insert('Acientos', $acientoData);
    $idAcientos = $this->db->insert_id();

    // Preparar datos para PlandeCuenta_has_Acientos
    $insertDataPA = [];
    if ($tipoComprovante == 0) {
        $data = [
            'PlandeCuenta_idPlandeCuenta' => '58',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => '(Ac +)',
            'HaberDetalle' => null,
            'Debe' => $finalcarrito + $descuento,
            'Haber' => null,
        ];
        $insertDataPA[] = $data;
    } else {

        $data = [
            'PlandeCuenta_idPlandeCuenta' => '58',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => '(Ac +)',
            'HaberDetalle' => null,
            'Debe' => ($finalcarrito + $descuento) - $lesiva,
            'Haber' => null,
        ];
        $insertDataPA[] = $data;
        if ($lesiva > 0) {
            $data = [
                'PlandeCuenta_idPlandeCuenta' => '479',
                'Acientos_idAcientos' => $idAcientos,
                'DebeDetalle' => '(Ac +)',
                'HaberDetalle' => null,
                'Debe' => $lesiva,
                'Haber' => null,
            ];
            $insertDataPA[] = $data;
        }

    }

    if (!empty($descuento)) {
        $data = [
            'PlandeCuenta_idPlandeCuenta' => '20',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => null,
            'HaberDetalle' => '(Ac +)',
            'Debe' => null,
            'Haber' => $descuento,
        ];
        $insertDataPA[] = $data;
    }

    // Insertar datos en PlandeCuenta_has_Acientos
    $this->db->insert_batch('PlandeCuenta_has_Acientos', $insertDataPA);

    // Agregar asiento plan
    $this->add_aciento_plan($idAcientos, $tipoComprovante, '182', $finalcarrito, '(P +)');

    // Finalizar la transacción
    $this->db->trans_complete();
    
    // Verificar si la transacción fue exitosa
    if ($this->db->trans_status() === FALSE) {
        // Manejar error (por ejemplo, lanzar una excepción o registrar el error)
        throw new Exception('Error en la transacción');
    }
}

    
            // Finalizar transacción
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === FALSE) {
                // Manejar error
                throw new Exception("Error en la transacción de compra.");
            }
    
            return $idFactura_Compra;
        } catch (Exception $e) {
            // Revertir transacción si ocurre un error
            $this->db->trans_rollback();
            log_message('error', $e->getMessage());
            return false;
        }
    }
    

    public function _pagado($id)
    {
        if ($id) {
            $this->db->trans_begin();
                $this->db->set('Estado', '0');
                $this->db->where('idFactura_Compra', $id);
                $this->db->update('Factura_Compra');
                $this->db->set('Estado', '1');
                $this->db->where('Factura_Compra_idFactura_Compra', $id);
                $this->db->update('Cuenta_Corriente_Empresa');
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
                $this->db->where('idFactura_Compra', $id);
                $this->db->update('Factura_Compra');
                $this->db->set('Estado', '0');
                $this->db->where('Factura_Compra_idFactura_Compra', $id);
                $this->db->update('Cuenta_Corriente_Empresa');

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

    public function gestionCompra($id, $condicion, $estado)
    {
        $this->db->trans_begin();
    
        // Obtener detalles de la compra
        $this->db->select('*');
        $this->db->where('Factura_Compra_idFactura_Compra', $id);
        $detalleCompra = $this->db->get('Detalle_Compra');
        
        if ($detalleCompra->num_rows() > 0) {
            foreach ($detalleCompra->result() as $item) {
                switch ($condicion) {
                    case '1':  // Modificar el stock
                        $this->db->set('Cantidad_A', 'Cantidad_A - ' . $item->Cantidad, FALSE);
                        $this->db->where('idProducto', $item->Producto_idProducto);
                        $this->db->update('Producto');
                        break;
                    case '2':  // Modificar el depósito
                        $this->db->set('Cantidad_D', 'Cantidad_D - ' . $item->Cantidad, FALSE);
                        $this->db->where('idProducto', $item->Producto_idProducto);
                        $this->db->update('Producto');
                        break;
                }
            }
        }
    
        // Actualizar estado de los pagos y movimientos relacionados
        $this->db->select('*');
        $this->db->join('Caja_Pagos cp', 'cp.idCaja_Pagos = mp.Pagos_idCaja_Pagos', 'inner');
        $this->db->where('cp.Factura_Compra_idFactura_Compra', $id);
        $metodosPago = $this->db->get('MetodoPago mp');
        
        if ($metodosPago->num_rows() > 0) {
            foreach ($metodosPago->result() as $pago) {
                // Actualizar Cuenta_Fabor
                if (!empty($pago->Cuenta_Fabor_idCuenta_Fabor)) {
                    $this->db->set('Estado', 0);
                    $this->db->where('Cuenta_Fabor_idCuenta_Fabor', $pago->Cuenta_Fabor_idCuenta_Fabor);
                    $this->db->update('Cuenta_Fabor');
                }
                // Actualizar Movimientos
                if (!empty($pago->Movimientos_idMovimientos)) {
                    $this->db->set('Activo_Inactivo', 1, FALSE);
                    $this->db->set('Pagos', null, FALSE);
                    $this->db->set('Cobros', 1, FALSE);
                    $this->db->set('Cobros_idCaja_Pagos', null, FALSE);
                    $this->db->where('idMovimientos', $pago->Movimientos_idMovimientos);
                    $this->db->where('Control', 1);
                    $this->db->update('Movimientos');
    
                    // Actualizar Gestor_Bancos
                    $this->db->select('Gestor_Bancos_idGestor_Bancos, Importe');
                    $this->db->where('idMovimientos', $pago->Movimientos_idMovimientos);
                    $movimiento = $this->db->get('Movimientos')->row();
                    if (!empty($movimiento->Gestor_Bancos_idGestor_Bancos)) {
                        $this->db->set('MontoActivo', 'MontoActivo - ' . $pago->Monto, FALSE);
                        $this->db->where('idGestor_Bancos', $movimiento->Gestor_Bancos_idGestor_Bancos);
                        $this->db->update('Gestor_Bancos');
                    }
                }
            }
        }
    
        // Actualizar o eliminar Factura_Compra
        switch ($estado) {
            case 'Anular':
                $this->db->set('Estado', 4);
                $this->db->where('idFactura_Compra', $id);
                $this->db->update('Factura_Compra');
                break;
            case 'Eliminar':
                $this->db->where('idFactura_Compra', $id);
                $this->db->delete('Factura_Compra');
                break;
        }
    
        // Eliminar registros relacionados
        $this->db->where('Factura_Compra_idFactura_Compra', $id);
        $this->db->delete('Caja_Pagos');
        
        $this->db->where('Factura_Compra_idFactura_Compra', $id);
        $this->db->delete('Acientos');
    
        // Comprobar el estado de la transacción
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
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
    // private function _get_anul_query($estatus, $ruc, $factura, $anho);
    // {
    //     // $this->output->enable_profiler(TRUE);
    //     $this->db->from(self::FACTURA);
    //     $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
    //     $this->db->where('Estado = 4');
    //     if ($this->session->userdata('idUsuario') != 1) {
    //     $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
    //     }
    //     $i = 0;

    //     foreach ($this->column as $item)
    //     {
    //         if($_POST['search']['value'])
    //             ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
    //         $column[$i] = $item;
    //         $i++;

    //     }

    //     if(isset($_POST['order']))
    //     {
    //         $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     }
    //     else if(isset($this->order))
    //     {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    // }

    // function get_anul($estatus, $ruc, $factura, $anho);
    // {
    //     $this->_get_anul_query($estatus, $ruc, $factura, $anho);
    //     if($_POST['length'] != -1)
    //     $this->db->limit($_POST['length'], $_POST['start']);
    //     $query = $this->db->get();
    //     return $query->result();
    // }
    function getanul()
    {
        $this->db->from(self::FACTURA);
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->where('Estado = 4');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
        }
        $query = $this->db->get();
        return $query->result();
    }

    // function count_filtroanul($estatus, $ruc, $factura, $anho);
    // {
    //     $this->_get_anul_query();
    //     $query = $this->db->get();
    //     return $query->num_rows();
    // }

    // public function count_todasanul()
    // {

    //     $this->db->from(self::FACTURA);
    //     $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
    //             $this->db->where('Estado = 4');
    //         if ($this->session->userdata('idUsuario') != 1) {
    //     $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
    //     }
    //     return $this->db->count_all_results();
    // }

public function add_aciento_plan($idAcientos,$tipoComprovante,$value='',$parcial,$signo='')
    {

          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => NULL,
          'HaberDetalle'                => $signo,
          'Debe'                        => null,
          'Haber'                       => $parcial,
          'Descuento_Debe'              => NULL,
          'Descuento_Haber'             => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);

    }

    public function add_flou( $idAcientos)
    {


          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '<p class="text-danger">Compra de Mercaderia</p>',
          'HaberDetalle'                => NULL,
          'Debe'                        => NULL,
          'Haber'                       => NULL,
          'Descuento_Debe'              => NULL,
          'Descuento_Haber'             => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
}

/* End of file Deuda_empresa_Model.php */
/* Location: ./application/models/Deuda_empresa_Model.php */