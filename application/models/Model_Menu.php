<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Menu extends CI_Model {
    private $permiso_id;
    private $idUsuario;
    public function __construct() {
       parent::__construct();
       $this->load->driver('cache');
       $this->permiso_id = $this->session->userdata('Permiso_idPermiso');
       $this->idUsuario = $this->session->userdata('idUsuario');

   }
public function octenerMenu($menuID) {
    // $this->output->enable_profiler(TRUE);
    
    $cacheKey = 'menu_' . $this->idUsuario;
    $menu = $this->cache->get($cacheKey);
    if ($menu === false) {
        $this->db->select('*');
        $this->db->from('Permiso_has_Menu');
        $this->db->join('Menu', 'Menu.idMenu = Permiso_has_Menu.Menu_idMenu', 'INNER');
        $this->db->where('Permiso_idPermiso', $this->permiso_id);
        
        if ($menuID > 0) {
            $this->db->where('EXISTS(SELECT Menu_idMenu FROM Permiso_has_Menu WHERE Menu_idMenu = ' . $menuID . ')');
        } 
        $query = $this->db->get();
        $menu = $query->result();
        $this->cache->save($cacheKey, $menu, 3600);
    }
    return $menu;
}

public function octener($value) {
	$cache_key = 'permiso_menu_count_' .$this->idUsuario;
	$count = $this->cache->get($cache_key);
	if ($count === false) {
		$this->db->select('COUNT(*) as count');
		$this->db->from('Permiso_has_Menu');
		$this->db->where('Permiso_idPermiso', $this->permiso_id);
		$this->db->where('Menu_idMenu', $value);
		$query = $this->db->get();
		$result = $query->row_array();
		$count = $result['count'];
		$this->cache->save($cache_key, $count, 3600);
	}
	return $count;
}

}
/* End of file Model_Menu.php */
/* Location: ./application/models/Model_Menu.php */ ?>