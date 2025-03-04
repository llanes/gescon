<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Producto_Model extends CI_Model {
	var $column = array('idProducto','Codigo','Nombre','Precio_Costo','Cantidad_A','Iva');

	var $order = array('idProducto' => 'asc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
	}
	/**
	 * 
	 */
    //const EMPLEADO  = 'Empleado';
    const PRODUCTO  = 'Producto';
    const PRODUCTO_X_PRO = 'Producto_has_Proveedor';
    const ACCESO  = 'Usuario';
    const ID  = 'idProducto';
    const PROVEEDOR  = 'Proveedor';
    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->select('idProducto,Marca,Nombre,Codigo,Precio_Costo,Img,Iva,Cantidad_A,Si_No,Precio_Venta,Cantidad_D,Unidad,Stock_Min,Medida');
		$this->db->from(self::PRODUCTO);
		$this->db->join('Marca', 'Marca.idMarca =  Producto.Marca_idMarca');
		$this->db->join('Categoria', 'Categoria.idCategoria =  Producto.Categoria_idCategoria');
		$this->db->order_by("idProducto", "asc");

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

	function get_Producto($id ='')
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);

		if ($id != '' && $id != 0) {
			$this->db->where('Cantidad_A <= Stock_Min');
			$this->db->where('idProducto', $id);
						$this->db->where('Si_No', false);
		} elseif ($id === '0') {
			$this->db->where('Cantidad_A <= Stock_Min');
						$this->db->where('Si_No', false);
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function getProducto($id='')
	{
		$this->db->from(self::PRODUCTO);
		$this->db->join('Marca', 'Marca.idMarca =  Producto.Marca_idMarca');
		$this->db->join('Categoria', 'Categoria.idCategoria =  Producto.Categoria_idCategoria');
		if ($id != '' && $id != 0) {
			$this->db->where('idProducto', $id);
		} 
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtro($id ='')
	{

		$this->_get_datatables_query();
            $this->db->where('Si_No', false);
		if ($id != '' && $id != 0) {
			$this->db->where('Cantidad_A <= Stock_Min');
			$this->db->where('idProducto', $id);
		} elseif ($id === '0') {
			$this->db->where('Cantidad_A <= Stock_Min');
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_todas($id ='')
	{
		if ($id == '') {
		$this->db->from(self::PRODUCTO);
        $this->db->where('Si_No', false);
		return $this->db->count_all_results();
		}
	}

	public function get_by_id($id)
	{
		$this->db->from(self::PRODUCTO);
		$this->db->join('Marca', 'Marca.idMarca =  Producto.Marca_idMarca', '');
		$this->db->join('Categoria', 'Categoria.idCategoria =  Producto.Categoria_idCategoria', '');
		$this->db->where(self::ID,$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_Marca()
	{
		$this->db->from('Marca');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_Categoria()
	{
		$this->db->from('Categoria');
		$query = $this->db->get();
		return $query->result();
	}

	public function proveedor_has($id)
	{
		$this->db->select('Razon_Social as Razon,Producto_idProducto as id,Proveedor_idProveedor as id2');
		$this->db->from(self::PROVEEDOR);
		$this->db->join(self::PRODUCTO_X_PRO, 'Producto_has_Proveedor.Proveedor_idProveedor =  Proveedor.idProveedor', '');
		$this->db->where('Producto_idProducto',$id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return  $query->result();
		}
	}

	/**
	 * [insert description]
	 * @param  Array  $data [description]
	 * @return [type]       [description]
	 */
    public function insert(Array $data) {
        if ($this->db->insert(self::PRODUCTO, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * [add_producto_x_pro description]
     * @param Array $_data [description]
     */
    public function add_producto_x_pro(Array $_data)
    {
        if ($this->db->insert(self::PRODUCTO_X_PRO, $_data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
 	
   	/**
   	 * [update description]
   	 * @param  [type] $where [description]
   	 * @param  [type] $data  [description]
   	 * @return [type]        [description]
   	 */
   	public function update($where, $data)
	{
		
		$this->db->update('Producto', $data, $where);
		return $this->db->affected_rows();
	}
	
	/**
	 * [delete_by_id description]
	 * @param  [type] $idProducto [description]
	 * @return [type]             [description]
	 */
	public function delete_by_id($idProducto)
	{
		$this->db->where('Producto_idProducto', $idProducto);
		$this->db->delete(self::PRODUCTO_X_PRO);
		$this->db->where(self::ID, $idProducto);
		$this->db->delete(self::PRODUCTO);
	}

	/**
	 * [delete_by_id_has description]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function delete_by_id_has($where)
	{
		$this->db->delete(self::PRODUCTO_X_PRO,$where);
	}

	/**
	 * [check_codigo description]
	 * @param  [type] $codigo_id [description]
	 * @return [type]            [description]
	 */
	public function check_codigo($codigo_id)
	{
		$this->db->select('Codigo', FALSE);
		$this->db->where('Codigo', $codigo_id);
		$Var = $this->db->get(self::PRODUCTO);
		if ($Var->num_rows() > 0) {
				return TRUE;
		} else {
			return FALSE;
		}

	}

	/**
	 * [get_alert description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function get_alert($value='')
	{
		$this->db->select('idProducto as id,Nombre as nom,Cantidad_A as can', FALSE);
		$this->db->where('Cantidad_A <= Stock_Min');
		$Var = $this->db->get(self::PRODUCTO);
    	return $Var->result();
	}
}

/* End of file Cliente_model.php */
/* Location: ./application/models/Empleado_model.php */