<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item
 *
 * @author Sakshi Jain
 */
defined('BASEPATH') OR die('No direct script access allowed');

class GBLib {
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model(['settings_model']);
    }

    public function get_all_site_settings() {
        $result = $this->CI->settings_model->get_all_site_settings();
        $settings = [];
        foreach($result as $row){
            $settings[$row['param_name']] = $row['param_value'];
        }
        return $settings;
    }
    
    public function sendNotification($message_title,$message_text,$fcm_ids,$type) {
        $registrationIds = $fcm_ids;
        $msg = array
            (
            'message' => $message_text,
            'title' => $message_title,
            'vibrate' => 1,
            'sound' => 1,
            'flag' => 'chat',
            'type' => $type
        );
        $fields = array
            (
            'registration_ids' => $registrationIds,
            'data' => $msg,
//            'notification' => array("title" => $message_title, "body" => $message_text,"sound" => "default","icon" => "ic_notification_app_icon"),
            'prority' => 'high'
        );

        $headers = array
            (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
