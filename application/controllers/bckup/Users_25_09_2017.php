<?php
class Users extends MY_Controller{
	function __construct(){
		parent::__construct();
	}
	
	public function emailvalidate($validation_code=''){
		if(!empty($validation_code)){
			$find_cond = array(
				'email_verify_token'=>$validation_code,
				'is_deleted'=>'0',
				'is_blocked'=>'0',
				'is_email_verify'=>'0'
			);
			$select_flds=array('user_id','is_user_verify');
			$user = $this->BaseModel->getData($this->tableNameUser,$find_cond,$select_flds);
			if(!empty($user)){
				$update_date=array(
					'is_email_verify'=>'1',
					'update_date'=>$this->dateformat
				);
				if(!$user['is_user_verify']){
					$update_date['is_user_verify']='1';
				}
				$find_cond['user_id']=$user['user_id'];
				$this->BaseModel->updateDatas($this->tableNameUser,$update_date,$find_cond);
				die("You verified your email successfully");
			}
		}
		die("Validate link invalid");
	}
	
	public function resetpassword($resetpassword=''){
		if(!empty($resetpassword)){
			$data=array(
				'error'=>'',
				'success'=>''
			);
			$this->load->library(array('form_validation'));
			$find_cond = array(
				'change_pass_token'=>$resetpassword,
				'is_deleted'=>'0',
				'is_blocked'=>'0',
			);
			$select_flds=array('user_id','email');
			$user = $this->BaseModel->getData($this->tableNameUser,$find_cond,$select_flds);
			if(!empty($user)){
				$rules=array(
					array(
						'field'=>'new_password',
						'label'=>'New Password',
						'rules'=>'trim|required',
						'errors'=>array()
					),
					array(
						'field'=>'con_password',
						'label'=>'Confirm Password',
						'rules'=>'trim|required',
						'errors'=>array()
					),
				);
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run()===true){
					$new_password = $this->input->post('new_password');
					$con_password = $this->input->post('con_password');
					if($new_password==$con_password){
						$update_data=array(
							'password'=>md5($new_password),
							'showpass'=>$new_password,
							'change_pass_token'=>'',
							'update_date'=>$this->dateformat
						);
						$find_cond['user_id']=$user['user_id'];
						$this->BaseModel->updateDatas($this->tableNameUser,$update_data,$find_cond);
						$data["success"]="Your new password updated successfully.";
					}
					else{
						$data["error"]="Confirm password does not match.";
					}
				}
				else{
					$data["error"]=validation_errors();
				}
			}
			else{
				$data["error"]="Reset password link expired.";
			}
			$this->load->view('resetpassword',$data);
		}
		else{
			die("Invalid link");
		}
	}
	
	public function termsconditions(){
		$this->load->view('header_one',array('pg_title'=>'Terms Conditions'));
		$this->load->view('termsconditions');
		$this->load->view('footer_one');
	}
	
	public function privacypolicies(){
		$this->load->view('header_one',array('pg_title'=>'Privacy Policies'));
		//$this->load->view('privacypolicies');
		$this->load->view('privacypolicy');
		$this->load->view('footer_one');
	}
}
?>