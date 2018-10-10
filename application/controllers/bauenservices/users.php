<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users extends CI_Controller {
    
    function __construct(){
        parent::__construct();
       $this->load->model('bauenservices/usersModel');
    }

    public function register(){
      date_default_timezone_set('america/lima'); 
      $fecha = date('Y-m-d H:i:s');
      $is_company=$this->input->post('is_company');
      $data['is_company']=$is_company;
      $data['first_name']=$this->input->post('first_name');
      $data['last_name']=$this->input->post('last_name');
      $email=$this->input->post('email');
      $data['email'] =$email;
      $phone_no=$this->input->post('phone_no');
      $data['phone_no'] =$phone_no; 
      $data['password'] = $this->input->post('password');
      $data['dni_no'] = $this->input->post('dni_no');
      $data['user_type'] = $this->input->post('user_type');
      $data['ruc_no'] = $this->input->post('ruc_no');
      $data['company_name'] = $this->input->post('company_name');
      $data['company_licence_no'] = $this->input->post('company_licence_no');
      $data['industrytype_id'] = $this->input->post('industrytype_id');
      $data['country_code'] = $this->input->post('country_code');
      $data['firabase_id'] = $this->input->post('firebase_id');
      $data['create_date'] =$fecha;
      $data['update_date'] =$fecha;

      $verifEmail=count($this->usersModel->verificarEmail($data));
      $verifTelefono=count($this->usersModel->verificarTelefono($data));

      if ($verifEmail>0) {
        $data['status'] = -1;
        $data['message'] = "Este email ya existe";
      }
      else if($verifTelefono>0)
        {
        $data['status'] = -1;
        $data['message'] = "Este numero ya existe";
        }
      else{
          // generate the verification code 
          $verify_code = $this->verify_code(); // must send on mobile for mobile number validation
          // email verification token send 
          $email_verify_token = $this->email_verify_token();
          $data['verification_code'] =$verify_code;
          $data['email_verify_token'] =$email_verify_token;

          $object =$this->usersModel->getRegister($data);
          if ($object>=0) {
            $this->sendsms($phone_no,$verify_code);
            $this->send_email_verify_link($email,$email_verify_token,$verify_code);
            $data['response'] = "A verification code send to your email address or phono";
            $data['status'] = 1;
            $data['id'] = $object;

            $data['message'] = "Datos guardados correctamente";
          }else{
            $data['status'] = 0;
            $data['message'] = "Error al guardar";
          }
        }
      
       print (json_encode($data));
    }

    private function verify_code(){
    $verify_code = rand(99999,1000000);
    return $verify_code;
    }
  
    private function email_verify_token(){
    $email_verify_token = time().rand(999,1000000);
    return md5($email_verify_token);
    }
    private function sendsms($phone_no='',$verify_code=''){
    if(!empty($phone_no) && !empty($verify_code)){
      // sms send api integration
      $verify_code="Your OTP is $verify_code";
      $this->twilio_send_sms($phone_no,$verify_code);
    }
  }

    private function send_email_verify_link($email='',$email_verify_token='',$verification_code=''){
    
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
      // send email
      $email_link='';
      if(!empty($email_verify_token)){
        $email_link = base_url('users/emailvalidate/'.$email_verify_token);
      }
      
      $email_data=array(
        'email_link'=>$email_link,
        'verification_code'=>$verification_code
      );
      // send mail section
      $this->sendemail(5,$email,$email_data);
    }
  }
}

