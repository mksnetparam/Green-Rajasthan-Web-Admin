<?php
class Services extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        $this->load->model('service_model');
    }

    public function dropAll()
    {
        $this->service_model->dropAll();
    }

    #####################################
    #######     GEO Section     #########
    #####################################

    public function getCountries()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_countries('Yes');
        $countries = [];
        foreach ($result as $row) {
            $countries[] = ['country_id' => $row['country_id'], 'country_name' => $row['country_name']];
        }
        $this->output->set_output(json_encode(['result' => 1, 'countries' => $countries]));
        return FALSE;
    }

    public function getStates()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_states('Yes');
        $states = [];
        foreach ($result as $row) {
            $states[] = ['state_id' => $row['state_id'], 'state_name' => $row['state_name'], 'country_id' => $row['country_id']];
        }
        $this->output->set_output(json_encode(['result' => 1, 'states' => $states]));
        return FALSE;
    }

    public function getCities()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_cities('Yes');
        $cities = [];
        foreach ($result as $row) {
            $cities[] = ['city_id' => $row['city_id'], 'city_name' => $row['city_name'], 'state_id' => $row['state_id'], 'country_id' => $row['country_id']];
        }
        $this->output->set_output(json_encode(['result' => 1, 'cities' => $cities]));
        return FALSE;
    }

    public function getPlantCost()
    {
        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['result' => 1, 'cost' => PLANT_COST, 'display_cost' => DISPLAY_COST]));
    }

    public function getOrganizations()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('organization_model');
        $result = $this->organization_model->get_organizations('Yes');
        $organizations = [];
        foreach ($result as $row) {
            $organizations[] = ['organization_id' => $row['organization_id'],
                'organization_enroll_id' => $row['organization_enroll_id'],
                'organization_name' => $row['organization_name'],
                'contact_person' => $row['contact_person'],
                'ownership' => $row['ownership'],
                'mobile' => $row['mobile'],
                'email' => $row['email'],
                'address_line1' => $row['address_line1'],
                'address_line2' => $row['address_line2'],
                'country_name' => $row['country_name'],
                'state_name' => $row['state_name'],
                'city_name' => $row['city_name'],
                'pincode' => $row['pincode'],
                'logo' => !empty($row['logo']) ? base_url('uploads/org/' . $row['logo']) : base_url('uploads/no_image.jpg')
            ];
        }
        $this->output->set_output(json_encode(['result' => 1, 'organizations' => $organizations, 'default' => ['id' => '1', 'title' => 'Green Rajasthan']]));
        return FALSE;
    }

    public function doOrderPlant()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->order_plant();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Success']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Fail']));
        return FALSE;
    }

    public function getUserPlantOrders()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_user_plant_orders();
        $orders = [];
        foreach ($result as $row) {
            $orders[] = ['id' => $row['id'],
                'user_id' => $row['user_id'],
                'number_of_plants' => $row['number_of_plants'],
                'payment_mode' => $row['payment_mode'],
                'plant_cost' => $row['plant_cost'],
                'organization_id' => $row['organization_id'],
                'nominee_name' => $row['nominee_name'],
                'nominee_relation' => $row['nominee_relation'],
                'is_payment_done' => $row['is_payment_done']
            ];
        }

        $this->output->set_output(json_encode(['result' => 1, 'orders' => $orders]));
        return FALSE;
    }

    public function getOrderPlantDetails()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_user_ordered_plant_details();
        $plants = [];
        foreach ($result as $row) {
            $latest_image = array_pop(explode(',', $row['progress_urls']));
            $plants[] = ['id' => $row['id'], 'plant_name' => $row['plant_name'], 'plantation_date' => $row['plantation_date'], 'recent_image' => base_url('uploads/planted_trees/' . $latest_image)];
        }
        $this->output->set_output(json_encode(['result' => 1, 'plants' => $plants]));
        return FALSE;
    }

    ##################################
    ######       OTP Section    ######
    ##################################

    private function __generateOTP($mobile)
    {
        $otp = rand(100000, 999999);
//        $message = 'Your otp is ' . $otp;
        if (file_get_contents('http://api.msg91.com/api/sendhttp.php?authkey=125383APc6je0PwbHx583d6b0e&mobiles=91' . $this->input->post('mobile') . '&message=Your OTP is :' . $otp . '&route=4&sender=verify')) {
            return $this->service_model->store_otp($mobile, $otp);
        }
        return FALSE;
    }

    public function generateSignupOTP()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->is_user_exists();

        if ($result === -1) {
            $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Email Already Exists']));
            return FALSE;
        } else if ($result === -2) {
            $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Mobile Already Exists']));
            return FALSE;
        }
        $mobile = $this->security->xss_clean($this->input->post('mobile'));
        if ($this->__generateOTP($mobile)) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'OTP Sent']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'OTP not Sent']));
        return FALSE;
    }

    public function generateForgotPasswordOTP()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->is_user_mobile_exists();
        if ($result === 1) {
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            if ($this->__generateOTP($mobile)) {
                $this->output->set_output(json_encode(['result' => 1, 'msg' => 'OTP Sent']));
                return FALSE;
            }
            $this->ouptut->set_output(json_encode(['result' => -1, 'msg' => 'OTP not Sent']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Mobile no. not registered']));
        return FALSE;
    }

    public function getUserByID()
    {
        $row = $this->service_model->get_user_by_id();
        if (substr($row['image'], 0, 4) !== 'http') {
            $row['image'] = base_url('uploads/user/' . $row['image']);
        }
        $this->output->set_output(json_encode(['result' => 1, 'user_info' => $row]));
        return FALSE;
    }

    public function matchOTP()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->match_otp();

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'OTP Matched']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'OTP doesn\'t match']));
    }

    ######################################
    ######      User Section        ###### 
    ######################################

    public function doSignup()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->do_signup();

        if ($result['id']) {
            if ($result['user_info']['is_active'] === 'Yes') {
                if (substr($result['user_info']['image'], 0, 4) != 'http') {
                    $result['user_info']['image'] = base_url('uploads/user/' . $result['user_info']['image']);
                }
                $this->output->set_output(json_encode(['result' => 1, 'msg' => 'User Registered Successfully.', 'user_info' => $result['user_info']]));
                return FALSE;
            }
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Your Account is not activated. Contact Admin.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'User Registered Failed. Try Again']));
        return FALSE;
    }

    public function doLogin()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->do_login();
        if ($result) {
            if ($result['is_active'] === 'Yes') {
                $result['image'] = base_url('uploads/user/' . $result['image']);
                $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Login Successfull.', 'user_info' => $result]));
                return FALSE;
            }
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Your Account is not activated. Contact Admin.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Invalid mobile/password.']));
        return FALSE;
    }

    public function doUpdateProfile()
    {
        $this->output->set_content_type('application/json');

        $image = $this->input->post('image');
        $filename = "";
        if (isset($image) && $image !== "") {
            $mobile = $this->input->post('mobile');
            $imagename = md5(time() . $mobile);
            $path = realpath(APPPATH . "../uploads/user");
            $img_path = "$path/$imagename.png";
            file_put_contents($img_path, base64_decode($image));
            $filename = $imagename . '.png';

            $previmage = $this->service_model->get_user_image()['image'];
            if (!empty($previmage) and file_exists(realpath(APPPATH . "../uploads/user/" . $previmage))) {
                unlink(realpath(APPPATH . "../uploads/user/" . $previmage));
            }
        } else {
            $filename = $this->service_model->get_user_image()['image'];
        }
        $result = $this->service_model->do_update_profile($filename);
        if ($result) {
            $this->load->model('user_model');
            $user_info = $this->user_model->get_user($this->input->post('user_id'));
            if (substr($user_info['image'], 0, 4) !== 'http') {
                $user_info['image'] = base_url('uploads/user/' . $user_info['image']);
            }
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Profile Update Successfully', 'user_info' => $user_info]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Noting gets Changed.']));
        return FALSE;
    }

    public function doChangePassword()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->change_password();
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Password Changed Successfully']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Password Can\'t be Changed']));
        return FALSE;
    }

    #######################################
    ########        Plants           ######   
    #######################################

    public function getPlants()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_plants();
        $plants = [];
        foreach ($result as $row) {
            $plants[] = ['plant_id' => $row['plant_id'],
                'plant_name' => $row['plant_name'],
                'biological_name' => $row['biological_name'],
                'max_height' => $row['max_height'],
                'tree_type' => $row['tree_type'],
                'stock_qty' => $row['stock_qty'],
                'description' => $row['description'],
                'soil_name' => $row['soil_name'],
                'image_url' => empty($row['image_url']) ? base_url('uploads/no_image.jpg') : base_url('uploads/plant/' . $row['image_url'])];
        }

        $this->output->set_output(json_encode(['result' => 1, 'plants' => $plants]));
        return FALSE;
    }

    public function getPlant()
    {
        $this->output->set_content_type('application/json');
        $row = $this->service_model->get_plant();
        $row['image_url'] = empty($row['image_url']) ? base_url('uploads/no_image.jpg') : base_url('uploads/plant/' . $row['image_url']);
        $this->output->set_output(json_encode(['result' => 1, 'plant' => $row]));
        return FALSE;
    }

    public function getLandProperties()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_land_properties();
        $this->output->set_output(json_encode(['result' => 1, 'unit' => $result['unit'], 'soil' => $result['soil']]));
        return FALSE;
    }

    public function doRegisterLand()
    {
        $this->output->set_content_type('application/json');
        $image = $this->input->post('land_image_url');
        $filename = "";
        if (isset($image) && $image !== "") {
            $imgid = uniqid();
            $imagename = md5(time() . $imgid);
            $path = realpath(APPPATH . "../uploads/land");
            $img_path = "$path/$imagename.png";
            file_put_contents($img_path, base64_decode($image));
            $filename = $imagename . '.png';
        }
        $result = $this->service_model->add_land($filename);

        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Land Enquiry Submitted Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Land Enquiry can\'t be Submitted.']));
        return FALSE;
    }

    public function getRegisteredLands()
    {
        $this->output->set_content_type('application/json');
        $lands = $this->service_model->get_user_register_lands();
        $size = count($lands);
        for ($i = 0; $i < $size; $i++) {
            $lands[$i]['land_image_url'] = base_url('uploads/land/' . $lands[$i]['land_image_url']);
            $lands[$i]['added_date'] = date('d-m-Y', strtotime($lands[$i]['added_date']));
        }
        $this->output->set_output(json_encode(['result' => 1, 'list' => $lands]));
        return FALSE;
    }

    public function doAddPlantOrder()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->add_order();
        if ($result === -1) {
            $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Invalid Referal Code']));
            return FALSE;
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Order Added Successfully.', 'order_id' => $result]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Order Can\'t be Added.']));
        return FALSE;
    }

    public function doAddPayment()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->add_payment();
        $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा | समय - समय पर आपके द्वारा लगाए गए पेड़ की जानकारी फोटो सहित मिलती रहेगी |';
        $payment_mode = $this->input->post('payment_type');
        if ($payment_mode === 'Offline') {
            $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा | समय - समय पर आपके द्वारा लगाए गए पेड़ की जानकारी फोटो सहित मिलती रहेगी |';
        } else if ($payment_mode === 'Paytm') {
            $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा | समय - समय पर आपके द्वारा लगाए गए पेड़ की जानकारी फोटो सहित मिलती रहेगी |';
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => $msg, 'order_id' => $result]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Order Can\'t be Added.']));
        return FALSE;
    }

    public function doDonationPayment()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->add_donation_payment();
        $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा |';
        $payment_mode = $this->input->post('payment_type');
        if ($payment_mode === 'Offline') {
            $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा |';
        } else if ($payment_mode === 'Paytm') {
            $msg = 'हरियालो राजस्थान प्रोजेक्ट में आपकी भागीदारी प्रकृति के प्रति अमुल्य योगदान है | आपका यह प्रयास आने वाली पीढ़ियो के लिए स्वच्छ वातावरण तैयार करेगा |';
        }
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => $msg]));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 0, 'msg' => 'Order Can\'t be Added.']));
        return FALSE;
    }

    public function getPlantOrders()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('order_model');
        $result = $this->service_model->get_plant_orders();
        $modes = ['Monthly' => '+30 days', 'Quarterly' => '+4 months', 'Half Yearly' => '+6 months', 'Yearly' => '+12 months'];
        $i = 0;

        foreach ($result as $row) {
            $payment_status = $this->order_model->get_order_payment_status($row['order_id'])['payment_status'];

            $due_date = "";

            if (!empty($row['last_payment_date']) and $row['payment_mode'] !== 'One Time') {
                $due_date = strtotime($modes[$row['payment_mode']], strtotime(str_replace('/', '-', $row['last_payment_date'])));
            }

            $result[$i]['payment_status'] = empty($payment_status) ? "" : $payment_status;
            $result[$i]['due_date'] = empty($due_date) ? date('d/m/Y') : date('d/m/Y', $due_date);

            $is_expired = "FALSE";
            if ($row['payment_mode'] === 'One Time') {
                $is_expired = "FALSE";
                $result[$i]['due_date'] = 'N/A';
            } elseif (time() > $due_date) {
                $is_expired = "TRUE";
            }
            $result[$i]['is_expired'] = $is_expired;
            $i++;
        }
        $this->output->set_output(json_encode(['result' => 1, 'orders' => $result]));
        return FALSE;
    }

    public function getAllottedLandTrees()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('land_model');
        $result = $this->land_model->get_allotted_trees();
        $img_result = $this->land_model->get_allotted_trees_images();
        $arr = [];
        foreach ($img_result as $row) {
            $due_date = strtotime('+' . DUE_DAYS . ' days', strtotime($row['capture_date']));
            $arr[] = [
                'plantation_id' => $row['plantation_id'],
                'image' => $row['image'],
                'capture_date' => date('d/m/Y', strtotime($row['capture_date'])),
                'due_date' => date('d/m/Y', $due_date),
                'is_expired' => time() >= $due_date ? "TRUE" : "FALSE"
            ];
        }
        $this->output->set_output(json_encode(['result' => 1, 'img_prefix' => base_url('uploads/user') . '/', 'list' => $result, 'plant_image_prefix' => base_url('uploads/plant-growth') . '/', 'images' => $arr]));
        return FALSE;
    }

    public function getAllottedOrderTrees()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('order_model');
        $result = $this->order_model->get_allotted_trees();
        $img_result = $this->order_model->get_allotted_trees_images();
        $arr = [];
        foreach ($img_result as $row) {
            $due_date = strtotime('+' . DUE_DAYS . ' days', strtotime($row['capture_date']));
            $arr[] = [
                'plantation_id' => $row['plantation_id'],
                'image' => $row['image'],
                'capture_date' => date('d/m/Y', strtotime($row['capture_date'])),
                'due_date' => date('d/m/Y', $due_date),
                'is_expired' => time() >= $due_date ? "TRUE" : "FALSE"
            ];
        }
        $this->output->set_output(json_encode(['result' => 1, 'img_prefix' => base_url('uploads/user') . '/', 'list' => $result, 'plant_image_prefix' => base_url('uploads/plant-growth') . '/', 'images' => $arr]));
        return FALSE;
    }

    public function verifyQR()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('order_model');
        $this->load->model('user_model');
        $this->load->library('gblib');
        $result = $this->order_model->verify_qr();
        if ($result['status']) {
            if (!empty($result['user_id'])) {
                $fcm_id = $this->user_model->get_fcm_id($result['user_id'])['fcm_id'];
                $this->gblib->sendNotification('Plant Order', 'Your Plant Order has been planted.', [$fcm_id], ['type' => 'order']);
            }
            $this->output->set_output(json_encode(['result' => '1', 'status' => $result['status'], 'plantation_id' => $result['plantation_id'], 'msg' => 'Verified']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => '1', 'status' => $result['status'], 'msg' => 'Not Verified']));
        return FALSE;
    }

    public function captureImage()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('order_model');

        $image = $this->input->post('capture_image');
        $imagename = "";
        if (isset($image) && $image !== "") {
            $path = realpath(APPPATH . "../uploads/plant-growth");
            $imagename = hash('sha256', mt_rand() . '-' . microtime()) . '.png';
            $img_path = "$path/" . $imagename;
            file_put_contents($img_path, base64_decode($image));
        }

        $result = $this->order_model->capture_image($imagename);
        if ($result) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Image Saved']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Image Can\'t be Saved']));
        return FALSE;
    }

    public function getPlantGrowthImages()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('land_model');
        $result = $this->land_model->get_land_growth_images();
        $this->output->set_output(json_encode(['result' => 1, 'items' => $result, 'base_image_url' => base_url('uploads/plant-growth') . '/']));
    }

    public function updateFcmID()
    {
        $this->output->set_content_type('application/json');
        $this->load->model('user_model');
        $affected_rows = $this->user_model->update_fcm_id();
        if ($affected_rows) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Fcm Update Successfully.']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Fcm can\'t be updated.']));
        return FALSE;
    }

    public function payment()
    {
        $key = $this->input->post('key');

        $salt = "RbkDB0DVTq";
        $txnId = $this->input->post('txnid');
        $amount = $this->input->post('amount');
        $productName = $this->input->post('productInfo');
        $firstName = $this->input->post('firstName');
        $email = $this->input->post('email');
        $udf1 = $this->input->post('udf1');
        $udf2 = $this->input->post('udf2');
        $udf3 = $this->input->post('udf3');
        $udf4 = $this->input->post('udf4');
        $udf5 = $this->input->post('udf5');

        $payhash_str = $key . '|' . $this->checkNull($txnId) . '|' . $this->checkNull($amount) . '|' . $this->checkNull($productName) . '|' . $this->checkNull($firstName) . '|' . $this->checkNull($email) . '|' . $this->checkNull($udf1) . '|' . $this->checkNull($udf2) . '|' . $this->checkNull($udf3) . '|' . $this->checkNull($udf4) . '|' . $this->checkNull($udf5) . '|' . $salt;


        $hash = strtolower(hash('sha512', $payhash_str));
        $arr['result'] = $hash;
        $arr['status'] = 0;
        $arr['errorCode'] = null;
        $arr['responseCode'] = null;
        $output = $arr;
        echo json_encode($output);
    }

    private function checkNull($value)
    {
        if ($value == null) {
            return '';
        } else {
            return $value;
        }
    }

    public function getTermsAndServices()
    {
        $this->output->set_content_type('text/html');
        $this->load->view('terms');
    }

    public function getAllTreeFriends()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_all_tree_friends();
        $size = count($result);
        for ($i = 0; $i < $size; $i++) {
            $number_of_referal_plants = $this->service_model->get_total_plants($result[$i]['referal_code']);
            $result[$i]['number_of_referal_plants'] = empty($number_of_referal_plants) ? 0 : $number_of_referal_plants;
        }
        $this->output->set_output(json_encode(['result' => 1, 'plant_friends' => $result, 'base_image_url' => base_url('uploads/user') . '/']));
        return FALSE;
    }

    public function getPromoters()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_promoters();
        $size = count($result);
        for ($i = 0; $i < $size; $i++) {
            $number_of_referal_plants = $this->service_model->get_total_plants($result[$i]['referal_code']);
            $result[$i]['number_of_referal_plants'] = empty($number_of_referal_plants) ? 0 : $number_of_referal_plants;
            $number_of_plants = $this->service_model->get_total_planted_plants($result[$i]['user_id']);
            $result[$i]['number_of_plants'] = empty($number_of_plants) ? 0 : $number_of_plants;
        }
        $this->output->set_output(json_encode(['result' => 1, 'promoters' => $result, 'base_image_url' => base_url('uploads/user') . '/']));
        return FALSE;
    }

    public function fillReferalCode()
    {
        $this->output->set_content_type('application/json');
        return $this->service_model->fill_referal_code();
    }

    public function verifyReferalCode()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->verify_referal_code();
        if ($result === 1) {
            $this->output->set_output(json_encode(['result' => 1, 'msg' => 'Valid Referal Code']));
            return FALSE;
        }
        $this->output->set_output(json_encode(['result' => -1, 'msg' => 'Invalid Referal Code']));
        return FALSE;
    }

    public function getPlantDonors()
    {
        $this->output->set_content_type('application/json');
        $result = $this->service_model->get_plant_donors();
        $this->output->set_output(json_encode(['result' => 1, 'donors' => $result, 'base_image_url' => base_url('uploads/user') . '/']));
        return FALSE;
    }

    public function sendDuePaymentMessage()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_content_type('application/json');
        $this->load->model('order_model');
        $modes = ['Monthly' => '+30 days', 'Quarterly' => '+4 months', 'Half Yearly' => '+6 months', 'Yearly' => '+12 months'];
        $orders = $this->order_model->get_all_order_ids();
        $dues = [];
        $dues_in_week = [];
        $dues_in_two = [];
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
            if (empty($due_date)) {
                $orders[$i]['due_date'] = 'One Time Paid';
                $order['due_date'] = 'One Time Paid';
            } else {
                $order['due_date'] = $orders[$i]['due_date'] = empty($due_date) ? date('d/m/Y') : date('d/m/Y', $due_date);
                if (time() > $due_date) {
//                    $dues[] = $order;
                    $dues[] = $order['fcm_id'];
                } else if ((ceil((($due_date - time()) / (60 * 60 * 24))) == 7)) {
                    $dues_in_week[] = $order['fcm_id'];
                } else if ((ceil((($due_date - time()) / (60 * 60 * 24))) == 2)) {
                    $dues_in_two[] = $order['fcm_id'];
                }
                echo ceil((($due_date - time()) / (60 * 60 * 24))) . '<br>';
            }
            $i++;
        }
        if (!empty($dues)) {
            $this->load->library('gblib');
            $this->gblib->sendNotification('Order Payment Due', 'Payment for your Plant Order is due. Please proceed the payment as soon as possible otherwise your order plants will be reallocated to another user.', $dues, ['type' => 'noti']);
        }
        if (!empty($dues_in_week)) {
            $this->load->library('gblib');
            $this->gblib->sendNotification('Order Payment Will Due in One Week', 'Payment for your Plant Order will due after one week. Please proceed the payment as soon as possible.', $dues_in_week, ['type' => 'noti']);
        }
        if (!empty($dues_in_two)) {
            $this->load->library('gblib');
            $this->gblib->sendNotification('Order Payment Will Due in Two Days', 'Payment for your Plant Order will due in Two Days. Please proceed the payment as soon as possible.', $dues_in_two, ['type' => 'noti']);
        }
        $this->output->set_output(json_encode(['result' => 1, 'dues' => $dues, 'dues_in_week' => $dues_in_week, 'dues_in_two' => $dues_in_two]));
        return FALSE;
    }
}
