<?php
class Land_model extends CI_Model 
{
    public function __construct() {
        parent::__construct();
    }
    public function get_land_user($land_id) {
        $this->db->select('u.user_id,fcm_id');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_land l', 'l.user_id = u.user_id');
        $this->db->where('l.land_id', $land_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_land_units() {
        $query = $this->db->get_where('tbl_land_area_unit', ['is_active' => 'active']);
        return $query->result_array();
    }
    public function add() {
        $this->db->insert('tbl_land', [
            'land_area' => $this->security->xss_clean($this->input->post('land_area')),
            'land_area_unit' => $this->security->xss_clean($this->input->post('land_area_unit')),
            'plant_capacity_qty' => $this->security->xss_clean($this->input->post('plant_capacity_qty')),
            'land_address' => $this->security->xss_clean($this->input->post('land_address')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'user_id' => $this->security->xss_clean($this->input->post('user_id')),
            'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
            'contact_person_relation' => $this->security->xss_clean($this->input->post('contact_person_relation')),
            'contact_person_mobile' => $this->security->xss_clean($this->input->post('contact_person_mobile')),
            'land_image_url' => $this->security->xss_clean($this->input->post('organization-image'))
        ]);
        return $this->db->insert_id();
    }
    public function reapply_land($id) {
        $query = $this->db->get_where('tbl_land', ['land_id' => $id]);
        $land = $query->row_array();
        if (!empty($land)) {
            $this->db->insert('tbl_land', [
                'land_area' => $land['land_area'],
                'land_area_unit' => $land['land_area_unit'],
                'plant_capacity_qty' => $land['plant_capacity_qty'],
                'land_address' => $land['land_address'],
                'organization_id' => $land['organization_id'],
                'user_id' => $land['user_id'],
                'contact_person' => $land['contact_person'],
                'contact_person_relation' => $land['contact_person_relation'],
                'contact_person_mobile' => $land['contact_person_mobile'],
                'land_image_url' => $land['land_image_url'],
                'country_id' => $land['country_id'],
                'state_id' => $land['state_id'],
                'city_id' => $land['city_id'],
                'soil_type_id' => $land['soil_type_id']
            ]);
            $insert_id = $this->db->insert_id();

            $this->db->delete('tbl_land', ['land_id' => $land['land_id']]);
            $this->db->delete('tbl_land_verification_request', ['land_id' => $land['land_id']]);
            $this->db->delete('tbl_land_soil_testing_request', ['land_id' => $land['land_id']]);
            return $insert_id;
        }
        return 0;
    }
    public function get_lands($type) {
        $this->db->order_by('l.land_id','desc');
        $this->db->select('l.land_id,land_area,land_area_unit,plant_capacity_qty,land_address,organization_name,l.contact_person,l.contact_person_relation,u.mobile user_mobile,u.name,u.email user_email,l.contact_person_mobile,o.mobile organization_mobile,o.email organization_email,l.land_image_url,l.land_verified_by,soil_verified_by,l.user_id,l.added_date');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->join('tbl_organization o', 'l.organization_id = o.organization_id');
        if ($type === 'Not Processed') {
            $this->db->where('l.is_verified', $type);
            $this->db->or_where('l.is_verified', 'Land_Processing');
        } else if ($type === 'Soil_Processing') {
            $this->db->where('l.is_verified', $type);
            $this->db->or_where('l.is_verified', 'Approved');
        } else {
            $this->db->where('l.is_verified', $type);
        }
        $this->db->order_by('l.land_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete($id) {
        $this->db->delete('tbl_land', ['land_id' => $id]);
        $affected_rows = $this->db->affected_rows();
        $this->db->delete('tbl_land_verification_request', ['land_id' => $id]);
        return $affected_rows;
    }

    public function assign_land($land_id) {
        if($this->input->post('user_id')==0) {
            return 0;
        }
        $query = $this->db->get_where('tbl_land_verification_request', ['land_id' => $land_id]);
        $this->db->update('tbl_land', ['is_verified' => 'Land_Processing'], ['land_id' => $land_id]);
        if ($query->num_rows() === 1) {
            $this->db->update('tbl_land_verification_request', ['user_id' => $this->security->xss_clean($this->input->post('user_id'))], ['land_id' => $land_id]);
            return $this->db->affected_rows();
        }
        $this->db->insert('tbl_land_verification_request', ['land_id' => $land_id, 'user_id' => $this->security->xss_clean($this->input->post('user_id'))]);
        return $this->db->insert_id();
    }
    public function assign_land_soil_testing($land_id) {
        if($this->input->post('user_id')==0) {
            return 0;
        }
        $query = $this->db->get_where('tbl_land_soil_testing_request', ['land_id' => $land_id]);
        if ($query->num_rows() === 1) {
            $this->db->update('tbl_land_soil_testing_request', ['user_id' => $this->security->xss_clean($this->input->post('user_id'))], ['land_id' => $land_id]);
            return $this->db->affected_rows();
        }
        $this->db->insert('tbl_land_soil_testing_request', ['land_id' => $land_id, 'user_id' => $this->security->xss_clean($this->input->post('user_id'))]);
        return $this->db->insert_id();
    }

    public function get_verfication_requested_lands() {
        $this->db->order_by('land_id','desc');
        $query = $this->db->get('tbl_land_verification_request');
        return $query->result_array();
    }

    public function get_soil_verfication_requested_lands() {
        $this->db->order_by('land_id','desc');
        $query = $this->db->get('tbl_land_soil_testing_request');
        return $query->result_array();
    }

    public function get_land($id) {
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.organization_id,l.contact_person,l.contact_person_relation,l.contact_person_mobile,l.land_image_url,e.firstname,e.lastname,l.user_id,l.is_verified,l.reason,e.id employee_id');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->join('tbl_land_verification_request v', 'v.land_id = l.land_id');
        $this->db->join('tbl_employee e', 'e.id = v.user_id');
        $this->db->where('l.land_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_land_for_soil($id) {
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.organization_id,l.contact_person,l.contact_person_relation,l.contact_person_mobile,l.land_image_url,e.firstname,e.lastname,l.user_id,l.is_verified,l.soil_type_id,l.reason,l.soil_verification_comments,e.id employee_id');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->join('tbl_land_soil_testing_request v', 'v.land_id = l.land_id');
        $this->db->join('tbl_employee e', 'e.id = v.user_id');
        $this->db->where('l.land_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function verify() {
        $isVerified = 'Rejected';
        if ($this->input->post('is_verified') === 'Approved') {
            $isVerified = 'Soil_Processing';
        }
        $fields = ['land_area' => $this->security->xss_clean($this->input->post('land_area')),
            'land_area_unit' => $this->security->xss_clean($this->input->post('land_area_unit')),
            'plant_capacity_qty' => $this->security->xss_clean($this->input->post('plant_capacity_qty')),
            'land_address' => $this->security->xss_clean($this->input->post('land_address')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
            'contact_person_relation' => $this->security->xss_clean($this->input->post('contact_person_relation')),
            'contact_person_mobile' => $this->security->xss_clean($this->input->post('contact_person_mobile')),
            'is_verified' => $isVerified,
            'reason' => $this->security->xss_clean($this->input->post('reason')),
            'land_verified_by' => $this->security->xss_clean($this->input->post('employee_id'))];

        if ($this->input->post('organization-image')) {
            $fields['land_image_url'] = $this->security->xss_clean($this->input->post('organization-image'));
        }

        $this->db->update('tbl_land', $fields, ['land_id' => $this->security->xss_clean($this->input->post('land_id'))]);
        if ($this->input->post('docs')) {
            $docs = $this->input->post('docs');
            foreach ($docs as $doc) {
                $this->db->insert('tbl_docs', ['land_id' => $this->security->xss_clean($this->input->post('land_id')),
                    'doc' => $doc]);
            }
        }
        return $this->db->affected_rows();
    }

    public function verify_soil() {
        $fields = [
            'land_area' => $this->security->xss_clean($this->input->post('land_area')),
            'land_area_unit' => $this->security->xss_clean($this->input->post('land_area_unit')),
            'plant_capacity_qty' => $this->security->xss_clean($this->input->post('plant_capacity_qty')),
            'land_address' => $this->security->xss_clean($this->input->post('land_address')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
            'contact_person_relation' => $this->security->xss_clean($this->input->post('contact_person_relation')),
            'contact_person_mobile' => $this->security->xss_clean($this->input->post('contact_person_mobile')),
            'soil_verification_comments' => $this->security->xss_clean($this->input->post('comments')),
            'soil_type_id' => $this->security->xss_clean($this->input->post('soil_type')),
            'is_verified' => $this->security->xss_clean($this->input->post('is_verified')),
            'soil_verified_by' => $this->security->xss_clean($this->input->post('employee_id'))
        ];

        if ($this->input->post('organization-image')) {
            $fields['land_image_url'] = $this->security->xss_clean($this->input->post('organization-image'));
        }

        $this->db->update('tbl_land', $fields, ['land_id' => $this->security->xss_clean($this->input->post('land_id'))]);
        if ($this->input->post('docs')) {
            $docs = $this->input->post('docs');
            foreach ($docs as $doc) {
                $this->db->insert('tbl_docs', ['land_id' => $this->security->xss_clean($this->input->post('land_id')),
                    'doc' => $doc]);
            }
        }
        return $this->db->affected_rows();
    }

    public function add_tree_suggestion() {
        $query = $this->db->get_where('tbl_plant_suggestion', ['plant_id' => $this->input->post('tree'), 'season' => $this->input->post('season'), 'land_id' => $this->input->post('land_id')]);
        if ($query->num_rows() > 0) {
            return 0;
        }
        $this->db->insert('tbl_plant_suggestion', [
            'land_id' => $this->input->post('land_id'),
            'plant_id' => $this->input->post('tree'),
            'season' => $this->input->post('season')
        ]);
        return $this->db->insert_id();
    }

    public function get_tree_suggestion($land_id) {
        $this->db->select('p.plant_name,season,id,land_id');
        $this->db->from('tbl_plant_suggestion ps');
        $this->db->join('tbl_plant p', 'p.plant_id = ps.plant_id');
        $this->db->where('land_id', $land_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete_tree_suggestion($id) {
        $this->db->delete('tbl_plant_suggestion', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function get_land_detail($id) {
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,co.country_name,s.state_name,c.city_name,l.organization_id,l.contact_person,l.contact_person_relation,l.contact_person_mobile,l.land_image_url,o.organization_name,l.user_id,l.is_verified,l.reason land_verification_comments,l.soil_verification_comments,l.soil_type_id,u.name username,l.land_verified_by,l.soil_verified_by,l.tehsil,l.district');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->join('tbl_country co', 'l.country_id = co.country_id');
        $this->db->join('tbl_state s', 'l.state_id = s.state_id');
        $this->db->join('tbl_city c', 'l.city_id = c.city_id');
        $this->db->where('l.land_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_soil_detail($soil_type_id) {
        $query = $this->db->get_where('tbl_soil_type', ['soil_id' => $soil_type_id]);
        return $query->row_array();
    }

    public function get_plant_suggesstions($land_id) {
        $this->db->from('tbl_plant_suggestion ps');
        $this->db->join('tbl_plant p', 'p.plant_id = ps.plant_id');
        $this->db->where('ps.land_id', $land_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_docs($land_id) {
        $query = $this->db->get_where('tbl_docs', ['land_id' => $land_id]);
        return $query->result_array();
    }

    public function get_organization_land($organization_id) {
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.district,l.tehsil,l.organization_id,l.contact_person,l.contact_person_relation,l.contact_person_mobile,l.land_image_url,l.user_id,l.is_verified,l.reason,u.name,u.mobile,u.email');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->where('organization_id', $organization_id);
        $this->db->where('is_verified', 'Approved');
        $query = $this->db->get();
//        $query = $this->db->get_where('tbl_land', ['organization_id' => $organization_id,'is_verified'=>'Approved']);
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_land_info($id) {
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.organization_id,l.contact_person,l.contact_person_relation,l.contact_person_mobile,l.land_image_url,e.firstname,e.lastname,l.user_id,l.is_verified,l.reason,e.id employee_id');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_users u', 'u.user_id = l.user_id');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->join('tbl_land_verification_request v', 'v.land_id = l.land_id');
        $this->db->join('tbl_employee e', 'e.id = v.user_id');
        $this->db->where('l.land_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_allotted_trees() {
        $this->db->select('pd.id plantation_id,u.user_id,u.name,u.image,date_format(pd.allocation_date,"%d/%m/%Y") allocation_date,pd.plantation_date,pd.status,p.plant_name,o.nominee_name');
        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail pd', 'a.id = pd.allocation_id');
        $this->db->join('tbl_plant p', 'p.plant_id = pd.plant_id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $this->db->where('a.land_id', $this->input->post('land_id'));
        $query = $this->db->get();
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_total_allocated_plants($land_id) {
        $this->db->select("count(*) total_allocated_plants");
        $this->db->from('tbl_plant_allocation pa');
        $this->db->join('tbl_plant_allocation_detail pd','pa.id = pd.allocation_id');
        $this->db->where('land_id',$land_id);
        $query = $this->db->get();
//        echo $this->db->last_query();die;
        return $query->row_array()['total_allocated_plants'];
    }

    public function get_allotted_trees_images() {
        $sql = "SELECT m1.* FROM tbl_plant_growth m1 LEFT JOIN tbl_plant_growth m2 ON (m1.plantation_id = m2.plantation_id AND m1.id < m2.id) WHERE m2.id IS NULL";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_land_orders() {
        $this->db->from('tbl_land l');
        $this->db->join('tbl_country c', 'c.country_id = l.country_id');
        $this->db->join('tbl_state s', 's.state_id = l.state_id');
        $this->db->join('tbl_city ci', 'ci.city_id = l.city_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_land_growth_images() {
        $this->db->order_by('id', 'desc');
        $this->db->select('image,date_format(capture_date,"%d-%m-%Y") capture_date');
        $query = $this->db->get_where('tbl_plant_growth', ['plantation_id' => $this->input->post('plantation_id')]);
        return $query->result_array();
    }
    public function get_all_allocated_lands(){
        $this->db->select('land_id');
        $query = $this->db->get('tbl_plant_allocation');
        return $query->result_array();
    }
    public function get_total_lands(){
        $query = $this->db->get('tbl_land');
        return $query->num_rows();
    }
    public function is_suggestion_added($id){
        $query = $this->db->get_where('tbl_plant_suggestion',['land_id'=>$id]);
        return $query->num_rows() > 0;
    }
    public function get_user_lands($id){
        $query = $this->db->get_where('tbl_land',['user_id'=>$id]);
        return $query->result_array();
    }
}
