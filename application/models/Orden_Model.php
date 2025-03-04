<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orden_Model extends CI_Model {
    var $column = array(
        'idOrden',
        'Razon_Social',
        'Entrega',
        'Devolucion',
        'Estado',
        'Monto_Estimado');

    var $order = array('idOrden' => 'desc');
	public function __construct()
	{
		parent::__construct();
        
    $this->load->database(); // Inyección de dependencia

	}

     const PROVEEDOR   = 'Proveedor pdr';
     const PRO_HAS_PRO = 'Producto_has_Proveedor has';
     const PRODUCTO    = 'Producto pto';
     const ORDEN       = 'Orden';
     const O_HAS_P     = 'Orden_has_Produccion';
     const PRI_INDEX = 'field';
     const ID = 'idOrden';
    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Recupera únicamente los registros que coinciden con los criterios dados , o todos los registros si no se da .
     * Si no se da matriz asociativa , debe encajar nombre_campo = > patrón de valores .
     * Si cadena , el valor será utilizado para emparejar contra PRI_INDEX
     * @return  matriz de resultados
     */
    public function listaTodos($where = NULL,$where2 = NULL) {

        $this->db->select('*');
        $this->db->from(self::PRODUCTO);
        $this->db->join(self::PRO_HAS_PRO, 'has.Producto_idProducto = pto.idProducto', 'left');

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where($where);
            }
            if ($where2 !== NULL) {
              $this->db->where('Cantidad_A <= 10');
            }
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
                foreach ($query->result() as $row)
                {
                    if ($row->Precio_Costo == 0) {
                       $precio = $row->Precio_Venta;
                    }else{
                       $precio = $row->Precio_Costo;
                    }
                    $opciones =  array(
                        'iva'   => $row->Iva,
                        'descuento' => 0,
                        'poriginal' => intval(preg_replace('/[^0-9]+/', '',$precio), 10),
                        'predesc'   => 0,
                        't_f'       => 0
                    );
                    $data = array(
                            'id'      =>$row->idProducto,
                            'qty'     => '10',
                            'price'   => intval(preg_replace('/[^0-9]+/', '',$precio), 10),
                            'name'    =>$row->Nombre,
                             'descuento'    => intval(preg_replace('/[^0-9]+/', '',$precio), 10), 
                            'options' => $opciones
                        );
                    $this->cart->insert($data); 
                }
                return $query->num_rows(); 
        } else {
            return false;
        }

    } 

    /**
     * [agregar_item description]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function agregar_item($where = NULL) {
    $this->db->select('*');
    $this->db->from(self::PRODUCTO);
    if ($where !== NULL) {
            $this->db->where($where);
    }
    $query = $this->db->get();
    foreach ($query->result() as $row)
    {
                $opciones =  array(
                    'iva'   => $row->Iva,
                    'descuento' => 0,
                    'poriginal' => intval(preg_replace('/[^0-9]+/', '', $row->Precio_Costo), 10),
                    'predesc'   => 0,
                    't_f'       => 0
                );


            $data = array(
                        'id'      =>$row->idProducto,
                        'qty'     => '1',
                        'price'   => intval(preg_replace('/[^0-9]+/', '', $row->Precio_Costo), 10), 
                        'name'    =>$row->Nombre, 
                        'descuento'    => intval(preg_replace('/[^0-9]+/', '', $row->Precio_Costo), 10), 
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
                $data = array();
                foreach ($this->cart->contents() as $items) {
                    if ($this->cart->has_options($items['rowid']) == TRUE) {
                        $data = $this->cart->product_options($items['rowid']);
                    }
                    $_data = array(
                    'Orden_idOrden'       => $id,
                    'Producto_idProducto' => $items['id'],
                    'Cantidad'            => $items['qty'],
                    'Precio'              => str_replace($this->config->item('caracteres'),"",$items['price']),
                    'Iva'                 => $data['iva'],
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
    ////////////////////////////////////////vista orden listado///////////////////////////////////////////////////////////
    private function _get_datatables_query()
    {
        $i = 0;
        $this->db->select('idProveedor,Razon_Social,Telefono as tel,Usuario as user,Observacion,idOrden,Entrega,Devolucion,Estado,Monto_Estimado,');
        $this->db->from(self::ORDEN);
        $this->db->join('Proveedor pro', 'pro.idProveedor = Orden.Proveedor_idProveedor', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');
        $this->db->where('Compra_Venta = 1');
                if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
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

    function get_orden_c()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_compra($val)
    {
        $this->db->select('idProveedor,Razon_Social,Telefono as tel,Usuario as user,Observacion,idOrden,Entrega,Devolucion,Estado,Monto_Estimado,');
        $this->db->from(self::ORDEN);
        $this->db->join('Proveedor pro', 'pro.idProveedor = Orden.Proveedor_idProveedor', 'left');
        $this->db->join('Usuario user', 'user.idUsuario = Orden.Usuario_idUsuario', 'left');

        if (!empty($val)) {
        $this->db->where('idOrden', $val);
        }else{
         $this->db->where('Compra_Venta = 1');
        }

                if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtro()
    {
        $this->_get_datatables_query();
                        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_todas()
    {
        $this->db->where('Compra_Venta = 1');
                        if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('Usuario_idUsuario', $this->session->userdata('idUsuario'));
        }
        $this->db->from(self::ORDEN);
        return $this->db->count_all_results();
    }

public function get_by_id($id)
{
     $this->db->select('Proveedor_idProveedor as id,Observacion as obser,Entrega as entre,Devolucion as devol,Estado as esta');
    $this->db->where(self::ID,$id);
    $query = $this->db->get(self::ORDEN);
     if ($query->num_rows() > 0) {
        $this->db->select('idProducto,Precio_Costo,Nombre,Cantidad,Precio,Producto.Iva as IVAA');
        $this->db->from(self::O_HAS_P);
        $this->db->join('Producto', 'Producto.idProducto = Orden_has_Produccion.Producto_idProducto', 'left');
        $this->db->where('Orden_idOrden', $id);
        $query2 = $this->db->get();
        foreach ($query2->result() as $row) {
            $opciones = array(
                'iva' => $row->IVAA,
                'descuento' => 0,
                'poriginal' => intval(preg_replace('/[^0-9]+/', '', $row->Precio_Costo), 10),
                't_f' => 0
            );
             $data = array(
                'id'      => $row->idProducto,
                'qty'     => $row->Cantidad,
                'price'   => intval(preg_replace('/[^0-9]+/', '', $row->Precio_Costo), 10), 
                'name'    =>$row->Nombre, 
                'descuento'    => 0, 
                'options' => $opciones
            );
            $this->cart->insert($data); 
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
                    $opciones =  array('iva' => $row->iva);
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
}
/* End of file Orden_model.php */
/* Location: ./application/models/Orden_model.php */ ?>