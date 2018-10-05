<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class industrytypes extends CI_Controller {
    
    function __construct(){
        parent::__construct();
       $this->load->model('bauenservices/industrytypesModel');
    }
    public function list(){
        $data = array();
        $data['industrytypes'] = array();
        $object =$this->industrytypesModel->getIndustrytypes();
        if (count($object)>0) {
          $data['status'] = 1;
          $data['industrytypes'] = $object;
        }else{
          $data['status'] = 0;
          $data['message'] = "No se encontraron registros";
        }
        print (json_encode($data));
    }


}

