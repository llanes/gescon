<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

    var $column = array('Usuario','Descripcion');
    var $order = array('idUsuario' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
        $this->load->database(); // cargar los helper de la database
	}
    /**
     * 
     */
    const USER  = 'Usuario';
    const ACCESO  = 'Permiso pr';
    const ID_USER  = 'idUsuario';

   private function _get_datatables()
    {
        $this->db->from(self::ACCESO);
        $this->db->join('Usuario user', 'user.Permiso_idPermiso = pr.idPermiso', 'INNER');
        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ?
            $this->db->like($item, $_POST['search']['value']) : 
            $this->db->or_like($item, $_POST['search']['value']);
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
     /**
      * [get_usuario description]
      * @return [type] [description]
      */
    public function get_usuario()
    {
        $this->_get_datatables();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * [count_filtro description]
     * @return [type] [description]
     */
    public function count_filtro()
    {
        $this->_get_datatables();
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * [count_todas_cobranzas description]
     * @return [type] [description]
     */
    public function count_todas()
    {
        $this->db->from(self::ACCESO);
        $this->db->join('Usuario user', 'user.Permiso_idPermiso = pr.idPermiso', 'INNER');
        return $this->db->count_all_results();
    }
    /**
     * [check_User chequear si no existe el usuario]
     * @param  [type] $user_id [es el nobre que se inserto]
     * @return [type]          [caracter]
     */
    public  function check_User($user_id)
    {
        # code...
        $this->db->select('Usuario');
        $this->db->where('Usuario',$user_id);
        $consulta = $this->db->get('Usuario');
        if ($consulta->num_rows()> 0) {
            # code...
            return true;
        }

    }
    /**
     * [insert description]
     * @param  Array  $data [description]
     * @return [type]       [description]
     */
    public function insert(Array $data) {
        if ($this->db->insert(self::USER, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    /**
     * [get_by_id description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function get_by_id($id)
    {
        $this->db->from(self::ACCESO);
        $this->db->join('Usuario user', 'user.Permiso_idPermiso = pr.idPermiso', 'INNER');
        $this->db->where(self::ID_USER , $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * [update description]
     * @param  Array  $data  [description]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function update($where, $data)    {
        $this->db->update(self::USER, $data, $where);
        return $this->db->affected_rows();
    }


    /**
     * [delete description]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function delete_by_id($id) {
        $where = array(self::ID_USER => $id);
        $this->db->delete(self::USER, $where);
        return $this->db->affected_rows();
    }

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */

