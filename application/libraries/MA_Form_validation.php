<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MA_Form_validation
 *
 * @author SUN
 */
class MA_Form_validation extends CI_Form_validation{
    public function __construct($rules = array()) {
        parent::__construct($rules);
    }
    public function error_array() {
        if(count($this->_error_array)>0){
            return $this->_error_array;
        }
    }
    public function corporate_email($str){
        $invalid_email_domains = ['gmail.com','yahoo.com','hotmail.com','in.com','facebook.com','rediff.com','rediffmail.com','live.com','outlook.com','yahoo.co.in'];
        foreach($invalid_email_domains as $invalid_suffix){
            if(strpos($str, $invalid_suffix)!==FALSE){
                $this->set_message('corporate_email', 'The %s field must contains valid corporate email');
                return FALSE;
            }
        }
        return TRUE;
    }
    public function valid_url_format($str){
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)){
            $this->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
 
        return TRUE;
    }
    public function url_exists($url){                                   
        $url_data = parse_url($url); // scheme, host, port, path, query
        if(!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)){
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }               
        return TRUE;
    }
    
}
