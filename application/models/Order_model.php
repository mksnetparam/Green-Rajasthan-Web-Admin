<?php
class Order_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_orders($filter = NULL)
    {
        $this->db->order_by('o.order_id', 'desc');
        $this->db->select('o.order_id,u.user_id,u.fcm_id,u.name,number_of_plants,payment_mode,plant_cost,oz.organization_id,organization_name,order_date,last_payment_date,order_status');
        $this->db->from('tbl_plant_orders o');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $this->db->join('tbl_organization oz', 'oz.organization_id = o.organization_id');
        if (isset($filter)) {
            if (is_numeric($filter)) {
                $this->db->where('o.order_id', $filter);
            } else {
                $this->db->where('o.order_status', $filter);
            }
        }
        if (!empty($this->input->post('payment_status_filter'))) {
            $this->db->where('order_status', $this->input->post('payment_status_filter'));
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_order_ids()
    {
        $this->db->select('o.order_id,u.user_id,u.fcm_id,payment_mode,order_date,last_payment_date');
        $this->db->from('tbl_plant_orders o');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_order($order_id)
    {
        $query = $this->db->get_where('tbl_plant_orders', ['order_id' => $order_id]);
        return $query->row_array();
    }

    public function delete_allocation($order_id)
    {
        $query = $this->db->delete('tbl_plant_allocation', ['order_id' => $order_id]);
        return $this->db->affected_rows();
    }

    public function do_allocate()
    {
        $this->db->insert('tbl_plant_allocation', ['organization_id' => $this->input->post('organization_id'),
            'land_id' => $this->input->post('land_id'),
            'order_id' => $this->input->post('order_id')
        ]);
        $insert_id = $this->db->insert_id();
        $plants = $this->input->post('plants');
        $plant_ids = $this->input->post('plant_id');
        for ($i = 0; $i < count($plants); $i++) {
            $plant_id = $plant_ids[$i];
            for ($j = 1; $j <= $plants[$i]; $j++) {
                $qr_code = hash('sha256', $this->input->post('order_id') . '#' . $this->input->post('organization_id') . '#' . $this->input->post('land_id') . '#' . mt_rand() . '#' . microtime() . '#' . $i . SALT);
                QRcode::png($qr_code, APPPATH . '/../Qrcode/' . $qr_code . '.png');
                $this->db->insert('tbl_plant_allocation_detail', [
                    'allocation_id' => $insert_id,
                    'plant_id' => $plant_id,
                    'qr_code' => $qr_code
                ]);
            }
        }
        return $this->db->insert_id();
    }

    public function get_no_of_plants_allocated($order_id)
    {
        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail ad', 'ad.allocation_id = a.id');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_order_plants_barcode($order_id)
    {
        $this->db->select('p.id,p.qr_code,pt.plant_name,o.nominee_name');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->join('tbl_plant pt', 'p.plant_id = pt.plant_id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_land_plants_barcode($land_id)
    {
        $this->db->select('p.id,p.qr_code,pt.plant_name,o.nominee_name');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->join('tbl_plant pt', 'p.plant_id = pt.plant_id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        $this->db->where('a.land_id', $land_id);
//        $this->db->where('p.status', 'Allocated');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function land_detail($land_id)
    {
        $this->db->from('tbl_land l');
        $this->db->join('tbl_city ci', 'ci.city_id = l.city_id');
        $this->db->join('tbl_state s', 's.state_id = l.state_id');
        $this->db->join('tbl_country c', 'c.country_id = l.country_id');
        $this->db->join('tbl_organization o', 'o.organization_id = l.organization_id');
        $this->db->where('l.land_id', $land_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function verify_qr()
    {
        $this->db->select('pd.id,pd.status,pd.qr_code');
        $this->db->from('tbl_plant_allocation_detail pd');
        $this->db->join('tbl_plant_allocation a', 'a.id = pd.allocation_id');
        $this->db->where(['plant_id' => $this->input->post('plant_id'),
            'land_id' => $this->input->post('land_id')]);

        if (!empty($this->input->post('qr_code'))) {
            $this->db->where('qr_code', $this->input->post('qr_code'));
            $flag = TRUE;
            $value = $this->input->post('qr_code');
        } else {
            $this->db->where('pd.id', $this->input->post('tree_number'));
            $flag = FALSE;
            $value = $this->input->post('tree_number');
        }

        $query = $this->db->get();
        if ($query->num_rows() === 1) {
            $row = $query->row_array();
            if ($row['status'] === 'Allocated') {
                $this->db->update('tbl_plant_allocation_detail', ['status' => 'Planted', 'plantation_date' => date('d/m/Y')], ['qr_code' => $row['qr_code']]);
            }
            return ['status' => TRUE, 'plantation_id' => $row['id'], 'user_id' => $this->check_total_planted_count($flag, $value)];
        }
        return ['status' => FALSE, 'plantation_id' => ''];
    }

    public function check_total_planted_count($flag, $value)
    {
        $this->db->select('o.order_id , o.number_of_plants,ad.allocation_id, o.user_id');
        $this->db->from('tbl_plant_allocation_detail ad');
        $this->db->join('tbl_plant_allocation a', 'a.id = ad.allocation_id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        if ($flag) {
            $this->db->where('qr_code', $value);
        } else {
            $this->db->where('ad.id', $value);
        }
        $query = $this->db->get();
        $row1 = $query->row_array();

        $this->db->select('id');
        $this->db->from('tbl_plant_allocation');
        $this->db->where('order_id', $row1['order_id']);
        $query = $this->db->get();
        $result = $query->result_array();
        $planted_count = 0;
        foreach ($result as $row) {
            $this->db->select('id');
            $this->db->from('tbl_plant_allocation_detail');
            $this->db->where('allocation_id', $row['id']);
            $this->db->where('status', 'Planted');
            $query = $this->db->get();
            $row2 = $query->num_rows();
            $planted_count += $row2;
        }
        if ($row1['number_of_plants'] == $planted_count) {
            $this->db->update('tbl_plant_orders', ['order_status' => 'Processed', 'last_payment_date' => date('d-m-Y')], ['order_id' => $row1['order_id'], 'order_status !=' => 'Processed']);
            if ($this->db->affected_rows() > 0) {
                return $row1['user_id'];
            } else {
                return '';
            }
        }
        return '';
    }

    public function get_allocations()
    {
        $this->db->select('a.order_id,count(*) allocated_plants');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->group_by('a.order_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_land_allocations()
    {
        $this->db->select('a.land_id,count(*) allocated_plants,p.status');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->group_by('a.land_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_land_planted()
    {
        $this->db->select('a.land_id,count(*) allocated_plants,p.status');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->where('p.status', 'Planted');
        $this->db->group_by('a.land_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_land_allocated()
    {
        $this->db->select('a.land_id,count(*) allocated_plants,p.status,o.nominee_name');
        $this->db->from('tbl_plant_allocation_detail p');
        $this->db->join('tbl_plant_allocation a', 'p.allocation_id = a.id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        $this->db->where('p.status', 'Allocated');
        $this->db->group_by('a.land_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function capture_image($image)
    {
        $this->db->insert('tbl_plant_growth', ['plantation_id' => $this->input->post('plantation_id'),
            'image' => $image]);
        return $this->db->insert_id();
    }

    public function get_land_orders()
    {
        $this->db->select('l.land_id,l.land_address,l.district,l.tehsil,c.country_name,s.state_name,ci.city_name,count(*) status_count,p.status');
        $this->db->from('tbl_land l');
        $this->db->join('tbl_plant_allocation a', 'a.land_id = l.land_id');
        $this->db->join('tbl_plant_allocation_detail p', 'p.allocation_id = a.id');
        $this->db->join('tbl_country c', 'c.country_id = l.country_id');
        $this->db->join('tbl_state s', 's.state_id = l.state_id');
        $this->db->join('tbl_city ci', 'ci.city_id = l.city_id');
        $this->db->group_by('l.land_id');
        $this->db->group_by('p.status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function change_order_status($order_id)
    {
        $this->db->update('tbl_plant_orders', ['order_status' => $this->input->post('order_status')], ['order_id' => $order_id]);
        return $this->db->affected_rows();
    }

    public function get_payment_info($payment_id)
    {
        $query = $this->db->get_where('tbl_payment_info', ['id' => $payment_id]);
        return $query->row_array();
    }

    public function update_payment_info()
    {
        $query = $this->db->get_where('tbl_payment_info', ['id' => $this->input->post('id')]);
        $row = $query->row_array();
        $this->db->set('message', $row['message'] . ' ' . $this->input->post('message'));
        $this->db->update('tbl_payment_info', ['payment_status' => $this->input->post('payment_status')], ['id' => $this->input->post('id')]);
        $affected_rows = $this->db->affected_rows();
        if ($affected_rows) {
            if ($this->input->post('payment_status') === 'Received') {
                $this->db->update('tbl_plant_orders', ['last_payment_date' => date('d/m/Y')], ['order_id' => $this->input->post('order_id')]);
            } else {
                $this->db->update('tbl_plant_orders', ['last_payment_date' => date('d/m/Y')], ['order_id' => $this->input->post('order_id')]);
            }
        }
        return $affected_rows;
    }

    public function get_allotted_trees()
    {
        $this->db->select('pd.id plantation_id,u.user_id,u.name,u.image,date_format(pd.allocation_date,"%d/%m/%Y") allocation_date,pd.plantation_date,pd.status,p.plant_name,concat(l.land_address,", ",l.district,", ",l.tehsil,", ",ci.city_name,", ",s.state_name,", ",c.country_name) address,or.organization_name,l.contact_person,o.nominee_name');
        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail pd', 'a.id = pd.allocation_id');
        $this->db->join('tbl_plant p', 'p.plant_id = pd.plant_id');
        $this->db->join('tbl_plant_orders o', 'o.order_id = a.order_id');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $this->db->join('tbl_land l', 'l.land_id = a.land_id');
        $this->db->join('tbl_organization or', 'or.organization_id = l.organization_id');
        $this->db->join('tbl_country c', 'c.country_id = l.country_id');
        $this->db->join('tbl_state s', 's.state_id = l.state_id');
        $this->db->join('tbl_city ci', 'ci.city_id = l.city_id');
        $this->db->where('a.order_id', $this->input->post('order_id'));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_allotted_trees_images()
    {
        $sql = "SELECT m1.* FROM tbl_plant_growth m1 LEFT JOIN tbl_plant_growth m2 ON (m1.plantation_id = m2.plantation_id AND m1.id < m2.id) WHERE m2.id IS NULL";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_order_payment_status($order_id)
    {
        $sql = "SELECT m1.payment_status,m1.id,m1.payment_type FROM tbl_payment_info m1 LEFT JOIN tbl_payment_info m2 ON (m1.order_id = m2.order_id AND m1.id < m2.id) WHERE m2.id IS NULL and m1.order_id = '" . $order_id . "'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_fcm_id_by_order($order_id)
    {
        $this->db->select('u.fcm_id');
        $this->db->from('tbl_plant_orders o');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $this->db->where('o.order_id', $order_id);
        $query = $this->db->get();
        return $query->row_array()['fcm_id'];
    }

    public function get_payments($order_id)
    {
        $this->db->select('p.name,p.amount,p.result_code,p.transaction_id,p.payment_id,p.payment_status,p.message,p.payment_type,p.payment_date,o.payment_mode');
        $this->db->from('tbl_payment_info p');
        $this->db->join('tbl_plant_orders o', 'o.order_id = p.order_id');
        $this->db->where('p.order_id', $order_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_order_plants($order_id)
    {
        $this->db->select('d.id,d.plant_id,p.plant_name');
        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail d', 'd.allocation_id = a.id');
        $this->db->join('tbl_plant p', 'p.plant_id = d.plant_id');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_order_plant_images($plantation_id)
    {
        $sql = "SELECT m1.* FROM tbl_plant_growth m1 LEFT JOIN tbl_plant_growth m2 ON (m1.plantation_id = m2.plantation_id AND m1.id < m2.id) WHERE m2.id IS NULL and m1.plantation_id = '" . $plantation_id . "'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_plant_growth_images($plantation_id)
    {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get_where('tbl_plant_growth', ['plantation_id' => $plantation_id]);
        return $query->result_array();
    }

    public function get_plants_allocated()
    {
        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail ad', 'ad.allocation_id = a.id');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_no_of_total_orders()
    {
        $query = $this->db->get('tbl_plant_orders');
        return $query->num_rows();
    }

    public function get_new_orders()
    {
//        $query = $this->db->get_where('tbl_plant_orders', ['order_status' => 'Processing']);
//        return $query->result_array();

        $this->db->order_by('o.order_id', 'desc');
        $this->db->select('o.order_id,u.user_id,u.name,number_of_plants,payment_mode,plant_cost,oz.organization_id,organization_name,order_date,last_payment_date,order_status');
        $this->db->from('tbl_plant_orders o');
        $this->db->join('tbl_users u', 'u.user_id = o.user_id');
        $this->db->join('tbl_organization oz', 'oz.organization_id = o.organization_id');

        $this->db->where('order_status', 'Processing');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function is_all_plant_planted($order_id)
    {
        $this->db->select('number_of_plants');
        $query = $this->db->get_where('tbl_plant_orders', ['order_id' => $order_id]);
        $order_plants = $query->row_array()['number_of_plants'] . '</br>';


        $this->db->from('tbl_plant_allocation a');
        $this->db->join('tbl_plant_allocation_detail pd', 'a.id = pd.allocation_id');
        $this->db->where('order_id', $order_id);
        $this->db->where('status', 'Planted');
        $query = $this->db->get();
        return ($query->num_rows() == $order_plants);
    }

    public function get_all_payments()
    {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $date_from = str_replace('/', '-', $datefrom);
        $date_to = str_replace('/', '-', $dateto);
        $from_string = strtotime($date_from);
        $to_string = strtotime($date_to);
        $sql = "select str_to_date(payment_date,'%Y-%m-%d') payment_date,sum(amount) amount from tbl_payment_info where payment_status='Received' AND str_to_date(payment_date,'%Y-%m-%d') between '" . date('Y-m-d', $from_string) . "' and '" . date('Y-m-d', $to_string) . "' group by str_to_date(payment_date,'%Y-%m-%d')";
        $query = $this->db->query($sql);
        /*echo $this->db->last_query();
        die;*/
        return $query->result_array();
    }

    public function get_payment_datewise($date)
    {
        $payment_date = base64_decode(rawurldecode($date));
        $pd = explode(' ', $payment_date);
        $this->db->from('tbl_payment_info p');
        $this->db->join('tbl_plant_orders o', 'o.order_id = p.order_id');
        $this->db->where('payment_status', 'Received');
        $this->db->like('p.payment_date', $pd[0]);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_user_orders($user_id)
    {
        $query = $this->db->get_where('tbl_plant_orders', ['user_id' => $user_id]);
        return $query->result_array();
    }
}
