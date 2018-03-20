<?php
class Land extends CI_Controller 
{
    private $settings;
    public function __construct() {
        parent::__construct();
        $this->load->model(['employee_model', 'settings_model', 'organization_model', 'land_model', 'soil_model', 'plant_model']);
        $this->load->library(['location', 'gblib']);
        $this->settings = $this->gblib->get_all_site_settings();
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
        $states = [];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }

    private function get_cities_arr() {
        $result = $this->location->get_cities();
        $cities = [];
        foreach ($result as $row) {
            $cities[$row['city_id']] = $row['city_name'];
        }
        return $cities;
    }

    private function get_organization_arr() {
        $result = $this->organization_model->get_organizations('Yes');
        $organizations = [];
        foreach ($result as $row) {
            $organizations[$row['organization_id']] = $row['organization_name'];
        }
        return $organizations;
    }

    private function get_land_unit_arr() {
        $result = $this->land_model->get_land_units();
        $units = [];
        foreach ($result as $row) {
            $units[$row['land_area_name']] = $row['land_area_name'];
        }
        return $units;
    }

    private function get_users_arr($type) {
        $result = $this->employee_model->get_users($type);
        $users[''] = ['--'];
        foreach ($result as $row) {
            $users[$row['id']] = $row['firstname'] . ' ' . $row['lastname'];
        }
        return $users;
    }

    private function get_soil_type_arr() {
        $result = $this->soil_model->get_soil_types();
        $soils = [];
        foreach ($result as $row) {
            $soils[$row['soil_id']] = $row['soil_name'];
        }
        return $soils;
    }

    private function get_tree_arr() {
        $result = $this->plant_model->get_plants('Yes');
        $trees[''] = ['--Select--'];
        foreach ($result as $row) {
            $trees[$row['plant_id']] = $row['plant_name'];
        }
        return $trees;
    }

    private function get_verfication_requested_lands() {
        $result = $this->land_model->get_verfication_requested_lands();
        $lands = [];
        foreach ($result as $row) {
            $lands[$row['land_id']] = $row['user_id'];
        }
        return $lands;
    }

    private function get_soil_verfication_requested_lands() {
        $result = $this->land_model->get_soil_verfication_requested_lands();
        $lands = [];
        foreach ($result as $row) {
            $lands[$row['land_id']] = $row['user_id'];
        }
        return $lands;
    }

    private function get_employees() {
        $result = $this->employee_model->get_employees();
        $employees = [];
        foreach ($result as $row) {
            $employees[$row['id']] = $row['firstname'] . ' ' . $row['lastname'];
        }
        return $employees;
    }

    public function get_states_by_country_json($id) {
        $this->output->set_content_type('application/json');
        $result = $this->location->get_states_by_country($id);
        $states[0] = '---Select State---';
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

    private function get_users_search_dropdown() {
        $this->load->model('user_model');
        $result = $this->user_model->get_users('Yes');
        $users[''] = 'Select User';
        foreach ($result as $row) {
            $users[$row['user_id']] = $row['mobile'] . ' - ' . $row['name'];
        }
        return $users;
    }

    public function index() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'land';
        $data['active'] = 'land';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('land/land', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/land/script');
        $this->load->view('commons/endpage');
    }

    public function add_land() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('land/upload-photo');
        $data['contact_upload_url'] = base_url('land/upload-contact-photo');
        $data['organizations'] = $this->get_organization_arr();
        $data['units'] = $this->get_land_unit_arr();
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['users'] = $this->get_users_search_dropdown();
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'land';
        $data['active'] = 'land';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('land/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/land/script');
        $this->load->view('scripts/commons/dropzone');
        $this->load->view('commons/endpage');
    }

    public function upload_photo() {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('land', 'file');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }

    public function upload_docs() {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('land/docs', 'file', 'pdf|doc|docx');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }

    public function upload_contact_photo() {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('user', 'file');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }

