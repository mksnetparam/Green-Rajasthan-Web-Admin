<?php
class User extends CI_Controller
{
    private $cities;
    private $states;
    private $countries;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model(['employee_model', 'master_model', 'settings_model', 'user_model', 'land_model', 'order_model', 'service_model']);
        $this->load->library(['location', 'gblib', 'fcmlib']);
        $this->countries = $this->location->get_countries_array();
        $this->states = $this->location->get_states_array();
        $this->cities = $this->location->get_cities_array();
        $this->settings = $this->gblib->get_all_site_settings();
        define('ROWS_PER_PAGE', $this->settings['ROWS_PER_PAGE']);
    }
    private function _is_login()
    {
        return $this->session->userdata('gbemail');
    }
    private function get_countries_arr()
    {
        $result = $this->location->get_countries();

        $countries[''] = '---Select Country---';
        foreach ($result as $row) {
            $countries[$row['country_id']] = $row['country_name'];
        }
        return $countries;
    }
    private function get_states_arr()
    {
        $result = $this->location->get_states();
        $states[] = [];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }
    private function get_cities_arr()
    {
        $result = $this->location->get_cities();
        $cities[] = [];
        foreach ($result as $row) {
            $cities[$row['city_id']] = $row['city_name'];
        }
        return $cities;
    }
    private function get_states_by_country_arr($country_id)
    {
        $result = $this->location->get_states_by_country($country_id);
        $states = [];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        return $states;
    }
    private function get_cities_by_states_arr($state_id)
    {
        $result = $this->location->get_cities_by_state($state_id);
        $states = [];
        foreach ($result as $row) {
            $states[$row['city_id']] = $row['city_name'];
        }
        return $states;
    }
    public function get_states_by_country_json($id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->location->get_states_by_country($id);
        $states[0] = ['---Select State---'];
        foreach ($result as $row) {
            $states[$row['state_id']] = $row['state_name'];
        }
        $this->output->set_output(json_encode(['result' => 1, 'states' => $states]));
        return FALSE;
    }
    public function get_city_by_state_json($id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->location->get_cities_by_state($id);
        $cities[0] = ['--Select City--'];
        foreach ($result as $row) {
            $cities[$row['city_id']] = $row['city_name'];
        }
        $this->output->set_output(json_encode(['result' => 1, 'cities' => $cities]));
        return FALSE;
    }
    public function get_roles()
    {
        $roles[''] = 'Assign Role';
        $result = $this->employee_model->get_admin_roles();
        foreach ($result as $row) {
            $roles[$row['role_id']] = $row['role_name'];
        }
        return $roles;
    }
    public function get_user_role()
    {
        $roles = [];
        $result = $this->user_model->get_user_roles();
        foreach ($result as $row) {
            $roles[$row['user_id']][] = $row['role_name'];
        }
        return $roles;
    }
    ##############################
    #########   Users     ########
    ##############################
    public function index()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('master/upload-photo');
        $data['cities'] = $this->cities;
        $data['countries'] = $this->countries;
        $data['states'] = $this->states;
