<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cdevolver_Model extends CI_Model {
        VAR $column   = array('Num_factura_Compra','Razon_Social','Fecha');
        var $order    = array('idDevoluciones' => 'desc');
        const DDD = 'Devoluciones de';
        const SELECT ='
        idDevoluciones,
        de.Estado,mm.Nombre as MotivoNombre,
        
        Venta_Compra,
        Factura_Compra_idFactura_Compra as id,
        Num_factura_Compra,Ticket,
        Vendedor,
        Razon_Social,
        Fecha,
        de.Monto_Total,
        Tipo_Compra';
         const SELEC='
                  idDetalle_Compra as id6,
        idDetalle_Devolucion,
        prc.Nombre,
        dd.Estado,
        dd.Motivo,
        dd.Estado as es,
        dd.Motivo as mo,
        dd.Precio,
        dd.Cantidad,
        idDetalle_Devolucion as id,
        dc.Producto_idProducto as id2,Devoluciones_idDevoluciones as del
        ';
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Asuncion");
    }

    private function _get_datatables_query()
    {
        $i = 0;
        $this->db->select(self::SELECT);
        $this->db->from(self::DDD);
        $this->db->join('Factura_Compra fc', 'de.Factura_Compra_idFactura_Compra = fc.idFactura_Compra', '');
        $this->db->join('Proveedor pr', 'fc.Proveedor_idProveedor  = pr.idProveedor', '');
        $this->db->join('Motivos_Devoluciones mm', 'de.Motivo_idMotivo  = mm.idMotivo', '');
    
        $column = []; // Asegurar que $column se inicializa correctamente
        foreach ($this->column as $item) {
            $this->db->where('de.Venta_Compra = 2');
            if ($_POST['search']['value']) {
                ($i === 0)
                    ? $this->db->like($item, $_POST['search']['value'])
                    : $this->db->or_like($item, $_POST['search']['value']);
            }
            $column[$i] = $item;
            $i++;
        }
    
        // Validar $_POST['order'] y $_POST['order']['0']['column']
        if (isset($_POST['order']) && isset($column[$_POST['order']['0']['column']])) {
            $this->db->order_by(
                $column[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    

    function get_Devolver()
    {
        $this->_get_datatables_query();
        if($_POST['length'] > 3)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getDevolver($where = NULL)
    {
        $this->db->select(self::SELECT);
        $this->db->from(self::DDD);
        $this->db->join('Factura_Compra fc', 'de.Factura_Compra_idFactura_Compra = fc.idFactura_Compra', '');
        $this->db->join('Proveedor pr', 'fc.Proveedor_idProveedor  = pr.idProveedor', '');

        if ($where != NULL) {
         $this->db->where($where);
         return $this->db->get();
        }else{
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fc.Caja_idCaja', $this->session->userdata('idCaja'));
        }
          $query = $this->db->get();
        return $query->result();
        }

    }


    function count_filtro()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas()
    {
        $this->db->select(self::SELECT);
        $this->db->from(self::DDD);
        $this->db->join('Factura_Compra fc', 'de.Factura_Compra_idFactura_Compra = fc.idFactura_Compra', '');
        $this->db->join('Proveedor pr', 'fc.Proveedor_idProveedor  = pr.idProveedor', '');
        $this->db->where('de.Venta_Compra = 2');
        return $this->db->count_all_results();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function detale($where = NULL) {
    // $this->output->enable_profiler(TRUE);
    $this->db->select(self::SELEC);
    $this->db->from('Detalle_Devolucion dd');
    $this->db->join('Detalle_Compra dc', 'dd.Detalle_Compra_idDetalle_Compra = dc.idDetalle_Compra', '');
    $this->db->join('Producto prc', 'dc.Producto_idProducto  = prc.idProducto', '');
    
    // Si se pasa un valor $where, agregar las condiciones
    if ($where !== NULL) {
        if (is_array($where)) {
            foreach ($where as $field=>$value) {
                $this->db->where($field, $value);
            }
        } else {
            $this->db->where($where);
        }
    }

    // Realizar la consulta
    $query = $this->db->get();
    
    // Verificar si se encontraron resultados
    if ($query->num_rows() > 0) {
        // Si existen resultados, procesarlos
        foreach ($query->result() as $key => $value) {
            $res[] = array(
                'Estado'               => $value->Estado,
                'Motivo'               => $value->Motivo,
                'es'                   => $value->es,
                'mo'                   => $value->mo,
                'Nombre'               => $value->Nombre,
                'Precio'               => $value->Precio,
                'id'                   => $value->id,
                'id2'                  => $value->id2,
                'del'                  => $value->del,
                'idDetalle_Devolucion' => $value->idDetalle_Devolucion,
                'Cantidad'             => $value->Cantidad,
                'id6'                  => $value->id6,
            );
        }
    } else {
        // Si no se encontraron resultados, devolver un mensaje o un array vacío
        $res = array('message' => 'No se encontraron resultados');
    }

    // Devolver los resultados o el mensaje
    return $res;
}

    public function detalele($where = NULL) {
        // $this->output->enable_profiler(TRUE);
        $this->db->select(self::SELEC);
        $this->db->from('Detalle_Devolucion dd');
        $this->db->join('Detalle_Compra dc', 'dd.Detalle_Compra_idDetalle_Compra = dc.idDetalle_Compra', '');
        $this->db->join('Producto prc', 'dc.Producto_idProducto  = prc.idProducto', '');
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

    public function list_comprobante($where = NULL) {
        $this->db->select('idFactura_Compra, Num_factura_Compra, Timbrado, Tipo_Compra, Contado_Credito, Descuento_Total, Monto_envio, Ticket, Insert');
        $this->db->from('Factura_Compra has');
        $this->db->where('Estado != 4');
    
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where($where);
            }
        }
    
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $options = '<option value=""></option>'; // Opción vacía
            foreach ($query->result() as $value) {
                // Determinar el texto del <option>
                $texto = ($value->Tipo_Compra == 0) 
                       ? 'Recibo Nº ' . $value->Ticket 
                       : 'Factura Nº ' . $value->Num_factura_Compra;
    
                // Crear el <option> con los atributos data-factura y data-timbrado
                $options .= '<option 
                    value="' . $value->idFactura_Compra . ',' . $value->Descuento_Total . ',' . $value->Monto_envio . ',' . $value->Insert . '"
                    data-factura="' . $value->Num_factura_Compra . '"
                    data-timbrado="' . $value->Timbrado . '">' . $texto . '</option>';
            }
            return $options;
        }
    }


    public function item_Comprobante($where = NULL) {
        // $this->output->enable_profiler(TRUE);
        
    $this->db->select('idDetalle_Compra,Producto_idProducto,Devolucion,dc.Cantidad,Cantidad_A,Cantidad_D,Precio,Nombre,pr.Iva as i_v_a');
    $this->db->from('Detalle_Compra dc');
    // $this->db->where('Devolucion < Cantidad');
    
    $this->db->join('Producto pr', 'dc.Producto_idProducto = pr.idProducto', 'inner');
    $this->db->order_by('Devolucion', 'asc');
    
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
    foreach ($query->result() as $row)
    {
            $opciones =  array(
                'Cantidad_max_devol' => $row->Cantidad-$row->Devolucion,
                'Cantidad_stock' => $row->Cantidad_A,
                'Cantidad_Deposito' => $row->Cantidad_D,

                'Producto_idProducto' => $row->Producto_idProducto,
                'iva' => $row->i_v_a

            );
            $data = array(
                        'id'      =>$row->idDetalle_Compra,
                        'qty'     => $row->Cantidad,
                        'price'   => $row->Precio,
                        'name'    =>$row->Nombre, 'descuento'    =>'',
                         'descuento'    =>'',
                        'options' => $opciones

                    );
            $this->cart->insert($data);
    }
    return $query->num_rows();
    }

    public function check_num($id)
    {
        $this->db->select('Num_factura_Compra');
        $this->db->where('Num_factura_Compra',$id);
        $consulta = $this->db->get(self::FACTURA);
        if ($consulta->num_rows()> 0) {
            return true;
        }
    }
    public function obtener_motivos() {
        $query = $this->db->get('Motivos_Devoluciones');
        return $query->result();
    }

    public function devolver($postData)
    {
    
        // Validación inicial de datos
        $proveedor = isset($postData['proveedor']) ? $postData['proveedor'] : false;
        $id = isset($postData['id']) ? $postData['id'] : false;
        $mov = isset($postData['mov']) ? $postData['mov'] : false;
        $tipooccion = isset($postData['tipooccion']) ? $postData['tipooccion'] : false;
        $cart_total = isset($postData['cartotal']) ? $postData['cartotal'] : false;
        $iva_cinco = isset($postData['iva_cinco']) ? $postData['iva_cinco'] : false;
        $iva_diez = isset($postData['iva_diez']) ? $postData['iva_diez'] : false;
        $inventario = isset($postData['inventario']) ? $postData['inventario'] : false;
        $fecha_pago = isset($postData['fecha_pago']) ? $postData['fecha_pago'] : false;


        // Validar datos mínimos
        if (!$cart_total || !$tipooccion) {
            throw new Exception("Datos insuficientes para procesar la devolución.");
        }
    
        $this->cart->update($postData);
        $session_data = $this->session->userdata();
        $idCaja = $session_data['idCaja'];
        $idUsuario = $session_data['idUsuario'];
        $hora = date("H:i");
        $motivo = $mov;
    
        // Registrar devolución principal
        $object = [
            'Estado' => $tipooccion,
            'Fecha' => date("Y-m-d"),
            'Monto_Total' => $cart_total,
            'Venta_Compra' => 2,
            'Factura_Compra_idFactura_Compra' => $id,
            'Usuario_idUsuario' => $idUsuario,
            'Motivo_idMotivo' => $motivo,
        ];
        $this->db->insert('Devoluciones', $object);
        $idDevoluciones = $this->db->insert_id();
        $postData['idDevoluciones'] = $idDevoluciones;
    
        $Cantidad_AD = ($inventario == 1) ? 'Cantidad_A' : 'Cantidad_D';
        $updates = [];
        $inserts = [];
        // $updatesD = [];

    
        // Procesar los artículos del carrito
        foreach ($this->cart->contents() as $items) {
            $options = $items['options'];
            $idproducto = $options['Producto_idProducto'];
            $idDetalle = $items['id'];
            $cantidad = isset($options['devolver']) ? $options['devolver'] : 0; // Obtener el valor de "devolver"
            $monto = $items['price'];
        
            if ($cantidad > 0) {
                $inserts[] = [
                    'Estado' => $tipooccion,
                    'Motivo' => $motivo,
                    'Precio' => $monto,
                    'Cantidad' => $cantidad,
                    'Detalle_Factura_idDetalle_Factura' => null,
                    'Detalle_Compra_idDetalle_Compra' => $idDetalle,
                    'Devoluciones_idDevoluciones' => $idDevoluciones,
                    'Producto_idProducto' => $idproducto,
                ];
        
                $updates[] = [
                    'idProducto' => $idproducto,
                    'cantidad' => $cantidad,
                    'idDetalle_Compra' => $idDetalle
                ];
            }
        }
        // var_dump($updates);
        // exit;
        // Actualizar inventario
        // $this->actualizar_inventario($updates, $Cantidad_AD);
    
        // Registrar en asientos y procesar según el tipo de opción
        $dataAciento = [
            'Fecha' => date("Y-m-d"),
            'Hora' => $hora,
            'Caja_idCaja' => $idCaja,
            'Compra_idDevoluciones' => $idDevoluciones,
        ];
        $this->db->insert('Acientos', $dataAciento);
        $idAcientos = $this->db->insert_id();
    
        // Procesar tipo de opción
        $doc = $this->procesar_tipo_opcion($postData,$tipooccion, $idAcientos, $cart_total, $motivo, $updates, $idDevoluciones, $proveedor,$fecha_pago,$Cantidad_AD);
    
        // Insertar detalles de la devolución
        if (!empty($inserts)) {
            $this->db->insert_batch('Detalle_Devolucion', $inserts);
        }
    
 

        return $doc ;
    }
    
    // Función para procesar el tipo de opción
    private function procesar_tipo_opcion($postData,$tipooccion, $idAcientos, $cart_total, $motivo, $updates, $idDevoluciones, $proveedor,$fecha_pago,$Cantidad_AD)
    {
        switch ($tipooccion) {
            case 1: // Mercadería Devuelta y Cambiada
                // $this->actualizar_estado_pedido($idDevoluciones, 'Cambiado');
                // $this->registrar_inventario($idDevoluciones, 'Cambio');
                break;
        
            case 2: // Mercadería Devuelta y Reembolsada
                $this->procesar_inventario_y_detalle($updates, $Cantidad_AD);
                $this->add_aciento_plan($idAcientos, '2', $cart_total, '(Ac +)');
                $this->add_aciento_debe($idAcientos, '484', $cart_total, '(Ac -)', $motivo);
                $this->load->model("Venta_Model",'Venta');

                $parcial1 = isset($postData['parcial1']) ? $postData['parcial1'] : false;
                $parcial2 = isset($postData['parcial2']) ? $postData['parcial2'] : false;
                $parcial3 = isset($postData['parcial3']) ? $postData['parcial3'] : false;
                $parcial4 = isset($postData['parcial4']) ? $postData['parcial4'] : false;
                $postData['concepto'] = 'Devolucion';
                $postData['tipo_identificacion'] = 110;
                $postData['timbrado'] = $postData['num_referencia'];


                return $this->Venta->pago_($postData,$parcial1,$parcial2,$parcial3,$parcial4,1);
                // $this->add_cobros($cart_total, $occion, $idDevoluciones);
                break;
        
            case 3: // Mercadería Devuelta y Acreditada en Cuenta
                $this->procesar_inventario_y_detalle($updates, $Cantidad_AD);
                $this->Cuenta_Fabor($_id, $cart_total, $proveedor, $idDevoluciones);
                $this->add_aciento_plan($idAcientos, '481', $cart_total, '(Ac +)');
                $this->add_aciento_debe($idAcientos, '484', $cart_total, '(Ac -)', $motivo);
                break;
        
            case 9: // Mercadería Devolver y Cambio Posterior
                $this->procesar_inventario_y_detalle($updates, $Cantidad_AD);
                // $this->actualizar_estado_pedido($idDevoluciones, 'Cambio Pendiente');
                // $this->notificar_cliente($idCliente, 'Tu solicitud de cambio está pendiente.');
                break;
        
            case 10: // Mercadería Devolver y Cobro Posterior
                $this->procesar_inventario_y_detalle($updates, $Cantidad_AD);
                // $this->actualizar_estado_pedido($idDevoluciones, 'Cobro Pendiente');
                $this->registrar_pendiente_cobro($idDevoluciones, $cart_total, $proveedor,$fecha_pago);
                // $this->notificar_provedor($idCliente, 'Devolución procesada y cobrada posteriormente.');
                break;
        
            default:
                throw new Exception("Tipo de opción no válida: $tipooccion");
        }
        
    }

    private function procesar_inventario_y_detalle($updates, $Cantidad_AD)
    {
        if (empty($updates)) {
            log_message('error', '⛔ No hay datos en $updates para procesar.');
            return false; // Evita ejecutar si no hay datos
        }
    
        $this->db->trans_start(); // Iniciar transacción
    
        foreach ($updates as $update) {
            // Validar y convertir valores a enteros
            $cantidad = intval($update['cantidad']);
            $idProducto = intval($update['idProducto']);
            $idDetalleCompra = intval($update['idDetalle_Compra']);
    
            // Verificar que los valores sean válidos
            if ($cantidad <= 0 || $idProducto <= 0 || $idDetalleCompra <= 0) {
                log_message('error', "⚠️ Error en datos de actualización: cantidad=$cantidad, idProducto=$idProducto, idDetalleCompra=$idDetalleCompra");
                continue; // Saltar este registro y continuar con los demás
            }
    
    
            // 🔹 Actualizar inventario restando cantidad
            $this->db->set($Cantidad_AD, "$Cantidad_AD - $cantidad", FALSE);
            $this->db->where('idProducto', $idProducto);
            $this->db->update('Producto');
    
            log_message('debug', '🛠 SQL Ejecutado (Producto): ' . $this->db->last_query());
            log_message('debug', '🛠 Filas afectadas (Producto): ' . $this->db->affected_rows());
    
            if ($this->db->affected_rows() == 0) {
                log_message('error', "❌ El UPDATE en Producto no afectó ninguna fila (idProducto=$idProducto).");
            }
    
            // 🔹 Actualizar detalle de compra sumando devoluciones
            $this->db->set('Devolucion', "Devolucion + $cantidad", FALSE);
            $this->db->where('idDetalle_Compra', $idDetalleCompra);
            $this->db->update('Detalle_Compra');
    
            log_message('debug', '🛠 SQL Ejecutado (Detalle_Compra): ' . $this->db->last_query());
            log_message('debug', '🛠 Filas afectadas (Detalle_Compra): ' . $this->db->affected_rows());
    
            if ($this->db->affected_rows() == 0) {
                log_message('error', "❌ El UPDATE en Detalle_Compra no afectó ninguna fila (idDetalle_Compra=$idDetalleCompra).");
            }
        }
    
        $this->db->trans_complete(); // Finalizar transacción
    
        // 🔹 Verificar si la transacción fue exitosa
        if ($this->db->trans_status() === FALSE) {
            log_message('error', '⛔ Transacción fallida en procesar_inventario_y_detalle(). Se ha hecho rollback.');
            return false;
        } else {
            log_message('info', '✅ Transacción completada correctamente en procesar_inventario_y_detalle().');
            return true;
        }
    }
    

    private function registrar_pendiente_cobro($idDevoluciones, $monto, $proveedor,$fecha_pago, $numCuota = 1, $estado = 0)
    {
        if (empty($proveedor) || $monto <= 0) {
            throw new Exception("Datos insuficientes para registrar la deuda del cliente.");
        }
    
        $data = [
            'Importe' => $monto,
            'Estado' => $estado, // 0 = Pendiente, 1 = Pagada
            'Num_cuota' => $numCuota,
            'Factura_Venta_idFactura_Venta' => null, // Si no hay factura asociada
            'Fecha_Ven' => $fecha_pago, // Fecha de vencimiento (opcional)
            'Proveedor_idProveedor' => $proveedor,

            'Devolucion_idDevoluciones' => $idDevoluciones, // Si deseas vincularlo a la devolución
        ];
    
        if (!$this->db->insert('Cuenta_Corriente_Cliente', $data)) {
            throw new Exception("Error al registrar la deuda del cliente.");
        }
    }
    

    public function Cuenta_Fabor($id,$agremicuenta,$proveedor,$idDevoluciones)
    {
        $this->db->trans_begin();
                $data                        = array(
                'Estado'                     => 1,
                'Fecha'                      => date("d-m-Y"),
                'Monto'                      => $agremicuenta,
                'Cliente_Empresa'            => 1,
                'Cliente_idCliente'          => null,
                'Proveedor_idProveedor'      => $proveedor,
                'idCuenta_Corriente_Empresa' => null,
                'Devoluciones_idDevoluciones'=> $idDevoluciones
                );
                $this->db->insert('Cuenta_Fabor', $data);
        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
                $this->db->trans_commit();
        }
    }

    public function add_cobros($efectivo,$occion,$idDevoluciones)
    {
        $this->db->trans_begin();
                $data = array(
                'Descripcion' => $occion,
                'MontoRecibido' => $efectivo,
                'Fecha' => date("Y-m-d"),
                'Hora' => strftime( "%H:%M", time() ),
                'Caja_idCaja' => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
                'Devoluciones_idDevoluciones'=>$idDevoluciones
                );
            $this->db->insert('Caja_Cobros', $data);

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
                $this->db->trans_commit();
        }
    }



        public function add_aciento_plan($idAcientos,$value='',$monto,$signo='')
        {
            $data                         = array(
            'PlandeCuenta_idPlandeCuenta' => $value,
            'Acientos_idAcientos'         => $idAcientos,
            'DebeDetalle'                 => $signo,
            'HaberDetalle'                => NULL,
            'Debe'                        => $monto,
            'Haber'                       => NULL,
            );
            $this->db->insert('PlandeCuenta_has_Acientos', $data);
        }
        public function add_aciento_debe($idAcientos,$value='',$monto,$signo='',$da_ta)
        {
            $data                         = array(
            'PlandeCuenta_idPlandeCuenta' => $value,
            'Acientos_idAcientos'         => $idAcientos,
            'DebeDetalle'                 => null,
            'HaberDetalle'                =>  $signo,
            'Debe'                        => null,
            'Haber'                       => $monto,
            );
            $this->db->insert('PlandeCuenta_has_Acientos', $data);


                $data                         = array(
                'PlandeCuenta_idPlandeCuenta' => NULL,
                'Acientos_idAcientos'         => $idAcientos,
                'DebeDetalle'                 => '<p class="text-danger">Segun devolucion Producto '.$da_ta.'</p>',
                'HaberDetalle'                => NULL,
                'Debe'                        => NULL,
                'Haber'                       => NULL,
                );
                $this->db->insert('PlandeCuenta_has_Acientos', $data);
        }

    }

/* End of file Cdevolver_Model.php */
/* Location: ./application/models/Cdevolver_Model.php */
// $this->output->enable_profiler(TRUE);
