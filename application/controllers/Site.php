<?php
class Site extends CI_Controller {
    private $settings;
    
    private function _is_login() {
        return $this->session->userdata('gbemail');
    }
    
    public function __construct() {
        parent::__construct();
        $this->load->library('Gblib');
        $this->load->model(['employee_model','settings_model','order_model']);
        $this->settings = $this->gblib->get_all_site_settings();
    }

    public function index() {
        if($this->_is_login()) {
            redirect('dashboard');
            die;
        }
        
        $data['title'] = 'Admin Login | '.$this->settings['SITE_NAME'];
        $this->load->view('commons/header', $data);
        $this->load->view('site/login');
        $this->load->view('commons/footer');
        $this->load->view('scripts/site/script');
        $this->load->view('commons/endpage');
    }

    public function do_login() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $result = $this->employee_model->is_auth_employee();

        if ($result) {
            $this->session->set_userdata('gbemail', $this->input->post('email'));
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Successfull Login', 'url' => base_url('dashboard')]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Invalid Username/Password']));
        }
        return FALSE;
    }

    private function _organize_plant_count($result) {
        $arr = [];
        foreach ($result as $row) {
            $arr[$row['order_id']] = $row['allocated_plants'];
        }
        return $arr;
    }
    public function dashboard() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = "Dashboard | ".$this->settings['SITE_NAME'];
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['gbemail'] = $this->session->userdata('gbemail');
        $data['active'] = 'dashboard';
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $this->load->model('land_model');
        $total_lands = $this->land_model->get_total_lands();
        $this->load->model('user_model');
        $total_users = $this->user_model->get_total_users();
        $this->load->model('order_model');
        $allocated_plants = $this->order_model->get_plants_allocated();
        $total_orders = $this->order_model->get_no_of_total_orders();
        $orders = $this->order_model->get_new_orders();
        
        $result = $this->order_model->get_allocations();
        $data['allocated_plants'] = $this->_organize_plant_count($result);
        $modes = ['Monthly' => '+30 days', 'Quarterly' => '+4 months', 'Half Yearly' => '+6 months', 'Yearly' => '+12 months'];
        $dues = [];
        $i = 0;
        foreach ($orders as $order) {
            if ($order['payment_mode'] !== 'One Time') {
                if (!empty($order['last_payment_date'])) {
                    $due_date = strtotime($modes[$order['payment_mode']], strtotime(str_replace('/', '-', $order['last_payment_date'])));
                } else {
                    $due_date = '';
                }
            } else {
                $due_date = '';
            }
            $payment = $this->order_model->get_order_payment_status($order['order_id']);
            $orders[$i]['payment_type'] = $payment['payment_type'];
            $orders[$i]['payment_status'] = $payment['payment_status'];
            $orders[$i]['payment_id'] = $payment['id'];
            $order['payment_type'] = $payment['payment_type'];
            $order['payment_status'] = $payment['payment_status'];
            $order['payment_id'] = $payment['id'];
            if (empty($due_date)) {
                $orders[$i]['due_date'] = 'One Time Paid';
                $order['due_date'] = 'One Time Paid';
            } else {
                $order['due_date'] = $orders[$i]['due_date'] = empty($due_date) ? date('d/m/Y') : date('d/m/Y', $due_date);
            }
            $i++;
            if (time() > $due_date) {
                $dues[] = $order;
            }
        }
        
        
        $data['total_lands'] = $total_lands;
        $data['users_count'] = $total_users;
        $data['total_plants_allocated'] = $allocated_plants;
        $data['total_orders'] = $total_orders;
        $data['new_orders'] = $orders;
        
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('includes/dashboard-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/site/script');
        $this->load->view('commons/endpage');
    }

    public function logout() {
        $this->session->unset_userdata('gbemail');
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function profile() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = "Profile | ".$this->settings['SITE_NAME'];
        $data['site_name'] = $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;

        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('includes/profile-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/employee/scripts');
        $this->load->view('commons/endpage');
    }

    public function update_profile() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('firstname', 'Firstname', 'required||regex_match[/^([A-Za-z ])+$/i]|regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('lastname', 'Lastname', 'required||regex_match[/^([A-Za-z ])+$/i]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|exact_length[10]|regex_match[/^\d{10}$/]|greater_than[0]');
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return false;
        }

        $affected_rows = $this->employee_model->update_profile($this->session->userdata('gbemail'));
        if ($affected_rows == 1) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Your Profile is Updated Successfully']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return false;
    }

    public function change_password() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = "Change Password | ".$this->settings['SITE_NAME'];
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['gbemail'] = $this->session->userdata('gbemail');
        $email = $this->session->userdata('gbemail');
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', '', TRUE);
        $data['page'] = $this->load->view('includes/change-password-view', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/employee/scripts');
        $this->load->view('commons/endpage');
    }

    public function do_change_password() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm-password', 'Confirm Password', 'required|min_length[6]|matches[password]');
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return false;
        }

        $affected_rows = $this->employee_model->update_password($this->session->userdata('gbemail'));
        if ($affected_rows == 1) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Your Password Updated Successfully']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return false;
    }
    
    public function do_change_forgot_password() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm-password', 'Confirm Password', 'required|min_length[6]|matches[password]');
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return false;
        }

        $affected_rows = $this->employee_model->update_password_by_mobile($this->session->userdata('otp-mobile'));
        if ($affected_rows == 1) {
            $this->session->unset_userdata('otp-mobile');
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! Your Password Updated Successfully']));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Nothing gets Changed.']));
        }
        return false;
    }
    public function forgot_password() {
        $data['title'] = 'Forgot Password | '.$this->settings['SITE_NAME'];
        $this->load->view('commons/header', $data);
        $this->load->view('site/page');
        $this->load->view('commons/footer');
        $this->load->view('scripts/site/script');
        $this->load->view('commons/endpage');
    }
    
    public function load_page($page) {
        $this->output->set_content_type('application/json');
        $output = $this->load->view('site/includes/'.$page,'',TRUE);
        $this->output->set_output(json_encode(['result'=>1,'output'=>$output]));
        return FALSE;
    }
    public function send_otp() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|exact_length[10]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array(),'url'=>  base_url('match-otp')]));
            return false;
        }
        
        $result = $this->employee_model->send_otp($this->input->post('mobile'));
        
        if ($result !== -1) {
            $this->session->set_userdata('otp-mobile',$this->input->post('mobile'));
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! OTP Sent Successfully','url'=>  base_url('load-page/match-otp')]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! Mobile no. Not Registerd. Try Again']));
        }
        return false;
    }
    
    public function resend_otp() {
        $this->output->set_content_type('application/json');
        $result = $this->employee_model->send_otp($this->session->userdata('otp-mobile'));
        if ($result !== -1) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success!!! OTP Sent Successfully','url'=>  base_url('load-page/match-otp')]));
        } else {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Sorry!!! OTP Can\'t be Sent. Try Again']));
        }
        return false;
    }
    
    public function match_otp() {
        $this->output->set_content_type('application/json');
        $result = $this->employee_model->match_otp($this->session->userdata('otp-mobile'));
        
        if($result) {
            $this->output->set_output(json_encode(['result' => 1,'msg' => 'Otp Matched','url' => base_url('load-page/change-password')]));
        }else {
            $this->output->set_output(json_encode(['result' => -1,'msg' => 'Otp doesn\'t Match. Try Again.']));
        }
        return FALSE;
    }
}