//        $data['countries'] = $this->get_countries_arr();
//        $data['states'] = $this->get_states_arr();

        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/index', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('scripts/commons/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    public function add()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('user/upload-photo');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = '';

        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('scripts/user/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    public function do_add_user()
    {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('name', 'Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^\d{10}$/]|exact_length[10]|is_unique[tbl_users.mobile]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|is_unique[tbl_users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('address', 'Address', 'required|max_length[150]');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('city_id', 'City', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $result = $this->user_model->add();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'User Added Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'User can\'t be Added.']));
        return FALSE;
    }
    public function get_users()
    {
        $this->output->set_content_type('application/json');
        $data['cities'] = $this->cities;
        $data['countries'] = $this->countries;
        $data['states'] = $this->states;
        $data['users'] = $this->user_model->get_users();
        $data['roles'] = $this->get_roles();
        $data['role'] = $this->get_user_role();
        $itemlist = $this->load->view('user/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }
    public function upload_photo()
    {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('user', 'file');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }
    public function change_status($id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->user_model->change_status($id);

        if ($result) {
            if ($this->input->post('status') === 'No') {
                $user = $this->user_model->get_fcm_id($id);
                $this->gblib->sendNotification("User Inactive Alert", "You have been inactive by admin.", [$user['fcm_id']], ['type' => 'inactive', 'user_id' => $id]);
            }
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Status Change Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Status Can\'t be Changed.']));
        return FALSE;
    }
    public function delete($id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->user_model->delete($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'User Deleted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 1, 'msg' => 'User Can\'t be deleted.']));
        return FALSE;
    }
    public function edit($id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $user = $this->user_model->get_user($id);
        if (empty($user)) {
            redirect(base_url('user'));
            die;
        }
        $data['user'] = $user;
        $data['upload_url'] = base_url('user/upload-photo');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_by_country_arr($user['country_id']);
        $data['cities'] = $this->get_cities_by_states_arr($user['state_id']);
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('scripts/user/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    public function do_update_user()
    {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('name', 'Name', 'required|regex_match[/[A-Za-z ]/]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^\d{10}$/]|exact_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('address', 'Address', 'required|max_length[150]');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('city_id', 'City', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $affected_rows = $this->user_model->update();
        if ($affected_rows === -1) {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Email Already Exists.']));
            return FALSE;
        }
        if ($affected_rows === -2) {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Mobile Already Exists.']));
            return FALSE;
        }
        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'User Details updated Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Nothing gets Changed.']));
        return FALSE;
    }
    public function view_detail($id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $user = $this->user_model->get_user($id);
        if (empty($user)) {
            redirect(base_url('user'));
            die;
        }
        $data['user'] = $user;
        $data['upload_url'] = base_url('user/upload-photo');
        $data['countries'] = $this->countries;
        $data['states'] = $this->states;
        $data['cities'] = $this->cities;
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/detail-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('commons/endpage');
    }
    public function donation_payments()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Donation Payments | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'donation-payments';
        $data['active'] = 'donation-payments';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('donation/donation-payments', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts');
        $this->load->view('commons/endpage');
    }
    public function get_donation_payments()
    {
        $this->output->set_content_type('application/json');
        $data['payments'] = $this->user_model->get_donation_payments();
        $page = $this->load->view('donation/includes/payments-list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'page' => $page]));
        return FALSE;
    }
    public function view_donation_payment_detail($date, $user_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['details'] = $this->user_model->get_payment_datewise($date, $user_id);
        $data['title'] = 'Donation Payments | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'donation-payments';
        $data['active'] = 'donation-payments';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('donation/includes/payment-detail', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts');
        $this->load->view('commons/endpage');
    }
    public function update_payment_status($id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['payment'] = $this->user_model->get_payment_info($id);
        $data['title'] = 'Donation Payments | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'donation-payments';
        $data['active'] = 'donation-payments';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('donation/update-payment-status', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts');
        $this->load->view('commons/endpage');
    }
    public function do_update_payment_status()
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('payment_status', 'Payment Status', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $result = $this->user_model->update_payment_status();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Payment Details saved Successfully', 'url' => base_url('user/donation-payments')]));
            $fcm_id = $this->fcmlib->get_fcm_id($this->input->post('user_id'));
            $this->gblib->sendNotification('Payment Confirmation', 'Payment ' . $this->input->post('payment_status'), [$fcm_id], ['type' => 'order']);
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Payment Details can\'t be Saved', 'url' => base_url('user/donation-payments')]));
        return FALSE;
    }
    public function user_lands($user_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $user = $this->user_model->get_user($user_id);
        $lands = $this->land_model->get_user_lands($user_id);
        $data['user'] = $user;
        $data['lands'] = $lands;
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/lands', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('scripts/user/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    public function user_orders($user_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $user = $this->user_model->get_user($user_id);
        $orders = $this->order_model->get_user_orders($user_id);
        $data['user'] = $user;
        $data['orders'] = $orders;
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "User Master | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'user';
        $data['active'] = 'user';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/orders', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('scripts/user/dropzone', $data);
        $this->load->view('commons/endpage');
    }
    public function add_role($user_id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->user_model->add_role($user_id);
        if ($result === FALSE) {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Role is Already Assigned to this User']));
            return FALSE;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Role Added Successfully']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Role can\'t be Added']));
        return FALSE;
    }
    private function __get_payments()
    {
        $result = $this->user_model->get_all_user_payment();
        $payments = [];
        foreach ($result as $row) {
            if (isset($payments[$row['user_id']])) {
                $payments[$row['user_id']] += $row['amount'];
            } else {
                $payments[$row['user_id']] = $row['amount'];
            }
        }
        return $payments;
    }
    public function tree_friend()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Tree Friends | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'nature-lover';
        $data['active'] = 'nature-lover';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/tree-friend', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('commons/endpage');
    }
    public function get_promoters()
    {
        $this->output->set_content_type('application/json');
        $data['users'] = $this->service_model->get_promoters();
        $data['payments'] = $this->__get_payments();
        $itemlist = $this->load->view('user/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }
    public function get_tree_friends()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_all_tree_friends();
        $size = count($result);
        for ($i = 0; $i < $size; $i++) {
            $number_of_referal_plants = $this->service_model->get_total_plants($result[$i]['referal_code']);
            $result[$i]['number_of_referal_plants'] = empty($number_of_referal_plants) ? 0 : $number_of_referal_plants;
        }

        $data['tfriends'] = $result;
        $data['payments'] = $this->__get_payments();
        $tree_friends = $this->load->view('user/includes/tree-friends-list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $tree_friends]));
        return FALSE;
    }

    public function copartner()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['title'] = "Co-partner | " . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'nature-lover';
        $data['active'] = 'nature-lover';
        $data['user_info'] = $this->employee_model->get_profile($email);


        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('user/copartner', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/user/scripts', $data);
        $this->load->view('commons/endpage');
    }
	
    public function get_copartner()
    {
        $this->output->set_content_type('application/json');
        $data['countries'] = $this->get_countries_arr();
        $data['states'] = $this->get_states_arr();
        $data['cities'] = $this->get_cities_arr();
        $data['payments'] = $this->__get_payments();

        $result = $this->service_model->get_promoters();
        $size = count($result);
        for ($i = 0; $i < $size; $i++) {
            $number_of_referal_plants = $this->service_model->get_total_plants($result[$i]['referal_code']);
            $result[$i]['number_of_referal_plants'] = empty($number_of_referal_plants) ? 0 : $number_of_referal_plants;
            $number_of_plants = $this->service_model->get_total_planted_plants($result[$i]['user_id']);
            $result[$i]['number_of_plants'] = empty($number_of_plants) ? 0 : $number_of_plants;
        }
        $data['copartners'] = $result;
        $copartners = $this->load->view('user/includes/copartners-list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $copartners]));
        return FALSE;
    }

    public function allocate_area_modal($id)
    {
        $this->output->set_content_type('application/json');
        $data['modal_title'] = 'Allocate Area';
        $data['countries'] = $this->get_countries_arr();
        $data['role_id'] = $id;
        $data['modal_body'] = $this->load->view('user/includes/allocate-area', $data, TRUE);
        $modal = $this->load->view('commons/modal-format', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'modal' => $modal]));
        return FALSE;
    }

    public function allocate_area($role_id)
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('country_id', 'Country', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $affected_rows = $this->user_model->allocate_area($role_id);
        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Area Assigned Successfully']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Area can\'t be assigned']));
        return FALSE;
    }

    public function payment_modal($user_id, $due_amount)
    {
        $this->output->set_content_type('application/json');
        $data['modal_title'] = 'Payment';
        $data['user_id'] = $user_id;
        $data['due_amount'] = $due_amount;
        $data['modal_body'] = $this->load->view('user/includes/payment-form', $data, TRUE);
        $modal = $this->load->view('commons/modal-format', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'modal' => $modal]));
        return FALSE;
    }

    public function do_user_payment()
    {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('amount', 'Amount', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $due_amount = $this->input->post('due_amount');
        $amount = $this->input->post('amount');

        if ($amount >= $due_amount) {
            $this->output->set_output(json_encode(['result' => -2, 'msg' => 'Amount must not be greater than Due Amount']));
            return FALSE;
        }

        $insert_id = $this->user_model->add_user_payment();
        if ($insert_id) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Payment Successful.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Payment not Successful']));
        return FALSE;
    }
}