<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Permiso_Model extends CI_Model {
	var $column = array(
		'Descripcion',
		'Oservacion');
	var $order = array('idPermiso' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

    const Permiso  = 'Permiso';
    const ID  = 'idPermiso';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{


		$this->db->from(self::Permiso);
		$this->db->where('(idPermiso != 1) AND (idPermiso != 3)');
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

	function get_Permiso()
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
		$this->db->from(self::Permiso);
		$this->db->where('(idPermiso != 1) AND (idPermiso != 3)');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('idPermiso,Descripcion as Nombre, Oservacion , Descripcion');
		$this->db->from(self::Permiso);
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
        if ($this->db->insert(self::Permiso, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    public function add_Permiso_has( Array $data)
    {
    	if ($this->db->insert('Permiso_has_Menu', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
   
   	public function update($where, $data)
	{
		$this->db->update(self::Permiso, $data, $where);
		return $this->db->affected_rows();
	}
	

	public function delete_by_id($idPermiso)
	{
		$this->delete_by_has($idPermiso);
		$this->db->where(self::ID, $idPermiso);
		$this->db->delete(self::Permiso);


	}

	public function delete_by_has($idPermiso='')
	{
		$this->db->where('Permiso_idPermiso', $idPermiso);
		$this->db->delete('Permiso_has_Menu');
	}

	public function check_Permiso($id)
	{
		$this->db->select('Descripcion');
		$this->db->where('Descripcion',$id);
		$consulta = $this->db->get(self::Permiso);
		if ($consulta->num_rows()> 0) {
			return true;
		}
	}

	public function permiso_has($id='')
	{
		$this->db->select('Menu_idMenu as id2,Nombre as res');
		$this->db->join('Menu', 'Menu.idMenu = Permiso_has_Menu.Menu_idMenu', 'left');
		$this->db->where('Permiso_idPermiso',$id);
		$Var = $this->db->get('Permiso_has_Menu');
		if ($Var->num_rows() > 0) {
				return  $Var->result();
		} else {
			return FALSE;
		}
	}

}

/* End of file Permiso_Model.php */
/* Location: ./application/models/Permiso_Model.php */