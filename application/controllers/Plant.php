<?php
class Plant extends CI_Controller {

    private $settings;

    public function __construct() {
        parent::__construct();
        $this->load->model(['employee_model', 'soil_model', 'plant_model']);
        $this->load->library(['location', 'gblib']);
        $this->settings = $this->gblib->get_all_site_settings();
    }

    private function _is_login() {
        return $this->session->userdata('gbemail');
    }

    private function get_soil_type_arr() {
        $result = $this->soil_model->get_soil_types();
        $soils = ['' => '--Select--'];
        foreach ($result as $row) {
            $soils[$row['soil_id']] = $row['soil_name'];
        }
        return $soils;
    }

    public function index() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'plant';
        $data['active'] = 'plant';
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('plant/plant', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/plant/script');
        $this->load->view('commons/endpage');
    }

    public function upload_photo() {
        $this->output->set_content_type('application/json');
        $this->load->library('Upload_lib');
        $result = $this->upload_lib->upload_file('plant', 'file');
        $this->output->set_output(json_encode($result));
        return FALSE;
    }
    
    public function add_plant() {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('plant/upload-photo');
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'plant';
        $data['active'] = 'add-plant';
        $data['soil'] = $this->get_soil_type_arr();
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('plant/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/commons/dropzone');
        $this->load->view('scripts/plant/script');
        $this->load->view('commons/endpage');
    }

    public function do_add_plant() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('plant_name', 'Plant Name', 'required');
        $this->form_validation->set_rules('biological_name', 'Biological Name', 'required');
        $this->form_validation->set_rules('max_height', 'Maximum Height', 'required|regex_match[/[0-9]/]');
        $this->form_validation->set_rules('tree_type', 'Tree Type', 'required');
        $this->form_validation->set_rules('soil_type', 'Soil Type', 'required');
        $this->form_validation->set_rules('in_stock', 'In Stock', 'required');
        $this->form_validation->set_rules('plant_description', 'Plant Description', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $insert_id = $this->plant_model->add();

        if ($insert_id) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Plant Added Successfully']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Plant Can\'t be Added']));
        return FALSE;
    }

    public function get_plants() {
        $this->output->set_content_type('application/json');
        $data['plants'] = $this->plant_model->get_plants();
        $itemlist = $this->load->view('plant/includes/list', $data, TRUE);
        $this->output->set_output(json_encode(['result' => 1, 'itemlist' => $itemlist]));
        return FALSE;
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');
        $result = $this->plant_model->delete($id);
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Plant Deleted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Plant can\'t be deleted.']));
        return FALSE;
    }

    public function change_status($id) {
        $this->output->set_content_type('application/json');
        $result = $this->plant_model->change_status($id);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Status Change Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Status Can\'t be Changed.']));
        return FALSE;
    }

    public function edit($id) {
        if (!$this->_is_login()) {
            redirect(base_url());
            die;
        }
        $data['upload_url'] = base_url('plant/upload-photo');
        $data['title'] = 'Admin Login | ' . $this->settings['SITE_NAME'];
        $email = $this->session->userdata('gbemail');
        $data['site_name'] = $this->settings['SITE_NAME'];
        $data['name'] = $this->employee_model->get_name($email);
        $data['email'] = $email;
        $data['menu_id'] = 'plant';
        $data['active'] = 'add-plant';
        $data['soil'] = $this->get_soil_type_arr();
        $data['plant'] = $this->plant_model->get_plant($id);
        $data['user_info'] = $this->employee_model->get_profile($email);
        $this->load->view('commons/header', $data);
        $this->load->view('commons/top-navbar', $data);
        $data['sidebar'] = $this->load->view('commons/sidebar', $data, TRUE);
        $data['page'] = $this->load->view('plant/add', $data, TRUE);
        $this->load->view('commons/dashboard', $data);
        $this->load->view('commons/footer');
        $this->load->view('scripts/commons/dropzone');
        $this->load->view('scripts/plant/script');
        $this->load->view('commons/endpage');
    }

    public function do_update_plant() {
        $this->output->set_content_type('application/json');
        $this->form_validation->set_rules('plant_name', 'Plant Name', 'required');
        $this->form_validation->set_rules('biological_name', 'Biological Name', 'required');
        $this->form_validation->set_rules('max_height', 'Maximum Height', 'required');
        $this->form_validation->set_rules('tree_type', 'Tree Type', 'required');
        $this->form_validation->set_rules('soil_type', 'Soil Type', 'required');
        $this->form_validation->set_rules('in_stock', 'In Stock', 'required');
        $this->form_validation->set_rules('plant_description', 'Plant Description', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->output->set_output(json_encode(['result' => 0, 'errors' => $this->form_validation->error_array()]));
            return FALSE;
        }
        $affected_rows = $this->plant_model->update();

        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Plant Updated Successfully']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Nothing gets Changed']));
        return FALSE;
    }

}
