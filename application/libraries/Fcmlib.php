<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fcmlib
 *
 * @author Mahesh
 */
class Fcmlib {
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
    }
    
    public function get_fcm_id_by_order($order_id){
        $fcm_id = $this->CI->order_model->get_fcm_id_by_order($order_id);
        return $fcm_id;
    }
    
}
