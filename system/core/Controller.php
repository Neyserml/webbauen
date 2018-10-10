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
	

}
