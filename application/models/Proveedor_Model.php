<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Proveedor_Model extends CI_Model {
	var $column = array(
		'Vendedor',
		'Razon_Social',
		'Correo',
		'Ruc','Telefono');
	var $order = array('idProveedor' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}
	/**
	 * 
	 */
    const PROVEEDOR  = 'Proveedor';
    const ID  = 'idProveedor';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->from(self::PROVEEDOR);
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

	function get_Proveedor()
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
		$this->db->from(self::PROVEEDOR);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::PROVEEDOR);
		$this->db->where(self::ID,$id);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * [insert description]
	 * @param  Array  $data [description]
	 * @return [type]       [description]
	 */
    public function insert(Array $data) {
        if ($this->db->insert(self::PROVEEDOR, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

	public function update($where, $data)
	{
		$this->db->update(self::PROVEEDOR,$data,$where);
		return $this->db->affected_rows();
	}


	public function delete_by_id($where)
	{
		$this->db->delete(self::PROVEEDOR,$where);
	}

	public function check_ruc($value)
	{
		$this->db->select('Ruc');
		$this->db->where('Ruc',$value);
		$consulta = $this->db->get(self::PROVEEDOR);
		if ($consulta->num_rows()> 0) {
			# code...
			return true;
		}
	}

}

/* End of file PROVEEDOR_model.php */
/* Location: ./application/models/PROVEEDOR_model.php */