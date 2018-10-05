<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class loadtypes extends CI_Controller {
    
    function __construct(){
        parent::__construct();
       $this->load->model('bauenservices/loadtypesModel');
    }
    public function list(){
        $data = array();
        $data['loadtypes'] = array();
        $object =$this->loadtypesModel->getLoadtypes();
        if (count($object)>0) {
          $data['status'] = 1;
          $data['loadtypes'] = $object;
        }else{
          $data['status'] = 0;
          $data['message'] = "No se encontraron registros";
        }
        print (json_encode($data));
    }


}
