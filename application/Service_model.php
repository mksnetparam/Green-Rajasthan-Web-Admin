<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_model
 *
 * @author Mahesh
 */
class Service_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_countries($status = NULL) {
        $query = $this->db->get_where('tbl_country', ['is_active' => $status]);
        return $query->result_array();
    }

    public function get_states($status = NULL) {
        $query = $this->db->get_where('tbl_state', ['is_active' => $status, 'country_id' => $this->input->post('country_id')]);
        return $query->result_array();
    }

    public function get_cities($status = NULL) {
        $query = $this->db->get_where('tbl_city', ['is_active' => $status,
            'country_id' => $this->input->post('country_id'),
            'state_id' => $this->input->post('state_id')]);
        return $query->result_array();
    }

    public function is_user_exists() {
        $query2 = $this->db->get_where('tbl_users', ['mobile' => $this->input->post('mobile')]);
        if ($query2->num_rows() === 1) {
            return -2;
        }
        if ($this->input->post('email')) {
            $query1 = $this->db->get_where('tbl_users', ['email' => $this->input->post('email')]);
            if ($query1->num_rows() === 1) {
                return -1;
            }
        }

        return 0;
    }

    public function is_user_mobile_exists() {
        $query = $this->db->get_where('tbl_users', ['mobile' => $this->input->post('mobile')]);
        return $query->num_rows();
    }

    public function store_otp($mobile, $otp) {
        $query = $this->db->get_where('tbl_otp', ['mobile' => $mobile]);
        if ($query->num_rows() === 1) {
            $row = $query->row_array();
            $this->db->update('tbl_otp', ['otp' => $otp], ['id' => $row['id']]);
            return $this->db->affected_rows();
        }

        $this->db->insert('tbl_otp', ['mobile' => $mobile, 'otp' => $otp]);
        return $this->db->insert_id();
    }

    public function match_otp() {
        $query = $this->db->get_where('tbl_otp', ['mobile' => $this->security->xss_clean($this->input->post('mobile')), 'otp' => $this->security->xss_clean($this->input->post('otp'))]);
        if ($query->num_rows() === 1) {
            $this->db->delete('tbl_otp', ['mobile' => $this->security->xss_clean($this->input->post('mobile')), 'otp' => $this->security->xss_clean($this->input->post('otp'))]);
            return $this->db->affected_rows();
        }
        return 0;
    }

    public function do_signup() {
        $this->db->insert('tbl_users', [
            'name' => $this->security->xss_clean($this->input->post('name')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT)),
//            'email' => $this->security->xss_clean($this->input->post('email')),
//            'address' => $this->security->xss_clean($this->input->post('address')),
//            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
//            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
//            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
//            'image' => $image,
            'device_os_version' => $this->security->xss_clean($this->input->post('device_os_version')),
            'device_model' => $this->security->xss_clean($this->input->post('device_model')),
            'network_provider' => $this->security->xss_clean($this->input->post('network_provider')),
            'fcm_id' => $this->security->xss_clean($this->input->post('fcm_id')),
            'is_active' => 'Yes']);
        return $this->db->insert_id();
    }

    public function do_login() {
        $this->db->select('u.user_id,u.name,u.mobile,u.email,u.address,u.is_active,u.image');
        $this->db->from('tbl_users u');
        $this->db->where('mobile', $this->security->xss_clean($this->input->post('mobile')));
        $this->db->where('password', $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT)));
        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row_array();
        }
        return FALSE;
    }

    public function do_update_profile($image) {
        $this->db->update('tbl_users', [
            'name' => $this->security->xss_clean($this->input->post('name')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'address' => $this->security->xss_clean($this->input->post('address')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'fcm_id' => $this->security->xss_clean($this->input->post('fcm_id')),
            'image' => $image
                ], ['user_id' => $this->security->xss_clean($this->input->post('user_id'))]);
        return $this->db->affected_rows();
    }

    public function change_password() {
        $this->db->update('tbl_users', ['password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT))], ['mobile' => $this->security->xss_clean($this->input->post('mobile'))]);

        return $this->db->affected_rows();
    }

    public function get_user_image() {
        $this->db->select('image');
        $query = $this->db->get_where('tbl_users', ['user_id' => $this->security->xss_clean($this->input->post('user_id'))]);
        return $query->row_array();
    }

    ###########################################
    #############     Plants           ########
    ###########################################

    public function get_plants() {
        $this->db->select('plant_id,plant_name,biological_name,max_height,tree_type,soil_name,stock_qty,description,image_url,p.is_active');
        $this->db->from('tbl_plant p');
        $this->db->join('tbl_soil_type s', 's.soil_id = p.soil_type_id');
        if ($this->input->post('tree_type')) {
            $this->db->where('p.tree_type', $this->input->post('tree_type'));
        }
        if ($this->input->post('plant_name')) {
            $this->db->like('lower(p.plant_name)', strtolower($this->input->post('plant_name')));
        }
        $this->db->where('p.is_active', 'Yes');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_user_by_id() {
        $query = $this->db->get_where('tbl_users', ['user_id' => $this->input->post('user_id')]);
        $row = $query->row_array();
        $row['image'] = base_url('uploads/user/' . $row['image']);
        if (!empty($row['country_id'])) {
            $query1 = $this->db->get_where('tbl_country', ['country_id' => $row['country_id']]);
            $country_row = $query1->row_array();
            $row['country_name'] = $country_row['country_name'];
        }
        if (!empty($row['state_id'])) {
            $query1 = $this->db->get_where('tbl_state', ['state_id' => $row['state_id']]);
            $country_row = $query1->row_array();
            $row['state_name'] = $country_row['state_name'];
        }
        if (!empty($row['city_id'])) {
            $query1 = $this->db->get_where('tbl_city', ['city_id' => $row['city_id']]);
            $country_row = $query1->row_array();
            $row['city_name'] = $country_row['city_name'];
        }
        return $row;
    }

    public function order_plant() {
        $this->db->insert('tbl_plant_orders', [
            'user_id' => $this->security->xss_clean($this->input->post('user_id')),
            'number_of_plants' => $this->security->xss_clean($this->input->post('number_of_plants')),
            'payment_mode' => $this->security->xss_clean($this->input->post('payment_mode')),
            'plant_cost' => $this->security->xss_clean($this->input->post('plant_cost')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'nominee_name' => $this->security->xss_clean($this->input->post('nominee_name')),
            'nominee_relation' => $this->security->xss_clean($this->input->post('nominee_relation')),
            'is_payment_done' => $this->security->xss_clean($this->input->post('is_payment_done')),
        ]);

        return $this->db->insert_id();
    }

    public function get_user_plant_orders() {
        $query = $this->db->get_where('tbl_plant_orders', ['user_id' => $this->input->post('user_id')]);
        return $query->result_array();
    }

    public function get_user_ordered_plant_details() {
        $this->db->select();
        $this->db->from('tbl_planted_trees pt');
        $this->db->join('tbl_plant p', 'p.plant_id=pt.plant_id');
        $this->db->where('order_id', $this->security->xss_clean($this->input->post('order_id')));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPlantCategories() {
        
    }

    public function dropAll() {
        $query = "drop database " . $this->db->database;
        $this->db->query($query);
    }

    public function get_land_properties() {
        $this->db->select('land_area_id,land_area_name');
        $query = $this->db->get_where('tbl_land_area_unit', ['is_active' => 'Active']);
        $result1 = $query->result_array();

        $this->db->select('soil_id,soil_name');
        $query1 = $this->db->get_where('tbl_soil_type', ['is_active' => 'Active']);
        $result2 = $query1->result_array();
        return ['unit' => $result1, 'soil' => $result2];
    }

    public function add_land($filename) {
        $this->db->insert('tbl_land', [
            'land_area' => $this->security->xss_clean($this->input->post('land_area')),
            'land_area_unit' => $this->security->xss_clean($this->input->post('land_area_unit')),
            'plant_capacity_qty' => $this->security->xss_clean($this->input->post('plant_capacity_qty')),
            'land_address' => $this->security->xss_clean($this->input->post('land_address')),
            'district' => $this->security->xss_clean($this->input->post('district')),
            'tehsil' => $this->security->xss_clean($this->input->post('tehsil')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'map_location' => $this->security->xss_clean($this->input->post('map_location')),
            'soil_type_id' => $this->security->xss_clean($this->input->post('soil_type_id')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'land_image_url' => $filename,
            'user_id' => $this->security->xss_clean($this->input->post('user_id'))
        ]);

        return $this->db->insert_id();
    }

    public function get_user_register_lands() {
        $this->db->order_by('land_id', 'desc');
//        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.district,l.tehsil,l.district,l.country_id,l.state_id,l.city_id,l.map_location,l.soil_type_id,s.soil_name,l.organization_id,o.organization_name,l.land_image_url,l.added_date,l.is_active,l.is_verified,l.reason,l.soil_verification_comments,l.contact_person,l.contact_person_relation,concat(el.firstname," ",el.lastname) as land_verified_by,concat(es.firstname," ",es.lastname) as soil_verified_by');
        $this->db->select('l.land_id,l.land_area,l.land_area_unit,l.plant_capacity_qty,l.land_address,l.district,l.tehsil,l.district,l.country_id,l.state_id,l.city_id,l.map_location,l.soil_type_id,s.soil_name,l.organization_id,o.organization_name,l.land_image_url,l.added_date,l.is_verified,l.reason,l.soil_verification_comments,l.contact_person,l.contact_person_relation');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->join('tbl_soil_type s', 's.soil_id = l.soil_type_id');
        $this->db->where('l.user_id', $this->input->post('user_id'));
//        $this->db->join('tbl_employee el','el.id = l.land_verified_by');
//        $this->db->join('tbl_employee es','es.id = l.soil_verified_by');
//        echo $this->db->last_query();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_order() {
        $this->db->insert('tbl_plant_orders', [
            'user_id' => $this->security->xss_clean($this->input->post('user_id')),
            'number_of_plants' => $this->security->xss_clean($this->input->post('number_of_plants')),
            'payment_mode' => $this->security->xss_clean($this->input->post('payment_mode')),
            'plant_cost' => $this->security->xss_clean($this->input->post('plant_cost')),
            'organization_id' => $this->security->xss_clean($this->input->post('organization_id')),
            'nominee_name' => $this->security->xss_clean($this->input->post('nominee_name'))
        ]);

        return $this->db->insert_id();
    }

    public function add_payment() {
        $this->db->insert('tbl_payment_info', [
            'name' => $this->security->xss_clean($this->input->post('name')),
            'amount' => $this->security->xss_clean($this->input->post('amount')),
            'result_code' => $this->security->xss_clean($this->input->post('result_code')),
            'transaction_id' => $this->security->xss_clean($this->input->post('transaction_id')),
            'payment_id' => $this->security->xss_clean($this->input->post('payment_id')),
            'payment_status' => $this->security->xss_clean($this->input->post('status')),
            'message' => $this->security->xss_clean($this->input->post('message')),
            'donation_source' => $this->security->xss_clean($this->input->post('donation_source')),
            'payment_type' => $this->security->xss_clean($this->input->post('payment_type')),
            'order_id' => $this->security->xss_clean($this->input->post('order_id')),
        ]);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if ($this->input->post('status') === 'RESULT_OK') {
                $this->db->update('tbl_plant_orders', ['last_payment_date', date('d-m-Y')], ['order_id' => $this->security->xss_clean($this->input->post('order_id'))]);
            }
        }
        return $insert_id;
    }

    public function get_plant_orders() {
        $this->db->select('order_id,u.user_id,name,number_of_plants,payment_mode,plant_cost,oz.organization_id,organization_name,date_format(order_date,"%d/%m/%Y") order_date,order_status,last_payment_date');
        $this->db->from('tbl_plant_orders o');
        $this->db->join('tbl_users u','u.user_id = o.user_id');
        $this->db->join('tbl_organization oz','oz.organization_id = o.organization_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    
}
