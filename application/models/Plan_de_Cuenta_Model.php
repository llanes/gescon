<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Plan_de_Cuenta_Model extends CI_Model {
	var $column = array(
		'Balance_General',
		'Codificacion','Nombre',
);
	var $order = array('idPlandeCuenta' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

    const PlandeCuenta  = 'PlandeCuenta pc';
    const ID  = 'idPlandeCuenta';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->join('SubPlanCuenta', 'pc.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
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

	function get_PlandeCuenta()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(self::PlandeCuenta);
		return $query->result();
	}

	function count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(self::PlandeCuenta);
		return $query->num_rows();
	}

	public function count_todas()
	{
		$this->db->join('SubPlanCuenta', 'pc.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
		$this->db->get(self::PlandeCuenta);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::PlandeCuenta);
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
    		        if ($this->db->insert('PlandeCuenta', $data)) {
			            return $this->db->insert_id();
			        } else {
			            return false;
			        }
    }
   
   	public function update($where, $data)
	{
					$this->db->update(self::PlandeCuenta, $data, $where);
					return $this->db->affected_rows();

	}
	

	public function delete_by_id($id)
	{
		$this->db->trans_begin();
				$this->db->where(self::ID, $id);
				$this->db->delete('PlandeCuenta');
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
				$this->db->select('Codificacion');
				$this->db->where('Codificacion',$marca_id);
				$consulta = $this->db->get(self::PlandeCuenta);
				if ($consulta->num_rows()> 0) {
					return true;
		}
		}else{
				$this->db->select('Codificacion');
				$this->db->where('Codificacion',$marca_id);
				$this->db->where('idPlandeCuenta !=', $id);
				$consulta = $this->db->get(self::PlandeCuenta);
				if ($consulta->num_rows()> 0) {
					return true;
				}
		}

	}

}

/* End of file Plan_de_Cuenta_Model.php */
/* Location: ./application/models/Plan_de_Cuenta_Model.php */