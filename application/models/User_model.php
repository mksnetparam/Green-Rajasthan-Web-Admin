<?php
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function add()
    {
        $fileds = ['name' => $this->security->xss_clean($this->input->post('name')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'password' => $this->security->xss_clean(hash('sha256', $this->input->post('password') . SALT)),
            'address' => $this->security->xss_clean($this->input->post('address')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'is_active' => $this->security->xss_clean($this->input->post('is_active'))];

        if ($this->input->post('image')) {
            $fileds['image'] = $this->input->post('image');
        }
        $this->db->insert('tbl_users', $fileds);
        return $this->db->insert_id();
    }

    public function get_users($status = NULL)
    {
        $this->db->order_by('user_id', 'desc');
        $this->db->select('user_id,name,mobile,email,address,u.country_id,u.state_id,u.city_id,u.is_active,u.image,u.added_date');
        $this->db->from('tbl_users u');
        if (isset($status)) {
            $this->db->where('u.is_active', $status);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function change_status($id)
    {
        $this->db->update('tbl_users', ['is_active' => $this->input->post('status')], ['user_id' => $id]);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->delete('tbl_users', ['user_id' => $id]);
        return $this->db->affected_rows();
    }
    public function get_user($id)
    {
        $this->db->select('u.user_id,u.password,u.name,u.mobile,u.email,u.address,u.image,u.is_active,u.state_id,u.city_id,u.country_id,u.referal_code');
        $this->db->from('tbl_users u');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function update()
    {
        $id = $this->input->post('user_id');
        $query = $this->db->get_where('tbl_users', ['user_id' => $id]);
        $row = $query->row_array();
        $user = ['user_id' => $row['user_id'], 'name' => $row['name'], 'mobile' => $row['mobile'], 'email' => $row['email'], 'password' => $this->input->post('password'), 'addres' => $row['address'], 'country_id' => $row['country_id'], 'state' => $row['state_id'], 'city_id' => $row['city_id'], 'is_active' => $row['is_active']];
        if ($this->input->post('image')) {
            $user['image'] = $this->input->post('image');
        }

        if ($user == $this->input->post()) {
            return 0;
        }
        $query1 = $this->db->get_where('tbl_users', ['email' => $this->input->post('email'), 'user_id!=' => $id]);
        if ($query1->num_rows() === 1) {
            return -1;
        }

        $query2 = $this->db->get_where('tbl_users', ['mobile' => $this->input->post('mobile'), 'user_id!=' => $this->input->post('user_id')]);
        if ($query2->num_rows() === 1) {
            return -2;
        }

        $fileds = ['name' => $this->security->xss_clean($this->input->post('name')),
            'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'address' => $this->security->xss_clean($this->input->post('address')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'city_id' => $this->security->xss_clean($this->input->post('city_id')),
            'is_active' => $this->security->xss_clean($this->input->post('is_active'))];

        if ($this->input->post('image')) {
            $fileds['image'] = $this->input->post('image');
        }
        $this->db->update('tbl_users', $fileds, ['user_id' => $this->security->xss_clean($this->input->post('user_id'))]);
        return $this->db->affected_rows();
    }
    public function get_fcm_id($user_id)
    {
        $this->db->select('fcm_id');
        $query = $this->db->get_where('tbl_users', ['user_id' => $user_id]);
        return $query->row_array();
    }
    public function update_fcm_id()
    {
        $this->db->update('tbl_users', ['fcm_id' => $this->input->post('android_key')], ['user_id' => $this->input->post('user_id')]);
        return $this->db->affected_rows();
    }
    public function get_total_users()
    {
        $query = $this->db->get('tbl_users');
        return $query->num_rows();
    }
    public function get_donation_payments()
    {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $date_from = str_replace('/', '-', $datefrom);
        $date_to = str_replace('/', '-', $dateto);
        $from_string = strtotime($date_from);
        $to_string = strtotime($date_to);
        $sql = "select user_id, str_to_date(payment_date,'%Y-%m-%d') payment_date, sum(amount) amount from tbl_donation_payment where str_to_date(payment_date,'%Y-%m-%d') between '" . date('Y-m-d', $from_string) . "' and '" . date('Y-m-d', $to_string) . "' group by payment_date";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_payment_datewise($date, $user_id)
    {
        $payment_date = base64_decode(rawurldecode($date));
        $pd = explode(' ', $payment_date);
        $this->db->order_by('p.id', 'desc');
        $this->db->select('p.*,u.name user_name');
        $this->db->from('tbl_donation_payment p');
        $this->db->join('tbl_users u', 'u.user_id = p.user_id');
        $this->db->where('p.user_id', $user_id);
        $this->db->like('p.payment_date', $pd[0]);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_payment_info($id)
    {
        $query = $this->db->get_where('tbl_donation_payment', ['id' => $id]);
        return $query->row_array();
    }
    public function update_payment_status()
    {
        $query = $this->db->get_where('tbl_donation_payment', ['id' => $this->input->post('id')]);
        $row = $query->row_array();
        $this->db->set('message', $row['message'] . ' ' . $this->input->post('message'));
        $this->db->update('tbl_donation_payment', ['payment_status' => $this->input->post('payment_status')], ['id' => $this->input->post('id')]);
        return $this->db->affected_rows();
    }
    public function has_land($user_id)
    {
        $query = $this->db->get_where('tbl_land', ['user_id' => $user_id]);
        return $query->num_rows();
    }
    public function has_order($user_id)
    {
        $query = $this->db->get_where('tbl_plant_orders', ['user_id' => $user_id]);
        return $query->num_rows();
    }
    public function add_role($user_id)
    {
        $query = $this->db->get_where('tbl_user_role', ['user_id' => $user_id, 'role_id' => $this->input->post('user_role')]);
        if ($query->num_rows() === 0) {
            $this->db->insert('tbl_user_role', ['user_id' => $user_id, 'role_id' => $this->input->post('user_role')]);
            return $this->db->insert_id();
        }
        return FALSE;
    }
    public function get_user_roles()
    {
        $this->db->from('tbl_user_role ur');
        $this->db->join('tbl_admin_role ar', 'ur.role_id = ar.role_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function allocate_area($role_id)
    {
        $this->db->update('tbl_user_role',[
            'country_id'=>$this->input->post('country_id'),
            'state_id'=>$this->input->post('state_id'),
            'city_id'=>$this->input->post('city_id')
            ],['id'=>$role_id]);
        return $this->db->affected_rows();
    }
    public function add_user_payment() {
        $this->db->insert('tbl_user_payments',[
            'user_id' => $this->input->post('user_id'),
            'amount' => $this->input->post('amount'),
            'payment_date' => $this->input->post('payment_date'),
            'comments' => $this->input->post('comments')
        ]);
        return $this->db->insert_id();
    }
    public function get_all_user_payment() {
        $query = $this->db->get('tbl_user_payments');
        return $query->result_array();
    }
}
