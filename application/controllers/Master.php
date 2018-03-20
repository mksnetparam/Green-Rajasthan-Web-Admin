<?php
class Master extends CI_Controller {

    private $settings;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model(['employee_model', 'master_model', 'settings_model', 'organization_model']);
        $this->load->library(['location', 'gblib']);
        $this->settings = $this->gblib->get_all_site_settings();
        define('ROWS_PER_PAGE', $this->settings['ROWS_PER_PAGE']);
    }

    private function _is_login() {
        return $this->session->userdata('gbemail');
    }

    private function get_countries_arr() {
        $result = $this->location->get_countries();

        $countries[''] = '---Select Country---';
        foreach ($result as $row) {
            $countries[$row['country_id']] = $row['country_name'];
        }
        return $countries;
    }

    private function get_states_arr() {
        $result = $this->location->get_states();
        $states[] = [];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }
    
    private function get_states_by_country_arr($country_id) {
        $result = $this->location->get_states_by_country($country_id);
        $states = [];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }
    
    private function get_cities_by_states_arr($state_id) {
        $result = $this->location->get_cities_by_state($state_id);
        $states = [];
        foreach ($result as $row) {
            $states[$row['city_id']] = $row['city_name'];
        }
        return $states;
    }
    
    public function get_states_by_country_json($id) {
        $this->output->set_content_type('application/json');
        $result = $this->location->get_states_by_country($id);
        $states[0] = ['---Select State---'];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        $this->output->set_output(json_encode(['result' => 1, 'states' => $states]));
        return FALSE;
    }
    
    public function get_city_by_state_json($id) {
        $this->output->set_content_type('application/json');
        $result = $this->location->get_cities_by_state($id);
        $cities = [];
        foreach ($result as $row) {
            $cities[$row['city_id']] = $row['city_name'];
        }
        $this->output->set_output(json_encode(['result' => 1, 'cities' => $cities]));
        return FALSE;
    }

    #######################################
    ##########      Country        ########
    #######################################

    public function country() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Country Master | Pavitram Administrator Control";
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'country';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/country/country', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/country/scripts');
        $this->load->view('commons/endpage');
    }

    public function do_add_country() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('country_name', 'Country Name', 'required|regex_match[/[A-Za-z ]/]|is_unique[tbl_country.country_name]');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $result = $this->master_model->add_country();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Country Added Successfully.']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Oops!!! Country can\'t be Added.']));
        }
        return FALSE;
    }

    public function get_countries($pg = 0, $type = 'json') {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $data['total_rows'] = $this->master_model->count_country_records();
        $data['countries'] = $this->master_model->get_countries(NULL, $pg);
        $itemlist = $this->load->view('master/country/includes/list', $data, TRUE);
        if ($type === 'json') {
            $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
            return FALSE;
        } else {
            return $itemlist;
        }
    }

    public function filter_country($pg = 0) {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $result = $this->master_model->get_filter_countries($pg);
        $data['countries'] = $result['data'];
        $data['total_rows'] = $result['count'];
        $itemlist = $this->load->view('master/country/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function get_country($id) {
        $this->output->set_content_type('application/json');
        $country = $this->master_model->get_country($id);
        $this->output->set_output(json_encode(['result' => 1, 'item' => $country, 'url' => base_url('master/update-country')]));
        return FALSE;
    }

    public function change_country_status($pg, $id, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_country_status($id, $status);
        if ($affected_rows) {
            $item_list = $this->get_countries($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Status Can\'t be Changed Successfully.']));
        }
        return FALSE;
    }

    public function change_all_country_status($pg, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_all_country_status($status);
        if ($affected_rows) {
            $item_list = $this->get_countries($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return FALSE;
    }

    public function delete_country($pg, $id) {
        $this->output->set_content_type('application/json');
        $result = $this->master_model->delete_country($id);
        if ($result) {
            $itemlist = $this->get_countries($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Country Deleted Successfully.', 'itemlist' => $itemlist]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Sorry!!! Country Can\'t be delete.']));
        return false;
    }

    public function update_country() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('country_name', 'Country Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }


        $result = $this->master_model->update_country();
        if ($result === -2) {
            $this->output->set_output(json_encode(['result' => -2, 'msg' => 'Oops!!! Country Already Exists.']));
            return false;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Country Updated Successfully.', 'url' => base_url('master/do-add-country')]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        return false;
    }

    #######################################
    ##########      State        ##########
    #######################################

    public function state() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['countries'] = $this->get_countries_arr();
        $data['title'] = "State Master | Pavitram Administrator Control";
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'state';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/state/state', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/state/scripts');
        $this->load->view('commons/endpage');
    }

    public function do_add_state() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('state_name', 'State Name', 'required|regex_match[/[A-Za-z ]/]|is_unique[tbl_state.state_name]');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }


        $result = $this->master_model->add_state();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! State Added Successfully.']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Oops!!! State can\'t be Added.']));
        }
        return FALSE;
    }

    public function get_states($pg = 0, $type = 'json') {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $data['total_rows'] = $this->master_model->count_state_records();
        $data['states'] = $this->master_model->get_states(NULL, $pg);

        $itemlist = $this->load->view('master/state/includes/list', $data, TRUE);
        if ($type === 'json') {
            $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
            return FALSE;
        } else {
            return $itemlist;
        }
    }

    public function filter_state($pg = 0) {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $result = $this->master_model->get_filter_states($pg);
        $data['states'] = $result['data'];
        $data['total_rows'] = $result['count'];
        $itemlist = $this->load->view('master/state/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function change_state_status($pg, $id, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_state_status($id, $status);
        if ($affected_rows) {
            $item_list = $this->get_states($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Status Can\'t be Changed Successfully.']));
        }
        return FALSE;
    }

    public function change_all_state_status($pg, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_all_state_status($status);
        if ($affected_rows) {
            $item_list = $this->get_states($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return FALSE;
    }

    public function get_state($id) {
        $this->output->set_content_type('application/json');
        $state = $this->master_model->get_state($id);
        $this->output->set_output(json_encode(['result' => 1, 'item' => $state, 'url' => base_url('master/update-state')]));
        return FALSE;
    }

    public function delete_state($pg, $id) {
        $this->output->set_content_type('application/json');
        $result = $this->master_model->delete_state($id);
        if ($result) {
            $itemlist = $this->get_states($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'State Deleted Successfully.', 'itemlist' => $itemlist]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Sorry!!! State Can\'t be delete.']));
        return false;
    }

    public function update_state() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('state_name', 'State Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->master_model->update_state();

        if ($result === -2) {
            $this->output->set_output(json_encode(['result' => -2, 'msg' => 'Oops!!! State Already Exists.']));
            return false;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! State Updated Successfully.', 'url' => base_url('master/do-add-state')]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        return false;
    }

    #######################################
    ##########      City         ##########
    #######################################

    public function city() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['title'] = "City Master | Pavitram Administrator Control";
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'city';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/city/city', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/city/scripts');
        $this->load->view('commons/endpage');
    }

    public function do_add_city() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('city_name', 'City Name', 'required|regex_match[/[A-Za-z ]/]|is_unique[tbl_city.city_name]');
        $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }


        $result = $this->master_model->add_city();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! City Added Successfully.']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Oops!!! City can\'t be Added.']));
        }
        return FALSE;
    }

    public function get_cities($pg = 0, $type = 'json') {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $data['total_rows'] = $this->master_model->count_city_records();
        $data['cities'] = $this->master_model->get_cities(NULL, $pg);

        $itemlist = $this->load->view('master/city/includes/list', $data, TRUE);
        if ($type === 'json') {
            $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
            return FALSE;
        } else {
            return $itemlist;
        }
    }

    public function filter_city($pg = 0) {
        $this->output->set_content_type('application/json');
        $data['pg'] = $pg;
        $result = $this->master_model->get_filter_cities($pg);
        $data['cities'] = $result['data'];
        $data['total_rows'] = $result['count'];
        $itemlist = $this->load->view('master/city/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function change_city_status($pg, $id, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_city_status($id, $status);
        if ($affected_rows) {
            $item_list = $this->get_cities($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Status Can\'t be Changed Successfully.']));
        }
        return FALSE;
    }

    public function change_all_city_status($pg, $status) {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->master_model->change_all_city_status($status);
        if ($affected_rows) {
            $item_list = $this->get_cities($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Status Changed Successfully.', 'itemlist' => $item_list]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return FALSE;
    }

    public function get_city($id) {
        $this->output->set_content_type('application/json');
        $state = $this->master_model->get_city($id);
        $this->output->set_output(json_encode(['result' => 1, 'item' => $state, 'url' => base_url('master/update-city')]));
        return FALSE;
    }

    public function delete_city($pg, $id) {
        $this->output->set_content_type('application/json');
        $result = $this->master_model->delete_city($id);
        if ($result) {
            $itemlist = $this->get_cities($pg, 'string');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'City Deleted Successfully.', 'itemlist' => $itemlist]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Sorry!!! City Can\'t be delete.']));
        return false;
    }

    public function update_city() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('city_name', 'City Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->master_model->update_city();
        if ($result === -2) {
            $this->output->set_output(json_encode(['result' => -2, 'msg' => 'Oops!!! City Already Exists.']));
            return false;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! City Updated Successfully.', 'url' => base_url('master/do-add-city')]));
            return false;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        return false;
    }

    ##############################################
    ############# Organization ###################
    ##############################################

    public function organization() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Organization Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'organization';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/organization/organization', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/organization/scripts');
        $this->load->view('commons/endpage');
    }

    public function add_organization() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('master/upload-photo');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = '';
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Organization Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'organization';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/organization/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/organization/scripts', $data);
        $this->load->view('scripts/commons/dropzone', $data);
        $this->load->view('commons/endpage');
    }

    public function do_add_organization() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('organization_name', 'Organization Name', 'required|is_unique[tbl_organization.organization_name]|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('ownership', 'Ownership', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|exact_length[10]|regex_match[/^\d{10}$/]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address_line1', 'Address Line1', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('city_id', 'City', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|exact_length[6]|regex_match[/^\d{6}$/]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->organization_model->add();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Organization Added Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Organization Can\'t be Added.']));
        return FALSE;
    }

    
    public function edit_organization($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $organization = $this->organization_model->get_organization($id);
        
        $data['upload_url'] = base_url('master/upload-photo');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_by_country_arr($organization['country_id']);
        $data['cities'] = $this->get_cities_by_states_arr($organization['state_id']);
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Organization Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'organization';
        $data['organization'] = $organization;
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/organization/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/organization/scripts', $data);
        $this->load->view('scripts/commons/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    
    public function do_update_organization() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('organization_name', 'Organization Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('ownership', 'Ownership', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address_line1', 'Address Line1', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('city_id', 'City', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[Yes,No]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->organization_model->update();
        if($result===-1) {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Organization Details Can\'t be updated. Organization Name already exists.']));
            return FALSE;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Organization Details Update Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Organization Details Can\'t be Updated.']));
        return FALSE;
    }
    
    public function get_organizations() {
        $this->output->set_content_type('application/json');
        $data['organizations'] = $this->organization_model->get_organizations();
        $itemlist = $this->load->view('master/organization/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');
        $this->load->model('organization_model');
        $result = $this->organization_model->delete($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Organization Deleted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Organization Can\'t be Deleted.']));
        return FALSE;
    }

    public function change_status($id) {
        $this->output->set_content_type('application/json');
        $this->load->model('organization_model');
        $result = $this->organization_model->change_status($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Status Change Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Status Can\'t be Changed.']));
        return FALSE;
    }    
    public function upload_photo() {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('org','file');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }
    
    public function view_detail($org_id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $organization = $this->organization_model->get_organization($org_id);
        
        $data['upload_url'] = base_url('master/upload-photo');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_by_country_arr($organization['country_id']);
        $data['cities'] = $this->get_cities_by_states_arr($organization['state_id']);
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Organization Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'master';
        $data['active'] = 'organization';
        $data['org'] = $organization;
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('master/organization/detail-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/master/organization/scripts', $data);
        $this->load->view('commons/endpage');
    }
}