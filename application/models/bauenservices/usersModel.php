<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class usersModel extends CI_Model
{    
	function __construct(){
	parent::__construct();
        $this->load->database();
	}
    public function verificarEmail($data){
        try {
              $query = $this->db->query("SELECT  email
                                         from trns_users where email='".$data['email']."'");
             return $query->result();
        } catch (Exception $e) {
            return  $e->getMessage();
        }
  
    }
    public function verificarTelefono($data){
        try {
              $query = $this->db->query("SELECT  phone_no
                                         from trns_users where phone_no='".$data['phone_no']."'");
             return $query->result();
        } catch (Exception $e) {
            return  $e->getMessage();
        }
  
    }
    public function getRegister($data){
    	try {
             $query = $this->db->query("INSERT INTO trns_users (
                   user_type,
                   create_date, 
                   first_name,
                   last_name,
                   email,
                   phone_no,
                   password,
                   verification_code,
                   is_company,
                   dni_no,
                   ruc_no,
                   industrytype_id,
                   company_name,
                   company_licence_no,
                   country_code,
                   email_verify_token,
                   showpass,
                   creater_id,
                   update_date,
                   firebase_id)
                   VALUES (
                   '".$data['user_type']."', 
                   '".$data['create_date']."',
                   '".$data['first_name']."', 
                   '".$data['last_name']."', 
                   '".$data['email']."', 
                   '".$data['phone_no']."', 
                   '".md5($data['password'])."', 
                   '".$data['verification_code']."',
                   '".$data['is_company']."', 
                   '".$data['dni_no']."', 
                   '".$data['ruc_no']."', 
                   '".$data['industrytype_id']."',
                   '".$data['company_name']."',
                   '".$data['company_licence_no']."',
                   '".$data['country_code']."', 
                   '".$data['email_verify_token']."',
                   '".$data['password']."',
                   '0',  
                   '".$data['update_date']."',
                   '".$data['firabase_id']."')");
 
             $queryid = $this->db->query("SELECT MAX(user_id) AS id FROM trns_users");
             $rs=$queryid->result();        
             $respuesta =$rs;
            return $respuesta;
    	} catch (Exception $e) {
    		 $respuesta = 0;
             return $respuesta;
    	}
 
    }
}
