<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cambio_Model extends CI_Model {
	var $column = array(
		'idMoneda',
		'Nombre',
		'Compra','Estado','Venta');

	var $order = array('idMoneda' => 'desc');

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
    const MONEDA  = 'Moneda';
    const ACCESO  = 'Usuario';
    const ID  = 'idMoneda';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->join('Cambios', self::MONEDA.'.Cambios_idCambios = Cambios.idCambios', 'INNER');
		$this->db->from(self::MONEDA);
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

	function get_Cambio()
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
		$this->db->join('Cambios', self::MONEDA.'.Cambios_idCambios = Cambios.idCambios', 'INNER');
		$this->db->from(self::MONEDA);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('idCambios as id,Nombre as Moneda,Compra as Cambio,Estado');
		$this->db->join('Cambios', self::MONEDA.'.Cambios_idCambios = Cambios.idCambios', 'INNER');
		$this->db->from(self::MONEDA);		
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
        if ($this->db->insert(self::MONEDA, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
   
   	public function update($campo,$where, $data)
	{
		
		$this->db->update($campo, $data, $where);
		return $this->db->affected_rows();
	}
	

	public function delete_by_id($idCategoria)
	{
		$this->db->where(self::ID, $idCategoria);
		$this->db->delete(self::MONEDA);
	}
	
	public function check_Cate($id)
	{
		$this->db->select('Categoria');
		$this->db->where('Categoria',$id);
		$consulta = $this->db->get(self::MONEDA);
		if ($consulta->num_rows()> 0) {
			return true;
		}
	}

	



}
