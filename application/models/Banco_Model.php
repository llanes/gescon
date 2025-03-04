<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Banco_Model extends CI_Model {
	var $column = array(
		'Nombre',
		'Numero',
		'MontoActivo',);
	var $order = array('idGestor_Bancos' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

    const Bancos  = 'Gestor_Bancos';
    const ID  = 'idGestor_Bancos';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
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

	function get_Banco()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(self::Bancos);
		return $query->result();
	}

	function count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(self::Bancos);
		return $query->num_rows();
	}

	public function count_todas()
	{
		$this->db->get(self::Bancos);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::Bancos);
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
    		        if ($this->db->insert(self::Bancos, $data)) {
			            return $this->db->insert_id();
			        } else {
			            return false;
			        }
    }
   
   	public function update($where, $data)
	{
					$this->db->update(self::Bancos, $data, $where);
					return $this->db->affected_rows();

	}
	

	public function delete_by_id($id)
	{
		$this->db->trans_begin();
				$this->db->where(self::ID, $id);
				$this->db->delete(self::Bancos);
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		}
	}

	public function check_Codigo($marca_id,$id)
	{
		if (empty($id)) {
				$this->db->select('CodigoCuenta');
				$this->db->where('CodigoCuenta',$marca_id);
				$consulta = $this->db->get(self::PlandeCuenta);
				if ($consulta->num_rows()> 0) {
					return true;
		}
		}else{
				$this->db->select('CodigoCuenta');
				$this->db->where('CodigoCuenta',$marca_id);
				$this->db->where('idPlandeCuenta !=', $id);
				$consulta = $this->db->get(self::PlandeCuenta);
				if ($consulta->num_rows()> 0) {
					return true;
				}
		}

	}


	public function detale($id='')
	{
		$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
		$this->db->where('Gestor_Bancos_idGestor_Bancos',$id);
		$query = $this->db->get('Movimientos');
	    if ($query->num_rows()> 0) {
					return $query->result();
				}
	}

}

/* End of file Banco_Model.php */
/* Location: ./application/models/Banco_Model.php */