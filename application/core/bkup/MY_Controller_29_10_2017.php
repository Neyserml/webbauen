<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

define('SES_ADMIN_ID','tnsp_admin_id');
define('SES_ADMIN_NAME','tnsp_admin_name');
define('SES_ADMIN_SUPER','tnsp_admin_super');
define('SES_ADMIN_TYPE','tnsp_admin_type');
define('BASE_FOLDER','Admin/');
define('APP_LANG','1'); // 1=Eng

define('SES_TRANS_ID','tnsp_trans_id');
define('SES_TRANS_NAME','tnsp_trans_name');
define('SES_TRANS_SUPER','tnsp_trans_super');
define('SES_TRANS_TYPE','tnsp_trans_type');
define('BASE_FOLDER_TRANS','Transporter/');
//request status global section
define('REQUEST_COMPLED_STATUS','13');

class MY_Controller extends CI_Controller {
	public $dbprefix;
	public $language_id;
	public $dateformat;
	public $tableNameAdmin;
	public $tableNameTrailer;
	public $thableNameUser;
	public $tableNameIndustryType;
	public $tableNameRequest;
	public $tableNameVehicle;
	public $tableNameDocumentType;
	public $tableNameUserDocument;
	public $tableNameVehicleDocument;
	public $tableNameUserRequestKey;
	public $tableNameUserRating;
	public $tableNameLoadType;
	public $tableNameRequestBid;
	public $tableNameChat;
	public $tableNameMessage;
	public $tableNameUserDeleteMessage;
	
	public $tableNameLanguage;
	public $tableNameLanguageTrailer;
	public $tableNameLanguageLoadType;
	public $tableNameVehicleImage;
	public $tableNameRequestDriverLocation;
	public $tableNameRequestNotification;
	
	function __construct(){
		parent::__construct();
		$this->load->model('Base_model','BaseModel');
		$this->dbprefix="trns_";
		$this->dateformat=date("Y-m-d H:i:s");
		$this->language_id=1; // default for english
		//
		$this->tableNameAdmin="admins";
		$this->tableNameTrailer="trailers";
		$this->tableNameUser="users";
		$this->tableNameIndustryType="industrytypes";
		$this->tableNameRequest="requests";
		$this->tableNameVehicle="vehicles";
		$this->tableNameDocumentType="documenttypes";
		$this->tableNameUserDocument="user_documents";
		$this->tableNameVehicleDocument="vehicle_documents";
		$this->tableNameUserRequestKey="user_request_keys";
		$this->tableNameUserRating="user_ratings";
		$this->tableNameLoadType="loadtypes";
		$this->tableNameRequestBid="request_bids";
		$this->tableNameChat="chats";
		$this->tableNameMessage="messages";
		$this->tableNameUserDeleteMessage="user_delete_messages";
		$this->tableNameLanguageTrailer="language_trailers";
		$this->tableNameLanguageLoadType="language_loadtypes";
		$this->tableNameVehicleImage="vehicle_images";
		$this->tableNameRequestDriverLocation="request_driver_locations";
		$this->tableNameRequestNotification="request_notifications";
		$this->tableNameLanguage="languages";
	}
	
