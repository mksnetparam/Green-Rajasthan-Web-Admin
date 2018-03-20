<?php
class Master_model extends CI_Model 
{
    public function __construct() {
        parent::__construct();
    }

    #########################################
    #######         Country         #########
    #########################################

    public function add_country() {
        $this->db->insert('tbl_country', ['country_name' => $this->security->xss_clean($this->input->post('country_name')),
            'is_active' => $this->security->xss_clean($this->input->post('is_active'))]);
        return $this->db->insert_id();
    }

    public function count_country_records() {
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->input->post('status'));
        }
        return $this->db->count_all_results('tbl_country');
    }

    public function get_countries($status = NULL, $pg = NULL) {
        $this->db->order_by('country_name');
        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->input->post('status'));
        }
        if(isset($status)) {
            $this->db->where('is_active',$status);
        }
        $query = $this->db->get('tbl_country');
        return $query->result_array();
    }

    public function get_filter_countries_count() {
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->input->post('status'));
        }
        $query = $this->db->get('tbl_country');
        return $query->num_rows();
    }

    public function get_filter_countries($pg) {
        $this->db->order_by('country_name');
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('is_active', $this->input->post('status'));
        }

        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }

        $query = $this->db->get('tbl_country');
        return ['data' => $query->result_array(), 'count' => $this->get_filter_countries_count()];
    }

    public function change_country_status($id, $status) {
        $this->db->update('tbl_country', ['is_active' => $status], ['country_id' => $id]);
        return $this->db->affected_rows();
    }

    public function change_all_country_status($status) {
        $countries = $this->input->post('countries');
        $output = false;
        foreach ($countries as $country) {
            $this->db->update('tbl_country', ['is_active' => $status], ['country_id' => $country]);
            if ($this->db->affected_rows()) {
                $output = true;
            }
        }
        return $output;
    }

    public function get_country($id) {
        $query = $this->db->get_where('tbl_country', ['country_id' => $id]);
        return $query->row_array();
    }

    public function delete_country($id) {
        $this->db->delete('tbl_country', ['country_id' => $id]);
        return $this->db->affected_rows();
    }

    public function update_country() {
        $query = $this->db->get_where('tbl_country', ['country_id' => $this->input->post('country_id')]);
        $row = $query->row_array();
        if ($this->input->post() == $row) {
            return 0;
        } else {
            $id = $this->input->post('country_id');
            $query = $this->db->get_where('tbl_country', ['country_name' => $this->input->post('country_name'), 'country_id!=' => $this->input->post('country_id')]);
            if ($query->num_rows() === 1) {
                return -2;
            } else {
                $this->db->update('tbl_country', ['country_name' => $this->security->xss_clean($this->input->post('country_name')),
                    'is_active' => $this->security->xss_clean($this->input->post('is_active'))], ['country_id' => $id]);
                return $this->db->affected_rows();
            }
        }
    }

    #########################################
    #######         States         ##########
    #########################################

    public function count_state_records() {
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('s.is_active', $this->input->post('status'));
        }
        return $this->db->count_all_results('tbl_state');
    }

    public function add_state() {
        $this->db->insert('tbl_state', ['state_name' => $this->security->xss_clean($this->input->post('state_name')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'is_active' => $this->security->xss_clean($this->input->post('is_active'))]);
        return $this->db->insert_id();
    }

    public function get_states($status = NULL, $pg = NULL) {
        $this->db->order_by('state_name');
        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('s.is_active', $this->input->post('status'));
        }
        $this->db->select('state_id,state_name,c.country_id,c.country_name,s.added_date,s.modified_date,s.is_active');
        $this->db->from('tbl_state s');
        $this->db->join('tbl_country c', 'c.country_id = s.country_id');
        if (!empty($status)) {
            $this->db->where('s.is_active', $status);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filter_states_count() {
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('s.is_active', $this->input->post('status'));
        }
        $this->db->select('state_id,state_name,c.country_id,c.country_name,s.added_date,s.modified_date,s.is_active');
        $this->db->from('tbl_state s');
        $this->db->join('tbl_country c', 'c.country_id = s.country_id');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_filter_states($pg) {
        $this->db->order_by('state_name');
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('s.is_active', $this->input->post('status'));
        }

        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }
        $this->db->select('state_id,state_name,c.country_id,c.country_name,s.added_date,s.modified_date,s.is_active');
        $this->db->from('tbl_state s');
        $this->db->join('tbl_country c', 'c.country_id = s.country_id');
        $query = $this->db->get();
        return ['data' => $query->result_array(), 'count' => $this->get_filter_states_count()];
    }

    public function change_state_status($id, $status) {
        $this->db->update('tbl_state', ['is_active' => $status], ['state_id' => $id]);
        return $this->db->affected_rows();
    }

    public function change_all_state_status($status) {
        $states = $this->input->post('states');
        $output = false;
        foreach ($states as $state) {
            $this->db->update('tbl_state', ['is_active' => $status], ['state_id' => $state]);
            if ($this->db->affected_rows()) {
                $output = true;
            }
        }
        return $output;
    }

    public function get_state($id) {
        $query = $this->db->get_where('tbl_state', ['state_id' => $id]);
        return $query->row_array();
    }

    public function delete_state($id) {
        $this->db->delete('tbl_state', ['state_id' => $id]);
        return $this->db->affected_rows();
    }

    public function update_state() {
        $query = $this->db->get_where('tbl_state', ['state_id' => $this->input->post('state_id')]);
        $row = $query->row_array();
        if ($this->input->post() == $row) {
            return 0;
        } else {
            $id = $this->input->post('state_id');
            $query = $this->db->get_where('tbl_state', ['state_name' => $this->input->post('state_name'), 'country_id' => $this->input->post('country_id'), 'state_id!=' => $this->input->post('state_id')]);
            if ($query->num_rows() === 1) {
                return -2;
            } else {
                $this->db->update('tbl_state', ['state_name' => $this->security->xss_clean($this->input->post('state_name')),
                    'country_id' => $this->security->xss_clean($this->input->post('country_id')),
                    'is_active' => $this->security->xss_clean($this->input->post('is_active'))], ['state_id' => $id]);
                return $this->db->affected_rows();
            }
        }
    }

    public function get_states_by_country($country_id) {
        $this->db->order_by('state_name');
        $query = $this->db->get_where('tbl_state', ['country_id' => $country_id, 'is_active' => 'Yes']);
        return $query->result_array();
    }

    #########################################
    #######         City             ########
    #########################################

    public function count_city_records() {
        if ($this->input->post('city')) {
            $this->db->like('lower(city_name)', strtolower($this->input->post('city')));
        }
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('c.is_active', $this->input->post('status'));
        }
        return $this->db->count_all_results('tbl_city');
    }

    public function add_city() {
        $this->db->insert('tbl_city', ['city_name' => $this->security->xss_clean($this->input->post('city_name')),
            'country_id' => $this->security->xss_clean($this->input->post('country_id')),
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'is_active' => $this->security->xss_clean($this->input->post('is_active'))]);
        return $this->db->insert_id();
    }

    public function get_cities($status = NULL, $pg = NULL) {
        $this->db->order_by('city_name');
        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }
        if ($this->input->post('city')) {
            $this->db->like('lower(city_name)', strtolower($this->input->post('city')));
        }
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('c.is_active', $this->input->post('status'));
        }
        $this->db->select('c.city_id,s.state_id,cr.country_id,cr.country_name,s.state_name,c.city_name,c.added_date,c.modified_date,c.is_active');
        $this->db->from('tbl_city c');
        $this->db->join('tbl_state s', 'c.state_id=s.state_id');
        $this->db->join('tbl_country cr', 'c.country_id=cr.country_id');
        if (!empty($status)) {
            $this->db->where('c.is_active', $status);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filter_cities_count() {
        if ($this->input->post('city')) {
            $this->db->like('lower(city_name)', strtolower($this->input->post('city')));
        }
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('c.is_active', $this->input->post('status'));
        }
        $this->db->select('c.city_id,s.state_id,cr.country_id,cr.country_name,s.state_name,c.city_name,c.added_date,c.modified_date,c.is_active');
        $this->db->from('tbl_city c');
        $this->db->join('tbl_state s', 'c.state_id=s.state_id');
        $this->db->join('tbl_country cr', 'cr.country_id=s.country_id');

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_filter_cities($pg) {
        $this->db->order_by('state_name');
        if ($this->input->post('city')) {
            $this->db->like('lower(city_name)', strtolower($this->input->post('city')));
        }
        if ($this->input->post('state')) {
            $this->db->like('lower(state_name)', strtolower($this->input->post('state')));
        }
        if ($this->input->post('country')) {
            $this->db->like('lower(country_name)', strtolower($this->input->post('country')));
        }
        if ($this->input->post('status')) {
            $this->db->where('c.is_active', $this->input->post('status'));
        }
        if (isset($pg)) {
            $start = $pg * ROWS_PER_PAGE;
            $records = ROWS_PER_PAGE;
            $this->db->limit($records, $start);
        }
        $this->db->select('c.city_id,s.state_id,cr.country_id,cr.country_name,s.state_name,c.city_name,c.added_date,c.modified_date,c.is_active');
        $this->db->from('tbl_city c');
        $this->db->join('tbl_state s', 'c.state_id=s.state_id');
        $this->db->join('tbl_country cr', 'cr.country_id=s.country_id');
        $query = $this->db->get();
        return ['data' => $query->result_array(), 'count' => $this->get_filter_cities_count()];
    }

    public function change_city_status($id, $status) {
        $this->db->update('tbl_city', ['is_active' => $status], ['city_id' => $id]);
        return $this->db->affected_rows();
    }

    public function change_all_city_status($status) {
        $cities = $this->input->post('cities');
        $output = false;
        foreach ($cities as $city) {
            $this->db->update('tbl_city', ['is_active' => $status], ['city_id' => $city]);
            if ($this->db->affected_rows()) {
                $output = true;
            }
        }
        return $output;
    }

    public function get_city($id) {
        $query = $this->db->get_where('tbl_city', ['city_id' => $id]);
        return $query->row_array();
    }

    public function delete_city($id) {
        $this->db->delete('tbl_city', ['city_id' => $id]);
        return $this->db->affected_rows();
    }

    public function update_city() {
        $query = $this->db->get_where('tbl_city', ['city_id' => $this->input->post('city_id')]);
        $row = $query->row_array();
        if ($this->input->post() == $row) {
            return 0;
        } else {
            $id = $this->input->post('city_id');
            $query = $this->db->get_where('tbl_city', ['city_name' => $this->input->post('city_name'), 'state_id' => $this->input->post('state_id'), 'country_id' => $this->input->post('country_id'), 'city_id!=' => $this->input->post('city_id')]);
            if ($query->num_rows() === 1) {
                return -2;
            } else {
                $this->db->update('tbl_city', ['city_name' => $this->security->xss_clean($this->input->post('city_name')),
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'country_id' => $this->security->xss_clean($this->input->post('country_id')),
                    'is_active' => $this->security->xss_clean($this->input->post('is_active'))], ['city_id' => $id]);
                return $this->db->affected_rows();
            }
        }
    }

    public function get_cities_by_state($state_id) {
        $this->db->order_by('city_name');
        $query = $this->db->get_where('tbl_city', ['state_id' => $state_id, 'is_active' => 'Yes']);
        return $query->result_array();
    }
}
