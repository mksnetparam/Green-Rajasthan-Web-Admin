<?php
class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['order_model', 'employee_model', 'organization_model', 'land_model', 'user_model']);
        $this->load->library(['location', 'gblib', 'fcmlib']);
        $this->settings = $this->gblib->get_all_site_settings();
    }

    private function _is_login()
    {
        return $this->session->userdata('gbemail');
    }

    private function __organize_organization($arr)
    {
        $organizations = [];
        foreach ($arr as $row) {
            $organizations[$row['organization_id']] = $row['organization_name'];
        }
        return $organizations;
    }

    private function __organize_land($arr)
    {
        $lands = [];
        foreach ($arr as $row) {
            $lands[$row['land_id']] = $row['land_address'] . '-' . $row['land_area'] . ' ' . $row['land_area_unit'];
        }
        return $lands;
    }

    private function __get_plant_suggestion($land_id)
    {
        $result = $this->land_model->get_plant_suggesstions($land_id);
        return $result;
    }

    public function get_organization_land($organization_id)
    {
        $this->output->set_content_type('application/json');
        $result = $this->order_model->get_land_allocations();
        $data['user_id'] = $this->input->post('user_id');
        $data['order_id'] = $this->input->post('order_id');
        $data['allocated_plants_land'] = $this->_organize_land_plant_count($result);
        $data['lands'] = $this->land_model->get_organization_land($organization_id);
        $lands = $this->load->view('order/includes/organization-lands', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'lands' => $lands]));
        return FALSE;
    }

    public function index($filter = NULL)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['filter'] = $filter;
        $data['title'] = 'Orders | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/orders', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    private function _organize_plant_count($result)
    {
        $arr = [];
        foreach ($result as $row) {
            $arr[$row['order_id']] = $row['allocated_plants'];
        }
        return $arr;
    }

    private function _organize_land_plant_count($result)
    {
        $arr = [];
        foreach ($result as $row) {
            $arr[$row['land_id']] = $row['allocated_plants'];
        }
        return $arr;
    }

    public function get_orders($type = NULL)
    {
        $this->output->set_content_type('application/json');
        $result = $this->order_model->get_allocations();
        $data['allocated_plants'] = $this->_organize_plant_count($result);
        $modes = ['Monthly' => '+30 days', 'Quarterly' => '+4 months', 'Half Yearly' => '+6 months', 'Yearly' => '+12 months'];
        $orders = $this->order_model->get_orders(((isset($type) && ($type == 'Due' || $type == 'All')) ? NULL : $type));
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
        if (!isset($type)) {
            $type = '';
        }
        if ($type === 'Due') {
            $data['orders'] = $dues;
        } else {
            $data['orders'] = $orders;
        }
        $itemlist = $this->load->view('order/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function allocate($order_id, $user_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Orders | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'view-land-order';
        $data['active'] = 'order';
        $data['order_id'] = $order_id;
        $data['user_id'] = $user_id;
        $data['user_info'] = $this->employee_model->get_profile($email);
        $order = $this->order_model->get_order($order_id);
        $data['no_of_allocated_plants'] = $this->order_model->get_no_of_plants_allocated($order_id);
//        print_r($this->land_model->get_organization_land($order['organization_id']));
        $data['lands'] = $this->land_model->get_organization_land($order['organization_id']);
        $data['order'] = $order;
        $data['organizations'] = $this->__organize_organization($this->organization_model->get_organization_dropdown());
        $result = $this->order_model->get_land_allocations();
        $data['allocated_plants_land'] = $this->_organize_land_plant_count($result);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/allocation-lands', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function allocation_plant_to_land($order_id, $land_id, $land_user_id, $user_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Orders | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $data['land'] = $this->land_model->get_land_detail($land_id);
        $data['order'] = $this->order_model->get_order($order_id);
        $data['no_of_allocated_plants'] = $this->order_model->get_no_of_plants_allocated($order_id);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['land_id'] = $land_id;
        $data['land_user_id'] = $land_user_id;
        $data['user_id'] = $user_id;
        $data['order_id'] = $order_id;
        $data['result'] = $this->__get_plant_suggestion($land_id);
        $data['land_allocated_plants'] = $this->land_model->get_total_allocated_plants($land_id);
        $data['suggestions'] = $this->load->view('order/includes/plant-suggestion', $data, TRUE);
        $data['page'] = $this->load->view('order/allocation-plant-to-lands', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function do_allocate()
    {
        $this->output->set_content_type('application/json');
        $this->load->library('ciqrcode');


        $plants = $this->input->post('plants');
        $flag = false;
        foreach ($plants as $plant) {
            if (!empty($plant)) {
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => ['Plants required']]));
            return FALSE;
        }

        $this->load->model('user_model');
        $insert_id = $this->order_model->do_allocate();
        $land_id = $this->input->post('land_id');
        $order_id = $this->input->post('order_id');
        if ($insert_id) {
            $land_user_id = $this->input->post('land_user_id');
            $user_id = $this->input->post('user_id');
            $land_user_fcm_id = $this->user_model->get_fcm_id($land_user_id);
            $user_fcm_id = $this->user_model->get_fcm_id($user_id);
            $this->gblib->sendNotification('Plant Processing', 'Plant Allocated to your land', [$land_user_fcm_id['fcm_id']], ['type' => 'land', 'land_id' => $land_id]);
            $this->gblib->sendNotification('Plant Processing', 'Your Plant Dispatched for Plantation ', [$user_fcm_id['fcm_id']], ['type' => 'order', 'order_id' => $order_id]);
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Plant Allocated Successfully.', 'order_id' => $insert_id]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Plant can\'t be Allocated']));
        return FALSE;
    }

    public function print_qrcode($order_id)
    {
        $this->load->library('ciqrcode');

        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Print Order QR-Code | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);

        $result = $this->order_model->get_order_plants_barcode($order_id);
        $data['result'] = $result;
        $this->load->view('order/qr-code', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function print_land_qrcode($land_id)
    {
        $this->load->library('ciqrcode');

        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Print Order QR-Code | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);

        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);

        $result = $this->order_model->get_land_plants_barcode($land_id);
        $land = $this->order_model->land_detail($land_id);
        $data['result'] = $result;
        $data['land'] = $land;
        $this->load->view('order/qr-code-land', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function order_by_land()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Orders | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'view-land-order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/land-orders', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function get_land_orders()
    {
        $this->output->set_content_type('application/json');
        $result = $this->land_model->get_land_orders();
        $result2 = $this->order_model->get_land_allocated();
        $result3 = $this->order_model->get_land_planted();
        $data['allocated'] = $this->_organize_land_plant_count($result2);
        $data['planted'] = $this->_organize_land_plant_count($result3);
        $data['orders'] = $result;
        $itemlist = $this->load->view('order/includes/land-list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function change_order_status($order_id)
    {
        $this->output->set_content_type('application/json');

        $status = $this->input->post('order_status');
        $remaining_plants = $this->input->post('remaining_plants');

        if ($status === 'Processed') {
            if (!$this->order_model->is_all_plant_planted($order_id)) {
                $this->output->set_output(json_encode(['result' => 0, 'msg' => 'All Plants are not Planted', 'url' => base_url('order/get_orders')]));
                return;
            }
        } else if ($status === 'Not Processed') {
            if ($this->order_model->is_all_plant_planted($order_id)) {
                $this->output->set_output(json_encode(['result' => 0, 'msg' => 'All Plants are already Planted', 'url' => base_url('order/get_orders')]));
                return;
            }
        }
        $affected_rows = $this->order_model->change_order_status($order_id);
        $user_id = $this->order_model->get_order($order_id)['user_id'];
        $fcm_id = $this->user_model->get_fcm_id($user_id)['fcm_id'];
        if ($affected_rows) {
            $this->gblib->sendNotification('Plant Order', 'Your Plant Order ' . $order_id . ' is ' . $status, [$fcm_id], ['type' => 'order']);
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Order Status Changed Successfully.', 'url' => base_url('order/get_orders')]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Nothing gets Changed.']));
        return FALSE;
    }

    public function update_payment_info($payment_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['payment'] = $this->order_model->get_payment_info($payment_id);
        $data['title'] = 'Update Payment Info | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/includes/payment-info', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function do_update_payment_info()
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('payment_status', 'Payment Status', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }

        $result = $this->order_model->update_payment_info();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Payment Details saved Successfully', 'url' => base_url('order')]));
            $fcm_id = $this->fcmlib->get_fcm_id_by_order($this->input->post('order_id'));
            $this->gblib->sendNotification('Payment Confirmation', 'Payment ' . $this->input->post('payment_status') . ' for the order# ' . $this->input->post('order_id'), [$fcm_id], ['type' => 'order']);
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Payment Details can\'t be Saved', 'url' => base_url('order')]));
        return FALSE;
    }

    public function view_payments($order_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['payments'] = $this->order_model->get_payments($order_id);
        $data['title'] = 'Update Payment Info | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/view-payment', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function get_order_plants($order_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $plants = $this->order_model->get_order_plants($order_id);
        $data['plants'] = $plants;
        $images = [];
        foreach ($plants as $plant) {
            $result = $this->order_model->get_order_plant_images($plant['id']);
            $images[$plant['id']] = $result['image'];
        }
        $data['images'] = $images;
        $data['title'] = 'Plant Growth | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/order-plants', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function delete_allocation($order_id)
    {
        $this->output->set_content_type('application/json');
        $affected_rows = $this->order_model->delete_allocation($order_id);
        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Order Allocation deleted Successfully.', 'url' => base_url('order/get_orders')]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Nothing gets Changed.']));
        return FALSE;
    }

    public function plant_growth($plantation_id)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['images'] = $this->order_model->get_plant_growth_images($plantation_id);
        $data['title'] = 'Plant Growth | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'order';
        $data['active'] = 'order';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/plant-growth', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function filter_order($type = '')
    {
        $this->output->set_content_type('application/json');
        $modes = ['Monthly' => '+30 days', 'Quarterly' => 'After 4 months', 'Half Yearly' => 'After 6 months', 'Yearly' => 'After 1 year'];
        $orders = $this->order_model->get_orders();
        $i = 0;
        $dues = [];
        foreach ($orders as $order) {
            if ($order['payment_mode'] !== 'One Time') {
                if (!empty($order['last_payment_date'])) {
                    $due_date = strtotime($modes[$order['payment_mode']], strtotime(str_replace('/', '-', $order['last_payment_date'])));
                }
            } else {
                $due_date = '';
            }
            $payment = $this->order_model->get_order_payment_status($order['order_id']);
            $orders[$i]['payment_type'] = $payment['payment_type'];
            $orders[$i]['payment_status'] = $payment['payment_status'];
            $orders[$i]['payment_id'] = $payment['id'];
            if (empty($due_date)) {
                $orders[$i]['due_date'] = 'One Time Paid';
            } else {
                $orders[$i]['due_date'] = empty($due_date) ? date('d/m/Y') : date('d/m/Y', $due_date);
            }
            if (time() >= $due_date) {
                $dues[] = $order;
            }
            $i++;
        }
        if ($type === 'Due') {
            $data['orders'] = $dues;
        }
        $itemlist = $this->load->view('order/includes/list', $data, TRUE);

        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function payments()
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Payments | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'payments';
        $data['active'] = 'payments';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/payments', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function get_payments()
    {
        $this->output->set_content_type('application/json');
        $data['payments'] = $this->order_model->get_all_payments();
        $page = $this->load->view('order/includes/payments-list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'page' => $page]));
        return FALSE;
    }

    public function view_payment_detail($date)
    {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['details'] = $this->order_model->get_payment_datewise($date);
        $data['title'] = 'Payments | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'payments';
        $data['active'] = 'payments';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('order/includes/payment-detail', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/order/script');
        $this->load->view('commons/endpage');
    }

    public function send_fcm_notification()
    {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $result = $this->gblib->sendNotification($this->input->post('title'), $this->input->post('message'), [$this->input->post('fcm_id')], ['type' => 'noti']);
        $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Notification sent successfully.']));
        return FALSE;
    }

}
