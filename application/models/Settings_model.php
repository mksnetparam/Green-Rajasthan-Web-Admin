<?php
class Settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    public function get_all_site_settings() {
        $query = $this->db->get('tbl_site_settings');
        return $query->result_array();
    }
}