	public function pr($data=array()){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	// admin 
	public function loadview($view_name='',$view_data=array()){
		$view_array=array('login','resetpassword');
		if(in_array($view_name,$view_array)){
			// with out login 
			$this->load->view(BASE_FOLDER.'header_one');
			$this->load->view(BASE_FOLDER.$view_name,$view_data);
			$this->load->view(BASE_FOLDER.'footer_one');
		}
		else{
			// with login
			$this->load->view(BASE_FOLDER.'header');
			$this->load->view(BASE_FOLDER.$view_name,$view_data);
			$this->load->view(BASE_FOLDER.'footer');
		}
	}
	
	//admin session set 
	public function admin_session_set($adminuser=array()){
		if(!empty($adminuser)){
			$admin_id = isset($adminuser['admin_id'])?$adminuser['admin_id']:0;
			$is_superadmin = isset($adminuser['is_super_admin'])?$adminuser['is_super_admin']:0;
			$admin_name = isset($adminuser['name'])?$adminuser['name']:'';
			$admin_type = isset($adminuser['admin_type'])?$adminuser['admin_type']:'0';
			if($admin_id>0){
				$this->session->set_userdata(SES_ADMIN_ID,$admin_id);
				$this->session->set_userdata(SES_ADMIN_SUPER,$is_superadmin);
				$this->session->set_userdata(SES_ADMIN_NAME,$admin_name);
				$this->session->set_userdata(SES_ADMIN_TYPE,$admin_type);
			}
		}
	}
	
	public function admin_session_destroy(){
		if($this->session->has_userdata(SES_ADMIN_ID) && $this->session->userdata(SES_ADMIN_ID)>0){
			$this->session->unset_userdata(SES_ADMIN_ID);
			$this->session->unset_userdata(SES_ADMIN_SUPER);
			$this->session->unset_userdata(SES_ADMIN_NAME);
			$this->session->unset_userdata(SES_ADMIN_TYPE);
		}
		redirect(BASE_FOLDER.'login');
	}
	
	//admin session checked 
	public function admin_session_on(){
		if($this->session->has_userdata(SES_ADMIN_ID) && $this->session->userdata(SES_ADMIN_ID)>0){
			redirect(BASE_FOLDER.'dashboard');
		}
	}
	
	public function admin_session_off(){
		if(!$this->session->has_userdata(SES_ADMIN_ID) || $this->session->userdata(SES_ADMIN_ID)<=0){
			$this->session->set_flashdata('error','Your session has expired. Please login again');
			redirect(BASE_FOLDER.'login');
		}
		else{
			$this->permissioncheck();
		}
	}
	
	// TRANSPORTER SECTION 
	
	// transporter view load 
	public function loadviewtrans($view_name='',$view_data=array()){
		$view_array=array('login','resetpassword');
		if(in_array($view_name,$view_array)){
			// with out login 
			$this->load->view(BASE_FOLDER_TRANS.'header_one');
			$this->load->view(BASE_FOLDER_TRANS.$view_name,$view_data);
			$this->load->view(BASE_FOLDER_TRANS.'footer_one');
		}
		else{
			// with login
			$this->load->view(BASE_FOLDER_TRANS.'header');
			$this->load->view(BASE_FOLDER_TRANS.$view_name,$view_data);
			$this->load->view(BASE_FOLDER_TRANS.'footer');
		}
	}
	
	// transporter section 
	public function trans_session_set($adminuser=array()){
		if(!empty($adminuser)){
			$admin_id = isset($adminuser['user_id'])?$adminuser['user_id']:0;
			$is_superadmin = isset($adminuser['is_company'])?$adminuser['is_company']:0;
			$admin_name = isset($adminuser['first_name'])?$adminuser['first_name']:'';
			$admin_type = isset($adminuser['user_type'])?$adminuser['user_type']:'0';
			if($admin_id>0){
				$this->session->set_userdata(SES_TRANS_ID,$admin_id);
				$this->session->set_userdata(SES_TRANS_SUPER,$is_superadmin);
				$this->session->set_userdata(SES_TRANS_NAME,$admin_name);
				$this->session->set_userdata(SES_TRANS_TYPE,$admin_type);
			}
		}
	}
	
	public function trans_session_destroy(){
		if($this->session->has_userdata(SES_TRANS_ID) && $this->session->userdata(SES_TRANS_ID)>0){
			$this->session->unset_userdata(SES_TRANS_ID);
			$this->session->unset_userdata(SES_TRANS_SUPER);
			$this->session->unset_userdata(SES_TRANS_NAME);
			$this->session->unset_userdata(SES_TRANS_TYPE);
		}
		redirect(BASE_FOLDER_TRANS.'login');
	}
	
	// transporter session checked 
	public function trans_session_on(){
		if($this->session->has_userdata(SES_TRANS_ID) && $this->session->userdata(SES_TRANS_ID)>0){
			redirect(BASE_FOLDER_TRANS.'dashboard');
		}
	}
	
	public function trans_session_off(){
		if(!$this->session->has_userdata(SES_TRANS_ID) || $this->session->userdata(SES_TRANS_ID)<=0){
			$this->session->set_flashdata('error','Your session has expired. Please login again');
			redirect(BASE_FOLDER_TRANS.'login');
		}
		else{
			$this->trans_permissioncheck();
		}
	}
	
	// PERMISSION CHECKED SECTION 
	
	public function admin_roles(){
		$admin_roles=array(
			'1'=>'Data Entry'
		);
		return $admin_roles;
	}
	// ADMIN PANEL
	public function permissioncheck(){
		$is_ajaxcall = $this->input->post('is_ajax');
		$controller = strtolower($this->uri->segment(2));
		$action = strtolower($this->uri->segment(3));
		$default=BASE_FOLDER.'dashboard';
		$cpt_admin_type = $this->session->userdata(SES_ADMIN_TYPE);
		$permissiondata = $this->permissiondata();
		//$this->pr($permissiondata);
		if(isset($permissiondata[$controller][$cpt_admin_type])){
			$permissions = $permissiondata[$controller][$cpt_admin_type];
			//$this->pr($permissions);
			if(is_array($permissions)){
				$action = (empty($action))?'index':$action;
				if(!in_array($action,$permissions)){
					if($is_ajaxcall){
						die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
					}
					$this->session->set_flashdata('error','Permission denied !');
					redirect($default);
				}
			}
			else{
				if(!$permissions){
					if($is_ajaxcall){
						die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
					}
					$this->session->set_flashdata('error','Permission denied !');
					redirect($default);
				}
			}
		}
		else{
			if($is_ajaxcall){
				die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
			}
			$this->session->set_flashdata('error','Permission denied !');
			redirect($default);
		}
	}
	
	// TRANSPORTER SECTION 
	public function trans_permissioncheck(){
		$is_ajaxcall = $this->input->post('is_ajax');
		$controller = strtolower($this->uri->segment(2));
		$action = strtolower($this->uri->segment(3));
		$default=BASE_FOLDER_TRANS.'dashboard';
		$cpt_admin_type = $this->session->userdata(SES_TRANS_TYPE);
		$permissiondata = $this->permissiondata();
		
		if(isset($permissiondata[$controller][$cpt_admin_type])){
			$permissions = $permissiondata[$controller][$cpt_admin_type];
			if(is_array($permissions)){
				$action = (empty($action))?'index':$action;
				if(!in_array($action,$permissions)){
					if($is_ajaxcall){
						die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
					}
					$this->session->set_flashdata('error','Permission denied !');
					redirect($default);
				}
			}
			else{
				if(!$permissions){
					if($is_ajaxcall){
						die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
					}
					$this->session->set_flashdata('error','Permission denied !');
					redirect($default);
				}
			}
		}
		else{
			if($is_ajaxcall){
				die(json_encode(array('status'=>'0','message'=>'Permission denied !')));
			}
			$this->session->set_flashdata('error','Permission denied !');
			redirect($default);
		}
	}
	
	public function permissiondata(){
		// 0=super admin, 1=data entry user
		$permissions=array(
			'admins'=>array(
				'0'=>array('logout','dashboard'),
				'1'=>array('logout','dashboard')
			),
			'trailers'=>array(
				'0'=>array('index','add','edit','blockestatuschange','blockunblockdelete','deletetblrecord'),
				'1'=>array()
			),
			'users'=>array(
				'0'=>array('index','transporters','drivers','vehicles','blockestatuschange','blockunblockdelete','deletetblrecord'),
				'1'=>array()
			),
			'industrytypes'=>array(
				'0'=>array('index','add','edit','blockestatuschange','blockunblockdelete','deletetblrecord'),
				'1'=>array()
			),
			'requests'=>array(
				'0'=>array('index','details'),
				'1'=>array('index','details','bids','bidaccept','assigndriver')
			),
			'documenttypes'=>array(
				'0'=>array('index','add','edit','blockestatuschange','blockunblockdelete','deletetblrecord'),
				'1'=>array()
			),
			'userdocuments'=>array(
				'0'=>array('index','view','blockestatuschange','deletetblrecord'),
				'1'=>array()
			),
			'vehicledocuments'=>array(
				'0'=>array('index','view','blockestatuschange','deletetblrecord'),
				'1'=>array()
			),
			'vehicle_documents'=>array(
				'0'=>array('blockunblockdelete'),
				'1'=>array('blockunblockdelete')
			),
			'user_documents'=>array(
				'0'=>array('blockunblockdelete'),
				'1'=>array()
			),
			'transporters'=>array(
				'0'=>array(),
				'1'=>array(),
			),
			'drivers'=>array(
				'0'=>array(),
				'1'=>array('index','add','edit','documents','adddocument','viewdocument'),
			),
			'vehicles'=>array(
				'0'=>array(),
				'1'=>array('index','add','edit','documents','adddocument','viewdocument','deletetblrecord'),
			),
			'documents'=>array(
				'0'=>array(),
				'1'=>array('index','add','view')
			),
			'loadtypes'=>array(
				'0'=>array('index','add','edit','blockestatuschange','deletetblrecord','blockunblockdelete'),
				'1'=>array(),
			),
			'chats'=>array(
				'0'=>array(),
				'1'=>array('index','add','messages','addmessage'),
			),
			'dashboard'=>array('0'=>'1','1'=>'1'),
			'logout'=>array('0'=>'1','1'=>'1'),
			'profile'=>array('0'=>'0','1'=>'1'),
		);
		return $permissions;
	}
	
	//row delete and blocked un blocked
	
	// all table blocke status change
	public function blockestatuschange($table_id=0,$is_blocked=0){
		$is_ajaxpost=0;
		if($this->input->server('REQUEST_METHOD')=='POST'){
			$is_ajaxpost=$this->input->post('is_ajax');
			$tbl_name=$this->input->post('tbl_name');
			if($table_id>0){
				if(!$this->action_permission_checked($tbl_name,$table_id,$is_delete=0)){
					// false 
					die(json_encode(array('status'=>'0','message'=>'Table Row Status changed Permission denied !')));
				}
				else{
					//do the operation
					$updatedata=array(
						'create_date'=>$this->dateformat,
						'is_blocked'=>$is_blocked
					);
					$primary_key=$this->primary_key($tbl_name);
					$conditions = array(
						$primary_key=>$table_id
					);
					
					$this->BaseModel->updateDatas($tbl_name,$updatedata,$conditions);
					//return type
					$is_blocked = ($is_blocked)?0:1;
					die(json_encode(array('status'=>'1','message'=>'Table Row Publish status changed successfully','is_blocked'=>$is_blocked)));
				}
			}
			else{
				die(json_encode(array('status'=>'0','message'=>'Table Row information not set')));
			}
		}
		else{
			$this->session->set_flashdata('error','Unauthenticated access');
		}
		// redirect to the referrer url 
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function deletetblrecord($table_id=0){
		$is_ajaxpost=0;
		if($this->input->server('REQUEST_METHOD')=='POST'){
			$is_ajaxpost=$this->input->post('is_ajax');
			$tbl_name=$this->input->post('tbl_name');
			if($table_id>0){
				if(!$this->action_permission_checked($tbl_name,$table_id,$is_delete=1)){
					// false 
					die(json_encode(array('status'=>'0','message'=>'Table Row deleted Permission denied !')));
				}
				else{
					//do the operation 
					$updatedata=array(
						'deleted_date'=>$this->dateformat,
						'is_deleted'=>'1'
					);
					$primary_key=$this->primary_key($tbl_name);
					$conditions = array(
						$primary_key=>$table_id
					);
					
					$this->BaseModel->updateDatas($tbl_name,$updatedata,$conditions);
					//return type
					die(json_encode(array('status'=>'1','message'=>'Table Row deleted successfully')));
				}
			}
			else{
				die(json_encode(array('status'=>'0','message'=>'Table Row information not set')));
			}
		}
		else{
			$this->session->set_flashdata('error','Unauthenticated access');
		}
		// redirect to the referrer url 
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function action_permission_checked($tbl_name='',$table_id=0,$is_delete=0){
		if($this->session->has_userdata(SES_ADMIN_SUPER)){
			//checked module permission 
			if(permissionchecked($tbl_name,'blockunblockdelete')){
				
				$primary_key=$this->primary_key($tbl_name);
				if(empty($primary_key)){
					return false;
				}
				$conditions = array(
					$primary_key=>$table_id
				);
				
				$row_details  = $this->BaseModel->getData($tbl_name,$conditions,$from_fields=array('is_blocked'));
				if(!empty($row_details)){
					if($this->session->userdata(SES_ADMIN_SUPER)==1){
						return true;
					}
					else{
						if($is_delete){
							if($row_details['is_blocked']){
								return true;
							}
						}
					}
				}
			}
		}
		elseif($this->session->has_userdata(SES_TRANS_SUPER)){
			//checked module permission		
			if(permissionchecked($tbl_name,'blockunblockdelete')){
				$primary_key=$this->primary_key($tbl_name);
				
				if(empty($primary_key)){
					return false;
				}
				$conditions = array(
					$primary_key=>$table_id
				);
				
				$row_details  = $this->BaseModel->getData($tbl_name,$conditions,$from_fields=array('is_blocked'));
				if(!empty($row_details)){
					if($this->session->userdata(SES_TRANS_SUPER)==1){
						return true;
					}
					else{
						if($is_delete){
							if($row_details['is_blocked']){
								return true;
							}
						}
					}
				}
			}
			else{
				die(json_encode(array('status'=>'0','message'=>'Delete permission does not available')));
			}
		}
		return false;
	}
	
	public function primary_key($table_name=''){
		$tables=array(
			'trailers'=>'trailer_id',
			'industrytypes'=>'industrytype_id',
			'requests'=>'request_id',
			'documenttypes'=>'documenttype_id',
			'user_documents'=>'user_document_id',
			'vehicle_documents'=>'vehicle_document_id',
			'users'=>'user_id',
			'loadtypes'=>'loadtype_id'
		);
		return (isset($tables[$table_name]))?$tables[$table_name]:'';
	}
	
	
	public function user_unique_email($email='',$id=0){
		if(!empty($email)){
			$find_cond=array(
				'email'=>$email,
			);
			if(!empty($id)){
				$find_cond['user_id !=']=$id;
			}
			$row = $this->BaseModel->tableRow($this->tableNameUser,$find_cond);
			if($row){
				return false;
			}
		}
		return true;
	}
	
	public function valid_phone_no($phone_no=''){
		if(!empty($phone_no)){
			$len = strlen($phone_no);
			if(in_array($len,array('9','10','11','12','13'))){
				if(is_numeric($phone_no)){
					if(strpos($phone_no,'-')!==false){
						return false;
					}
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		return true;
	}
	
	public function user_unique_phone_no($phone_no='',$id=0){
		if(!empty($phone_no)){
			$find_cond = array(
				'phone_no'=>$phone_no
			);
			if($id>0){
				$find_cond['user_id !=']=$id;
			}
			$tableRow = $this->BaseModel->tableRow($this->tableNameUser,$find_cond);
			if(!empty($tableRow)){
				return false;
			}
		}
		return true;
	}
	
	
	// common section 
	public function getdocument_for(){
		$getdocument_for=array(
			'1'=>'User',
			'2'=>'Vehicle'
		);
		return $getdocument_for;
	}
	
	public function getdocumenttypes($find_cond=array()){
		$select_flds=array('document_title','documenttype_id');
		$documenttypes = $this->BaseModel->getDatas($this->tableNameDocumentType,$find_cond,$select_flds);
		return $documenttypes;
	}
	
	public function getindustrytypes($find_cond=array()){
		$select_fields=array('industrytype_id','industrytype_name');
		$industrytypes = $this->BaseModel->getDatas($this->tableNameIndustryType,$find_cond,$select_fields);
		return $industrytypes;
	}
	public function getloadtypes($find_cond=array(),$select_fields=array()){
		if(empty($select_fields)){
			$select_fields=array('loadtype_id','load_name');
		}
		$joins=array(
			array(
				'table_name'=>$this->tableNameLanguageLoadType,
				'join_with'=>$this->tableNameLoadType,
				'join_with_alias'=>'',
				'join_type'=>'left',
				'join_on'=>array('loadtype_id'=>'loadtype_id'),
				'oncond'=>array('is_blocked'=>'0','is_deleted'=>'0','language_id'=>$this->language_id),
				'select_fields'=>'IFNULL(loadtype_name,load_name) load_name'
			)
		);
		$loadtypes = $this->BaseModel->getDatas($this->tableNameLoadType,$find_cond,$select_fields,array(),$joins);
		return $loadtypes;
	}
	
	
	public function getrequests($find_cond=array(),$assos_cond=array(),$offset=0,$limit=0){
		$select_flds=array();
		$order_by=array('request_id'=>'DESC');
		$is_count=false;
		// driver lat long last position 
		$DR=$this->dbprefix.$this->tableNameRequestDriverLocation;
		//$DR=$this->tableNameRequestDriverLocation;
		$RQ=$this->dbprefix.$this->tableNameRequest;
		
		$select_flds['special_select_field']="ifnull((SELECT concat(`$DR`.`latitude`,',',`$DR`.`longitude`) FROM $DR WHERE request_id=request_id AND user_id=driver_id ORDER BY driver_location_id DESC limit 0,1),'') as driver_location";
		
		/*$complexCondition=array(
			'DATE('.$this->dbprefix.$this->tableNameRequest.'.create_date)'=>date('Y-m-d')
		);*/
		
		$complexCondition=array();
		
		$trans_bid_cond=array();
		
		if(isset($find_cond['complexCondition'])){
			$complexCondition=$find_cond['complexCondition'];
			unset($find_cond['complexCondition']);
		}
		
		// request status section 
		if(!empty($find_cond['request_status'])){
			$request_status = $find_cond['request_status'];
			if(strpos($request_status,'*')>=0){
				/*$request_status = explode("*",$request_status);
				$find_cond['request_status'] = $request_status;*/
				$find_cond['request_status'] = explode("*",$request_status);
			}
		}
		
		// associate conditions 
		//custome select fields 
		if(isset($assos_cond['fields']) && !empty($assos_cond['fields'])){
			$select_flds = $assos_cond['fields'];
		}
		// custome order order_by 
		if(isset($assos_cond['order_by']) && !empty($assos_cond['order_by'])){
			$order_by = $assos_cond['order_by'];
		}
		//only count return 
		if(isset($assos_cond['count']) && !empty($assos_cond['count'])){
			$is_count = $assos_cond['count'];
		}
		
		// transporter bid details with request 
		$trans_bid_assos=array();
		$bid_assos=array();
		$bid_join_type="left";
		
		if(isset($assos_cond['trans_bid_cond']) && !empty($assos_cond['trans_bid_cond'])){
			$trans_bid_cond = $assos_cond['trans_bid_cond'];
		}
		if(isset($assos_cond['trans_bid_assos']) && !empty($assos_cond['trans_bid_assos'])){
			$trans_bid_assos = $assos_cond['trans_bid_assos'];
		}
		if(isset($assos_cond['bid_assos']) && !empty($assos_cond['bid_assos'])){
			$bid_assos = $assos_cond['bid_assos'];
			$bid_join_type="inner";
		}
		
		$joins=array(
			array(
				'table_name'=>$this->tableNameUser,
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'inner',
				'join_on'=>array('user_id'=>'user_id'),
				'select_fields'=>array('first_name cus_first_name','last_name cus_last_name','email cus_email','phone_no cus_phone_no','image cus_image')
			),
			array(
				'table_name'=>$this->tableNameTrailer,
				'table_name_alias'=>'',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'inner',
				'join_on'=>array('trailer_id'=>'trailer_id'),
				'select_fields'=>array('min_load trailer_min_load','max_load trailer_max_load','image trailer_image'),
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameLanguageTrailer,
				'table_name_alias'=>'',
				'join_with'=>$this->tableNameTrailer,
				'join_type'=>'left',
				'join_on'=>array('trailer_id'=>'trailer_id'),
				'oncond'=>array('language_id'=>$this->language_id),
				'select_fields'=>'IFNULL(trailer_name,name) trailer_name',
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameLoadType,
				'table_name_alias'=>'',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'inner',
				'join_on'=>array('loadtype_id'=>'loadtype_id'),
				'select_fields'=>array('load_name'),
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameUser,
				'table_name_alias'=>'trans',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('transporter_id'=>'user_id'),
				'select_fields'=>array('first_name trans_first_name','last_name trans_last_name','email trans_email','phone_no trans_phone_no')
			),
			array(
				'table_name'=>$this->tableNameUser,
				'table_name_alias'=>'driver',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('driver_id'=>'user_id'),
				'select_fields'=>array('first_name driver_first_name','last_name driver_last_name','email driver_email','phone_no driver_phone_no','image driver_image')
			),
			array(
				'table_name'=>$this->tableNameVehicle,
				'table_name_alias'=>'',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('vehicle_id'=>'vehicle_id'),
				'select_fields'=>array('plate_no'),
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameRequestBid,
				'table_name_alias'=>'',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>$bid_join_type,
				'join_on'=>array('bid_id'=>'bid_id'),
				'select_fields'=>array('bid_id','bid_amount','bid_status','bid_comment','cancel_comment','create_date bid_create_date'),
				'conditions'=>$bid_assos
			),
			/*array(
				'table_name'=>$this->tableNameRequestBid,
				'table_name_alias'=>'totBid',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('request_id'=>'request_id'),
				'select_fields'=>'IFNULL(count(totBid.request_id),0) total_bids',
				'conditions'=>array()
			),*/
			array(
				'table_name'=>" (SELECT RB.request_id FROM $this->dbprefix$this->tableNameRequestBid AS RB, $this->dbprefix$this->tableNameUser AS TU WHERE RB.user_id=TU.user_id AND RB.is_deleted='0' AND TU.is_deleted='0') ",
				'is_custom'=>'1',
				'table_name_alias'=>'totBid',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('request_id'=>'request_id'),
				'select_fields'=>'IFNULL(count(totBid.request_id),0) total_bids',
				'conditions'=>array()
			),
			// rating section
			array(
				'table_name'=>$this->tableNameUserRating,
				'table_name_alias'=>'cusrating',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'receiver_user_id'),
				'select_fields'=>'IFNULL(TRUNCATE(AVG(cusrating.rating),2),0) cus_rating',
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameUserRating,
				'table_name_alias'=>'transrating',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('transporter_id'=>'receiver_user_id'),
				'select_fields'=>'IFNULL(TRUNCATE(AVG(transrating.rating),2),0) trans_rating',
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameUserRating,
				'table_name_alias'=>'driverrating',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('driver_id'=>'receiver_user_id'),
				'select_fields'=>'IFNULL(TRUNCATE(AVG(driverrating.rating),2),0) driver_rating',
				'conditions'=>array()
			),
			//rating of the request
			array(
				'table_name'=>$this->tableNameUserRating,
				'table_name_alias'=>'requsertating',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'receiver_user_id','request_id'=>'request_id'),
				'oncond'=>array('is_deleted'=>'0'),
				'select_fields'=>array('rating_id cus_req_rating_id','rating cus_req_rating','communication_rating cus_req_communication_rating','trust_rating cus_req_trust_rating','quality_rating cus_req_quality_rating','user_comment cus_req_user_comment'),
				'conditions'=>array()
			),
			array(
				'table_name'=>$this->tableNameUserRating,
				'table_name_alias'=>'reqtranstating',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>'left',
				'join_on'=>array('transporter_id'=>'receiver_user_id','request_id'=>'request_id'),
				'oncond'=>array('is_deleted'=>'0'),
				'select_fields'=>array('rating_id trans_req_rating_id','rating trans_req_rating','communication_rating trans_req_communication_rating','trust_rating trans_req_trust_rating','quality_rating trans_req_quality_rating','user_comment trans_req_user_comment'),
				'conditions'=>array()
			),
		);
		
		if(!empty($trans_bid_cond)){
			$bid_join_type="left";
			if(!empty($trans_bid_assos)){
				$bid_join_type="inner";
				$trans_bid_cond = array_merge($trans_bid_cond,$trans_bid_assos);
			}
			
			$joins[]=array(
				'table_name'=>$this->tableNameRequestBid,
				'table_name_alias'=>'trans_bid',
				'join_with'=>$this->tableNameRequest,
				'join_type'=>$bid_join_type,
				'join_on'=>array('request_id'=>'request_id'),
				'oncond'=>$trans_bid_cond,
				'select_fields'=>array('bid_id trans_bid_id','bid_amount trans_bid_amount','bid_status trans_bid_status','bid_comment trans_bid_comment','cancel_comment trans_cancel_comment','create_date trans_bid_create_date'),
				'conditions'=>array(),
			);
		}
		
		$group_by=array('request_id');
		$services = $this->BaseModel->getDatas($this->tableNameRequest,$find_cond,$select_flds,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		if(!$is_count && is_array($services) && !empty($services)){
			$trialer_image_path=base_url('uploads/trailers/');
			foreach($services as $key=>$service){
				if(!empty($service['trailer_image'])){
					$service['trailer_image']=$trialer_image_path.$service['trailer_image'];
				}
				if(!empty($service['cus_image'])){
					$service['cus_image']=base_url('uploads/users/'.$service['cus_image']);
				}
				if(!empty($service['driver_image'])){
					$service['driver_image']=base_url('uploads/users/'.$service['driver_image']);
				}
				if(!empty($service['request_image'])){
					$service['request_image']=base_url('uploads/requests/'.$service['request_image']);
				}
				
				if(empty($service['completed_date'])){
					$service['completed_date']='';
				}
				if(empty($service['deleted_date'])){
					$service['deleted_date']='';
				}
				$driver_location=array();
				if(!empty($service['driver_location'])){
					$driver_location = explode(",",$service['driver_location']);
				}
				$service['driver_latitude']=(isset($driver_location[0]))?trim($driver_location[0]):'';
				$service['driver_longitude']=(isset($driver_location[1]))?trim($driver_location[1]):'';
				unset($service['driver_location']);
				
				$services[$key]=$service;
			}
		}
		return $services;
	}
	
	public function getrequestbids($find_cond=array(),$extra=array()){
		$select_fields=array('bid_id','bid_amount','bid_status','user_id','bid_comment','cancel_comment','create_date');
		
		$select_fields=(isset($extra['select_fields']))?$extra['select_fields']:$select_fields;
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$search_text=(isset($extra['search_text']))?$extra['search_text']:'';
		$complexCondition=array();
		$conditions=array();
		if(!empty($search_text)){
			if(filter_var($search_text,FILTER_VALIDATE_EMAIL)){
				$conditions=array(
					'like'=>array('email'=>$search_text)
				);
				$complexCondition="email like '".$search_text."%'";
			}
			else{
				$conditions=array(
					'like'=>array('first_name'=>$search_text)
				);
				
				//$complexCondition="first_name like '".$first_name."%' OR last_name LIKE '%".$last_name."'";
				$complexCondition="concat(first_name,' ',last_name) LIKE '%".$search_text."%'";
			}
			$conditions=array();
		}
		$group_by=array();
		$joins=array(
			array(
				'table_name'=>$this->tableNameUser,
				'table_name_alias'=>'trans',
				'join_with'=>$this->tableNameRequestBid,
				'join_type'=>'inner',
				'join_on'=>array('user_id'=>'user_id'),
				'oncond'=>array('is_deleted'=>'0'),
				'select_fields'=>array('company_name trans_first_name','last_name trans_last_name','email trans_email','phone_no trans_phone_no','image trans_image','company_name trans_company_name'),
				'conditions'=>$conditions
			),
			array(
				'table_name'=>"(SELECT R.transporter_id,count(R.transporter_id) total_rec FROM $this->dbprefix$this->tableNameRequest AS R WHERE R.request_status='".REQUEST_COMPLED_STATUS."'  GROUP BY R.transporter_id)",
				'table_name_alias'=>'totcomp',
				'is_custom'=>'1',
				'join_with'=>$this->tableNameRequestBid,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'transporter_id'),
				'select_fields'=>'IFNULL((totcomp.total_rec),0) completed_request',
				'conditions'=>array()
			),
			array(
				'table_name'=>"(SELECT BP.user_id p_user_id,count(BP.user_id) total_place_rec FROM $this->dbprefix$this->tableNameRequestBid AS BP WHERE BP.is_deleted='0' GROUP BY BP.user_id) ",
				'table_name_alias'=>'totalreq',
				'is_custom'=>'1',
				'join_with'=>$this->tableNameRequestBid,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'p_user_id'),
				'select_fields'=>'IFNULL(totalreq.total_place_rec,0) total_request',
				'conditions'=>array()
			)
		);
		
		if(!$is_count){
			$joins[]=array(
				'table_name'=>"( SELECT TRUNCATE(AVG(rating),2) rating,RT.receiver_user_id FROM $this->dbprefix$this->tableNameUserRating AS RT WHERE RT.is_deleted='0' AND RT.is_blocked='0' GROUP BY RT.receiver_user_id ) ",
				'table_name_alias'=>'URT',
				'is_custom'=>'1',
				'join_with'=>$this->tableNameRequestBid,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'receiver_user_id'),
				'select_fields'=>'IFNULL(URT.rating,0) rating',
			);
		}
		
		$bids = $this->BaseModel->getDatas($this->tableNameRequestBid,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		if(!$is_count && is_array($bids) && !empty($bids)){
			foreach($bids as $key=>$bid){
				if(!empty($bid['trans_image'])){
					$bid['trans_image']=base_url('uploads/users/'.$bid['trans_image']);
				}
				if(!empty($bid['trans_last_name'])){
					$bid['trans_last_name']='';
				}
				$bids[$key]=$bid;
			}
		}
		return $bids;
	}
	
	public function gettrailers($find_conds=array()){
		$select_fields=array('trailer_id','name','image','min_load','max_load');
		$joins=array(
			array(
				'table_name'=>$this->tableNameLanguageTrailer,
				'join_with'=>$this->tableNameTrailer,
				'join_with_alias'=>'',
				'join_type'=>'left',
				'join_on'=>array('trailer_id'=>'trailer_id'),
				'oncond'=>array('is_blocked'=>'0','is_deleted'=>'0','language_id'=>$this->language_id),
				'select_fields'=>'IFNULL(trailer_name,name) name'
			)
		);
		$trailers = $this->BaseModel->getDatas($this->tableNameTrailer,$find_conds,$select_fields,array(),$joins);
		if(!empty($trailers)){
			foreach($trailers as $key=>$trailer){
				if(!empty($trailer['image'])){
					$trailer['image'] = base_url('uploads/trailers/'.$trailer['image']);
				}
				$trailers[$key]=$trailer;
			}
		}
		return $trailers;
	}
	
	public function getrequeststatus(){
		$request_status = array(
			'0'=>'New',
			'1'=>'Bid Placed',
			'2'=>'Bid Accepted By Customer',
			'3'=>'Transporter Accepted',
			'4'=>'Bid Cancelled',
			'5'=>'Driver & Vehicle Assigned',
			'6'=>'In transit',
			'13'=>'Completed',
			'14'=>'Expired'
		);
		return $request_status;
	}
	
	public function getdriverchangestatus(){
		$request_status=array(
			'6'=>'Arriving',
			'7'=>'Loading',
			'8'=>'Loaded',
			'9'=>'Trip start',
			'10'=>'Reached',
			'11'=>'Unloading',
			'12'=>'Unloaded',
			'13'=>'Completed',
		);
		return $request_status;
	}
	
	public function getuserstatus(){
		$user_status=array(
			'1'=>'Available',
			'2'=>'Assigned',
			'3'=>'In Transit'
		);
		if($this->language_id==2){ //spanish section
			$user_status=array(
				'1'=>'Disponible',
				'2'=>'Asignado',
				'3'=>'En tránsito'
			);
		}
		return $user_status;
	}
	
	public function getvehiclestatus(){
		$user_status=array(
			'1'=>'Available',
			'2'=>'Assigned',
			'3'=>'In Transit'
		);
		if($this->language_id==2){
			$user_status=array(
				'1'=>'Disponible',
				'2'=>'Asignado',
				'3'=>'En tránsito'
			);
		}
		return $user_status;
	}
	
	public function getbidstatus(){
		$bid_status=array(
			'0'=>'Placed',
			'1'=>'Customer Accepted',
			'2'=>'Transporter Accepted',
			'3'=>'Transporter Cancelled',
			'4'=>'Transporter Lost'
		);
	}
	
	public function getvehicles($find_cond=array(),$extra=array()){
		$select_fields=array('vehicle_id','trailer_id','plate_no','purchase_year','is_blocked','vehicle_status','vehicle_maxload','vehicle_minload');
		$complexCondition=array();
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		// search section 
		$search_text=(isset($extra['search_text']))?$extra['search_text']:'';
		if(!empty($search_text)){
			$complexCondition = "plate_no LIKE '%".$search_text."%'";
		}
		$group_by=array();
		$joins=array(
			array(
				'table_name'=>$this->tableNameTrailer,
				'join_with'=>$this->tableNameVehicle,
				'join_type'=>'inner',
				'join_on'=>array('trailer_id'=>'trailer_id'),
				'select_fields'=>array('name trailer_name','max_load','min_load')
			)
		);
		$vehicles = $this->BaseModel->getDatas($this->tableNameVehicle,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		if(!$is_count && !empty($vehicles) ){
			foreach($vehicles as $key=>$vehicle){
				$vehicles[$key]['vehicle_status_text']=vehicle_status($vehicle['vehicle_status']);
			}
		}
		return $vehicles;
	}
	
	public function getdrivers($find_cond=array(),$extra=array()){
		$select_fields=array('user_id','first_name','last_name','phone_no','email','dni_no','ruc_no','company_licence_no','licence_no','image','is_blocked','user_status');
		$complexCondition=array();
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$search_text=(isset($extra['search_text']))?$extra['search_text']:'';
		if(!empty($search_text)){
			$complexCondition = "(concat(first_name,' ',last_name) LIKE '%".$search_text."%' OR phone_no LIKE '%".$search_text."%')";
			if(filter_var($search_text,FILTER_VALIDATE_EMAIL)){
				$complexCondition = "email LIKE '%".$search_text."%'";
			}
		}
		$group_by=array('user_id');
		$joins=array(
			array(
				'table_name'=>$this->tableNameUserRating,
				'join_with'=>$this->tableNameUser,
				'join_type'=>'left',
				'join_on'=>array('user_id'=>'receiver_user_id'),
				'select_fields'=>'IFNULL(TRUNCATE(AVG(rating),2),0) rating',
				'oncond'=>array('is_deleted'=>'0')
			)
		);
		$drivers = $this->BaseModel->getDatas($this->tableNameUser,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		//for loap for image section 
		if(!$is_count && !empty($drivers)){
			foreach($drivers as $key=>$driver){
				if(!empty($driver['image'])){
					$driver['image']=base_url('uploads/users/'.$driver['image']);
					$drivers[$key]=$driver;
				}
				$drivers[$key]['user_status_text']=user_status($driver['user_status']);
				//phone no format
			}
		}
		return $drivers;
	}
	
	public function getuserdocuments($find_cond=array(),$extra=array()){
		$select_fields=array('user_document_id','user_id','documenttype_id','file_name','document_status','is_blocked');
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$complexCondition=array();
		$group_by=array();
		$joins=array(
			array(
				'table_name'=>$this->tableNameDocumentType,
				'join_with'=>$this->tableNameUserDocument,
				'join_type'=>'inner',
				'join_on'=>array('documenttype_id'=>'documenttype_id'),
				'select_fields'=>array('document_title')
			)
		);
		$documents = $this->BaseModel->getDatas($this->tableNameUserDocument,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		//for loap for image section 
		if(!$is_count && !empty($documents)){
			foreach($documents as $key=>$document){
				if(!empty($document['file_name'])){
					$document['file_name']=base_url('uploads/documents/'.$document['file_name']);
					$documents[$key]=$document;
				}
				//phone no format
			}
		}
		return $documents;
	}
	
	public function getvehicledocuments($find_cond=array(),$extra=array()){
		$select_fields=array('vehicle_document_id','user_id','vehicle_id','documenttype_id','file_name','document_status','is_blocked');
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$complexCondition=array();
		$group_by=array();
		$joins=array(
			array(
				'table_name'=>$this->tableNameDocumentType,
				'join_with'=>$this->tableNameVehicleDocument,
				'join_type'=>'inner',
				'join_on'=>array('documenttype_id'=>'documenttype_id'),
				'select_fields'=>array('document_title')
			)
		);
		$documents = $this->BaseModel->getDatas($this->tableNameVehicleDocument,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		//for loap for image section 
		if(!$is_count && !empty($documents)){
			foreach($documents as $key=>$document){
				if(!empty($document['file_name'])){
					$document['file_name']=base_url('uploads/documents/'.$document['file_name']);
					$documents[$key]=$document;
				}
				//phone no format
			}
		}
		return $documents;
	}
	
	public function getchats($find_cond=array(),$extra=array()){
		$select_fields=array('chat_id','user_id','transporter_id','request_id','create_date');
		$user_type=(isset($extra['user_type']))?$extra['user_type']:0;
		
		$select_fields=(isset($extra['select_fields']))?$extra['select_fields']:$select_fields;
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$complexCondition=array();
		$group_by=array();
		
		$joins=array(
			array(
				'table_name'=>$this->tableNameRequest,
				'join_with'=>$this->tableNameChat,
				'join_type'=>'inner',
				'join_on'=>array('request_id'=>'request_id'),
				'select_fields'=>array('pickup_location','pickup_date','pickup_time','request_amount')
			),
			array(
				'table_name'=>$this->tableNameMessage,
				'join_with'=>$this->tableNameChat,
				'join_type'=>'left',
				'join_on'=>array('chat_id'=>'chat_id'),
				'oncond'=>" message_id = (SELECT message_id FROM $this->dbprefix$this->tableNameMessage WHERE chat_id=$this->dbprefix$this->tableNameChat.chat_id AND is_blocked='0' AND is_deleted='0' ORDER BY message_id DESC LIMIT 1) ",
				'select_fields'=>array('user_id message_creater_id','message_type','message_data','create_date message_create_date')
			)
		);
		
		if($user_type==1){ // transporter want to see user detail
			$joins[]=array(
				'table_name'=>$this->tableNameUser,
				'join_with'=>$this->tableNameChat,
				'table_name_alias'=>'',
				'join_type'=>'inner',
				'join_on'=>array('user_id'=>'user_id'),
				'select_fields'=>array('user_id','first_name','last_name','email','phone_no','image')
			);
		}
		else{ // user section want to see the transporter
			$joins[]=array(
				'table_name'=>$this->tableNameUser,
				'join_with'=>$this->tableNameChat,
				'table_name_alias'=>'',
				'join_type'=>'inner',
				'join_on'=>array('transporter_id'=>'user_id'),
				'select_fields'=>array('user_id','first_name','last_name','email','phone_no','image')
			);
		}
		
		$chats = $this->BaseModel->getDatas($this->tableNameChat,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		//for loap for image section 
		if(!$is_count && !empty($chats)){
			foreach($chats as $key=>$chat){
				if(!empty($chat['image'])){
					$chat['image']=base_url('uploads/users/'.$chat['image']);
					$chats[$key]=$chat;
				}
			}
		}
		return $chats;
	}
	
	public function getmessages($find_cond=array(),$extra=array()){
		$select_fields=array('message_id','chat_id','user_id','message_type','message_data','create_date');
		$user_id=(isset($extra['user_id']))?$extra['user_id']:0;
		
		$select_fields=(isset($extra['select_fields']))?$extra['select_fields']:$select_fields;
		$order_by=(isset($extra['order_by']))?$extra['order_by']:array();
		$is_count=(isset($extra['is_count']))?$extra['is_count']:0;
		$offset=(isset($extra['offset']))?$extra['offset']:0;
		$limit=(isset($extra['limit']))?$extra['limit']:0;
		$complexCondition=array();
		$complexCondition='user_deleted_id IS NULL';
		
		$group_by=array();
		
		$joins=array(
			array(
				'table_name'=>$this->tableNameUser,
				'join_with'=>$this->tableNameMessage,
				'join_type'=>'inner',
				'join_on'=>array('user_id'=>'user_id'),
				'select_fields'=>array('first_name','last_name','image')
			),
			array(
				'table_name'=>$this->tableNameUserDeleteMessage,
				'join_with'=>$this->tableNameMessage,
				'join_type'=>'left',
				'join_on'=>array('message_id'=>'message_id'),
				'oncond'=>array('user_id'=>$user_id),
				//'select_fields'=>array('user_deleted_id','create_date')
			),
		);
		
		$messages = $this->BaseModel->getDatas($this->tableNameMessage,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		
		//for loap for image section 
		if(!$is_count && !empty($messages)){
			foreach($messages as $key=>$message){
				$is_mine=0;
				if(!empty($message['image'])){
					$message['image']=base_url('uploads/users/'.$message['image']);
				}
				if($message['message_type']==1 && !empty($message['message_data'])){
					$message['message_data']=base_url('uploads/chats/'.$message['message_data']);
				}
				if($message['user_id']==$user_id){
					$is_mine=1;
				}
				$message['is_mine']=$is_mine;
				$messages[$key]=$message;
			}
		}
		return $messages;
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
			if($user_type==1){
				// transport section
				
			}
			else{
				$find_cond['user_id']=$user_id;
			}
			
		}
		return $request_summery;
	}
	
	public function getnotifications($find_cond=array(),$extra=array()){
		$select_fields=array('notification_id','request_id','user_id','receiver_user_id','notification_type','notification_text','is_read','create_date');
		
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
				'join_with'=>$this->tableNameRequestNotification,
				'join_type'=>'inner',
				'join_on'=>array('user_id'=>'user_id'),
				'select_fields'=>array('first_name','last_name','image')
			),
		);
		
		$notifications = $this->BaseModel->getDatas($this->tableNameRequestNotification,$find_cond,$select_fields,$order_by,$joins,$offset,$limit,$complexCondition,$group_by,$is_count);
		
		//for loap for image section 
		if(!$is_count && !empty($notifications)){
			$notification_data=array(
				'request_id'=>'0',
				'user_id'=>'0',
			);
			foreach($notifications as $key=>$notification){
				if(!empty($notification['image'])){
					$notification['image']=base_url('uploads/users/'.$notification['image']);
				}
				$notification_data['request_id']=$notification['request_id'];
				$notification_data['notification_type']=$notification['notification_type'];
				
				$notify_text = $this->getnotificationtypes($notification_data);
				if(!empty($notify_text)){
					$notification['notification_text']=$notify_text;
				}
				$notifications[$key]=$notification;
			}
		}
		return $notifications;
	}
	
	public function getnotificationtypes($data=array()){
		$request_id=(isset($data['request_id']))?$data['request_id']:0;
		$notification_type=(isset($data['notification_type']))?$data['notification_type']:0;
		$amount=number_format(((isset($data['amount']))?$data['amount']:'0'),2);
		
		$noty_type=array(
			'1'=>array( // place bid
				'1'=>"placed a new bid in the request #{$request_id}",// english
				'2'=>"Ha recibido una propuesta en la Orden #{$request_id}",// spanish
			),
			'2'=>array( // customer accept bid
				'1'=>"You have won a bid #{$request_id}",
				'2'=>"Gano la Subasta! #{$request_id}"
			),
			'3'=>array( // trans approved
				'1'=>"approved your awarded bid of the request #{$request_id}",
				'2'=>"approved your awarded bid of the request #{$request_id}"
			),
			'4'=>array( // trans cancelled bid
				'1'=>"cancelled your awarded bid of the request #{$request_id}",
				'2'=>"cancelled your awarded bid of the request #{$request_id}"
			),
			'5'=>array( // driver and vehicle assigned
				'1'=>"driver and vehicle assigned in your request #{$request_id}",
				'2'=>"Ya puede iniciar el Trackeo para su Orden #{$request_id}"
			),
			'6'=>array( // driver assing into a request 
				'1'=>"assigned you in a new request #{$request_id} for transport",
				'2'=>"assigned you in a new request #{$request_id} for transport",
			),
			'7'=>array( // driver arriving
				'1'=>"arriving for pickup of request #{$request_id}",
				'2'=>"arriving for pickup of request #{$request_id}",
			),
			'8'=>array( // driver arrived
				'1'=>"arrived for pickup at location for request #{$request_id}",
				'2'=>"arrived for pickup at location for request #{$request_id}",
			),
			'9'=>array( // loading goods
				'1'=>"#{$request_id} loading",
				'2'=>"#{$request_id} loading",
			),
			'10'=>array( // loading finished
				'1'=>"#{$request_id} has loaded.",
				'2'=>"#{$request_id} ha terminado de cargar."
			),
			'11'=>array( // trip started
				'1'=>"leave the pickup location for delever for request #{$request_id}",
				'2'=>"leave the pickup location for delever for request #{$request_id} ",
			),
			'12'=>array( //arrive at deliver place
				'1'=>"arrived at the dropoff location for request #{$request_id}",
				'2'=>"arrived at the dropoff location for request #{$request_id}",
			),
			'13'=>array( // unloading
				'1'=>"#{$request_id} unloading",
				'2'=>"#{$request_id} unloading",
			),
			'14'=>array( // unloading done 
				'1'=>"#{$request_id} unloading finished",
				'2'=>"#{$request_id} unloading finished",
			),
			'15'=>array( // completed
				'1'=>"#{$request_id} has been completed",
				'2'=>"#{$request_id} ha terminado el servicio.",
			),
		);
		if($notification_type){
			return $noty_type[$notification_type][$this->language_id];
		}
		else{
			return $noty_type;
		}
		
	}
	
	//file upload section 
	public function uploadimage($image=array(),$folder=''){
		$file_name='';
		if(isset($image['name']) && !empty($image['name'])){
			$file_name = time().'_'.$this->strreplace($image['name']);
			//$mime_type = $image_file['myme_type'];
			// file uploaded code pending
			$destination_path='uploads/';
			if(!empty($folder)){
				$destination_path.=$folder."/";
			}
			if(!move_uploaded_file($image['tmp_name'],$destination_path.$file_name)){
				$file_name='';
			}
		}
		return $file_name;
	}
	
	private function strreplace($strname=''){
		$replace_array=array('@','#',' ','(',')','^','&','%','$','!','+','`','~','=');
		$strname = str_replace($replace_array ,"", $strname);
		return $strname;
	}
	
	public function removeimage($image_name='',$folder=''){
		if(!empty($image_name)){
			$destination_path='uploads/';
			if(!empty($folder)){
				$destination_path.=$folder.'/';
			}
			$image_path = $destination_path.$image_name;
			if(file_exists($image_path)){
				unlink($image_path);
			}
		}
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
				
				$this->email->from('no-reply@bauenfreight.com','BAUEN FREIGHT');
				$this->email->to($email);
				$this->email->subject($subjects);
				$this->email->message($messages);
				$this->email->set_alt_message('BAUEN FREIGHT Default Message');
				$this->email->send();
			}
		}
	}
	
	public function dateformatvalidate($date=''){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
			return true;
		} else {
			return false;
		}
	}
	
	public function requestmapimage($locations=array(), $request_id = 0,$is_saved=0){
		$image_name='';
		$is_image_generat=true;
		if(empty($locations)){
			// need to get the location objects of the request 
			if(!empty($request_id)){
				$find_request = array(
					'request_id'=>$request_id
				);
				$request = $this->BaseModel->getData($this->tableNameRequest,$find_request);
				if(!empty($request)){
					$locations['markers'][]=array(
						'lat'=>$request['pickup_latitude'],
						'long'=>$request['pickup_longitude'],
						'place'=>'pickup',
					);
					$locations['markers'][]=array(
						'lat'=>$request['dropoff_latitude'],
						'long'=>$request['dropoff_longitude'],
						'place'=>'dropoff',
					);
				}
				else{
					$is_image_generat=false;
				}
			}
			else{
				$is_image_generat=false;
			}
		}
		// map image create section 
		$markers = "";
		$patha = "";
		if($is_image_generat && !empty($locations)){
			//$api_key="AIzaSyD7R2SUMfghvkHnFGCV4S8zSO5ijPTKCyc"; // mrinfire secrion api remove later after testing
			$api_key="AIzaSyD7R2SUMfghvkHnFGCV4S8zSO5ijPTKCyc";
			$map_url = "https://maps.googleapis.com/maps/api/staticmap?key=$api_key&size=320x180&format=JPEG";
			$marker_points = isset($locations['markers'])?$locations['markers']:array();
			$path_points = isset($locations['path'])?$locations['path']:array();
			$start_point= array();
			$end_point = array();
			if(!empty($marker_points)){
				$color="green";
				foreach($marker_points as $marker){
					$place = $marker['place'];
					$lat = $marker['lat'];
					$long = $marker['long'];
					$markers.="&markers=color:$color%7Clabel:$place%7C$lat,$long";
					if(!empty($color)){
						$color="yellow";
					}
					if(empty($start_point)){
						$start_point[]=array(
							'lat'=>$lat,
							'long'=>$long
						);
					}
					else{
						if(empty($end_point)){
							$end_point[]=array(
								'lat'=>$lat,
								'long'=>$long
							);
						}
						
					}
				}
			}
			
			if(!empty($path_points)){
				// need to find all the path of the request
				// merge all the lat long
				$path_points = array_merge($start_point,$path_points,$end_point);
				$patha="&path=color:red|weight:2";
				foreach($path_points as $path_point){
					$patha.="|".$path_point['lat'].",".$path_point['long'];
				}
				
			}
			
			$map_url.=$markers.$patha;
			
			$image_name = time()."request_".$request_id.".jpg";
			copy($map_url,'uploads/requests/'.$image_name);
			
			/*
			$imageString = file_get_contents($map_url);
			file_put_contents('uploads/requests/'.$image_name,$imageString);
			*/
			
			if($is_saved=='1' && $request_id>0){
				$update_cond=array(
					'request_id'=>$request_id
				);
				$update_data=array(
					'request_image'=>$image_name
				);
				$this->BaseModel->updateDatas($this->tableNameRequest,$update_data,$update_cond);
			}
			//$image_name=$map_url;
		}
		return $image_name;
	}
}
?>