<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Deuda_empresa_Model extends CI_Model {
        VAR $column    = array('Num_Cuotas','Razon_Social','Ruc','Importe','Num_factura_Compra');
        var $order     = array('Fecha_Ven' => 'desc');
        const WHERE    = 'cr.Estado = 0 or cr.Estado = 3'; // no pagado
        const WHERE2    = 'cf.Estado = 0 AND Activo_Inactivo = 0'; // no
        const WHE_RE   = 'cr.Estado = 1 or cr.Estado = 3'; //  1 = pagado 3 = pago parcial
        const VOLETA  = 'Tipo_Compra = 0'; //  COMPROVANTE VOLETA
        const FACTURA = 'Tipo_Compra = 1'; //  cOMPROVANTE FACTURA
        const Group_By = '
        Monto_envio,
        Monto_Total,
        pr.Razon_Social,
        pr.Vendedor,
        fa.Num_factura_Compra, 
        fa.Tipo_Compra,
        fa.Contado_Credito,
         fa.Proveedor_idProveedor ';
        const SELECT_DEUDAS =
        '
        Monto_envio,
        Monto_Total as monto_totales,
        fa.Proveedor_idProveedor as idProveedor,
        fa.idFactura_Compra,
        sum(cr.Importe) as inporte_total,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Empresa,
        cr.Fecha_Pago,
        pr.Razon_Social,
        pr.Vendedor,
        fa.Num_factura_Compra,
        fa.Tipo_Compra, 
        fa.Contado_Credito,fa.Estado as esta'
        ;
        const SELECT_PAGADAS =
        'cr.Num_Cuotas as Num_cuota,
        COUNT(cr.Num_Cuotas ) as totalrous,
        Monto_envio,
        idCuenta_Corriente_Empresa as id,
        cr.Num_Recibo,
        fa.Monto_Total as monto_totales,
        fa.Proveedor_idProveedor as idProveedor,
        fa.idFactura_Compra,
        cr.Importe as inporte_total,
        cr.Fecha_Ven,
        cr.idCuenta_Corriente_Empresa,
        cr.Estado as crestado,
        pr.Razon_Social,
        pr.Vendedor,
        fa.Num_factura_Compra,
        fa.Tipo_Compra,
        fa.Contado_Credito,
        fa.Estado as esta'
        ;
        const SELECT_PAGADA =
        'cr.Num_Cuotas as Num_cuota,
        Monto_envio,
        idCuenta_Corriente_Empresa as id,
        cr.Num_Recibo,
        fa.Monto_Total as monto_totales,
        fa.Proveedor_idProveedor as idProveedor,
        fa.idFactura_Compra,
        cr.Importe as inporte_total,
        cr.MontoRecibidoCCC  AS total_caja_pagos,
        cr.Fecha_Ven,cr.Fecha_Pago,
        cr.idCuenta_Corriente_Empresa,
        cr.Estado as crestado,
        pr.Razon_Social,
        pr.Vendedor,
        fa.Num_factura_Compra,
        fa.Tipo_Compra,
        fa.Contado_Credito,
        fa.Estado as esta'
        ;
        const DEUDA         = 'Cuenta_Corriente_Empresa cr';
        const ID               = 'idProducto';
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Asuncion");
        $this->load->database();
    }

    function filterSeach($estatus , $ruc , $factura , $anho) {
                // Filtrar por permisos
                if ($this->session->userdata('Permiso_idPermiso') != 1) {
                    $this->db->where('fc.Caja_idCaja', $this->session->userdata('idCaja'));
                }
            // Filtrar por estatus
            if ($estatus) {
                $this->db->where('fc.Estado', $estatus); // Cambia 'Estado' por el nombre correcto si es necesario
            }
        
            // Filtrar por RUC
            if ($ruc) {
                $this->db->like('pr.Ruc', $ruc); // Filtrar por RUC
            }
        
            // Filtrar por factura
            if ($factura) {
                $this->db->like('fc.Num_factura_Compra', $factura); // Filtrar por ID de factura
                $this->db->or_like('fc.Ticket', $factura);
            }
        
            // Filtrar por año
            if ($anho) {
                $this->db->where('YEAR(fc.Fecha_expedicion)', $anho); // Cambia 'fecha_column' por el nombre correcto de la columna que contiene la fecha
            }
        
    }

    private function _get_datatables_query($search_value = '', $order_column = '', $order_dir = '', $estatus = '', $ruc = '', $factura = '', $anho = '')
    {
        $this->db->select('
            fc.idFactura_Compra AS Factura_ID,
            fc.Monto_Total AS Monto_Total_Factura,
            pr.Razon_Social,
            pr.Vendedor,
            pr.Ruc,
  fc.Num_factura_Compra , fc.Ticket,
            cc.Fecha_Ven,
            cc.Fecha_Pago,
            cc.Estado AS crestado,
            fc.Estado AS esta,
            COUNT(DISTINCT cc.idCuenta_Corriente_Empresa) AS Cuotas_Total,
            COUNT(DISTINCT CASE WHEN cc.Estado = 1 THEN cc.idCuenta_Corriente_Empresa END) AS Cuotas_Pagadas,
            COUNT(DISTINCT CASE WHEN cc.Estado NOT IN (1) THEN cc.idCuenta_Corriente_Empresa END) AS Cuotas_NoPagado,
            COUNT(DISTINCT CASE WHEN cc.Estado NOT IN (0, 1) THEN cc.idCuenta_Corriente_Empresa END) AS Cuotas_Parcial,
        (SELECT SUM(MontoRecibidoCCC) 
         FROM Cuenta_Corriente_Empresa cr2 
         WHERE cr2.Factura_Compra_idFactura_Compra = fc.idFactura_Compra AND cr2.Estado IN (3, 1)) AS Total_Pagado, 
        (SELECT SUM(MontoRecibidoCCC) 
         FROM Cuenta_Corriente_Empresa cr2 
         WHERE cr2.Factura_Compra_idFactura_Compra = fc.idFactura_Compra AND cr2.Estado = 3) AS Total_Parcial', false);
        $this->db->from('Factura_Compra fc');
        $this->db->join('Cuenta_Corriente_Empresa cc', 'fc.idFactura_Compra = cc.Factura_Compra_idFactura_Compra', 'inner');
        $this->db->join('Proveedor pr', 'fc.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->where('fc.Contado_Credito', 2); // Filtrar por tipo de compra (crédito)
        $this->db->group_by('fc.idFactura_Compra, pr.idProveedor'); // Agrega la cláusula GROUP BY
        $this->filterSeach($estatus , $ruc , $factura , $anho );



        $i = 0;
        foreach ($this->column as $item) {
            if ($search_value) {
                ($i === 0) ? $this->db->like($item, $search_value) : $this->db->or_like($item, $search_value);
            }
            $column[$i] = $item;
            $i++;
        }
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
    
    function getdeuda()
    {
        $query = $this->get_Deuda();
        return $query;
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
        SELECT sum(Monto) as total1,'' as cuenta from Caja_Pagos  WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        LEFT JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id' OR idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT '' as total1, sum(Monto) as cuenta from Cuenta_Fabor
        WHERE   Estado = '3' AND idCuenta_Corriente_Empresa ='$id'
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
        SELECT sum(Monto) as total1,'' as cuenta from Caja_Pagos  WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
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
        ccc.idCuenta_Corriente_Empresa AS idCCE,
        ccp.Caja_Pagos_idCaja_Pagos AS idCA,
        ccc.Factura_Compra_idFactura_Compra AS idFC,
        mp.Movimientos_idMovimientos AS idMM,
        m.Pagos,

        ccp.CCE_idCuenta_Corriente_Empresa AS idCF'
        

    )
    ->from('Cuenta_Corriente_Pagos_Cobros ccp')
    ->join('Caja_Pagos cc', 'cc.idCaja_Pagos = ccp.Caja_Pagos_idCaja_Pagos', 'inner')
    ->join('Cuenta_Corriente_Empresa ccc', 'ccc.idCuenta_Corriente_Empresa = ccp.CCE_idCuenta_Corriente_Empresa', 'inner')
    ->join('MetodoPago mp', 'mp.Pagos_idCaja_Pagos = cc.idCaja_Pagos', 'inner')
    ->join('Movimientos m', 'm.idMovimientos = mp.Movimientos_idMovimientos', 'left')
    ->where('ccp.CCE_idCuenta_Corriente_Empresa', $id)
    ->group_by('idCaja_Pagos')
    ->get();

       
        $resultados = $query->result();

        

        return $resultados;
    }
    ////////FIN DEUDAS//////////////////
    //////////// PAGADAS///////////////
    VAR $column1    = array('Num_Cuotas','Num_Recibo','Fecha_Pago','Razon_Social','Importe_Total_Cuota','Monto_Pagado');
    var $order1     = array('fc.Fecha_Pago' => 'ACS');
    private function _get_datatables_query_($estatus, $ruc, $factura, $anho)
    {

        $this->db->select('
                cr.Num_Cuotas AS Cuota_Numero,
            cr.Estado as crestado,
            fc.Estado as esta,
            cr.Fecha_Pago,
            cr.idCuenta_Corriente_Empresa as id,
            fc.Num_factura_Compra AS Comprobante_Numero,
                cr.Num_Recibo AS Num_Recibo,
                pr.Razon_Social AS Nombres,
                pr.Ruc,
                cr.Importe AS Importe_Total_Cuota,
                cr.Fecha_Pago AS Fecha_Pago,
                cr.Pagos_idCaja_Pagos as idCaja_Pagos,
                cr.MontoRecibidoCCC AS Monto_Pagado
        ');
        $this->db->from('Cuenta_Corriente_Empresa cr')
            ->join('Factura_Compra fc', 'cr.Factura_Compra_idFactura_Compra = fc.idFactura_Compra')
            ->join('Proveedor pr', 'fc.Proveedor_idProveedor = pr.idProveedor')
            ->join('Caja_Pagos cp', 'cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cr.idCuenta_Corriente_Empresa', 'left')
            ->join('Caja_Cobros cc', 'cc.idCuentaEmpresa = cr.idCuenta_Corriente_Empresa', 'left')
            ->where('fc.Contado_Credito', 2)
            ->where_in('cr.Estado', array(3, 1)) ; // Estado 3 (parcial), Estado 1 (pagado)
            $this->filterSeach($estatus , $ruc , $factura , $anho );
        $this->db->group_by('cr.Num_Cuotas, fc.Num_factura_Compra, pr.Razon_Social, cr.Importe');

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
        $this->db->from('Factura_Compra fa');
        $this->db->join(self::DEUDA, 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'left outer');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->order_by('Fecha_Pago', 'ACS');
        $this->db->where(self::WHE_RE);
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario') );
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
        $this->db->select('Fecha_Pago');
        $this->db->from('Factura_Compra fa');
        $this->db->join(self::DEUDA, 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'left outer');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->order_by('Fecha_Pago', 'ACS');
        $this->db->where(self::WHE_RE);
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
            $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja') );
        }
        return $this->db->count_all_results();
    }


  

    // fin pagadas
////////////////////////////////////////////////////////////////////////////////// Listar lista

    private function _get_datatables_list($id)
    {
        //  $ultima = $this->ultimaCaja();
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Compra fa', 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'left outer');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->where('cr.Estado = 0 and cr.Factura_Compra_idFactura_Compra = '.$id.' or cr.Estado = 3 and cr.Factura_Compra_idFactura_Compra = '.$id.'');
        if ($this->session->userdata('Permiso_idPermiso') != 1) { // si el usuario es distinto a administrador
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja') );
        }
        $this->db->order_by('Num_Recibo', 'ACS');
        $i = 0;

        foreach ($this->column2 as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column2[$i] = $item;
            $i++;

        }

        if(isset($_POST['order']))
        {
            $this->db->order_by($column2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order2))
        {
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
// Define columnas y ordenación
protected $column2 = array('cr.Num_Cuotas', 'cr.Num_Recibo', 'pr.Razon_Social', 'cr.Fecha_Ven');
protected $order2 = array('cr.Num_Cuotas' => 'ASC');

// Función para obtener la lista de deudas con suma
public function get_Deuda_list_with_sum($idCuentasCorrientes)
{
    // Selección de campos
    $this->db->select(self::SELECT_PAGADA);
    $this->db->from('Factura_Compra fa');
    $this->db->join('Cuenta_Corriente_Empresa cr', 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'inner');
    $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
    $this->db->join('Caja_Pagos cp', 'cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cr.idCuenta_Corriente_Empresa', 'left');
    $this->db->join('Caja_Cobros cc', 'cc.idCuentaEmpresa = cr.idCuenta_Corriente_Empresa', 'left');
    $this->db->where_in('cr.Estado', array(0, 3));
    $this->db->where('cr.Factura_Compra_idFactura_Compra', $idCuentasCorrientes);
    $this->db->group_by('cr.idCuenta_Corriente_Empresa');

    // Manejo de búsqueda
    $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
    if (!empty($searchValue)) {
        foreach ($this->column2 as $index => $column) {
            $this->db->or_like($column, $searchValue);
        }
    }

    // Manejo de ordenación
    $orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : null;
    $orderDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : null;
    if ($orderColumnIndex !== null && $orderDirection !== null) {
        if (isset($this->column2[$orderColumnIndex])) {
            $this->db->order_by($this->column2[$orderColumnIndex], $orderDirection);
        }
    } else {
        // Ordenación por defecto
        if (isset($this->order2)) {
            $this->db->order_by(key($this->order2), $this->order2[key($this->order2)]);
        }
    }
}

// Función para obtener la lista de deudas
public function get_Deuda_list($id)
{
    $this->get_Deuda_list_with_sum($id);
    if ($this->input->post('length') != -1) {
        $this->db->limit($this->input->post('length'), $this->input->post('start'));
    }

    $query = $this->db->get();
    return $query->result();
}


    public function get_Deudalist($id='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Compra fa', 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'left outer');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
       $this->db->where('cr.Estado = 0 and cr.Factura_Compra_idFactura_Compra = '.$id.' or cr.Estado = 3 and cr.Factura_Compra_idFactura_Compra = '.$id.'');
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
        $this->db->where('fa.Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
        $this->db->order_by('Num_Recibo', 'ACS');
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro_list($id)
    {
        $this->get_Deuda_list_with_sum($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas_list($id)
    {  
        $this->db->select('COUNT(*)');
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Compra fa', 'cr.Factura_Compra_idFactura_Compra = fa.idFactura_Compra', 'left outer');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->where('cr.Estado = 0 and cr.Factura_Compra_idFactura_Compra = '.$id.' or cr.Estado = 3 and cr.Factura_Compra_idFactura_Compra = '.$id.'');
        if ($this->session->userdata('Permiso_idPermiso') != 1) {
            $this->db->where('fa.Caja_idCaja', $this->session->userdata('idCaja') );
        }
        return $this->db->count_all_results();
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function lis_deuda($where = NULL) {
        $this->db->select(self::SELECT_PAGADAS);
        $this->db->from(self::DEUDA);
        $this->db->join('Factura_Compra fa', 'fa.idFactura_Compra = cr.Factura_Compra_idFactura_Compra', 'inner');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        // $this->db->where(self::WHERE);
        $this->db->where($where);
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
        $this->db->select('idMovimientos as id, NumeroCheque as numch, Importe as im');
        $this->db->where('Cobros', 1);
        $this->db->where('Activo_Inactivo', 1);
        $this->db->where('Control', 0);
        $query = $this->db->get('Movimientos');
    
        if ($query->num_rows() > 0) {
            $options = '<option value="">Selecciona una opción</option>';
            foreach ($query->result() as $value) {
                $monto = number_format($value->im, 0, '.', ',');
                $options .= '<option value="'.$value->im.','.$value->id.'">'.$monto.' ₲S.</option>';
            }
            return $options;
        } else {
            return '<option value="">No hay datos disponibles</option>';
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

    /**
     * [Cuenta_Fabor Nueva cuenta a fabor del algun proveedor]
     * @param [type] $id           [description]
     * @param [type] $agremicuenta [description]
     * @param [type] $idProveedor  [description]
     */
    public function Cuenta_Fabor($id,$agremicuenta,$idProveedor)
    {
        $this->db->trans_begin();
                $data                                                    = array(
                'Estado'                                                 => 1,
                'Fecha'                                                  => date("Y-m-d"),
                'Monto'                                                  => $agremicuenta,
                'Cliente_Empresa'                                        => 2,
                'Cliente_idCliente'                                      => null,
                'Proveedor_idProveedor'                                  => $idProveedor,
                'idCuenta_Corriente_Empresa'                             => $id,
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

    public function cheque_insert($id,$id_cheque,$idProveedor,$fecha_pago,$pago,$ultimaCaja )
    {
        $this->db->trans_begin();
                $this->db->set('Activo_Inactivo', '2', FALSE);
                $this->db->where('idMovimientos', $id_cheque);
                $this->db->update('Movimientos');
                $this->db->select('*', FALSE);
                $this->db->where('idMovimientos', $id_cheque);
                $query = $this->db->get('Movimientos');
                        if ($query->num_rows() > 0) {
                                foreach ( $query->result() as $key => $value) {
                                    $data                                                 = array(
                                    'Descripcion'                                         => $pago. ' Cuota con Cheque Tercero N° '.$value->NumeroCheque ,
                                    'Monto'                                               => $value->Importe,
                                    'Caja_idCaja'                                         => $ultimaCaja,
                                    'Empleado_idEmpleado' => null,
                                    'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $id,
                                    'Movimientos_idMovimientos'                            =>$id_cheque 
                                    );
                                $this->db->insert('Caja_Pagos', $_data);
                                }
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

  public function add_pagos($id,$efectivo,$pago,$ultimaCaja )
  {
     $this->db->trans_begin();
             $data = array(
            'Descripcion' =>$pago. ' en Efectivo por Cuota' ,
            'Monto' => $efectivo,
            'Caja_idCaja' => $ultimaCaja,
            'Empleado_idEmpleado' => null,
            'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $id,
            );
         $this->db->insert('Caja_Pagos', $data);
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

  public function add_cheque_new($id,$idProveedor,$fecha_pago,$numcheque,$cuenta_bancaria,$importe,$pago,$ultimaCaja  )
  {
      $this->db->trans_begin();
            $data                                                 = array(
            'NumeroCheque'                                        => $numcheque,
            'Control'                                             => 0,
            'ConceptoSalida'                                      => $pago ,
            'Importe'                                             => $importe,
            'Pagos'                                               => 1,
            'Activo_Inactivo'                                     => 2,
            'FechaPago'                                           => $fecha_pago ,
            'Entrada_Salida'                                      => 'Salida',
            'Gestor_Bancos_idGestor_Bancos'                       => $cuenta_bancaria,
            'Proveedor_idProveedor'                               => $idProveedor,
            'Cliente_idCliente'                                   => null,
            'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
            'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' =>$id
            );
                $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
                $this->db->set('MontoActivo', 'MontoActivo-'.$Importe, FALSE);
                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
                $this->db->update('Gestor_Bancos');

               $_data                                                 = array(
               'Descripcion'                                         => $pago. ' por Cuota en Cheque N° ' .$numcheque,
               'Monto'                                               => $importe,
               'Caja_idCaja'                                         => $ultimaCaja,
               'Empleado_idEmpleado' => null,
               'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' =>$id,
               'Movimientos_idMovimientos'                            =>$idm 
               );
                  $this->db->insert('Caja_Pagos', $_data);
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
   * @param [type] $idProveedor   [description]
   * @param [type] $pago          [description]
   */
  public function add_set_cuenta($id,$idCuestaFabor,$idProveedor,$pago,$ultimaCaja)
  {
     $this->db->trans_begin();
            $this->db->set('Estado', '0', FALSE);
            $this->db->where('idCuenta_Fabor', $idCuestaFabor);
            $this->db->update('Cuenta_Fabor');
            $this->db->select('Monto');
            $this->db->where('idCuenta_Fabor', $idCuestaFabor);
            $query = $this->db->get('Cuenta_Fabor');
            $row = $query->row();
            $row->Monto;

                             $data                                                 = array(
                             'Accion'                                              => '',
                             'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $id,
                             'Cuenta_Fabor_idCuenta_Fabor'                         => $idCuestaFabor,
                             );
            $this->db->insert('Cuenta_Corriente_Empresa_has_Cuenta_Fabor', $data);
                      $_data                                                 = array(
                       'Descripcion'                                         => $pago.'  por Cuenta Fabor',
                       'Monto'                                               => $row->Monto,
                       'Caja_idCaja'                                         => $ultimaCaja,
                       'Empleado_idEmpleado' => null,
                       'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa'  => $id,
                       'Cuenta_Fabor_idCuenta_Fabor'  => $idCuestaFabor,
                       );
                   $this->db->insert('Caja_Pagos', $_data);

     if ($this->db->trans_status() === FALSE)
     {
             $this->db->trans_rollback();
     }
     else
     {
             $this->db->trans_commit();
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

    
public function pagoDeuda(
$i_d,
$idF,
$totalrous,
$crEstado,
$cfEstado,
$idProveedor,
$Totalparclal,
$vueltototal,
$si_no,
$ajustado,
$numcheque, 
$cuenta_bancaria ,
$fecha_pago,
$efectivoTarjeta,
$Tarjeta,
$matriscuanta,
$parcial1,
$parcial2,
$parcial3,
$parcial4 ,
$moneda, 
$matris,
$Acheque_tercero,
$Acheque,
$monto,
$cuotaN ,
$id,
$cuotaPrecio )
{
    $this->db->trans_begin();

    // Obtener la última caja
    $idCaja = $this->session->userdata('idCaja');
    $idUsuario = $this->session->userdata('idUsuario');
    $plandata = [];
    $montoTotalPagado = $Totalparclal;
    $montoTotalCuotas = $cuotaPrecio;
    $Descripcion = ($id == 1 ) ? 'Pago Total' : 'Pago Parcial' ;
    $retVal = ($montoTotalPagado <  $montoTotalCuotas) ? $montoTotalPagado : $montoTotalCuotas ;


    if ($si_no == 1) {
        $vueltototal = 0;
        $montoTotalPagado = $montoTotalCuotas;
    }

    // Insertar en la tabla 'cobroCaja'
    $dataPagos = [
        'Descripcion'   => $Descripcion.' Cuota N ' . $cuotaN ,
        'MontoRecibido' =>  $montoTotalPagado,
        'MontoTotal'    =>  $retVal,
        'MontoVuelto'   =>  $vueltototal,
        'Caja_idCaja'   => $idCaja,
        'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $i_d,
        'Factura_Compra_idFactura_Compra' => $idF
    ];
    $idPago = $this->insertCajaPagos($dataPagos);


        // Inserción en la tabla 'Acientos'
        $dataAcientos = [
            'Factura_Compra_idFactura_Compra' => $idF,
            'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $i_d,
            'Caja_idCaja' => $idCaja,
            'idCaja_Pagos' => $idPago,
        ];
        $idAcientos = $this->insertAcientos($dataAcientos);

        // Actualizar el estado del cobro de la cuota
        if ($id == 1) {
            if ($totalrous == 1) {
                $var = '0';
                $this->_pagado($idF, $var);
            }
            if ($crEstadoCuota != 1) {
                $this->Estado_1($i_d,$idPago,$cuotaPrecio);
            }
        } else {
            if ($crEstadoCuota != 3) {
                $query = $this->Estado_3($i_d,$idPago,$montoTotalPagado);
            }
        }
        // Insertar en la tabla 'Acientos'
        $Cuenta = array(

            'CCE_idCuenta_Corriente_Empresa' => $i_d,
            'Caja_Pagos_idCaja_Pagos' => $idPago,

        );
        $this->db->insert('Cuenta_Corriente_Pagos_Cobros', $Cuenta);

        $data2                         = array(
            'PlandeCuenta_idPlandeCuenta' => '182',
            'Acientos_idAcientos'         => $idAcientos,
            'DebeDetalle'                 => '(Ac +)',
            'HaberDetalle'                => null,
            'Debe'                        => $retVal,
            'Haber'                       => null,
            );
        $this->db->insert('PlandeCuenta_has_Acientos', $data2);

        if (!empty($vueltototal)) {
            $data1                         = array(
                'PlandeCuenta_idPlandeCuenta' => '483',
                'Acientos_idAcientos'         => $idAcientos,
                'DebeDetalle'                 => '(Ac +)',
                'HaberDetalle'                => null,
                'Debe'                        => $vueltototal,
                'Haber'                       => null,
                );
                $this->db->insert('PlandeCuenta_has_Acientos', $data1);
                $Caja_Cobros                                                 = array(
                'Descripcion'                                         =>  'Diferencia Vuelto Cuota  Nº: '.$cuotaN,
                'MontoRecibido' => $vueltototal,
                'Caja_idCaja'                                         => $idCaja,
                'Tipos_de_Pago_idTipos_de_Pago ' => 5,
                'idCuentaEmpresa' => $i_d,
                'idCompraVuelto' => $idPago,
    
                );
                $this->db->insert('Caja_Cobros', $Caja_Cobros);
    
        }


            if ($parcial1) {
                foreach ($moneda as $key => $value) {
                    if ($value['cambiado'] > 0) {
                        $data = [
                            'Pagos_idCaja_Pagos' => $idPago,
                            'Descripcion' => $Descripcion.' Cuota ' . $value['EF'] . ' ' . $value['signo'],
                            'Monto' => $value['cambiado'],
                            'Moneda_idMoneda' => $value['Moneda'],
                        ];
                        $this->db->insert('MetodoPago', $data);
                    }
                }
                $this->add_aciento_plan($idAcientos,'2', $parcial1, '(Ac -)');
            }
        
            if ($parcial2) {
                $movimientosBase = [
                    'Control' => 0,
                    'ConceptoSalida' => $Descripcion.' Cuota  Nº: '.$cuotaN,
                    'Pagos' => 1,
                    'Cobros' => null,
                    'Activo_Inactivo' => 1,
                    'FechaPago' => $fecha_pago,
                    'Entrada_Salida' => 'Salida',
                    'Proveedor_idProveedor' => $proveedor,
                    'Cliente_idCliente' => null,
                    'Usuario_idUsuario' => $idUsuario,
                    'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => $i_d,
                ];
        
                if ($numcheque > 0 && $parcial2 > 0) {
                    $movimientos = $movimientosBase;
                    $movimientos['NumeroCheque'] = $numcheque;
                    $movimientos['Importe'] = $parcial2;
                    $movimientos['Cobros_idCaja_Pagos'] = $idPago;
        
                    if ($cuenta_bancaria) {
                        $this->db->set('MontoActivo', 'MontoActivo-' . str_replace(",", "", $parcial2), FALSE);
                        $this->db->where('idGestor_Bancos', $cuenta_bancaria);
                        $this->db->update('Gestor_Bancos');
                        $this->add_aciento_plan($idAcientos, $tipoComprovante, '4', $parcial2);
                        $movimientos['Gestor_Bancos_idGestor_Bancos'] = $cuenta_bancaria;
                    }
                    $this->db->insert('Movimientos', $movimientos);
                    $idm = $this->db->insert_id();
        
                    $_data = [
                        'Pagos_idCaja_Pagos' => $idPago,
                        'Descripcion' => 'Pago Cuota con Cheque N° ' . $numcheque,
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
                            $this->db->set('ConceptoSalida', "'Pago por cuota'");
                            $this->db->where('idMovimientos', $seleccionado, FALSE);
                            $this->db->update('Movimientos');
        
                            $_data = [
                                'Pagos_idCaja_Pagos' => $idPago,
                                'Descripcion' => $Descripcion.' cuota con Cheque Tercero',
                                'Monto' => $monto,
                                'Movimientos_idMovimientos' => $seleccionado,
                            ];
                            $this->db->insert('MetodoPago', $_data);
        
                            $parcialtodo += $monto;
                        }
                    }
                    $this->add_aciento_plan($idAcientos, '33', $parcialtodo, '(Ac -)');
                }
            }
        
            if ($parcial3) {
                $Tarje = $Tarjeta == 1 ? "Tarjetas de Crédito" : "Tarjetas de Débito";
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
                    'Descripcion' => $Descripcion.' cuota con ' . $Tarje,
                    'Monto' => $parcial3,
                    'Tarjeta_idTarjeta' => $idt,
                ];
                $this->db->insert('MetodoPago', $_data);
        
                $this->add_aciento_plan($idAcientos, '304', $parcial3, '(Ac -)');
            }
        
            if ($parcial4) {
                $seleccionados = explode(',', $matriscuanta);
                $matri = explode(',', $matris);
        
                $this->db->set('Estado', 2, FALSE);
                $this->db->set('Pagos_idCaja_Pagos', $idPago, FALSE);
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
                $this->add_aciento_plan($idAcientos, '481', $parcial4, '(Ac -)');
            }

            if ($si_no == 1) {
                $data = array(
                    'Estado' => 1,
                    'Monto' => $ajustado,
                    'Cliente_Empresa' => 2,
                    'Cliente_idCliente' => null,
                    'Proveedor_idProveedor' => $idProveedor,
                    'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
                    'Devoluciones_idDevoluciones' => null,
                    'Pagos_idCaja_Pagos'=> $idPago, 
                    'InsertadoUtilizado' => 1
                );
        
                $this->db->insert('Cuenta_Fabor', $data);
                $idCuenta = $this->db->insert_id();
        
                $data = array(
                    'PlandeCuenta_idPlandeCuenta' => '481',
                    'Acientos_idAcientos' => $idAcientos,
                    'DebeDetalle' => '(Ac +)',
                    'HaberDetalle' => null,
                    'Debe' => $ajustado,
                    'Haber' => null,
                );
        
                $this->db->insert('PlandeCuenta_has_Acientos', $data);


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



  public function verificar($id)
  {
   $this->db->select('count(Num_Recibo) as num');
   $this->db->where('Estado = 0 and Factura_Compra_idFactura_Compra = '.$id.' or Estado = 3 and Factura_Compra_idFactura_Compra = '.$id.'');
   $query = $this->db->get('Cuenta_Corriente_Empresa');
   return $query->num_rows();
  }
  public function Estado_0($id)
  {

                  $this->db->set('Estado', '0');
                  $this->db->set('MontoRecibidoCCC', 0, FALSE);
                  $this->db->where('idCuenta_Corriente_Empresa', $id);
                  $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                  $query = $this->db->update('Cuenta_Corriente_Empresa');

  }

  public function Estado_1($id,$idCobro,$MontoRecibidoCCC)
  {

                  $this->db->set('Estado', '1');
                  $this->db->where('idCuenta_Corriente_Empresa', $id);
                  $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                  $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC+'.$MontoRecibidoCCC, FALSE);
                  $this->db->set('Pagos_idCaja_Pagos',$idCobro);

                  $query = $this->db->update('Cuenta_Corriente_Empresa');

  }


public function Estado_3($id, $idCobro, $MontoRecibidoCCC)
{
    $this->db->set('Estado', '3');
    $this->db->where('idCuenta_Corriente_Empresa', $id);
    $this->db->set('Fecha_Pago', date("Y-m-d H:i:s"));
    $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC + ' . $MontoRecibidoCCC, FALSE);
    
    if ($idCobro) {
        $this->db->set('Pagos_idCaja_Pagos', $idCobro);
    }
    
    $this->db->trans_start();
    $this->db->update('Cuenta_Corriente_Empresa');
    $this->db->trans_complete();
}


    public function delete_cuenta($id,$a="")
    {
                 $this->db->set('Estado', '0', FALSE);
                 $this->db->where('idCuenta_Fabor', $a);
                 $this->db->update('Cuenta_Fabor');
                 if ($a != '') {
                 $this->db->where('Cuenta_Fabor_idCuenta_Fabor', $a);
                 $this->db->delete('Cuenta_Corriente_Empresa_has_Cuenta_Fabor');
                 }

    }
    public function res_factura($id,$val)
    {
           $this->db->set('Estado', $val, FALSE);
           $this->db->where('idFactura_Compra', $id, FALSE);
           $this->db->update('Factura_Compra');

    }
    public function _pagado($id,$var)
    {

           $this->db->set('Estado', $var);
           $this->db->where('idFactura_Compra', $id);
           $this->db->update('Factura_Compra');
           return $this->db->affected_rows();

    }
    public function delete($params)
    {
    
        $this->db->trans_begin();
    
        $id1 = $params['id1'];
        $id2 = $params['id2'];
        $id3 = $params['id3'];
        $id4 = $params['id4'];
        $id5 = $params['id5'];
        $tipopago = $params['tipopago'];
        $cantidad = $params['cantidad'];
        $monto = $params['monto'];
    
        try {

            // Delete from Caja_Pagos
            $this->db->where('idCaja_Pagos', $id2);
            $this->db->delete('Caja_Pagos');
            if ($this->db->affected_rows() === 0) {
                throw new Exception("Failed to delete from Caja_Pagos");
            }
    
            // Update Cuenta_Fabor where InsertadoUtilizado != 1
            $this->db->set('Estado', 1, FALSE);
            $this->db->where('Pagos_idCaja_Pagos', $id2);
            $this->db->where('InsertadoUtilizado != 1');
            $this->db->update('Cuenta_Fabor');
    
            // Delete from Cuenta_Fabor where InsertadoUtilizado = 1
            $this->db->where('Pagos_idCaja_Pagos', $id2);
            $this->db->where('InsertadoUtilizado = 1');
            $this->db->delete('Cuenta_Fabor');
    
            // Update Movimientos
            if ($id5) {

                $this->db->set('Activo_Inactivo',1, FALSE);
                $this->db->set('Pagos', null);
                $this->db->set('Cobros', 1);
                $this->db->set('ConceptoEntrada', 'Cobros');
                $this->db->set('ConceptoSalida', null);
                $this->db->where('idMovimientos', $id5, FALSE);
                $this->db->update('Movimientos');
       
             }


            // Update Cuenta_Corriente_Empresa based on cantidad
            if ($cantidad == 1) {
                $this->db->set('Estado', '0');
                $this->db->set('MontoRecibidoCCC', 0, FALSE);
                $this->db->set('Fecha_Pago', date("Y-m-d") . ' : ' . strftime("%H:%M", time()));
                $this->db->where('Pagos_idCaja_Pagos', $id2);
                $this->db->update('Cuenta_Corriente_Empresa');
            } else {
                $this->db->set('Estado', '3');
                $this->db->where('idCuenta_Corriente_Cliente', $id1);
                $this->db->set('MontoRecibidoCCC', 'MontoRecibidoCCC - ' . $monto, FALSE);
                $this->db->update('Cuenta_Corriente_Empresa');
            }
    
            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Transaction failed.");
            } else {
                $this->db->trans_commit();
                return $this->db->affected_rows();
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', $e->getMessage());
            return FALSE;
        }
    }
    

// public function delete($params)
// {
//     $id1 = $params['id1'];
//     $id2 = $params['id2'];
//     $id3 = $params['id3'];
//     $id4 = $params['id4'];
//     $id5 = $params['id5'];
//     $id6 = $params['id6'];
//     $cantidad = $params['cantidad'];
//     $tipopago = $params['tipopago'];

//     $this->db->trans_begin();

//     $estado = ($cantidad == 1) ? 0 : 3;

//     if ($tipopago) {
//         $this->db->where('idCaja_Cobros', $id2);
//         $this->db->delete('Caja_Cobros');
//     } else {
//         $this->db->set('Estado', $estado);
//         $this->db->where('idCuenta_Corriente_Empresa', $id1);
//         $this->db->update('Cuenta_Corriente_Empresa');

//         if ($id2) {
//             $this->db->where('idCaja_Pagos', $id2);
//             $this->db->delete('Caja_Pagos');
//         }

//         if ($id4) {
//             $this->db->set('Estado', 0);
//             $this->db->where('idCuenta_Fabor', $id4);
//             $this->db->where('Estado', 1);
//             $this->db->update('Cuenta_Fabor');

//             $this->db->where('idCuenta_Fabor', $id4);
//             $this->db->where('Estado', 2);
//             $this->db->delete('Cuenta_Fabor');
//         }

//         if ($id5) {
//             $this->db->set('Activo_Inactivo', 1);
//             $this->db->where('idMovimientos', $id5);
//             $this->db->where('Cobros', 1);
//             $this->db->update('Movimientos');

//             $this->db->where('idMovimientos', $id5);
//             $this->db->where('Pagos', 1);
//             $this->db->delete('Movimientos');
//         }

//         if ($id6) {
//             $this->db->where('idTarjeta', $id6);
//             $this->db->delete('Tarjeta');
//         }
//     }

//     if ($this->db->trans_status() === FALSE) {
//         $this->db->trans_rollback();
//     } else {
//         $this->db->trans_commit();
//         return $this->db->affected_rows();
//     }
// }
    public function add_aciento_plan($idAcientos,$value='',$parcial,$signo='')
    {

          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => NULL,
          'HaberDetalle'                => $signo,
          'Debe'                        => null,
          'Haber'                       => $parcial,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);

    }

    public function add_flou( $idAcientos,$CONDICION)
    {


          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => $CONDICION,
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