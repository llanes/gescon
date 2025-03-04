<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Empresa_Model extends CI_Model {
	var $column = array(
		'Nombre',
		'Direccion',
		'Telefono',
		'Email',
		'R_U_C',
		'Timbrado',
		'Series');
	var $order = array('idEmpresa' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}
	/**
	 * 
	 */
    const EMPRESA  = 'Empresa';
    const ACCESO  = 'Permiso pr';
    const ID  = 'idEmpresa';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->from(self::EMPRESA);
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

	function get_empresa()
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
		$this->db->from(self::EMPRESA);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::EMPRESA);
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
        if ($this->db->insert(self::EMPRESA, $data)) {
            return $this->db->insert_id();
            
        } else {
            return false;
        }
    }

	public function update($where, $data)
	{
		$this->db->update(self::EMPRESA, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($idEmpresa)
	{
		$this->db->where(self::ID, $idEmpresa);
		$this->db->delete(self::EMPRESA);
	}

}

/* End of file Empresa_model.php */
/* Location: ./application/models/Empresa_model.php */