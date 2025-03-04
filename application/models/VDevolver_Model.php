<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vdevolver_Model extends CI_Model {
        VAR $column   = array('Num_Factura_Venta','Nombres','Fecha');
        var $order    = array('idDevoluciones' => 'desc');
        const DDD = 'Devoluciones de';
        const SELECT ='
        idDevoluciones,Nombres,Ruc,
        Motivo,
        Venta_Compra,
        Factura_Venta_idFactura_Venta as id,
        Num_Factura_Venta,
        Nombres,Apellidos,Ruc,
        Fecha,
        de.Monto_Total,Ticket,
        Tipo_Venta';
         const SELEC='
         idDetalle_Factura as id6,
        idDetalle_Devolucion,
        prc.Nombre,
        dd.Estado,
        dd.Motivo,
        dd.Precio,
        dd.Cantidad,
        idDetalle_Devolucion as id,
        df.Producto_idProducto as id2,Devoluciones_idDevoluciones as del
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
        $this->db->join('Factura_Venta fc', 'de.Factura_Venta_idFactura_Venta = fc.idFactura_Venta', '');
        $this->db->join('Cliente cl', 'fc.Cliente_idCliente  = cl.idCliente', '');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fc.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        foreach ($this->column as $item)
        {
            $this->db->where('de.Venta_Compra = 1');
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

    function get_Devolver()
    {
        $this->_get_datatables_query();
        if($_POST['length'] > 3)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function getDevolver($where = NULL)
    {
        $this->db->select(self::SELECT);
        $this->db->from(self::DDD);
        $this->db->join('Factura_Venta fc', 'de.Factura_Venta_idFactura_Venta = fc.idFactura_Venta', '');
        $this->db->join('Cliente cl', 'fc.Cliente_idCliente  = cl.idCliente', '');
        $this->db->where('de.Venta_Compra = 1');

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
        $this->db->join('Factura_Venta fc', 'de.Factura_Venta_idFactura_Venta = fc.idFactura_Venta', '');
        $this->db->join('Cliente cl', 'fc.Cliente_idCliente  = cl.idCliente', '');
        $this->db->where('de.Venta_Compra = 1');
        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fc.Caja_idCaja', $this->session->userdata('idCaja'));
        }
        return $this->db->count_all_results();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function detale($where = NULL) {
        $this->db->select(self::SELEC);
        $this->db->from('Detalle_Devolucion dd');
        $this->db->join('Detalle_Factura df', 'dd.Detalle_Factura_idDetalle_Factura = df.idDetalle_Factura', '');
        $this->db->join('Producto prc', 'df.Producto_idProducto  = prc.idProducto', '');
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
         foreach ($query->result() as $key => $value) {
            switch ($value->Estado) {
                case 'Mercaderia Recibido y Cambiado':
                    $Estado = 1;
                    break;
                case 'Mercaderia Recibido y Pagado en Efectivo':
                    $Estado = 2;
                    break;
                case 'Mercaderia Devuelto y Agregado monto a Cuenta':
                    $Estado = 3;
                    break;
            }
            switch ($value->Motivo) {
                case 'Otros':
                    $Motivo = 1;
                    break;
                case 'Vencido':
                    $Motivo = 2;
                    break;
                case 'Descompuesto':
                    $Motivo = 3;
                    break;
            }
             $res[] = array(
                'Estado'               => $Estado,
                'Motivo'               => $Motivo,
                'mo'                   => $value->Motivo,
                'es'                   => $value->Estado,
                'Nombre'               => $value->Nombre,
                'Precio'               => $value->Precio,
                'id'                   => $value->id,
                'id2'                  => $value->id2,
                'del'                  => $value->del,
                'idDetalle_Devolucion' => $value->idDetalle_Devolucion,
                'Cantidad'             => $value->Cantidad,
                'id6'                  => $value->id6,   );
         }
         return  $res;
  
    }
    public function detalele($where = NULL) {
        $this->db->select(self::SELEC);
        $this->db->from('Detalle_Devolucion dd');
        $this->db->join('Detalle_Factura df', 'dd.Detalle_Factura_idDetalle_Factura = df.idDetalle_Factura', '');
        $this->db->join('Producto prc', 'df.Producto_idProducto  = prc.idProducto', '');
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
    public function lis_comprobante($where = NULL) {
        $this->db->select('idFactura_Venta,Num_Factura_Venta,Tipo_Venta,Contado_Credito,Descuento_Total,Cargos_Envios,Cliente_idCliente,Ticket');
        $this->db->from('Factura_Venta');
        $this->db->where('Estado !=4');
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
                if ($value->Tipo_Venta == 0) {
                $options.='<option 
                value="'.$value->idFactura_Venta.','.$value->Descuento_Total.','.$value->Cargos_Envios.'">Recibo Nº  '.$value->Ticket.'</option>';

                }elseif ($value->Tipo_Venta == 1) {
                $options.='<option 
                value="'.$value->idFactura_Venta.','.$value->Descuento_Total.','.$value->Cargos_Envios.'">Factura Nº   '.$value->Num_Factura_Venta.'</option>';

                }
            }
         return $options; 
         }


    }


    public function item_Comprobante($where = NULL) {
        
    $this->db->select('idDetalle_Factura,Producto_idProducto,Devolucion,dc.Cantidad,Cantidad_A,Precio,Nombre,dc.Iva as i_v_a');
    $this->db->from('Detalle_Factura dc');
    $this->db->join('Producto prc', 'dc.Producto_idProducto = prc.idProducto', 'left');
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
            $opciones =  array('Cantidad_max_devol' => $row->Cantidad-$row->Devolucion);
            $opcione =  array('Cantidad_stock' => $row->Cantidad_A);
            $option2 =  array('Producto_idProducto' => $row->Producto_idProducto);
            $data = array(
                        'id'      =>$row->idDetalle_Factura,
                        'qty'     => $row->Cantidad,
                        'price'   => $row->Precio,
                        'name'    =>$row->Nombre, 'descuento'    =>'',
                        'descuento'    =>'',
                        'options' => $opciones,
                        'option' => $opcione,
                        'option2' => $option2
                    );
            $this->cart->insert($data);
    }
    return $query->num_rows();
    }

    public function check_num($id)
    {
        $this->db->select('Num_Factura_Venta');
        $this->db->where('Num_Factura_Venta',$id);
        $consulta = $this->db->get(self::FACTURA);
        if ($consulta->num_rows()> 0) {
            return true;
        }
    }

    public function devolver($Cliente,$_id,$mov,$tipooccion,$cart_total,$data)
    {

        $this->db->trans_begin();
        // $this->output->enable_profiler(TRUE);
            $this->cart->update($data);
                        switch ($mov) {
                        case '3':
                           $motivo = 'Otros';
                            break;
                        case '1':
                           $motivo = 'Vencido';
                            break;
                        case '2':
                            $motivo = 'Descompuesto';
                            break;
                        }
            switch ($tipooccion) {
                case '1': //Devolver y Cambiar
                // $this->output->enable_profiler(TRUE);
                    $occion = 'Mercaderia Recibido y Cambiado';
                                $object = array(
                                'Fecha'                           => date("d-m-Y"),
                                'Monto_Total'                     => $cart_total,
                                'Venta_Compra'                    => 1,
                                'Factura_Venta_idFactura_Venta'   => $_id,
                                'Factura_Compra_idFactura_Compra' =>null,
                                );
                                $this->db->insert('Devoluciones', $object);
                                $idDevoluciones =  $this->db->insert_id();
                        $i = 1;
                        foreach ($this->cart->contents() as $items) {
                                foreach ($this->cart->product_option2($items['rowid']) as $option_name => $idproducto) {
                                }
                                $id = $items['id'];
                                $qty = $items['qty'];
                                $monto = $items['price'];
                                if ($mov != 3) {
                                $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
                                $this->db->where('idProducto', $idproducto);
                                $this->db->update('Producto');
                                }
                                if ($qty > 0) {
                                    $name =  array(
                                      'Estado' => $occion,
                                      'Motivo' => $motivo,
                                      'Precio' => $monto,
                                      'Cantidad' =>$qty ,
                                      'Detalle_Factura_idDetalle_Factura' =>$id,
                                      'Detalle_Compra_idDetalle_Compra' =>null,
                                      'Devoluciones_idDevoluciones' =>$idDevoluciones,
                                      'Producto_idProducto' =>$idproducto
                                    );
                                    $this->db->insert('Detalle_Devolucion', $name);
                                    $this->db->set('Devolucion', 'Devolucion+'.$qty, FALSE);
                                    $this->db->where('idDetalle_Factura', $id);
                                    $this->db->update('Detalle_Factura');
                                }

                        $i++;
                        }
                    break;
                case '2': //Devolver y Cobrar Efectivo
                    $occion = 'Mercaderia Recibido y Pagado en Efectivo';
                     $object = array(
                    'Fecha'                           => date("d-m-Y"),
                    'Monto_Total'                     => $cart_total,
                    'Venta_Compra'                    => 1,
                    'Factura_Venta_idFactura_Venta'   => $_id,
                    'Factura_Compra_idFactura_Compra' =>null,
                    );
                    $this->db->insert('Devoluciones', $object);
                    $idDevoluciones =  $this->db->insert_id();
                      $data                           = array(
                      'Fecha'                         => date("Y-m-d"),
                      'Hora'                          => strftime( "%H:%M", time() ),
                      'Caja_idCaja'                   => $this->session->userdata('idCaja'),
                      'Devoluciones_idDevoluciones'  => $idDevoluciones,
                      );
                      $this->db->insert('Acientos', $data);
                      $idAcientos  = $this->db->insert_id();
                      $this->add_aciento_plan( $idAcientos,'485',$cart_total,'(Ac +)');
                      $this->add_aciento_debe( $idAcientos,'2',$cart_total,'(Ac -)',$motivo );
                      $this->add_pagos($cart_total,$occion,$motivo,$idDevoluciones);

                    $i = 1;
                    foreach ($this->cart->contents() as $items) {
                                foreach ($this->cart->product_option2($items['rowid']) as $option_name => $idproducto) {
                                }
                            $id = $items['id'];
                            $qty = $items['qty'];
                            $monto = $items['price'];
                            if ($mov == 3) {
                                $this->db->set('Cantidad_A', 'Cantidad_A+'.$items['qty'], FALSE);
                                $this->db->where('idProducto',  $idproducto);
                                $this->db->update('Producto');
                            }
                                if ($qty > 0) {
                                    $name =  array(
                                      'Estado' => $occion,
                                      'Motivo' => $motivo,
                                      'Precio' => $monto,
                                      'Cantidad' =>$qty ,
                                      'Detalle_Factura_idDetalle_Factura' =>$id,
                                      'Detalle_Compra_idDetalle_Compra' =>null,
                                      'Devoluciones_idDevoluciones' =>$idDevoluciones,
                                      'Producto_idProducto' =>$idproducto
                                    );
                                    $this->db->insert('Detalle_Devolucion', $name);
                                    $this->db->set('Devolucion', 'Devolucion+'.$qty, FALSE);
                                    $this->db->where('idDetalle_Factura', $id);
                                    $this->db->update('Detalle_Factura');
                            }

                    $i++;
                    }
                    break;
                case '3': //Devolver y Cobrar Agregar a Cuenta
                    $occion = 'Mercaderia Devuelto y Agregado monto a Cuenta Favor';
                    $object = array(
                    'Fecha'                           => date("d-m-Y"),
                    'Monto_Total'                     => $cart_total,
                    'Venta_Compra'                    => 1,
                    'Factura_Venta_idFactura_Venta'   => $_id,
                    'Factura_Compra_idFactura_Compra' =>null,
                    );
                    $this->db->insert('Devoluciones', $object);
                    $idDevoluciones =  $this->db->insert_id();
                      $data                           = array(
                      'Fecha'                         => date("Y-m-d"),
                      'Hora'                          => strftime( "%H:%M", time() ),
                      'Caja_idCaja'                   => $this->session->userdata('idCaja'),
                      'Devoluciones_idDevoluciones'  => $idDevoluciones,
                      );
                      $this->db->insert('Acientos', $data);
                      $idAcientos  = $this->db->insert_id();
                      $this->add_aciento_plan( $idAcientos,'485',$cart_total,'(Ac +)');
                      $this->add_aciento_debe( $idAcientos,'482',$cart_total,'(Ac +)',$motivo );
                      $this->Cuenta_Fabor($_id,$cart_total,$Cliente,$idDevoluciones);
                    $i = 1;
                    foreach ($this->cart->contents() as $items) {
                                  foreach ($this->cart->product_option2($items['rowid']) as $option_name => $idproducto) {
                                }
                            $id = $items['id'];
                            $qty = $items['qty'];
                            $monto = $items['price'];
                                if ($mov == 3) {
                                $this->db->set('Cantidad_A', 'Cantidad_A+'.$items['qty'], FALSE);
                                $this->db->where('idProducto',  $idproducto);
                                $this->db->update('Producto');
                            }
                                if ($qty > 0) {
                                    $name =  array(
                                      'Estado' => $occion,
                                      'Motivo' => $motivo,
                                      'Precio' => $monto,
                                      'Cantidad' =>$qty ,
                                      'Detalle_Factura_idDetalle_Factura' =>$id,
                                      'Detalle_Compra_idDetalle_Compra' =>null,
                                      'Devoluciones_idDevoluciones' =>$idDevoluciones,
                                      'Producto_idProducto' =>$idproducto
                                    );
                                    $this->db->insert('Detalle_Devolucion', $name);
                                    $this->db->set('Devolucion', 'Devolucion+'.$qty, FALSE);
                                    $this->db->where('idDetalle_Factura', $id);
                                    $this->db->update('Detalle_Factura');
                                }

                    $i++;
                    }
                    break;
            }
        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
               return $this->db->trans_commit();
        }

    }

    public function Cuenta_Fabor($id,$agremicuenta,$idCliente,$idDevoluciones)
    {
        $this->db->trans_begin();
                $data                                                    = array(
                'Estado'                                                 => 1,
                'Fecha'                                                  => date("Y-m-d"),
                'Monto'                                                  => $agremicuenta,
                'Cliente_Empresa'                                        => 1,
                'Cliente_idCliente'                                      => $idCliente,
                'Proveedor_idProveedor'                                  => null,
                'idCuenta_Corriente_Empresa'                             => null,
                'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente'    => null,
                'Devoluciones_idDevoluciones'                            =>$idDevoluciones
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

      public function add_pagos($efectivo,$occion,$pago,$idDevoluciones)
  {
     $this->db->trans_begin();
             $data                                                 = array(
             'Descripcion'                                         => $occion.' Producto'.$pago ,
             'Monto'                                               => $efectivo,
             'Fecha'                                               => date("Y-m-d"),
             'Hora'                                                => strftime( "%H:%M", time() ),
             'Caja_idCaja'                                         => $this->ultimaCaja(),
             'Empleado_idEmpleado'                                 => NULL,
             'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
             'Devoluciones_idDevoluciones'                         => $idDevoluciones
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

    public function ultimaCaja()
    {
            $this->db->select_max('idCaja');
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
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

/* End of file Vdevolver_Model.php */
/* Location: ./application/models/Vdevolver_Model.php */