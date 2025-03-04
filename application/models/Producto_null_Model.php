<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Producto_null_Model extends CI_Model {
	var $column = array('Codigo','Nombre','Motivo','Cantidad');

	var $order = array('idDetalle_Devolucion' => 'desc');

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
    const DANHADO  = 'Detalle_Devolucion dd';
    const ID  = 'idDetalle_Devolucion';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query($Value)
	{
		$this->db->select('idDetalle_Devolucion,Estado,Motivo,Precio,Cantidad,Nombre,Codigo,Img');
		$this->db->from(self::DANHADO);
		$this->db->join('Producto', 'dd.Producto_idProducto = Producto.idProducto', 'inner');
		$this->db->where('Motivo', $Value);

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

	function get_Producto($id)
	{
		$this->_get_datatables_query($id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtro($id)
	{
		$this->_get_datatables_query($id);

		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_todas($id)
	{
		$this->db->where('Motivo', $id);
		$this->db->from(self::DANHADO);
		return $this->db->count_all_results();

	}
}

/* End of file Producto_null_Model.php */
/* Location: ./application/models/Producto_null_Model.php */