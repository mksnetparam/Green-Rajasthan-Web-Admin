<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_records() {
        if ($this->input->post('name')) {
            $this->db->like('lower(name)', strtolower($this->security->xss_clean($this->input->post('name'))));
        }
        if ($this->input->post('email')) {
            $this->db->like('lower(email)', strtolower($this->security->xss_clean($this->input->post('email'))));
        }
        if ($this->input->post('mobile')) {
            $this->db->like('lower(mobile)', strtolower($this->security->xss_clean($this->input->post('mobile'))));
        }
        if ($this->input->post('address')) {
            $this->db->like('lower(shipping_address1)', strtolower($this->security->xss_clean($this->input->post('address'))));
            $this->db->or_like('lower(shipping_address2)', strtolower($this->security->xss_clean($this->input->post('address'))));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->security->xss_clean($this->input->post('status')));
        }
        return $this->db->count_all_results('tbl_users');
    }

    public function add_user() {
        $this->db->insert('tbl_users', [
            'name' => $this->security->xss_clean($this->input->post('name')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT)),
            'shipping_address1' => $this->security->xss_clean($this->input->post('shipping_address1')),
            'shipping_address2' => $this->security->xss_clean($this->input->post('shipping_address2')),
            'facebook_id' => $this->security->xss_clean($this->input->post('facebook_id')),
            'google_plus_id' => $this->security->xss_clean($this->input->post('google_plus_id')),
            'android_key' => $this->security->xss_clean($this->input->post('android_key')),
            'iphone_key' => $this->security->xss_clean($this->input->post('iphone_key')),
            'device_model' => $this->security->xss_clean($this->input->post('device_model')),
            'device_os_version' => $this->security->xss_clean($this->input->post('device_os_version')),
            'network_provider' => $this->security->xss_clean($this->input->post('network_provider'))
        ]);
        return $this->db->insert_id();
    }

    public function is_auth_user() {
        date_default_timezone_set('Asia/Kolkata');
        $query = $this->db->get_where('tbl_users', ['email' => $this->security->xss_clean($this->input->post('email')),
            'password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT)), 'is_active' => 'Yes']);
        if ($query->num_rows() === 1) {
            $this->db->update('tbl_users', ['last_login_date' => date('Y-m-d H:i:s', time())], ['email' => $this->input->post('email')]);
            return TRUE;
        }
        return FALSE;
    }

    public function get_info() {
        $this->db->select('user_id,name,user_image_url,mobile,email,shipping_address1,shipping_address2,facebook_id,google_plus_id,android_key,iphone_key,last_login_date');
        $query = $this->db->get_where('tbl_users', ['email' => $this->input->post('email')]);
        return $query->row_array();
    }

    public function update_profile() {
        $fields = ['name' => $this->security->xss_clean($this->input->post('name')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'shipping_address1' => $this->security->xss_clean($this->input->post('shipping_address1')),
            'shipping_address2' => $this->security->xss_clean($this->input->post('shipping_address2'))];
        if ($this->input->post('user_image_url')) {
            $fields['user_image_url'] = $this->security->xss_clean($this->input->post('email') . '.png');
        }
        $this->db->update('tbl_users', $fields, ['email' => $this->input->post('email')]);
        return $this->db->affected_rows();
    }

    public function change_password() {
        $query = $this->db->get_where('tbl_users', ['email' => $this->input->post('email'), 'password' => $this->security->xss_clean(hash('sha256', $this->input->post('old_password') . SALT))]);

        if ($query->num_rows() !== 1) {
            return -1;
        }
        $this->db->update('tbl_users', ['password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT))], ['email' => $this->security->xss_clean($this->input->post('email')),
            'password' => $this->security->xss_clean(hash('sha256', $this->input->post('old_password') . SALT))]);


        return $this->db->affected_rows();
    }

    public function change_password_with_mobile() {
        $this->db->update('tbl_users', ['password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT))], ['mobile' => $this->input->post('mobile')]);
        return $this->db->affected_rows();
    }

//    public function get_users($status = NULL, $pg = NULL) {
//        $this->db->order_by('user_id', 'desc');
//        if (isset($pg)) {
//            $start = $pg * ROWS_PER_PAGE;
//            $records = ROWS_PER_PAGE;
//            $this->db->limit($records, $start);
//        }
//        if ($this->input->post('name')) {
//            $this->db->like('lower(name)', strtolower($this->security->xss_clean($this->input->post('name'))));
//        }
//        if ($this->input->post('email')) {
//            $this->db->like('lower(email)', strtolower($this->security->xss_clean($this->input->post('email'))));
//        }
//        if ($this->input->post('mobile')) {
//            $this->db->like('lower(mobile)', strtolower($this->security->xss_clean($this->input->post('mobile'))));
//        }
//        if ($this->input->post('address')) {
//            $this->db->like('lower(shipping_address1)', strtolower($this->security->xss_clean($this->input->post('address'))));
//            $this->db->or_like('lower(shipping_address2)', strtolower($this->security->xss_clean($this->input->post('address'))));
//        }
//        if ($this->input->post('status')) {
//            $this->db->where('is_active', $this->security->xss_clean($this->input->post('status')));
//        }
//
//        if (isset($status)) {
//            $this->db->where('status', $status);
//            $query = $this->db->get_where('tbl_users', ['is_active' => 'Yes']);
//        }
//        $query = $this->db->get('tbl_users');
//
//        return $query->result_array();
//    }

    public function get_filter_users_count() {
        if ($this->input->post('name')) {
            $this->db->like('lower(name)', strtolower($this->security->xss_clean($this->input->post('name'))));
        }
        if ($this->input->post('email')) {
            $this->db->like('lower(email)', strtolower($this->security->xss_clean($this->input->post('email'))));
        }
        if ($this->input->post('mobile')) {
            $this->db->like('lower(mobile)', strtolower($this->security->xss_clean($this->input->post('mobile'))));
        }
        if ($this->input->post('address')) {
            $this->db->like('lower(shipping_address1)', strtolower($this->security->xss_clean($this->input->post('address'))));
            $this->db->or_like('lower(shipping_address2)', strtolower($this->security->xss_clean($this->input->post('address'))));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->security->xss_clean($this->input->post('status')));
        }
        $query = $this->db->get('tbl_users');
        return $query->num_rows();
    }

    public function get_filter_users($pg) {
        $this->db->order_by('user_id', 'desc');
        if ($this->input->post('name')) {
            $this->db->like('lower(name)', strtolower($this->security->xss_clean($this->input->post('name'))));
        }
        if ($this->input->post('email')) {
            $this->db->like('lower(email)', strtolower($this->security->xss_clean($this->input->post('email'))));
        }
        if ($this->input->post('mobile')) {
            $this->db->like('lower(mobile)', strtolower($this->security->xss_clean($this->input->post('mobile'))));
        }
        if ($this->input->post('address')) {
            $this->db->like('lower(shipping_address1)', strtolower($this->security->xss_clean($this->input->post('address'))));
            $this->db->or_like('lower(shipping_address2)', strtolower($this->security->xss_clean($this->input->post('address'))));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->security->xss_clean($this->input->post('status')));
        }
        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }

        $query = $this->db->get('tbl_users');
        return ['data' => $query->result_array(), 'count' => $this->get_filter_users_count()];
    }

    public function change_status($user_id, $status) {
        $this->db->update('tbl_users', ['is_active' => $status], ['user_id' => $user_id]);
        return $this->db->affected_rows();
    }

    public function change_all_user_status($status) {
        $users = $this->input->post('user');
        $output = false;
        foreach ($users as $user) {
            $this->db->update('tbl_users', ['is_active' => $status], ['user_id' => $user]);
            if ($this->db->affected_rows()) {
                $output = true;
            }
        }
        return $output;
    }

    public function do_social_login() {
        $this->db->select('user_id,name,user_image_url,mobile,email,shipping_address1,shipping_address2,is_active');
        $this->db->from('tbl_users');
        $this->db->where('facebook_id', $this->input->post('facebook_id'));
        $this->db->or_where('google_plus_id', $this->input->post('google_plus_id'));
        $this->db->or_where('is_active', 'Yes');
        $query = $this->db->get();
        if ($query->num_rows() === 1) {
            return $query->row_array();
        }

        $query2 = $this->db->get_where('tbl_users', ['email' => $this->input->post('email')]);
        if ($query2->num_rows() === 1) {
            return 0;
        }
        $fields = ['email' => $this->input->post('email'), 'name' => $this->input->post('name')];
        if ($this->input->post('facebook_id')) {
            $fields['facebook_id'] = $this->input->post('facebook_id');
        }
        if ($this->input->post('google_plus_id')) {
            $fields['google_plus_id'] = $this->input->post('google_plus_id');
        }
        if ($this->input->post('user_image_url')) {
            $fields['user_image_url'] = $this->input->post('user_image_url');
        }
        if ($this->input->post('device_model')) {
            $fields['device_model'] = $this->input->post('device_model');
        }
        if ($this->input->post('device_os_version')) {
            $fields['device_os_version'] = $this->input->post('device_os_version');
        }
        if ($this->input->post('network_provider')) {
            $fields['network_provider'] = $this->input->post('network_provider');
        }
        if ($this->input->post('android_key')) {
            $fields['android_key'] = $this->input->post('android_key');
        }
        $this->db->insert('tbl_users', $fields);
        $insert_id = $this->db->insert_id();
        $this->db->select('user_id,name,user_image_url,mobile,email,shipping_address1,shipping_address2,is_active');
        $query1 = $this->db->get_where('tbl_users', ['user_id' => $insert_id]);
        return $query1->row_array();
    }

    public function store_otp($otp) {
        $query3 = $this->db->get_where('tbl_users', ['email' => $this->input->post('email')]);
        if ($query3->num_rows() > 0) {
            return -2;
        }

        $query2 = $this->db->get_where('tbl_users', ['mobile' => $this->input->post('mobile')]);
        if ($query2->num_rows() > 0) {
            return -1;
        }

        $query = $this->db->get_where('tbl_otp', ['mobile' => $this->input->post('mobile')]);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $this->db->update('tbl_otp', ['otp' => $otp], ['id' => $row['id']]);
            return $this->db->affected_rows();
        }
        $this->db->insert('tbl_otp', ['mobile' => $this->input->post('mobile'), 'otp' => $otp]);
        return $this->db->insert_id();
    }

    public function store_forget_password_otp($otp) {
        $query = $this->db->get_where('tbl_users', ['mobile' => $this->input->post('mobile')]);
        if ($query->num_rows() === 1) {
            $this->db->insert('tbl_otp', ['mobile' => $this->input->post('mobile'), 'otp' => $otp]);
            return $this->db->insert_id();
        }
        return 0;
    }

    public function confirm_otp() {
        $query = $this->db->get_where('tbl_otp', ['mobile' => $this->input->post('mobile'),
            'otp' => $this->input->post('otp')]);

        $rows = $query->num_rows() > 0;
        if ($rows) {
            $this->db->delete('tbl_otp', ['mobile' => $this->input->post('mobile'),
                'otp' => $this->input->post('otp')]);
        }
        return $rows;
    }


    
}
