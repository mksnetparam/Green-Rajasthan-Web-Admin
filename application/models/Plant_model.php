<?php
class Plant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add() {
        $this->db->insert('tbl_plant', [
            'plant_name' => $this->security->xss_clean($this->input->post('plant_name')),
            'biological_name' => $this->security->xss_clean($this->input->post('biological_name')),
            'max_height' => $this->security->xss_clean($this->input->post('max_height')),
            'tree_type' => $this->security->xss_clean($this->input->post('tree_type')),
            'soil_type_id' => $this->security->xss_clean($this->input->post('soil_type')),
            'stock_qty' => $this->security->xss_clean($this->input->post('in_stock')),
            'description' => $this->security->xss_clean($this->input->post('plant_description')),
            'is_active' => $this->security->xss_clean($this->input->post('status')),
            'image_url' => $this->security->xss_clean($this->input->post('organization-image'))
        ]);
        return $this->db->insert_id();
    }

    public function get_plants($status = NULL) {
        $this->db->select('plant_name,biological_name,max_height,tree_type,s.soil_name,p.is_active,description,stock_qty,plant_id,image_url');
        $this->db->from('tbl_plant p');
        $this->db->join('tbl_soil_type s', 'p.soil_type_id = s.soil_id');
        if (isset($status)) {
            $this->db->where('p.is_active', $status);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete($id) {
        $this->db->delete('tbl_plant', ['plant_id' => $id]);
        return $this->db->affected_rows();
    }

    public function change_status($id) {
        $this->db->update('tbl_plant', ['is_active' => $this->input->post('status')], ['plant_id' => $id]);
        return $this->db->affected_rows();
    }

    public function get_plant($id) {
        $query = $this->db->get_where('tbl_plant', ['plant_id' => $id]);
        return $query->row_array();
    }

    public function update() {
        $fields = [
            'plant_name' => $this->input->post('plant_name'),
            'biological_name' => $this->input->post('biological_name'),
            'max_height' => $this->input->post('max_height'),
            'tree_type' => $this->input->post('tree_type'),
            'soil_type_id' => $this->input->post('soil_type'),
            'stock_qty' => $this->input->post('in_stock'),
            'description' => $this->input->post('plant_description'),
            'is_active' => $this->input->post('status')
        ];

        if($this->input->post('organization-image')) {
            $fields['image_url'] = $this->security->xss_clean($this->input->post('organization-image'));
        }

        $this->db->update('tbl_plant',$fields, ['plant_id' => $this->input->post('plant_id')]);
        return $this->db->affected_rows();
    }    
}
