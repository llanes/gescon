<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ordenventa_Model extends CI_Model {
    var $column = array(
        'idOrden',
        'Nombres',
        'Telefono',
        'Entrega',
        'Estado',
        'Monto_Estimado');

    var $order = array('idOrden' => 'desc');
    public function __construct()
    {
        parent::__construct();

    }

     const PROVEEDOR   = 'Cliente';
     const PRO_HAS_PRO = 'Producto_has_Proveedor has';
     const PRODUCTO    = 'Producto pto';
     const ORDEN       = 'Orden';
     const O_HAS_P     = 'Orden_has_Produccion';
     const ID = 'idOrden';
  
    /**
     * [agregar_item description]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function agregar_item($where = NULL,$val='') {
    $this->db->select('*');
    $this->db->from(self::PRODUCTO);
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
                $precio = intval(preg_replace('/[^0-9]+/', '', $row->Precio_Venta), 10);
                $opciones =  array(
                    'iva'   => $row->Iva,
                    'descuento' => 0,
                    'poriginal' => $precio,
                    'predesc'   => 0,
                    't_f'       => 0
                );
            $data = array(
                        'id'      =>$row->idProducto,
                        'qty'     => '1',
                        'price'   => $precio, 
                        'name'    =>$row->Nombre, 
                        'descuento'    => $precio, 
                        'options' => $opciones
                    );
            $this->cart->insert($data);


    }
    return $query->num_rows(); 
    } 
    

    /**
     * [insert description]
     * @param  Array  $data [description]
     * @return [type]       [description]
     */
    public function insert(Array $data) {
        $this->db->trans_begin();
            if ($this->db->insert(self::ORDEN, $data)) {
                $id = $this->db->insert_id();
                $i = 1;
                foreach ($this->cart->contents() as $items) {
                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                        $iva =  $option_value;
                     }
                    $_data = array(
                    'Orden_idOrden'       => $this->security->xss_clean($id),
                    'Producto_idProducto' => $this->security->xss_clean($items['id']),
                    'Cantidad'            => $this->security->xss_clean($items['qty']),
                    'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                    'Iva'                 => $this->security->xss_clean($iva ),
                    );
                    $this->db->insert(self::O_HAS_P, $_data);
                 $i++;
                }
            } else {
                return false;
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

    public function insert_remision(Array $data,$val) {
        $this->db->trans_begin();
            if ($this->db->insert(self::ORDEN, $data)) {
                $id = $this->db->insert_id();
                $this->inserdetalle($val,$id);

            } else {
                return false;
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
    ////////////////////////////////////////vista orden listado///////////////////////////////////////////////////////////
    private function _get_datatables_query()
    {
        $i = 0;
        $this->db->select('Usuario as user,idCliente,Nombres,Apellidos,Ruc,Telefono as tel,Observacion,idOrden,Entrega,Estado,Monto_Estimado,Monto_envio');
        $this->db->from(self::ORDEN);
        $this->db->join('Cliente cli', 'cli.idCliente = Orden.Cliente_idCliente', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');
        $this->db->where('Compra_Venta = 2');
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

    function get_orden_v()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_oventa($value='')
    {
        $this->db->select('Usuario as user,idCliente,Nombres,Apellidos,Ruc,Telefono as tel,Observacion,idOrden,Entrega,Estado,Monto_Estimado,Monto_envio');
        $this->db->from(self::ORDEN);
        $this->db->join('Cliente cli', 'cli.idCliente = Orden.Cliente_idCliente', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');
        $this->db->where('Compra_Venta = 2');
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas()
    {
        $this->db->where('Compra_Venta = 2');
        $this->db->from(self::ORDEN);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {

        $this->db->select('Cliente_idCliente as id,Observacion as obser,Entrega as entre,Compra_Venta as esta,Monto_envio as invi');
        $this->db->where(self::ID,$id);
        $query = $this->db->get(self::ORDEN);
        if ($query->num_rows() > 0) {
            $this->db->select('idProducto,Precio_Venta,Nombre,Cantidad,Precio,Producto.Iva as IVAA');
            $this->db->from(self::O_HAS_P);
            $this->db->join('Producto', 'Producto.idProducto = Orden_has_Produccion.Producto_idProducto', 'left');
            $this->db->where('Orden_idOrden', $id);
            $query2 = $this->db->get();
            foreach ($query2->result() as $row)
                {
                    $opciones =  array('Importe' => $row->IVAA);
                    $_data = array(
                            'id'      =>$row->idProducto,
                            'qty'     => $row->Cantidad,
                            'price'   => intval(preg_replace('/[^0-9]+/', '',$row->Precio_Venta), 10),
                            'name'    =>$row->Nombre, 'descuento'    =>'',
                            'options' => $opciones
                        );
                    $this->cart->insert($_data); 
                }
           return $query->row();
        }

    }


    /**
     * [update description]
     * @param  Array  $data  [description]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function update($where,$data,$id) {
        $this->db->trans_begin();
            $this->db->update(self::ORDEN,$data,$where);
            $this->db->where('Orden_idOrden', $id);
            $this->db->delete(self::O_HAS_P);
                $i = 1;
                foreach ($this->cart->contents() as $items) {
                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                        $iva =  $option_value;
                     }
                    $_data = array(
                    'Orden_idOrden'       => $this->security->xss_clean($id),
                    'Producto_idProducto' => $this->security->xss_clean($items['id']),
                    'Cantidad'            => $this->security->xss_clean($items['qty']),
                    'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                    'Iva'                 => $this->security->xss_clean($iva ),
                    );
                    $this->db->insert(self::O_HAS_P, $_data);
                 $i++;
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

    public function update2($where,$data,$val,$id,$controval) {
        $this->db->trans_begin();

            $this->db->update(self::ORDEN,$data,$where);
            $this->db->where('Orden_idOrden', $id);
            $this->db->delete(self::O_HAS_P);

            $this->resetproduct($id,$controval);

            $this->inserdetalle($val,$id);


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

    public function ver_detalles($where = NULL,$id) {
            $this->cart->destroy();
            $this->db->select('Codigo as id,Cantidad,Precio,Orden_has_Produccion.Iva as iva,Nombre,Img');
            $this->db->from(self::O_HAS_P);
            $this->db->join('Producto', 'Producto.idProducto = Orden_has_Produccion.Producto_idProducto', 'left');
            if ($where !== NULL) {
                if (is_array($where)) {
                    foreach ($where as $field=> $value) {
                        $this->db->where($field, $value);
                    }
                } else {
                    $this->db->where($where);
                }
            }
            $query = $this->db->get();
                        foreach ($query->result() as $row)
                {
                    $opciones =  array('Importe' => $row->iva);
                    $_data = array(
                            'id'      =>$row->id,
                            'qty'     => $row->Cantidad,
                            'price'   => intval(preg_replace('/[^0-9]+/', '',$row->Precio), 10),
                            'name'    =>$row->Nombre, 'descuento'    =>'',
                            'options' => $opciones
                        );
                    $this->cart->insert($_data); 
                }
           return $query->row();

        }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where,$id) {
        $this->db->trans_begin();

            $this->db->where('Orden_idOrden', $id);
            $this->db->delete(self::O_HAS_P);
            $this->db->delete(self::ORDEN, $where);
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

    public function delete_item($where,$id,$id2)
    {
        $this->db->trans_begin();

            $this->resetproduct($id,$id2);
            $this->db->where('Orden_idOrden', $id);
            $this->db->delete(self::O_HAS_P);
            $this->db->delete(self::ORDEN, $where);
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

    public function getcliente()
    {
        $this->db->where('idCliente != 1');
        $query = $this->db->get('Cliente');
        $query->result();
    }



    public function list_productos($where = NULL,$res= null) {
        $this->db->select('idProducto,Marca,Nombre,Codigo,Precio_Venta,Unidad,Medida,Img,Iva,Cantidad_A');
        $this->db->from('Producto');
        $this->db->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'left');
        if (!is_null($res)) {

            $this->db->where($res);
        }
        $this->db->where($where);
        $query = $this->db->get();
         if ($query->num_rows() > 0) {

            $options='<option value=""></option>';
            foreach ($query->result() as $key => $value) {
                $options.='<option 
                value='.$value->idProducto.','.'0'.'>
                '.$value->Nombre.' ['.$value->Marca.']</option>';
            }
         return $options; 
         }

    }

    public function list_produccion($res='')
    {
        $this->db->select('idDetale_Produccion,idProducto,Marca,Nombre,Codigo,Precio_Venta,Unidad,Medida,Img,Iva,Cantidad_A,Estado_d,Fecha_Produccion');
        $this->db->from('Producto');
        $this->db->join('Detale_Produccion', 'Producto.idProducto = Detale_Produccion.Producto_idProducto', 'inner');
        $this->db->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'inner');
        $this->db->where($res);
        $query = $this->db->get();

           if ($query->num_rows() > 0) {
            
            $options='<option value=""></option>';
            foreach ($query->result() as $key => $value) {
                $options.='<option 
                value='.$value->idProducto.','.$value->idDetale_Produccion.'>
                '.$value->Nombre.' ['.$value->Marca.']   Produciendo   '.$value->Fecha_Produccion.'</option>';
            }
         return $options; 
           }
    }

    private function _get_datatables_()
    {
        $i = 0;
        $this->db->select('Compra_Venta,Usuario as user,idCliente,Nombres,Apellidos,Ruc,Telefono as tel,Observacion,idOrden,Entrega,Estado,Monto_Estimado,Monto_envio');
        $this->db->from(self::ORDEN);
        $this->db->join('Cliente cli', 'cli.idCliente = Orden.Cliente_idCliente', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');
        $this->db->where('Compra_Venta > 2 ');
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

    function get_remision()
    {
        $this->_get_datatables_();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getremision($value='')
    {
        $this->db->select('Compra_Venta,Usuario as user,idCliente,Nombres,Apellidos,Ruc,Telefono as tel,Observacion,idOrden,Entrega,Estado,Monto_Estimado,Monto_envio');
        $this->db->from(self::ORDEN);
        $this->db->join('Cliente cli', 'cli.idCliente = Orden.Cliente_idCliente', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');
        $this->db->where('Compra_Venta > 2 ');
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro_remision()
    {
        $this->_get_datatables_();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas_remision()
    {
        $this->db->where('Compra_Venta > 2 ');
        $this->db->from(self::ORDEN);
        return $this->db->count_all_results();
    }

    public function inserdetalle($val='',$id='')
    {
            // $this->output->enable_profiler(TRUE);
            switch ($val) {
                case '3':
                    // Entra producto
                        $i = 1;
                        foreach ($this->cart->contents() as $items) {
                                foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                                $iva =  $option_value;
                             }
                            $_data = array(
                            'Orden_idOrden'       => $this->security->xss_clean($id),
                            'Producto_idProducto' => $this->security->xss_clean($items['id']),
                            'Cantidad'            => $this->security->xss_clean($items['qty']),
                            'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                            'Iva'                 => $this->security->xss_clean($iva ),
                            );
                            $this->db->insert(self::O_HAS_P, $_data);
                            $this->db->set('Cantidad_A', 'Cantidad_A+'.$items['qty'], FALSE);
                            $this->db->where('idProducto', $items['id']);
                            $this->db->update('Producto');
                         $i++;
                        }
                    break;
                case '4':
                    // Sale Producto
                        $i = 1;
                        foreach ($this->cart->contents() as $items) {
                                foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                                $iva =  $option_value;
                             }
                            $_data = array(
                            'Orden_idOrden'       => $this->security->xss_clean($id),
                            'Producto_idProducto' => $this->security->xss_clean($items['id']),
                            'Cantidad'            => $this->security->xss_clean($items['qty']),
                            'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                            'Iva'                 => $this->security->xss_clean($iva ),
                            );
                            $this->db->insert(self::O_HAS_P, $_data);
                            $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
                            $this->db->where('idProducto', $items['id']);
                            $this->db->update('Producto');
                         $i++;
                        }
                    break;
                case '5':
                    // Devolucion de producto
                        $i = 1;
                        foreach ($this->cart->contents() as $items) {
                                foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                                $iva =  $option_value;
                             }
                            $_data = array(
                            'Orden_idOrden'       => $this->security->xss_clean($id),
                            'Producto_idProducto' => $this->security->xss_clean($items['id']),
                            'Cantidad'            => $this->security->xss_clean($items['qty']),
                            'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                            'Iva'                 => $this->security->xss_clean($iva ),
                            );
                            $this->db->insert(self::O_HAS_P, $_data);
                            $this->db->set('Cantidad_A', 'Cantidad_A+'.$items['qty'], FALSE);
                            $this->db->where('idProducto', $items['id']);
                            $this->db->update('Producto');
                         $i++;
                        }
                    break;
                case '6':

                            $i = 1;
                            foreach ($this->cart->contents() as $items) {
                                    foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                                    $iva =  $option_value;
                                 }
                                             foreach ($this->cart->product_option($items['rowid']) as $option_ => $option_v) {
                                                                $idProducto =  $option_v;
                                             }
                                $_data = array(
                                'Orden_idOrden'       => $this->security->xss_clean($id),
                                'Producto_idProducto' => $this->security->xss_clean($items['id']),
                                'Cantidad'            => $this->security->xss_clean($items['qty']),
                                'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                                'Iva'                 => $this->security->xss_clean($iva ),
                                );
                                $this->db->insert(self::O_HAS_P, $_data);
                                $this->db->set('Cantidad_A', 'Cantidad_A+'.$items['qty'], FALSE);
                                $this->db->where('idProducto', $items['id']);
                                $this->db->update('Producto');
                                $this->db->set('CantidadProduccion', 'CantidadProduccion+'.$items['qty'], FALSE);
                                $this->db->set('Estado_d', '1', FALSE);
                                $this->db->where('idDetale_Produccion',  $idProducto,false );
                                $this->db->update('Detale_Produccion');
                             $i++;
                            }
                    break;
                case '7':
                    // Entra producto en produccion
                            $i = 1;
                            foreach ($this->cart->contents() as $items) {
                                    foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                                    $iva =  $option_value;
                                 }
                                $_data = array(
                                'Orden_idOrden'       => $this->security->xss_clean($id),
                                'Producto_idProducto' => $this->security->xss_clean($items['id']),
                                'Cantidad'            => $this->security->xss_clean($items['qty']),
                                'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                                'Iva'                 => $this->security->xss_clean($iva ),
                                );
                                $this->db->insert(self::O_HAS_P, $_data);
                             $i++;
                            }
                    break;
            }
    }

    public function resetproduct($id,$id2)
    {
        $this->db->select('Producto_idProducto as idd,Cantidad');
        $this->db->where('Orden_idOrden', $id);
        $query = $this->db->get(self::O_HAS_P);

        switch ($id2) {
                case '3':
                    // Entra producto
                    foreach ($query->result() as $key => $value) {

                            $this->db->set('Cantidad_A', 'Cantidad_A-'.$value->Cantidad, FALSE);
                            $this->db->where('idProducto', $value->idd);
                            $this->db->update('Producto');                      
                        
                    }
                     break;
                case '4':
                    foreach ($query->result() as $key => $value) {

                            $this->db->set('Cantidad_A', 'Cantidad_A+'.$value->Cantidad, FALSE);
                            $this->db->where('idProducto', $value->idd);
                            $this->db->update('Producto');                      
                        
                    }
                    break;
                case '5':
                    // Sale Producto
                    foreach ($query->result() as $key => $value) {

                            $this->db->set('Cantidad_A', 'Cantidad_A+'.$value->Cantidad, FALSE);
                            $this->db->where('idProducto', $value->idd);
                            $this->db->update('Producto');                      
                        
                    }
                    break;
                case '6':
                    // Devolucion de producto
                    foreach ($query->result() as $key => $value) {

                            $this->db->set('Cantidad_A', 'Cantidad_A-'.$value->Cantidad, FALSE);
                            $this->db->set('Produccion', '1', FALSE);
                            $this->db->where('idProducto', $value->idd);
                            $this->db->update('Producto'); 
                            $this->db->set('CantidadProduccion', 'CantidadProduccion-'.$value->Cantidad, FALSE);
                            $this->db->update('Detale_Produccion');                     
                        
                    }
                    break;


            }

    }

}
/* End of file Ordenventa_model.php */
/* Location: ./application/models/Ordenventa_model.php */ ?>