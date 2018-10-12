<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
public $countryCallingCode;


	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		$this->countryCallingCode="+51"; //hard coded
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	function twilio_send_sms( $to='', $body='' ) {
		$sid = 'ACc6ef76e1744963aa4e9f15d6d11265c3';
		$token = '1cb62737389d41cb8db8978568d4c773';
		$from='+14159807654';
		
		$result='';
		$erro='';
		if(empty($to) || empty($body)){
			return array('result'=>$result,'error'=>$erro);;
		}
		$to = $this->countryCallingCode.$to;
		
	   // resource url & authentication
	   $uri = 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/Messages.json';
	   $auth = $sid . ':' . $token;
	   $fields = 
		   '&To=' .  urlencode( $to ) . 
		   '&From=' . urlencode( $from ) . 
		   '&Body=' . urlencode( $body );

	   // start cURL
	   $res = curl_init();
	   // set cURL options
	   curl_setopt( $res, CURLOPT_URL, $uri );
	   curl_setopt( $res, CURLOPT_POST, 3 ); // number of fields
	   curl_setopt( $res, CURLOPT_POSTFIELDS, $fields );
	   curl_setopt( $res, CURLOPT_USERPWD, $auth ); // authenticate
	   curl_setopt( $res, CURLOPT_RETURNTRANSFER, true ); // don't echo
	   curl_setopt( $res, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt( $res, CURLOPT_SSL_VERIFYPEER, 0);
		
	   // send cURL
	   $result = curl_exec( $res );
	   if($result===false){
		$erro = curl_error($res);	 
	   }
	   else{
		   $erro=array();
	   }
	   curl_close( $res );
	   
	   return array('result'=>$result,'error'=>$erro);
	}


	// email section 
	public function sendemail($email_for=0,$email='',$email_data=array()){
		//pending section
		if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			$iemal_seed_to_sent=true;
			$subjects='';
			$messages='';
			switch($email_for){
				case 1:// signup confirm section
					$subjects="You successfully completed your registration";
					$messages = "Thank to you for connecting with us.";
					$messages.=json_encode($email_data);
					break;
				case 2://forgot password
					$subjects="Password reset request";
					$messages = "Your new password reset url is follows please copy the link and past on browser : \n";
					$messages.=json_encode($email_data);
					break;
				case 3://password change successfully
					$subjects="You changed your password";
					$messages = "Thank to you for updating your old paasword Its help us to keep your account more saved.";
					break;
				case 4: // user create and send password and login details 
					$subjects="Your login details for track your request";
					$messages="Using below details you can track your request status. Details are :\n";
					$messages.=json_encode($email_data);
					break;
				case 5: // email validation link send
					$v_code = $email_data['verification_code'];
					$email_link = $email_data['email_link'];
					$subjects="Email verification link";
					$messages="Please click on the button for validate your email address :\n";
					$messages.="Verification Code : $v_code \n\n";
					$messages.="Copy the link to verify : $email_link \n\n";
					//$messages.=json_encode($email_data);
					
					break;
				case 6://contact us
					$subjects="One user trying to contact with you";
					$messages=$email_data['messages'];
					break;
				default:
					// no email send
					$iemal_seed_to_sent=false;
					break;
			}
			
			if($iemal_seed_to_sent){
				$this->load->library('email');
				$config = Array(
					'protocol' => 'mail',
				);
				
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");
				$this->email->mailtype="both";
				
				$this->email->from('no-reply@bauenfreight.com','BAUEN');
				$this->email->to($email);
				$this->email->subject($subjects);
				$this->email->message($messages);
				$this->email->set_alt_message('BAUEN Default Message');
				$this->email->send();
			}
		}
	}


	public function getrequest_summery($find_cond=array()){
		$select_fields=array();
		$user_id = (!empty($find_cond['user_id']))?$find_cond['user_id']:0;
		$user_type = (!empty($find_cond['user_type']))?$find_cond['user_type']:0;
		$is_company = (!empty($find_cond['is_company']))?$find_cond['is_company']:0;
		$total_request=0;
		$completed_request=0;
		$expired_request=0;
		$in_transit_request=0;
		$panding_request=0;
		
		$request_summery=array(
			'total_request'=>$total_request,
			'completed_request'=>$completed_request,
			'expired_request'=>$expired_request,
			'in_transit_request'=>$in_transit_request,
			'panding_request'=>$panding_request,
		);
		// main section getting if user details found 
		if($user_id>0){
			$find_cond=array(
				'is_blocked'=>'0'
			);
			$tb = $this->dbprefix.$this->tableNameRequest;
			$sql = "SELECT IFNULL(count($tb.request_id),0) total_request, IFNULL(SUM(IF($tb.request_status='".REQUEST_COMPLED_STATUS."',1,0)),0) completed_request, IFNULL(SUM(IF($tb.request_status='14',1,0)),0) expired_request, IFNULL(SUM(IF(($tb.request_status > '5' && $tb.request_status < '13'),1,0)),0) in_transit_request";
			$from = " FROM $tb";
			$where = " WHERE $tb.is_blocked='0' AND $tb.is_deleted='0' ";
			if($user_type==1){
				// transport section
				$sql.=", IFNULL(SUM(IF(($tb.request_status = '1' || $tb.request_status = '4'),1,0)),0) panding_request";
				if($is_company){
					$where.=" AND transporter_id='$user_id'";
				}
				else{
					$where.=" AND driver_id='$user_id'";
				}
			}
			else{
				$sql.=", IFNULL(SUM(IF(($tb.request_status = '2'),1,0)),0) panding_request";
				$where.=" AND user_id='$user_id'";
			}
			$query = $sql.$from.$where;
			$request_summery_res = $this->BaseModel->customSelect($query);
			
			if(!empty($request_summery_res)){
				$request_summery = $request_summery_res[0];
			}
		}
		return $request_summery;
	}


		public function getratings($find_cond=array(),$extra=array()){
		$select_fields=array('rating_id','giver_user_id','rating','user_comment','create_date');
		$select_fields=(isset($extra['select_fields']))?$extra['select_fields']:$select_fields;
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$complexCondition=array();
		$group_by=array();
		$joins=array(
			array(
				'table_name'=>$this->tableNameUser,
				'join_with'=>$this->tableNameUserRating,
				'join_type'=>'inner',
				'join_on'=>array('giver_user_id'=>'user_id'),
				'select_fields'=>array('first_name','last_name','image')
			)
		);
		
		$ratings = $this->BaseModel->getDatas($this->tableNameUserRating,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		
		//for loap for image section 
		if(!$is_count && !empty($ratings)){
			foreach($ratings as $key=>$rating){
				$is_mine=0;
				if(!empty($rating['image'])){
					$rating['image']=base_url('uploads/users/'.$rating['image']);
				}
				if(!empty($rating['create_date'])){
					$rating['create_date']=date("d F Y",strtotime($rating['create_date']));
				}
				$ratings[$key]=$rating;
			}
		}
		return $ratings;
	}
	

}
