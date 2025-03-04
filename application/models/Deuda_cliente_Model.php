 <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deuda_cliente_Model extends CI_Model {
        VAR $column    = array('Fecha_expedicion','Nombres','Apellidos','Importe','idFactura_Venta');
        var $order     = array('Fecha_expedicion' => 'desc');
        const WHERE    = 'cr.Estado = 0 or cr.Estado = 3'; // no pagado
        const WHERE2    = 'cf.Estado = 0 AND Activo_Inactivo = 0'; // no
        const WHE_RE   = 'cr.Estado = 1 or cr.Estado = 3'; //  1 = pagado 3 = pago parcial
        const VOLETA  = 'Tipo_Venta = 0'; //  COMPROVANTE VOLETA
        const FACTURA = 'Tipo_Venta = 1'; //  cOMPROVANTE FACTURA
        const Group_By = 'Monto_Total,cl.Nombres,cl.Apellidos,fa.idFactura_Venta, fa.Tipo_Venta,fa.Contado_Credito,fa.Num_Factura_Venta,fa.Cliente_idCliente';
        const SELECT_DEUDAS =
        'COUNT(cr.Num_Cuota ) as Num_cuota,
        Monto_Total as monto_totales,
        fa.Cliente_idCliente as idCliente,
        fa.idFactura_Venta,
        fa.Num_Factura_Venta,Tipo_Venta,Ticket,
        sum(cr.Importe) as inporte_total,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Cliente,
        cr.Fecha_Pago,
        cl.Nombres,Proveedor_idProveedor,
        cl.Apellidos,pr.Razon_Social,pr.Vendedor,
        fa.Contado_Credito,fa.Estado as esta'
        ;
        const SELECT_PAGADAS =
        'cr.Num_Cuota as Num_cuota,
        COUNT(cr.Num_Cuota ) as totalrous,
        Cargos_Envios,
        idCuenta_Corriente_Cliente as id,
        cr.Num_Recibo,
        fa.Monto_Total as monto_totales,
        fa.Cliente_idCliente as idCliente,
        fa.idFactura_Venta,
        cr.Importe as inporte_total,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Cliente,
        cr.Estado as crestado,
        cl.idCliente,
        cl.Nombres,
        cl.Apellidos,pr.Razon_Social,pr.Vendedor,Proveedor_idProveedor,
        cl.Limite_max_Credito,
        fa.idFactura_Venta,
        fa.Tipo_Venta,
        fa.Contado_Credito,Tipo_Venta,
        fa.Estado as esta,fa.Num_Factura_Venta'
        ;
        const SELECT_PAGADA =
        'cr.Num_Cuota as Num_cuota,
        Cargos_Envios,
        cr.Num_Recibo,
        fa.Monto_Total as monto_totales,
        fa.Cliente_idCliente as idCliente,
        fa.idFactura_Venta,
        cr.Importe as inporte_total,
        cr.MontoRecibidoCCC  AS total_caja_pagos,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Cliente as id,
        cr.Estado as crestado,
        cl.Nombres,
        cl.Apellidos,pr.Razon_Social,pr.Vendedor,Proveedor_idProveedor,
        cl.Limite_max_Credito,
        fa.idFactura_Venta,
        fa.Tipo_Venta,
        fa.Contado_Credito,Tipo_Venta,
        fa.Estado as esta,fa.Num_Factura_Venta'
        ;
        const DEUDA         = 'Cuenta_Corriente_Cliente cr';
        const ID               = 'idProducto';
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Asuncion");
        $this->load->database();
    }
    // DEUDAS
    private function _get_datatables_query($search_value = '', $order_column = '', $order_dir = '', $estatus = '', $ruc = '', $factura = '', $anho = '')
    {
        $this->db->select('
        fa.idFactura_Venta AS Factura_ID,
        fa.Monto_Total AS Monto_Total_Factura,
        cl.idCliente,
        cl.Nombres,
        cl.Ruc,
        cr.Estado as crestado,
        fa.Estado as esta,
        fa.Num_Factura_Venta,
        COUNT(DISTINCT cr.idCuenta_Corriente_Cliente) AS Cuotas_Total,
        COUNT(DISTINCT CASE WHEN cr.Estado = 1 THEN cr.idCuenta_Corriente_Cliente END) AS Cuotas_Pagadas,
        COUNT(DISTINCT CASE WHEN cr.Estado NOT IN (1) THEN cr.idCuenta_Corriente_Cliente END) AS Cuotas_NoPagado,
        COUNT(DISTINCT CASE WHEN cr.Estado NOT IN (0, 1) THEN cr.idCuenta_Corriente_Cliente END) AS Cuotas_Parcial,
        (SELECT SUM(MontoRecibidoCCC) 
         FROM Cuenta_Corriente_Cliente cr2 
         WHERE cr2.Factura_Venta_idFactura_Venta = fa.idFactura_Venta AND cr2.Estado IN (3, 1)) AS Total_Pagado, 
        (SELECT SUM(MontoRecibidoCCC) 
         FROM Cuenta_Corriente_Cliente cr2 
         WHERE cr2.Factura_Venta_idFactura_Venta = fa.idFactura_Venta AND cr2.Estado = 3) AS Total_Parcial', false);
    $this->db->from('Factura_Venta fa');
    $this->db->join('Cuenta_Corriente_Cliente cr', 'fa.idFactura_Venta = cr.Factura_Venta_idFactura_Venta', 'inner');
    $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
    $this->db->where('fa.Contado_Credito', 2); // Filtrar por tipo de compra (crédito)
    $this->db->group_by('fa.idFactura_Venta, cl.idCliente'); // Agrega la cláusula GROUP BY
    
        // Filtrar por permisos
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
            $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
    // Filtrar por estatus
    if ($estatus) {
        $this->db->where('fa.Estado', $estatus); // Cambia 'Estado' por el nombre correcto si es necesario
    }

    // Filtrar por RUC
    if ($ruc) {
        $this->db->like('cl.Ruc', $ruc); // Filtrar por RUC
    }

    // Filtrar por factura
    if ($factura) {
        $this->db->like('fa.Num_Factura_Venta', $factura); // Filtrar por ID de factura
    }

    // Filtrar por año
    if ($anho) {
        $this->db->where('YEAR(fa.Fecha_expedicion)', $anho); // Cambia 'fecha_column' por el nombre correcto de la columna que contiene la fecha
    }

        // Filtrar por valor de búsqueda
        $i = 0;
        foreach ($this->column as $item) {
            if ($search_value) {
                if ($i === 0) {
                    $this->db->like($item, $search_value);
                } else {
                    $this->db->or_like($item, $search_value);
                }
            }
            $column[$i] = $item;
            $i++;
        }
    
        // Ordenar resultados
        if ($order_column && $order_dir) {
            $this->db->order_by($column[$order_column], $order_dir);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    
    function get_Deuda($length = 0, $start = 0, $search_value = '', $order_column = '', $order_dir = '', $estatus = '', $ruc = '', $factura = '', $anho = '')
    {
        $this->_get_datatables_query($search_value, $order_column, $order_dir,$estatus, $ruc, $factura, $anho);
        if ($length > 3) {
            $this->db->limit($length, $start);
        }
        $query = $this->db->get();
        // Verificar si se devolvieron filas
        if ($query->num_rows() > 0) {
            // Hay datos, devolver los resultados
            return $query->result();
        } else {
            // No se encontraron datos, devolver un conjunto de datos vacío o un mensaje
            return array(); // o un mensaje indicando que no se encontraron datos
        }
    }

    function getDeuda()
    {
        $this->db->select('COUNT(cr.Num_Cuota ) as Num_cuota,
        Monto_Total as monto_totales,
        fa.Cliente_idCliente as idCliente,
        fa.idFactura_Venta,
        fa.Num_Factura_Venta,Tipo_Venta,Ticket,
        sum(cr.Importe) as inporte_total,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Cliente,
        cr.Fecha_Pago,
        cl.Nombres,
        cl.Apellidos,
        fa.Contado_Credito,fa.Estado as esta');
        $this->db->from('Factura_Venta fa');
        $this->db->join(self::DEUDA, 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'inner');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where(self::WHERE);
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
         $this->db->group_by(self::Group_By);
         $query = $this->db->get();
         return $query->result();

    }
    function count_filtro($search_value = '', $order_column = '', $order_dir = '', $estatus = '', $ruc = '', $factura = '', $anho = '')
    {
        $this->_get_datatables_query($search_value, $order_column, $order_dir,$estatus, $ruc, $factura, $anho);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas()
    {
        $query = $this->get_Deuda();
        return count($query);
    }


    public function sum_pagos_tods($id)
    {
        $consult="
        SELECT sum(MontoRecibido) as total1,'' as cuenta from Caja_Cobros  WHERE  Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        LEFT JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Fabor.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id' OR has.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cobros = '1' AND Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        UNION ALL
        SELECT '' as total1, sum(Monto) as cuenta from Cuenta_Fabor
        WHERE   Estado = '3' AND Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        ";
        $query = $this->db->query($consult);
        $pagados = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $value) {
                    $pagados += $value->total1 - $value->cuenta;
            }
        }
        return $pagados ;
    }

    public function sum_pagos_($id)
    {
        $consult="
        SELECT sum(MontoRecibido) as total1,'' as cuenta from Caja_Cobros  WHERE  Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cobros = '1' AND Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        ";
        $query = $this->db->query($consult);
        $pagados = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $value) {
                    $pagados += $value->total1 ;
            }
        }
        return $pagados ;
    }
    public function pagos_referentes($id)
    {


        $query = $this->db->select('
        cc.Descripcion AS des,
        cc.MontoTotal AS mon,
        cc.MontoRecibido AS MontoRecibido,
        cc.MontoVuelto AS MontoVuelto,
        cc.Fecha AS fec,
        cc.Tipos_de_Pago_idTipos_de_Pago AS tipopago,
        ccc.idCuenta_Corriente_Cliente AS idCCE,
        ccp.Caja_Cobros_idCaja_Cobros AS idCA,
        ccc.Factura_Venta_idFactura_Venta AS idFC,
        ccp.CCC_idCuenta_Corriente_Cliente AS idCF'
    )
    ->from('Cuenta_Corriente_Pagos_Cobros ccp')
    ->join('Caja_Cobros cc', 'cc.idCaja_Cobros = ccp.Caja_Cobros_idCaja_Cobros', 'inner')
    ->join('Cuenta_Corriente_Cliente ccc', 'ccc.idCuenta_Corriente_Cliente = ccp.CCC_idCuenta_Corriente_Cliente', 'inner')
    ->where('ccp.CCC_idCuenta_Corriente_Cliente', $id)
    ->group_by('idCaja_Cobros')
    ->get();

       
        $resultados = $query->result();

        

        return $resultados;
    }
    ////////FIN DEUDAS//////////////////

        var $column1 = array('Num_Cuota', 'Num_Recibo', 'Nombres', 'Monto_Total', 'Num_Cuota', 'Monto_Cobrado');
        var $order1 = array('Fecha' => 'desc');
        
        private function _get_datatables_query_($estatus, $ruc, $factura, $anho) {
            $this->db->select('
                cr.Num_Cuota AS Cuota_Numero,
            cr.Estado as crestado,
            fa.Estado as esta,
            cr.Fecha_Pago,
            cr.idCuenta_Corriente_Cliente as id,
            fa.Num_Factura_Venta AS Comprobante_Numero,

                cr.Num_Recibo AS Num_Recibo,
                cl.Nombres AS Nombres,
                cl.Ruc,
                cr.Importe AS Importe_Total_Cuota,
                cr.Fecha_Pago AS Fecha_Pago,
                cr.Caja_Cobros_idCaja_Cobros as idcobro,
                cr.MontoRecibidoCCC AS Monto_Cobrado,
            ');
            $this->db->from('Cuenta_Corriente_Cliente cr')
                ->join('Factura_Venta fa', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'inner')
                ->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left')
                ->join('Caja_Pagos cp', 'cp.idCuentaCliente = cr.idCuenta_Corriente_Cliente', 'left')
                ->join('Caja_Cobros cc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = cr.idCuenta_Corriente_Cliente', 'left')
                ->where('fa.Contado_Credito', 2)
                ->where_in('cr.Estado', array(3, 1))  // Estado 3 (parcial), Estado 1 (pagado)
                ->group_by('cr.Num_Cuota, fa.Num_Factura_Venta, cl.Nombres, cr.Importe');
            if ($this->session->userdata('Permiso_idPermiso') != 1) {
                $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja') );
            }
    // Filtrar por estatus
    if ($estatus) {
        $this->db->where('fa.Estado', $estatus); // Cambia 'Estado' por el nombre correcto si es necesario
    }

    // Filtrar por RUC
    if ($ruc) {
        $this->db->like('cl.Ruc', $ruc); // Filtrar por RUC
    }

    // Filtrar por factura
    if ($factura) {
        $this->db->like('fa.Num_Factura_Venta', $factura); // Filtrar por ID de factura
    }

    // Filtrar por año
    if ($anho) {
        $this->db->where('YEAR(fa.Fecha_expedicion)', $anho); // Cambia 'fecha_column' por el nombre correcto de la columna que contiene la fecha
    }

            $i = 0;
            foreach ($this->column1 as $item)
            {
                if($_POST['search']['value'])
                    ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
                $column1[$i] = $item;
    
    
            }
    
            if(isset($_POST['order']))
            {
                $this->db->order_by($column1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }
            else if(isset($this->order1))
            {
                $order = $this->order1;
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }
    
        function get_Deuda_($estatus, $ruc, $factura, $anho)
        {
            $this->_get_datatables_query_($estatus, $ruc, $factura, $anho);
            if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result();
        }
    
    

    public function get_Deuda_pagads($value='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from('Factura_Venta fa');
        $this->db->join(self::DEUDA, 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'inner');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where(self::WHE_RE);
                if ($this->session->userdata('Permiso_idPermiso') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro_($estatus, $ruc, $factura, $anho)
    {
        $this->_get_datatables_query_($estatus, $ruc, $factura, $anho);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas_($id ='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from('Factura_Venta fa');
        $this->db->join(self::DEUDA, 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'left outer');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where(self::WHE_RE);
        return $this->db->count_all_results();
    }
    // fin pagadas
////////////////////////////////////////////////////////////////////////////////// Listar lista
    VAR $column2    = array('Num_Cuota','Num_Recibo','Nombres','Nombres','Fecha_Ven','Nombres');
    var $order2     = array('Fecha_Ven' => 'asc');
    public function get_Deuda_list_with_sum($idCuentasCorrientes)
    {
        $this->db->select(self::SELECT_PAGADA);
            // $this->db->from(self::DEUDA);
            $this->db->from('Factura_Venta fa');
            $this->db->join('Cuenta_Corriente_Cliente cr', 'fa.idFactura_Venta = cr.Factura_Venta_idFactura_Venta', 'inner');
            $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'left');
            $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
            $this->db->join('Caja_Pagos cp', 'cr.idCuenta_Corriente_Cliente = cp.idCuentaCliente', 'left');
            $this->db->join('Caja_Cobros co', 'co.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = cr.idCuenta_Corriente_Cliente', 'left');
            $this->db->where_in('cr.Estado',array(0,3));
            $this->db->where('cr.Factura_Venta_idFactura_Venta', $idCuentasCorrientes);
            $this->db->group_by('cr.idCuenta_Corriente_Cliente');
            $this->db->order_by('Num_Recibo', 'ACS');
            $i = 0;
            $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

            foreach ($this->column2 as $i => $item) {
                if (!empty($searchValue)) {
                    if ($i === 0) {
                        $this->db->like($item, $searchValue);
                    } else {
                        $this->db->or_like($item, $searchValue);
                    }
                }
                $column2[$i] = $item;
            }

    if (isset($_POST['order'][0]['column']) && isset($_POST['order'][0]['dir'])) {
        $orderColumn = $_POST['order'][0]['column'];
        $orderDir = $_POST['order'][0]['dir'];
        if (isset($column2[$orderColumn])) {
            $this->db->order_by($column2[$orderColumn], $orderDir);
        }
    } else if (isset($this->order2)) {
        $order = $this->order2;
        $this->db->order_by(key($order), $order[key($order)]);
    }

}
    function get_Deuda_list($id)
    {
        $this->get_Deuda_list_with_sum($id);
    
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    
        $query = $this->db->get();
        return $query->result();
    }
    

    public function get_Deudalist($value='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Venta fa', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'left outer');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where('cr.Estado = 0  or cr.Estado = 3 ');
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro_list($id)
    {
        $this->get_Deuda_list_with_sum($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas_list($id ='')
    {
        $this->db->select('COUNT(*)');
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Venta fa', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'left outer');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where('cr.Estado = 0 and cr.Factura_Venta_idFactura_Venta = '.$id.' or cr.Estado = 3 and cr.Factura_Venta_idFactura_Venta = '.$id.'');
        return $this->db->count_all_results();
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function lis_deuda($where = NULL) {
        // $this->output->enable_profiler(TRUE);
        $this->db->select(self::SELECT_PAGADAS);
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Venta fa', 'fa.idFactura_Venta = cr.Factura_Venta_idFactura_Venta', 'inner');
        $this->db->join('Cliente cl', 'cr.Cliente_idCliente = cl.idCliente', 'left');
         $this->db->join('Proveedor pr', 'cr.Proveedor_idProveedor = pr.idProveedor', 'left');
        $this->db->where(self::WHERE);
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
        return $query->row();
    }

    public function afabor($where = NULL) {
        $this->db->select('idCuenta_Fabor,cf.Monto');
        $this->db->from('Cuenta_Fabor cf');
        $this->db->where('cf.Estado = 1 AND cf.Cliente_Empresa = 1');
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
                $options.='<option value='.$value->Monto.','.$value->idCuenta_Fabor.'>'.$value->Monto.'</option>';
            }
         return $options; 
         }


    }

  public function che_ingreso()
  {
    $this->db->select('idMovimientos as id, NumeroCheque as numch,Importe as im ');
     $this->db->where('Cobros = 1 AND Activo_Inactivo = 1');
     $query = $this->db->get('Movimientos');
    if ($query->num_rows() > 0) {
        $options='<option value=""></option>';
        foreach ($query->result() as $key => $value) {
            $options.='<option value='.$value->im.','.$value->id.'>'.$value->numch.' ₲S. '.$value->im.'</option>';
        }
     return $options; 
     }
  }

   /**
     * [Cuenta_Fabor Nueva cuenta a fabor del algun proveedor]
     * @param [type] $id           [description]
     * @param [type] $agremicuenta [description]
     * @param [type] $idCliente  [description]
     */
    public function Cuenta_Fabor($id,$agremicuenta,$idCliente,$idCobro)
    {
        $this->db->trans_begin();
                $data                                                 = array(
                'Estado'                                              => 1,
                'Fecha'                                               => date("Y-m-d"),
                'Monto'                                               => $agremicuenta,
                'Cliente_Empresa'                                     => 1,
                'Cliente_idCliente'                                   => null,
                'Cliente_idCliente'                                   => $idCliente,
                'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $id,
                'Cobros_idCaja_Cobros ' => $idCobro,
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

  /**
   * [add_set_cuenta utilizar alguna cuenta que tenia de fabor]
   * @param [type] $id            [description]
   * @param [type] $idCuestaFabor [description]
   * @param [type] $idCliente   [description]
   * @param [type] $pago          [description]
   */
  public function add_set_cuenta($id,$idCuestaFabor,$idCliente,$pago, $ultimaCaja)
  {
     $this->db->trans_begin();
            $this->db->set('Estado', '0', FALSE);
            $this->db->where('idCuenta_Fabor', $idCuestaFabor);
            $this->db->update('Cuenta_Fabor');
            $this->db->where('idCuenta_Fabor', $idCuestaFabor);
            $query = $this->db->get('Cuenta_Fabor');
            $row = $query->row();
            $row->Monto;
                             $data                                                 = array(
                             'Accion'                                              => '',
                             'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $id,
                             'Cuenta_Fabor_idCuenta_Fabor'                         => $idCuestaFabor,
                             );
            $this->db->insert('Cuenta_Corriente_Empresa_has_Cuenta_Fabor', $data);
            $this->db->insert('Movimientos', $data);
			$_data                                                = array(
			'Descripcion'                                         => $pago.' por Cuenta Fabor',
			'MontoRecibido'                                               =>   $row->Monto,
			'Fecha'                                               => date("Y-m-d"),
			'Hora'                                                => strftime( "%H:%M", time() ),
			'Caja_idCaja'                                         => $ultimaCaja,
			'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $id,
			'Cuenta_Fabor_idCuenta_Fabor'                         => $idCuestaFabor,
            );
         $this->db->insert('Caja_Cobros', $_data);

     if ($this->db->trans_status() === FALSE)
     {
             $this->db->trans_rollback();
     }
     else
     {
             $this->db->trans_commit();
     }
  }

  public function cobroDeuda(
    $i_d, 
    $idF, 
    $totalrous, 
    $crEstado,
    $cfEstado, 
    $idCliente,
    $Totalparclal, 
    $vueltototal,
    $si_no,
    $ajustado, 
    $numcheque,
    $fecha_pago,
    $efectivoTarjeta,
    $Tarjeta,
    $matriscuanta,
    $parcial1, 
    $parcial2,
    $parcial3, 
    $parcial4, 
    $moneda,
    $matris,
    $Acheque_tercero,
    $Acheque,
    $monto,
    $cuotaN,
    $id,
    $cuotaPrecio
) {
    // Separar los datos de entrada por comas en arrays
    $idArray = explode(',', $i_d);
    $idFArray = explode(',', $idF);
    $crEstadoArray = explode(',', $crEstado);
    $cfEstadoArray = explode(',', $cfEstado);
    $cuotaNArray = explode(',', $cuotaN);
    $cuotaPrecioArray = explode(',', $cuotaPrecio);

    // Comenzar la transacción de base de datos
    $this->db->trans_begin();

    // Obtener la última caja
    $ultimaCaja = $this->session->userdata('idCaja');
    $idUsuario = $this->session->userdata('idUsuario');
    $hora = date("H:i:s");
    $plandata = [];
    $pago = "Cobros Total";
    $montoTotalPagado = $Totalparclal;
    $montoTotalCuotas = array_sum($cuotaPrecioArray);
    $Descripcion = ($id == 1 ) ? 'Cobro Total' : 'Cobro Parcial' ;
    $retVal = ($montoTotalPagado <  $montoTotalCuotas) ? $montoTotalPagado : $montoTotalCuotas ;


    if ($si_no == 1) {
        $vueltototal = 0;
        $montoTotalPagado = $montoTotalCuotas;
    }

    // Insertar en la tabla 'cobroCaja'
    $dataCobro = array(
        'Descripcion'                                         => $Descripcion.' Cuota N ' . $cuotaN ,
        'MontoRecibido'                                               =>  $montoTotalPagado,
        'MontoTotal'                                               =>  $retVal,
        'MontoVuelto'                                               =>  $vueltototal,


        'Caja_idCaja'                                         => $ultimaCaja,
        'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $idArray[0],
        'Factura_Venta_idFactura_Venta' => $idFArray[0]
    );
    $this->db->insert('Caja_Cobros', $dataCobro);
    $idCobro = $this->db->insert_id();

    // Insertar en la tabla 'Acientos'
    $dataAcientos = array(

        'Factura_Venta_idFactura_Venta' => $idFArray[0],
        'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $idArray[0],
        'Caja_idCaja' => $ultimaCaja,
        'idCaja_Cobros' => $idCobro,

    );
    $this->db->insert('Acientos', $dataAcientos);
    $idAcientos = $this->db->insert_id();


    // Iterar sobre cada cuota
    foreach ($cuotaNArray as $index => $cuotaNumero) {
   
        $idCuota = $idArray[$index];
        $idFCuota = $idFArray[$index];
        $crEstadoCuota = $crEstadoArray[$index];
        $cfEstadoCuota = $cfEstadoArray[$index];
        $precioCuota = $cuotaPrecioArray[$index];

        // Actualizar el estado del cobro de la cuota
        if ($id == 1) {
            if ($totalrous == 1) {
                $var = '0';
                $this->_pagado($idFCuota, $var);
            }
            if ($crEstadoCuota != 1) {
                $this->Estado_1($idCuota,$idCobro,$precioCuota);
            }
        } else {
            if ($crEstadoCuota != 3) {
                $query = $this->Estado_3($i_d,$idCobro,$montoTotalPagado);
            }
        }
        // Insertar en la tabla 'Acientos'
        $Cuenta = array(

            'CCC_idCuenta_Corriente_Cliente' => $idCuota,
            'Caja_Cobros_idCaja_Cobros' => $idCobro,

        );
        $this->db->insert('Cuenta_Corriente_Pagos_Cobros', $Cuenta);

        
        $totalrous--;
    }

    // Manejar parcial1
    if (!empty($parcial1)) {
        foreach ($moneda as $key => $value) {
            if ($value['cambiado'] > 0) {
                $data = array(
                    'Cobros_idCaja_Cobros ' => $idCobro,
                    'Descripcion' => 'Cobro Cuota ' . $value['EF'] . ' ' . $value['signo'],
                    'Monto' => $value['cambiado'],
                    'Moneda_idMoneda' => $value['Moneda'],
                );
                $this->db->insert('MetodoPago', $data);
                $id = $this->db->insert_id();
            }
        }
        $this->add_aciento_plan($idAcientos, '2', $parcial1, '(Ac +)');

    }
    // Manejar parcial2
    if (!empty($parcial2)) {
        $data = array(
            'NumeroCheque' => $numcheque,
            'Control' => 0,
            'ConceptoEntrada' => 'Cobro Cuota',
            'FechaExpedicion' => date("Y-m-d"),
            'Hora' => strftime("%H:%M", time()),
            'Importe' => $parcial2,
            'Pagos' => null,
            'Cobros' => 1,
            'Activo_Inactivo' => 1,
            'FechaPago' => $fecha_pago,
            'Entrada_Salida' => 'Entrada',
            'Gestor_Bancos_idGestor_Bancos' => null,
            'Proveedor_idProveedor' => null,
            'Cliente_idCliente' => $idCliente,
            'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
            'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $idArray[0],
            'Cobros_idCaja_Cobros ' => $idCobro,

        );
        $this->db->insert('Movimientos', $data);
        $idm = $this->db->insert_id();
        $_data = array(
            'Cobros_idCaja_Cobros ' => $idCobro,
            'Descripcion' => 'Cobro Cuota Cheque N° ' . $numcheque,
            'Monto' => $parcial2,
            'Movimientos_idMovimientos' => $idm,

        );
        $this->db->insert('MetodoPago', $_data);
        $this->add_aciento_plan($idAcientos, '4', $parcial2, '(Ac +)');
    }

    // Manejar parcial3
    if (!empty($parcial3)) {
        $Tarje = $Tarjeta == 1 ? "'Tarjetas de Crédito'" : "'Tarjetas de Débito'";
        $data = array(
            'TipodeTarjeta' => $Tarjeta,
            'MontodeTarjeta' => $parcial3,
            'FechaTarjeta' => date("Y-m-d"),
            'Caja_idCaja_Cobros ' => $idCobro,
        );
        $this->db->insert('Tarjeta', $data);
        $idt = $this->db->insert_id();
        $_data = array(
            'Cobros_idCaja_Cobros ' => $idCobro,
            'Descripcion' => 'Cobro Cuota por ' . $Tarje,
            'Monto' => $parcial3,        
            'Tarjeta_idTarjeta' => $idt,
        );
        $this->db->insert('MetodoPago', $_data);
        $this->add_aciento_plan($idAcientos, '304', $parcial3, '(Ac +)');
    }

    // Manejar parcial4
    if (!empty($parcial4)) {
        $seleccionados = explode(',', $matriscuanta);
        $matrir = explode(',', $matris);
        $this->db->set('Estado', 0, FALSE);
        $this->db->set('Cobros_idCaja_Cobros', $idCobro, FALSE);

        $this->db->where_in('idCuenta_Fabor', $seleccionados);
        $this->db->update('Cuenta_Fabor');

        for ($i = 0; $i < count($seleccionados); $i++) {
            $data = array(
                'Cobros_idCaja_Cobros ' => $idCobro,
                'Descripcion' => $pago.' Cuota Nº: '.$cuotaN.' ['.$matri[$i] .'] con Saldo Fabor',
                'Monto' => $matri[$i],
                'Cuenta_Fabor_idCuenta_Fabor' => $seleccionados[$i]
            );

            $this->db->insert('MetodoPago', $data);

            $data = array(
                'Accion' => '',
                'Cuenta_Fabor_idCuenta_Fabor' => $seleccionados[$i],
                'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ' => $idArray[0],
                'Cobros_idCaja_Cobros ' => $idCobro,

            );

            $this->db->insert('Cuenta_Corriente_Empresa_has_Cuenta_Fabor', $data);
        }



        $this->add_aciento_plan($idAcientos, '482', $parcial4, '(P -)');
    }

    if ($ajustado > 0) {
        $this->Cuenta_Fabor($idArray[0], $ajustado, $idCliente, $pago,$idCobro);
        $data = array(
            'PlandeCuenta_idPlandeCuenta' => '482',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => null,
            'HaberDetalle' => '(Ac +)',
            'Debe' => $ajustado,
            'Haber' => null,
        );
        $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }

    // Manejar vuelto total
    if (!empty($vueltototal)) {
        $data = array(
            'PlandeCuenta_idPlandeCuenta' => '483',
            'Acientos_idAcientos' => $idAcientos,
            'DebeDetalle' => null,
            'HaberDetalle' => '(Ac -)',
            'Debe' => null,
            'Haber' => $vueltototal,
        );
        $this->db->insert('PlandeCuenta_has_Acientos', $data);
        $_data = array(
            'Descripcion' => 'Diferencia Vuelto  Cuota N ' . $cuotaN  ,
            'MontoRecibido' => $vueltototal,
            'Caja_idCaja' => $ultimaCaja,
            'Tipos_de_Pago_idTipos_de_Pago ' => 5,
            'idCuentaCliente' => $idArray[0],
            'idVentaVuelto' => $idCobro,

        );
        $this->db->insert('Caja_Pagos', $_data);
    }

    // Finalizar la transacción de base de datos
    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    } else {
        $this->db->trans_commit();
        return true;
    }
}

  
public function pagar_parcial($i_d,$idF,$totalrous,$crEstado,$cfEstado,$idCliente,$Totalparclal,$vueltototal,$si_no,$ajustado,$numcheque,$fecha_pago,$efectivoTarjeta,$Tarjeta,$matriscuanta,$parcial1,$parcial2,$parcial3,$parcial4,$moneda, $matris )
{
    $this->db->trans_begin();
       $ultimaCaja = $this->ultimaCaja();
                     $pago = "Cobro Parcial ";
                  $data                         = array(
                  'Fecha'                       => date("Y-m-d"),
                  'Hora'                        => strftime( "%H:%M", time() ),
                  'Factura_Venta_idFactura_Venta'                       => $idF,
                  'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' =>  $i_d,
                  'Caja_idCaja'                 => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
                   if (!empty($parcial1)) {
                          foreach ($moneda as $key => $value) {
                                 $data                                                 = array(
                                 'Descripcion'                                         => 'Cobro Parcial Cuota '.$value['EF'].' '.$value['signo'] ,
                                 'Monto'                                               => $value['cambiado'],
                                 'Fecha'                                               => date("Y-m-d"),
                                 'Hora'                                                => strftime( "%H:%M", time() ),
                                 'Caja_idCaja'                                         => $ultimaCaja,
                                 'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,
                                 'Moneda_idMoneda'                                     =>  $value['Moneda'], 
                                 );
                             $this->db->insert('Caja_Cobros', $data);
                             $id = $this->db->insert_id();
                          }
			        $this->add_aciento_plan( $idAcientos,'2',$parcial1,'(Ac +)');
                   }
                    // si existe algun cheque creado
                    if (!empty($parcial2)) {
                        $data                                                 = array(
                        'NumeroCheque'                                        => $numcheque,
                        'Control'                                             => 0,
                        'ConceptoEntrada'                                     => 'Cobro Parcial Cuota',
                        'FechaExpedicion'                                     => date("Y-m-d"),
                        'Hora'                                                => strftime( "%H:%M", time() ),
                        'Importe'                                             => $parcial2,
                        'Pagos'                                               => null,
                        'Cobros'                                              => 1,
                        'Activo_Inactivo'                                     => 1,
                        'FechaPago'                                           => $fecha_pago ,
                        'Entrada_Salida'                                     => 'Entrada',
                        'Gestor_Bancos_idGestor_Bancos'                       => null,
                        'Proveedor_idProveedor'                               => null,
                        'Cliente_idCliente'                                   => $idCliente,
                        'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                        'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,
                        );
                        $this->db->insert('Movimientos', $data);
                        $idm = $this->db->insert_id();
                       $_data                                                 = array(
                       'Descripcion'                                         => 'Cobro Parcial Cuota Cheque N° ' .$numcheque,
                       'Monto'                                               => $parcial2,
                       'Fecha'                                               => date("Y-m-d"),
                       'Hora'                                                => strftime( "%H:%M", time() ),
                       'Caja_idCaja'                                         => $ultimaCaja,
                       'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,

                       );
                   $this->db->insert('Caja_Cobros', $_data);
		           $this->add_aciento_plan( $idAcientos,'4',$parcial2,'(Ac +)');



                   }


                  if (!empty($parcial3)) {
                  switch ($Tarjeta) {
                    case 1:
                     $Tarje = "'Tarjetas de Crédito'";
                      break;
                    case 2:
                     $Tarje = "'Tarjetas de Débito'";
                      break;
                  }
                    $data                                                 = array(
                    'TipodeTarjeta'  => $Tarjeta,
                    'MontodeTarjeta' => $parcial3,
                    'FechaTarjeta'   => date("Y-m-d")
                    );
                    $this->db->insert('Tarjeta', $data);
                    $idt = $this->db->insert_id();
                   $_data                                                 = array(
                   'Descripcion'                                         => 'Cobro Parcial Cuota por '.$Tarje,
                   'Monto'                                               => $parcial3,
                   'Fecha'                                               => date("Y-m-d"),
                   'Hora'                                                => strftime( "%H:%M", time() ),
                   'Caja_idCaja'                                         => $ultimaCaja,
                   'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,
                   'Tarjeta_idTarjeta'                            =>$idt
                   );
               $this->db->insert('Caja_Cobros', $_data);
           $this->add_aciento_plan( $idAcientos,'304',$parcial3,'(Ac +)');

               }
                    // si existe algun monto de mi cuenta
            if (!empty($parcial4)) {
                     $seleccionados = explode(',',$matriscuanta);
                       $matrir = explode(',',$matri);
                    for ($i=0;$i<count($seleccionados);$i++)
                    {
                      for ($j=0;$j<count($matrir);$j++)
                      {
                           $this->db->set('Estado', '0', FALSE);
                           $this->db->where('idCuenta_Fabor', $seleccionados[$i]);
                           $this->db->update('Cuenta_Fabor');
                            $data                                                 = array(
                            'Accion'                                              => '',
                            'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,
                            'Cuenta_Fabor_idCuenta_Fabor'                         => $seleccionados[$i],
                            );
                            $this->db->insert('Cuenta_Corriente_Empresa_has_Cuenta_Fabor', $data);
                            $_data                          = array(
                            'Descripcion'                   => 'Cobro Parcial Cuota por Cuenta Fabor',
                            'Monto'                         => $matrir[$j],
                            'Fecha'                         => date("Y-m-d"),
                            'Hora'                          => strftime( "%H:%M", time() ),
                            'Caja_idCaja'                   => $ultimaCaja,
                            'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => $i_d,
                            'Cuenta_Fabor_idCuenta_Fabor'                         =>  $seleccionados[$i],
                           );
                           $this->db->insert('Caja_Cobros', $_data);
                       }

                    } 
             $this->add_aciento_plan( $idAcientos,'482',$parcial4,'(P -)');
           }
             $this->add_aciento_debe($idAcientos,'34',$Totalparclal,'(Ac -)');
           $this->add_flou($idAcientos,'<p class="text-danger">Cobro Parcial de cuota</p>');

                    if ($crEstado != 3) {
                        $query = $this->Estado_3($id);
                    }
                    if ($cfEstado  != 1) {
                         $var = '1';
                          $this->_pagado($idF, $var);
                    }
    if ($this->db->trans_status() === FALSE)
    {
            $this->db->trans_rollback();
    }
    else
    {
            $this->db->trans_commit();
    }
}


      public function ultimaCaja()
    {
            $this->db->select_max('idCaja');
            $this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }



  public function verificar($id)
  {
   $this->db->select('count(Num_Recibo) as num');
   $this->db->where('Estado = 0 and Factura_Venta_idFactura_Venta = '.$id.' or Estado = 3 and Factura_Venta_idFactura_Venta = '.$id.'');
   $query = $this->db->get('Cuenta_Corriente_Cliente');
   return $query->num_rows();
  }
    public function Estado_0($id)
    {

                    $this->db->set('Estado', '0');
                    $this->db->set('MontoRecibidoCCC', 0, FALSE);
                    $this->db->where('idCuenta_Corriente_Cliente', $id);
                    $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                    $query = $this->db->update('Cuenta_Corriente_Cliente');

    }

    public function Estado_1($id,$idCobro,$MontoRecibidoCCC)
    {

                    $this->db->set('Estado', '1');
                    $this->db->where('idCuenta_Corriente_Cliente', $id);
                    $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                    $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC+'.$MontoRecibidoCCC, FALSE);
                    $this->db->set('Caja_Cobros_idCaja_Cobros',$idCobro);

                    $query = $this->db->update('Cuenta_Corriente_Cliente');

    }

    public function Estado_3($id, $idCobro, $MontoRecibidoCCC)
    {
        $this->db->set('Estado', '3');
        $this->db->where('idCuenta_Corriente_Cliente', $id);
        $this->db->set('Fecha_Pago', date("Y-m-d H:i:s"));
        $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC + ' . $MontoRecibidoCCC, FALSE);
        
        if ($idCobro) {
            $this->db->set('Caja_Cobros_idCaja_Cobros', $idCobro);
        }
        
        $this->db->trans_start();
        $this->db->update('Cuenta_Corriente_Cliente');
        $this->db->trans_complete();
    }
    public function delete_cuenta($id,$a="")
    {
                 $this->db->set('Estado', 1, FALSE);
                 $this->db->where('idCuenta_Fabor', $a);
                 $this->db->update('Cuenta_Fabor');
                //  if ($a != '') {
                //  $this->db->where('Cuenta_Fabor_idCuenta_Fabor', $a);
                //  $this->db->delete('Cuenta_Corriente_Empresa_has_Cuenta_Fabor');
                //  }

    }
    public function res_factura($id,$val)
    {
           $this->db->set('Estado', $val, FALSE);
           $this->db->where('idFactura_Venta', $id, FALSE);
           $this->db->update('Factura_Venta');

    }
    public function _pagado($id,$var)
    {

           $this->db->set('Estado', $var);
           $this->db->where('idFactura_Venta', $id);
           $this->db->update('Factura_Venta');
           return $this->db->affected_rows();

    }

    public function delete($id,$id2,$id3,$id4,$cantidad,$tipopago,$monto)
    {
        $this->db->trans_begin();


                 $this->db->where('idCaja_Cobros', $id2);
                 $this->db->delete('Caja_Cobros');

                $this->db->set('Estado', 1, FALSE);
                $this->db->where('Cobros_idCaja_Cobros', $id2);
                $this->db->update('Cuenta_Fabor');


                if ($cantidad == 1) {							
                    $this->db->set('Estado', '0');
                    $this->db->set('MontoRecibidoCCC', 0, FALSE);
                    $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                    $this->db->where('Caja_Cobros_idCaja_Cobros', $id2);
                    $query = $this->db->update('Cuenta_Corriente_Cliente');
        		}else{
                    $this->db->set('Estado', '3');
                    $this->db->where('idCuenta_Corriente_Cliente', $id);
                    $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC - ' . $monto, FALSE);
                    $this->db->update('Cuenta_Corriente_Cliente');

                }




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


    
    public function add_aciento_plan($idAcientos,$value='',$parcial,$signo='')
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => $signo,
          'HaberDetalle'                => NULL,
          'Debe'                        => $parcial,
          'Haber'                       => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
    public function add_aciento_debe($idAcientos,$value='',$parcial,$signo='')
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
    public function add_flou($idAcientos,$res)
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => $res,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
}

/* End of file Deuda_cliente_Model.php */
/* Location: ./application/models/Deuda_cliente_Model.php */