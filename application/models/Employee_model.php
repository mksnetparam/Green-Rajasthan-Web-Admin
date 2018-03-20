<?php
class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function is_auth_employee() {
        $enc_password = hash('sha256', $this->security->xss_clean($this->input->post('password')) . SALT);
        $query = $this->db->get_where('tbl_employee', ['email' => $this->security->xss_clean($this->input->post('email')), 'password' => $enc_password, 'is_active' => 'Yes']);
        return $query->num_rows() == 1;
    }

    public function get_employees() {
        $this->db->select('e.firstname,e.lastname,e.id,e.phone,e.email,e.is_active,r.role_name');
        $this->db->from('tbl_employee e');
        $this->db->join('tbl_admin_role r', 'e.role_id = r.role_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_name($email) {
        $query = $this->db->get_where('tbl_employee', ['email' => $email]);
        $emp_record = $query->row_array();
        return $emp_record['firstname'] . ' ' . $emp_record['lastname'];
    }

    public function get_profile($email) {
        $query = $this->db->get_where('tbl_employee', ['email' => $email]);
        return $query->row_array();
    }

    public function update_profile($email) {
        $this->db->update('tbl_employee', ['firstname' => $this->security->xss_clean($this->input->post('firstname')),
            'lastname' => $this->security->xss_clean($this->input->post('lastname')),
            'phone' => $this->security->xss_clean($this->input->post('mobile'))], ['email' => $email]);

        return $this->db->affected_rows();
    }

    public function update_password($email) {
        $enc_password = hash('sha256', $this->security->xss_clean($this->input->post('password')) . SALT);
        $this->db->update('tbl_employee', ['password' => $enc_password], ['email' => $email]);
        return $this->db->affected_rows();
    }

    public function update_password_by_mobile($mobile) {
        $enc_password = hash('sha256', $this->security->xss_clean($this->input->post('password')) . SALT);
        $this->db->update('tbl_employee', ['password' => $enc_password], ['phone' => $mobile]);
        return $this->db->affected_rows();
    }

    public function send_otp($phone) {
        $query1 = $this->db->get_where('tbl_employee', ['phone' => $this->security->xss_clean($phone)]);

        if ($query1->num_rows() === 1) {
            $row1 = $query1->row_array();
            $otp = rand(100000, 999999);
            $message = 'Your otp is ' . $otp;
            file_get_contents('http://api.msg91.com/api/sendhttp.php?authkey=109642A0uWgJ3iJqqg5708b1f1&mobiles=' . $row1['phone'] . '&message=' . $message . '&route=4&sender=verify');
            $query2 = $this->db->get_where('tbl_otp', ['mobile' => $row1['phone']]);
            if ($query2->num_rows() === 1) {
                $row2 = $query2->row_array();
                $this->db->update('tbl_otp', ['otp' => $otp], ['id' => $row2['id']]);
                return $this->db->affected_rows();
            }
            $this->db->insert('tbl_otp', ['mobile' => $this->security->xss_clean($this->input->post('mobile')),
                'otp' => $otp]);
            return $this->db->insert_id();
        }
        return -1;
    }

    public function match_otp($mobile) {
        $query = $this->db->get_where('tbl_otp', ['mobile' => $mobile, 'otp' => $this->security->xss_clean($this->input->post('otp'))]);
        if ($query->num_rows() === 1) {
            $this->db->delete('tbl_otp', ['mobile' => $mobile, 'otp' => $this->security->xss_clean($this->input->post('otp'))]);
            return $this->db->affected_rows();
        }
        return 0;
    }

    public function get_users($type) {
        $this->db->select('e.id,e.firstname,e.lastname');
        $this->db->from('tbl_employee e');
        $this->db->join('tbl_admin_role r', 'e.role_id = r.role_id');
        $this->db->where('r.role_name', $type);
        $query = $this->db->get();
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_admin_roles() {
        $query = $this->db->get('tbl_admin_role');
        return $query->result_array();
    }

    public function add() {
        $this->db->insert('tbl_employee', [
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'phone' => $this->input->post('mobile'),
            'email' => $this->input->post('email'),
            'password' => hash('sha256', $this->input->post('password') . SALT),
            'role_id' => $this->input->post('role_id'),
            'country_id' => $this->input->post('country_id'),
            'state_id' => $this->input->post('state_id'),
            'city_id' => $this->input->post('city_id'),
            'is_active' => $this->input->post('is_active')
        ]);
        return $this->db->insert_id();
    }

    public function change_status($id) {
        $this->db->update('tbl_employee', ['is_active' => $this->input->post('status')], ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete($id) {
        $this->db->delete('tbl_employee', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function get_employee($id) {
        $query = $this->db->get_where('tbl_employee', ['id' => $id]);
        return $query->row_array();
    }

    public function update() {
        $this->db->update('tbl_employee', [
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'phone' => $this->input->post('mobile'),
            'email' => $this->input->post('email'),
            'role_id' => $this->input->post('role_id'),
            'country_id' => $this->input->post('country_id'),
            'state_id' => $this->input->post('state_id'),
            'city_id' => $this->input->post('city_id'),
            'is_active' => $this->input->post('is_active')
                ], ['id' => $this->input->post('employee_id')]);
        return $this->db->affected_rows();
    }

}
