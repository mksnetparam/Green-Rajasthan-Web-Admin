<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Master
 *
 * @author Mahesh
 */
defined('BASEPATH') OR die('No direct script access allowed');

class Location {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('master_model');
    }
    public function get_countries_array(){
        $result = $this->get_countries();
        $arr[0] = '';
        foreach ($result as $row){
            $arr[$row['country_id']] = $row['country_name'];
        }
        return $arr;
    }
    public function get_states_array(){
        $result = $this->get_states();
        $arr[0] = '';
        foreach ($result as $row){
            $arr[$row['state_id']] = $row['state_name'];
        }
        return $arr;
    }
    public function get_cities_array(){
        $result = $this->get_cities();
        $arr[0] = '';
        foreach ($result as $row){
            $arr[$row['city_id']] = $row['city_name'];
        }
        return $arr;
    }
    public function get_countries() {
        $result = $this->CI->master_model->get_countries('Yes');
        return $result;
    }

    public function get_states() {
        $result = $this->CI->master_model->get_states('Yes');
        return $result;
    }

    public function get_states_by_country($country_id) {
        $result = $this->CI->master_model->get_states_by_country($country_id);
        return $result;
    }

    public function get_cities() {
        $result = $this->CI->master_model->get_cities('Yes');
        return $result;
    }

    public function get_cities_by_state($state_id) {
        $result = $this->CI->master_model->get_cities_by_state($state_id);
        return $result;
    }
}
