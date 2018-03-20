<?php
class Organization_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add() {
        $this->db->insert('tbl_organization', [
            'organization_enroll_id' => $this->security->xss_clean($this->input->post('organization_enroll_id')),
            'organization_name' => $this->security->xss_clean($this->input->post('organization_name')),
            'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
            'ownership' => $this->security->xss_clean($this->input->post('ownership')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'address_line1' => $this->security->xss_clean($this->input->post('address_line1')),
            'address_line2' => $this->security->xss_clean($this->input->post('address_line2')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'pincode' => $this->security->xss_clean($this->input->post('pincode')),
            'is_active' => $this->security->xss_clean($this->input->post('status')),
            'logo' => $this->input->post('organization-image')
        ]);

        return $this->db->insert_id();
    }
    
    public function update() {
        $query = $this->db->get_where('tbl_organization',['organization_id!=' => $this->input->post('organization_id'),
            'organization_name' => $this->security->xss_clean($this->input->post('organization_name'))]);
        
        if($query->num_rows() ===1) {
            return -1;
        }
        $fields = [
            'organization_enroll_id' => $this->security->xss_clean($this->input->post('organization_enroll_id')),
            'organization_name' => $this->security->xss_clean($this->input->post('organization_name')),
            'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
            'ownership' => $this->security->xss_clean($this->input->post('ownership')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'address_line1' => $this->security->xss_clean($this->input->post('address_line1')),
            'address_line2' => $this->security->xss_clean($this->input->post('address_line2')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'pincode' => $this->security->xss_clean($this->input->post('pincode')),
            'is_active' => $this->security->xss_clean($this->input->post('status'))
        ];
        if($this->input->post('organization-image')){
            $fields['logo'] = $this->input->post('organization-image');
        }
        $this->db->update('tbl_organization',$fields,['organization_id' => $this->security->xss_clean($this->input->post('organization_id'))]);

        return $this->db->affected_rows();
    }

    public function get_organizations($status=NULL) {
        $this->db->select('organization_id,organization_enroll_id,organization_name,contact_person,ownership,mobile,email,address_line1,address_line2,c.city_name,s.state_name,co.country_name,pincode,logo,o.is_active');
        $this->db->from('tbl_organization o');
        $this->db->join('tbl_country co', 'o.country_id = co.country_id');
        $this->db->join('tbl_state s', 'o.state_id = s.state_id');
        $this->db->join('tbl_city c', 'o.city_id = c.city_id');
        if(isset($status)) {
            $this->db->where('o.is_active',$status);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function get_organization($id){
        $this->db->select('organization_id,organization_enroll_id,organization_name,contact_person,ownership,email,mobile,address_line1,address_line2,co.country_id,s.state_id,c.city_id,logo,c.city_name,s.state_name,co.country_name,pincode,o.is_active');
        $this->db->from('tbl_organization o');
        $this->db->join('tbl_country co', 'o.country_id = co.country_id');
        $this->db->join('tbl_state s', 'o.state_id = s.state_id');
        $this->db->join('tbl_city c', 'o.city_id = c.city_id');
        $this->db->where('o.organization_id',$id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function delete($id) {
        $this->db->delete('tbl_organization',['organization_id' => $id]);
        return $this->db->affected_rows();
    }

    public function change_status($id) {
        $this->db->update('tbl_organization',['is_active'=>$this->input->post('status')],['organization_id' => $id]);
        return $this->db->affected_rows();
    }
    
    public function get_organization_dropdown() {
        $this->db->select('organization_id,organization_name');
        $query = $this->db->get_where('tbl_organization',['is_active' =>'Yes']);
//        echo $this->db->last_query();
        return $query->result_array();
    }
}
