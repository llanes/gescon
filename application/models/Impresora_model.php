<?php
// application/models/Impresora_model.php

class Impresora_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtener_impresoras() {
        return $this->db->get('impresoras')->result();
    }

    public function guardar_impresora($data) {
        $this->db->insert('impresoras', $data);
    }

    public function obtener_impresora_por_id($id) {
        return $this->db->get_where('impresoras', array('id' => $id))->row();
    }

    public function actualizar_impresora($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('impresoras', $data);
    }

    public function eliminar_impresora($id) {
        $this->db->where('id', $id);
        $this->db->delete('impresoras');
        
        return $this->db->affected_rows() > 0;
    }
}
