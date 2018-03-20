<?php
class Employee extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model(['employee_model']);
        $this->load->library(['location', 'gblib']);
        $this->settings = $this->gblib->get_all_site_settings();
    }

    private function _is_login() {
        return $this->session->userdata('gbemail');
    }

    private function _organize_admin_roles() {
        $result = $this->employee_model->get_admin_roles();
        $arr = ['' => '--Select Role--'];
        foreach ($result as $row) {
            $arr[$row['role_id']] = $row['role_name'];
        }
        return $arr;
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
        $states[''] = ['---Select State---'];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }

    private function get_cities_arr() {
        $result = $this->location->get_cities();
        $cities[''] = ['---Select City---'];
        foreach ($result as $row) {
            $cities[$row['city_id']] = $row['city_name'];
        }
        return $cities;
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

    public function index() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Employee Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'employee';
        $data['active'] = 'employee';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('employee/index', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('commons/endpage');
    }

    public function add() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Employee Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'organization';
        $data['countries'] = $this->get_countries_arr();
        $data['user_info'] = $this->employee_model->get_profile($email);
        $data['roles'] = $this->_organize_admin_roles();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = $this->get_cities_arr();
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('employee/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
//        $this->load->view('scripts/user/dropzone', $data);
        $this->load->view('commons/endpage');
    }

    public function do_add_user() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('firstname', 'First Name', 'required|regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^\d{10}$/]|exact_length[10]|is_unique[tbl_employee.phone]|greater_than[0]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_employee.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|min_length[6]|matches[password]');
        $this->form_validation->set_rules('role_id', 'Role', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->employee_model->add();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Employee Added Successfully.']));
            return FALSE;
        }

        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Employee can\'t be Added.']));
        return FALSE;
    }

    public function get_employee() {
        $this->output->set_content_type('application/json');
        $data['users'] = $this->employee_model->get_employees();
        $itemlist = $this->load->view('employee/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function change_status($id) {
        $this->output->set_content_type('application/json');
        $result = $this->employee_model->change_status($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Status Change Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Status Can\'t be Changed.']));
        return FALSE;
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');
        $result = $this->employee_model->delete($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Employee Deleted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Employee Can\'t be deleted.']));
        return FALSE;
    }

    public function edit($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Employee Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['countries'] = $this->get_countries_arr();
        $data['menu_id'] = 'master';
        $data['active'] = 'organization';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $data['roles'] = $this->_organize_admin_roles();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = $this->get_cities_arr();
        $data['user'] = $this->employee_model->get_employee($id);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('employee/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('commons/endpage');
    }

    public function do_update_user() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('firstname', 'First Name', 'required|regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^\d{10}$/]|exact_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('role_id', 'Role', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $affected_rows = $this->employee_model->update();
        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Employee Details updated Successfully.']));
            return FALSE;
        }

        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Nothing gets Changed.']));
        return FALSE;
    }
}