    public function do_add_land() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('organization_id', 'Organization Name', 'required');
        $this->form_validation->set_rules('land_area', 'Land Area', 'required|regex_match[/^[^+-][0-9]{0,3}([.])?[0-9]+/]');
        $this->form_validation->set_rules('land_area_unit', 'Land Area Unit', 'required');
        $this->form_validation->set_rules('plant_capacity_qty', 'Capacity', 'required|integer');
        $this->form_validation->set_rules('land_address', 'Land Address', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('contact_person_relation', 'Relationship', 'required|/^([A-Za-z ])+$/i');
        $this->form_validation->set_rules('contact_person_mobile', 'Contact Person Mobile', 'required|exact_length[10]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->land_model->add();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Added Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Land Can\'t be Added.']));
        return FALSE;
    }

    public function get_lands($type = 'Not Processed') {
        $this->output->set_content_type('application/json');

        $user_type = 'Land Verifier';
        $user_assign_url = base_url('land/assign-land');
        $verify_url = 'land/verify-land';
        if ($type === 'Soil_Processing') {
            $user_type = 'Soil Tester';
            $user_assign_url = base_url('land/assign-land-soil-testing');
            $verify_url = 'land/verify-soil';
            $data['verfiy_requested_lands'] = $this->get_soil_verfication_requested_lands();
            $data['land_type'] = 2;
        } else if ($type === 'Rejected') {
            $data['land_type'] = 3;
            $data['verfiy_requested_lands'] = $this->get_verfication_requested_lands();
        } else {
            $data['verfiy_requested_lands'] = $this->get_verfication_requested_lands();
            $data['land_type'] = 1;
        }

        $data['employees'] = $this->get_employees();
        $data['users'] = $this->get_users_arr($user_type);
        $data['lands'] = $this->land_model->get_lands($type);
        $data['user_assign_url'] = $user_assign_url;
        $data['verify_url'] = $verify_url;
        $allocations = $this->land_model->get_all_allocated_lands();
        $allocates = [];
        foreach ($allocations as $allocation){
            $allocates[] = $allocation['land_id'];
        }
        $data['allocations'] = $allocates;
        $itemlist = $this->load->view('land/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function reapply($id) {
        $this->output->set_content_type('application/json');
        $insert_id = $this->land_model->reapply_land($id);
        if ($insert_id) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Resubmission done.', 'url' => base_url('land')]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Land Resubmission Can\'t be done. Try Again']));
        return FALSE;
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');
        $result = $this->land_model->delete($id);
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Deleted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Land can\'t be deleted.']));
        return FALSE;
    }

    public function assign_land($land_id) {
        $this->output->set_content_type('application/json');
        $result = $this->land_model->assign_land($land_id);
        if ($result) {
            $user = $this->land_model->get_land_user($land_id);
            $this->gblib->sendNotification(LAND_VERIFIER_TITLE,LAND_VERIFIER_MSG,[$user['fcm_id']],['type'=>'land']);
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Assigned Successfully.', 'url' => base_url('land/get-lands')]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Nothing gets Changed.']));
        return FALSE;
    }

    public function assign_land_soil_testing($land_id) {
        $this->output->set_content_type('application/json');
        $result = $this->land_model->assign_land_soil_testing($land_id);
        if ($result) {
            $title = 'Soil Verification';
            $msg = 'Soil Verifier is assigned for soil testing. Expert will visit soon.';
            $user = $this->land_model->get_land_user($land_id);
            $this->gblib->sendNotification($title, $msg, [$user['fcm_id']],['type'=>'land']);
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Assigned Successfully.', 'url' => base_url('land/get-lands/Soil_Processing')]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Nothing gets Changed.', 'url' => base_url('land/get-lands/Soil_Processing')]));
        return FALSE;
    }

    public function verify_land($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $land = $this->land_model->get_land($id);
        if (empty($land)) {
            redirect(base_url('land'));
            die;
        }
        $data['upload_url'] = base_url('land/upload_photo');
        $data['docs_upload_url'] = base_url('land/upload_docs');
        $data['organizations'] = $this->get_organization_arr();
        $data['units'] = $this->get_land_unit_arr();
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = $this->get_cities_arr();
        $data['users'] = $this->get_users_search_dropdown();
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'land';
        $data['active'] = 'land';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $data['land'] = $land;

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('land/verify-land', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/commons/dropzone');
        $this->load->view('scripts/land/script');
        $this->load->view('scripts/land/supported-docs-script');
        $this->load->view('commons/endpage');
    }

    public function do_verify_land() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('organization_id', 'Organization Name', 'required');
        $this->form_validation->set_rules('land_area', 'Land Area', 'required|max_length[4]');
        $this->form_validation->set_rules('land_area_unit', 'Land Area Unit', 'required');
        $this->form_validation->set_rules('plant_capacity_qty', 'Capacity', 'required|max_length[4]');
        $this->form_validation->set_rules('land_address', 'Land Address', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('contact_person_relation', 'Relationship', 'required');
        $this->form_validation->set_rules('contact_person_mobile', 'Contact Person Mobile', 'required|exact_length[10]');
        $this->form_validation->set_rules('is_verified', 'Action', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->land_model->verify();
        if ($result) {
            $user = $this->land_model->get_land_user($this->input->post('land_id'));
            $title = 'Land Verification';
            $msg = 'Your Land Details/Documents are '.$this->input->post('is_verified');
            $this->gblib->sendNotification($title, $msg, [$user['fcm_id']],['type'=>'land']);
            if($this->input->post('is_verified')==='Approved') {
                $msg = 'Land Verified Successfully';
            }elseif($this->input->post('is_verified')==='Rejected') {
                $msg = 'Land Rejected';
            }
            $this->output->set_output(json_encode(['result' => 1, 'msg' => $msg]));
            return FALSE;
        }

        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Land Can\'t be Verified.']));
        return FALSE;
    }

    public function verify_soil($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $land = $this->land_model->get_land_for_soil($id);
        if (empty($land)) {
            redirect(base_url('land'));
            die;
        }
        $data['upload_url'] = base_url('land/upload-photo');
        $data['docs_upload_url'] = base_url('land/upload-docs');
        $data['organizations'] = $this->get_organization_arr();
        $data['units'] = $this->get_land_unit_arr();
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = $this->get_cities_arr();
        $data['soils'] = $this->get_soil_type_arr();
        $data['trees'] = $this->get_tree_arr();
        $data['users'] = $this->get_users_search_dropdown();
        $data['result'] = $this->land_model->get_tree_suggestion($id);
        $data['itemlist'] = $this->load->view('land/tree-suggestion', $data, TRUE);
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'land';
        $data['active'] = 'land';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $data['land'] = $land;
        
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('land/verify-soil', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/commons/dropzone');
        $this->load->view('scripts/land/script');
        $this->load->view('scripts/land/supported-docs-script');
        $this->load->view('commons/endpage');
    }

    public function add_tree_suggestion() {
        $this->output->set_content_type('application/json');



        $insert_id = $this->land_model->add_tree_suggestion();
        if ($insert_id === 0) {
            $this->output->set_output(json_encode(['result' => -1]));
            return FALSE;
        }
        if ($insert_id) {
            $data['result'] = $this->land_model->get_tree_suggestion($this->input->post('land_id'));
            $itemlist = $this->load->view('land/tree-suggestion', $data, TRUE);
            $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0]));
        return FALSE;
    }

    public function delete_tree_suggestion($id, $land_id) {
        $this->output->set_content_type('application/json');
        $result = $this->land_model->delete_tree_suggestion($id);
        if ($result) {
            $data['result'] = $this->land_model->get_tree_suggestion($land_id);
            $itemlist = $this->load->view('land/tree-suggestion', $data, TRUE);
            $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
            return FALSE;
        }
    }

    public function do_verify_soil() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('organization_id', 'Organization Name', 'required');
        $this->form_validation->set_rules('land_area', 'Land Area', 'required|max_length[4]');
        $this->form_validation->set_rules('land_area_unit', 'Land Area Unit', 'required');
        $this->form_validation->set_rules('plant_capacity_qty', 'Capacity', 'required|max_length[4]');
        $this->form_validation->set_rules('land_address', 'Land Address', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required|regex_match[/[a-zA-Z ]*/]');
        $this->form_validation->set_rules('contact_person_relation', 'Relationship', 'required');
        $this->form_validation->set_rules('contact_person_mobile', 'Contact Person Mobile', 'required|exact_length[10]');
        $this->form_validation->set_rules('comments', 'Comment', 'required');
        $this->form_validation->set_rules('soil_type', 'Soil Type', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        if(!$this->land_model->is_suggestion_added($this->input->post('land_id'))){
            $this->output->set_output(json_encode(['result'=>-2,'msg'=>'Please Add Tree Suggestions for this Land']));
            return FALSE;
        }
        $result = $this->land_model->verify_soil();
        if ($result) {
            $user = $this->land_model->get_land_user($this->input->post('land_id'));
            $msg_title = 'Soil Verification';
            $msg = 'Your land/soil is '.$this->input->post('is_verified').'. Plant allocation will be done soon.';
            $this->gblib->sendNotification($msg_title, $msg, [$user['fcm_id']],['type'=>'land']);
            if($this->input->post('is_verified')==='Approved') {
                $msg = 'Soil Test Verified';
            }else {
                $msg = 'Soil Test Rejected';
            }
            $this->output->set_output(json_encode(['result' => 1, 'msg' => $msg]));
            return FALSE;
        }

        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Soil Can\'t be Verified.']));
        return FALSE;
    }

    public function view_detail($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $land = $this->land_model->get_land_detail($id);

        if (empty($land)) {
            redirect(base_url('land'));
            die;
        }
        $data['employees'] = $this->get_employees();
        # Land Detail
        $data['land'] = $land;

        # Soil Detail
        if (!empty($land['soil_type_id'])) {
            $data['soil_name'] = $this->land_model->get_soil_detail($land['soil_type_id'])['soil_name'];
        }

        # Plant Suggesstions
        $data['plants'] = $this->land_model->get_plant_suggesstions($id);

        #docs
        $data['docs'] = $this->land_model->get_docs($id);

        $data['title'] = 'View Land Detail | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'land';
        $data['active'] = 'land';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('land/detail-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/land/script');
        $this->load->view('commons/endpage');
    }

}
