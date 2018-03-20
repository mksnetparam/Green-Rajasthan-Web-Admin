<?php

/**
 * Description of Upload_lib
 *
 * @author Mahesh
 */
defined('BASEPATH') OR die('No direct script access allowed');
class Upload_lib{
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
    }
    public function upload_file($path,$control,$allwoed_types='jpg|png') {
        $upload_path = realpath(APPPATH . '../uploads/'.$path);
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = $allwoed_types;
        $config['max_size'] = '5120';
        $config['file_name'] = uniqid();
        $config['overwrite'] = TRUE;
        $this->CI->load->library('upload', $config);
        if (!$this->CI->upload->do_upload($control)) {
            return ['result' => 0, 'errors' => $this->CI->upload->display_errors()];
        }
        
       return ['result' => 1,'errors' => $this->CI->upload->data()];
    }
}
