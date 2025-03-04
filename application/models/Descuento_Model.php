<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Descuento_Model extends CI_Model {
	var $column = array('Codigo','Nombre','Motivo','Cantidad');

	var $order = array('idProducto' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}
	/**
	 * 
	 */
    //const EMPLEADO  = 'Empleado';
    const PRODUCTO  = 'Producto';
    const ID  = 'idProducto';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->from(self::PRODUCTO);
		$this->db->join('Categoria', self::PRODUCTO.'.Categoria_idCategoria = Categoria.idCategoria', 'inner');
		$this->db->join('Marca', self::PRODUCTO.'.Marca_idMarca = Marca.idMarca', 'inner');
		$this->db->where('Descuento > 0');
		$this->db->group_by('Categoria_idCategoria');

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

	function get_descuento()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
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
		$this->db->where('Descuento > 0');
		$this->db->group_by('Categoria_idCategoria');
		$this->db->from(self::PRODUCTO);
		return $this->db->count_all_results();

	}

	public function insert($categoria,$marca ,$Descuento) 
	{
		$this->db->where('Categoria_idCategoria', $categoria);
		$this->db->or_where('idProducto', $marca);
	    $this->db->set('Descuento', $Descuento, FALSE);
	    $this->db->update(self::PRODUCTO);
	    return $this->db->affected_rows();
    }


        public function get_by_id($id)
    {
        $this->db->from(self::PRODUCTO);
        $this->db->where('Categoria_idCategoria', $id);
        $query = $this->db->get();
        return $query->row();
    }

    	function getdescuento()
	{
		$this->db->from(self::PRODUCTO);
		$this->db->join('Categoria', self::PRODUCTO.'.Categoria_idCategoria = Categoria.idCategoria', 'inner');
		$this->db->join('Marca', self::PRODUCTO.'.Marca_idMarca = Marca.idMarca', 'inner');
		$this->db->where('Descuento > 0');
		$query = $this->db->get();
		return $query->result();
	}

}

/* End of file Producto_null_Model.php */
/* Location: ./application/models/Producto_null_Model.php */