<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users extends CI_Controller {
    
    function __construct(){
        parent::__construct();
       $this->load->model('bauenservices/loadtypesModel');
    }
    
  public function registration(){
        $response_data = array();
        $this->load->library(array('form_validation'));
        $this->load->helper(array('array'));
        
        $is_company = $this->input->post('is_company');
        // rule form validation 
        $rules=array(
          array(
            'field'=>'first_name',
            'label'=>'First Name',
            'rules'=>'trim|required',
            'errors'=>array()
          ),
          array(
            'field'=>'email',
            'label'=>'Email',
            'rules'=>'trim|required|valid_email|callback_user_unique_email',
            'errors'=>array(
              'user_unique_email'=>'This %s value already exists.'
            )
          ),
          array(
            'field'=>'phone_no',
            'label'=>'Phone No.',
            'rules'=>'trim|required|callback_valid_phone_no|callback_user_unique_phone_no',
            'errors'=>array(
              'user_unique_phone_no'=>'This %s value already exists.',
              'valid_phone_no'=>'Please enter valid %s.',
            )
          ),
          array(
            'field'=>'password',
            'label'=>'Password',
            'rules'=>'trim|required',
            'errors'=>array()
          ),
          /*array(
            'field'=>'dni_no',
            'label'=>'DNI No.',
            'rules'=>'trim|required',
            'errors'=>array()
          ),*/
        );
        // if the user registration as company then 
        if($is_company){
          $com_rules=array(
            array(
              'field'=>'company_name',
              'label'=>'Company Name',
              'rules'=>'trim|required',
              'errors'=>array()
            ),
            array(
              'field'=>'industrytype_id',
              'label'=>'Industry Type',
              'rules'=>'trim|required|greater_than[0]',
              'errors'=>array(
                'greater_than'=>'The %s field is required.'
              )
            ),
            /*array(
              'field'=>'ruc_no',
              'label'=>'RUC No.',
              'rules'=>'trim|required',
              'errors'=>array()
            ),*/
          );
          // merge the array 
          $rules = array_merge($rules,$com_rules);
        }
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('','');
        if($this->form_validation->run()===true){
          $first_name = $this->input->post('first_name');
          $last_name = $this->input->post('last_name');
          $email = $this->input->post('email');
          $phone_no = $this->input->post('phone_no');
          $password = $this->input->post('password');
          $dni_no = $this->input->post('dni_no');
          $user_type = $this->input->post('user_type');
          $ruc_no = $this->input->post('ruc_no');
          $company_name = $this->input->post('company_name');
          $company_licence_no = $this->input->post('company_licence_no');
          $industrytype_id = $this->input->post('industrytype_id');
          $country_code = $this->input->post('country_code');
          
          $about_us = $this->input->post('about_us');
          $address = $this->input->post('address');
          $latitude = $this->input->post('latitude');
          $longitude = $this->input->post('longitude');
          $firabase_id = $this->input->post('firebase_id');
          //
          if(empty($last_name)){
            $last_name='';
          }
          if(empty($company_name)){
            $company_name='';
          }
          if(empty($company_licence_no)){
            $company_licence_no='';
          }
          if(empty($country_code)){
            $country_code='';
          }
          
          if(empty($about_us)){
            $about_us='';
          }
          
          if(empty($address)){
            $address='';
          }
          if(empty($latitude)){
            $latitude='';
          }
          if(empty($longitude)){
            $longitude='';
          }
          if(empty($firebase_id)){
            $firebase_id='';
          }
          // generate the verification code 
          $verify_code = $this->verify_code(); // must send on mobile for mobile number validation
          // email verification token send 
          $email_verify_token = $this->email_verify_token();
          
          // save the user 
          $save_data=array(
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'email'=>$email,
            'phone_no'=>$phone_no,
            'user_type'=>$user_type,
            'password'=>md5($password),
            'showpass'=>$password,
            'dni_no'=>$dni_no,
            'is_company'=>$is_company,
            'company_name'=>$company_name,
            'company_licence_no'=>$company_licence_no,
            'ruc_no'=>$ruc_no,
            'industrytype_id'=>$industrytype_id,
            'country_code'=>$country_code,
            'verification_code'=>$verify_code,
            'email_verify_token'=>$email_verify_token,
            'about_us'=>$about_us,
            'address'=>$address,
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'create_date'=>$this->dateformat,
            'update_date'=>$this->dateformat,
            'firebase_id'=>$firabase_id
          );
          $response_data['user']=$save_data;
          $user_id = $this->BaseModel->insertData($this->tableNameUser,$save_data);
          if($user_id>0){
            // create request token or this user 
            $request_key = $this->generate_request_key($user_id,$is_new=1);
            // user info saved successfully
            // send sms with verification code 
            $this->sendsms($phone_no,$verify_code);
            // send the email verify link 
            $this->send_email_verify_link($email,$email_verify_token,$verify_code);
            // get user details
            $response_data=$this->userdetails($user_id);
            $response_data['user_request_key']=$request_key;
            $this->response_status=1;
            $this->response_message="A verification code send to your email address";
            // create user count table 
            $save_count_data=array(
              'user_id'=>$user_id,
              'create_date'=>$this->dateformat,
              'update_date'=>$this->dateformat,
            );
            $this->BaseModel->insertData($this->tableNameUserCount,$save_count_data);
          }
          else{
            $this->response_message="We get some error in processing the data";
          }
        }
        else{
          $error = validation_errors();
          $this->response_message=$error;
        }
        $this->json_output($response_data);
  }

  private function verify_code(){
    $verify_code = rand(99999,1000000);
    return $verify_code;
  }
  private function email_verify_token(){
    $email_verify_token = time().rand(999,1000000);
    return md5($email_verify_token);
  }


}
