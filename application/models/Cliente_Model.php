<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cliente_Model extends CI_Model {
	var $column = array(
		'Nombres',
		'Telefono',
		'Apellidos',
		'Direccion',
		'Limite_max_Credito',
		'Correo');
	var $order = array('idCliente' => 'desc');

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
    const CLIENTE  = 'Cliente';
    const ACCESO  = 'Usuario';
    const ID  = 'idCliente';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->from(self::CLIENTE);
		$this->db->where('idCliente !=1');
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

	function get_Cliente()
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
		$this->db->from(self::CLIENTE);
		$this->db->where('idCliente !=1');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::CLIENTE);
		//$this->db->join('Usuario', 'Usuario.Empleado_idEmpleado =  Empleado.idEmpleado', 'left');
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
        if ($this->db->insert(self::CLIENTE, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
   
   	public function update($where, $data)
	{
		$this->db->update(self::CLIENTE, $data, $where);
		return $this->db->affected_rows();
	}
	

	public function delete_by_id($idCliente)
	{
		$this->db->where(self::ID, $idCliente);
		$this->db->delete(self::CLIENTE);
	}


	/**
	 * [check_User description]
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	 public	function check_ruc($ruc_id)
	{
		# code...
		$this->db->select('Ruc');
		$this->db->where('Ruc',$ruc_id);
		$consulta = $this->db->get(self::CLIENTE);
		if ($consulta->num_rows()> 0) {
			# code...
			return true;
		}

	}

}

/* End of file Cliente_model.php */
/* Location: ./application/models/Empleado_model.php */