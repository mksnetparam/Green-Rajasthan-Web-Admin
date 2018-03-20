<?php
class Soil_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function get_soil_types() {
        $query = $this->db->get_where('tbl_soil_type',['is_active' => 'Active']);
        return $query->result_array();
    }
}
