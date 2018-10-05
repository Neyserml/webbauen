<?php

namespace App\model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class user extends Model
{
    protected $table = 'trns_users';
    public function get_user($data){
        $get_result_type = 0;
        if(isset($data['get_result_type']) && !empty($data['get_result_type'])){
        $get_result_type = $data['get_result_type'];
        }
        $user = DB::table('trns_users as us');
         if($get_result_type == 1) {
            $user->where('us.email', '=', "{$data['email']}");
            $user->where('us.password', '=', "{$data['password']}");
            $user->where(function ($q){
                    return $q->where('us.user_type', '=',0)->orWhere('us.user_type', '=',1);
                  });
            $result = $user->first();
         }elseif($get_result_type == 2) {
            $user->where('us.user_id', '=', "{$data['user_id']}");
            $result = $user->first();
         }elseif($get_result_type == 4) {
            $user->where('us.email', '=', "{$data['email']}");
            $result = $user->first();
         }elseif($get_result_type == 3) {
            $user->where('us.email', '=', "{$data['email']}");
            $user->orWhere('us.phone_no', '=', "{$data['phone_no']}");
            $result = $user->first();
         }else{
            $result = $user->get();
         }
        
        return $result;
    }
    public function count_requests($data){
        $get_result_type = 0;
        if(isset($data['get_result_type']) && !empty($data['get_result_type'])){
        $get_result_type = $data['get_result_type'];
        }
        $user = DB::table('trns_requests as requests');
        $user->select(DB::raw('count(requests.request_id) as total_row'),'request_status');
        $user->whereIn('request_status', [1,4,6,13]);
        $user->groupBy('requests.request_status');        
        $user->where('requests.user_id', '=', "{$data['user_id']}");     
        if($get_result_type == 1) {            
            $result = $user->get();
        }elseif($get_result_type == 2) {           
            $result = $user->get();
        }else{
            $result = $user->get();
            }
        return $result ;
        } 
		/*
    public function get_requests($data){
        $get_result_type = 0;
        if(isset($data['get_result_type']) && !empty($data['get_result_type'])){
        $get_result_type = $data['get_result_type'];
        }
        $requests = DB::table('trns_requests as requests');
        $requests->select(
                'requests.*',
                'sub_user.first_name as sub_first_name',
                'sub_user.last_name as sub_last_name',
                'sub_user.image as sub_image',
                'main_user.first_name as main_first_name',
                'main_user.last_name as main_last_name',
                'main_user.image as main_image'
                );    
        $requests->leftJoin('trns_users as sub_user', 'requests.creater_id', '=', 'sub_user.user_id');
        $requests->leftJoin('trns_users as main_user', 'requests.user_id', '=', 'main_user.user_id');
        $requests->where('requests.user_id', '=', "{$data['user_id']}");
        $requests->orderBy('requests.request_id', 'desc');
        if($get_result_type == 1) {
            $requests->where('requests.request_status', '=', 1); 
            $result = $requests->get();
        }elseif($get_result_type == 2) {
            $requests->where('requests.request_status', '=', 2); 
            $result = $requests->get();
        }elseif($get_result_type == 3) {
            $requests->where('requests.request_status', '=', 3); 
            $result = $requests->get();
        }elseif($get_result_type == 4) {
            $requests->where('requests.request_status', '=', 4); 
            $result = $requests->get();
        }elseif($get_result_type == 5) {
            $requests->where('requests.request_status', '=', 5); 
            $result = $requests->get();
        }elseif($get_result_type == 6) {
            $requests->where('requests.request_status', '=', 6); 
            $result = $requests->get();
        }elseif($get_result_type == 7) {
            $requests->where('requests.request_status', '=', 7); 
            $result = $requests->get();
        }elseif($get_result_type == 8) {
            $requests->where('requests.request_status', '=', 8); 
            $result = $requests->get();
        }elseif($get_result_type == 9) {
            $requests->where('requests.request_status', '=', 9); 
            $result = $requests->get();
        }elseif($get_result_type == 10) {
            $requests->where('requests.request_status', '=', 10); 
            $result = $requests->get();
        }elseif($get_result_type == 11) {
            $requests->where('requests.request_status', '=', 11); 
            $result = $requests->get();
        }elseif($get_result_type == 12) {
            $requests->where('requests.request_status', '=', 12); 
            $result = $requests->get();
        }elseif($get_result_type == 13) {
            $requests->where('requests.request_status', '=', 13); 
            $result = $requests->get();
        }else{
            $result = $requests->paginate(2);
            }
        return $result ;
        } */
	public function get_requests($data){
        $get_result_type = 0;
        if(isset($data['get_result_type']) && !empty($data['get_result_type'])){
        $get_result_type = $data['get_result_type'];
        }
        $requests = DB::table('trns_requests as requests');
        $requests->select(
                'requests.*',
                'sub_user.first_name as sub_first_name',
                'sub_user.last_name as sub_last_name',
                'sub_user.image as sub_image',
                'main_user.first_name as main_first_name',
                'main_user.last_name as main_last_name',
                'main_user.image as main_image'               
                );    
        $requests->leftJoin('trns_users as sub_user', 'requests.creater_id', '=', 'sub_user.user_id');
        $requests->leftJoin('trns_users as main_user', 'requests.user_id', '=', 'main_user.user_id');
        $requests->where('requests.user_id', '=', "{$data['user_id']}");
        $requests->orderBy('requests.request_id', 'desc');
        if($get_result_type == 1) {
            $requests->where('requests.request_status', '=', 1); 
            $result = $requests->get();
        }elseif($get_result_type == 2) {
            $requests->where('requests.request_status', '=', 2); 
            $result = $requests->get();
        }elseif($get_result_type == 3) {
            $requests->where('requests.request_status', '=', 3); 
            $result = $requests->get();
        }elseif($get_result_type == 4) {
            $requests->where('requests.request_status', '=', 4); 
            $result = $requests->get();
        }elseif($get_result_type == 5) {
            $requests->where('requests.request_status', '=', 5); 
            $result = $requests->get();
        }elseif($get_result_type == 6) {
            $requests->where('requests.request_status', '=', 6); 
            $result = $requests->get();
        }elseif($get_result_type == 7) {
            $requests->where('requests.request_status', '=', 7); 
            $result = $requests->get();
        }elseif($get_result_type == 8) {
            $requests->where('requests.request_status', '=', 8); 
            $result = $requests->get();
        }elseif($get_result_type == 9) {
            $requests->where('requests.request_status', '=', 9); 
            $result = $requests->get();
        }elseif($get_result_type == 10) {
            $requests->where('requests.request_status', '=', 10); 
            $result = $requests->get();
        }elseif($get_result_type == 11) {
            $requests->where('requests.request_status', '=', 11); 
            $result = $requests->get();
        }elseif($get_result_type == 12) {
            $requests->where('requests.request_status', '=', 12); 
            $result = $requests->get();
        }elseif($get_result_type == 13) {
            $requests->where('requests.request_status', '=', 13); 
            $result = $requests->get();
        }elseif($get_result_type == 14) {
            $requests->where('requests.request_status', '=', 14); 
            $result = $requests->get();
        }elseif($get_result_type == 15) {
            $requests->where('requests.request_id', '=',"{$data['request_id']}"); 
            $result = $requests->first();
        }else{
            $result = $requests->paginate(10);
            }
        return $result ;
        }
        public function get_requests_details($id){
            $requests = DB::table('trns_request_bids as bids');
            $requests->select(
                                'bids.*',
                                'main_user.first_name as main_first_name',
                                'main_user.last_name as main_last_name',
                                'main_user.image as main_image'  
                            );
            $requests->leftJoin('trns_users as main_user', 'bids.user_id', '=', 'main_user.user_id');
            $requests->where('bids.request_id', '=',$id); 
            $result = $requests->get();
            return $result ;
        }
    public function total_notification($data){
        $get_result_type = 0;
        if(isset($data['get_result_type']) && !empty($data['get_result_type'])){
        $get_result_type = $data['get_result_type'];
        }
        $notifications = DB::table('trns_request_notifications as notifications');
        $notifications->select('notifications.*');
        $notifications->where('notifications.receiver_user_id', '=', "{$data['user_id']}");    
        if($get_result_type == 1) {     
            $notifications->where('notifications.is_read', '=', 0);    
            $result = $notifications->get();
        }elseif($get_result_type == 2) {           
            $result = $notifications->get();
        }else{
            $result = $notifications->get();
            }
        return $result ;
        }
		 public function fiend_chat_list($data){
         $chat= DB::table('trns_messages as messages');
        $chat->select('messages.*',
                'user_trns.first_name as trns_name',
                'user_trns.image as trns_image',
                DB::raw('count(messages.message_id) as total_msg')
                ); 
        $chat->where('chats.user_id','=',$data['user_id']); 
        $chat->leftJoin('trns_chats as chats', 'messages.chat_id', '=', 'chats.chat_id');
        $chat->leftJoin('trns_users as user_trns', 'chats.transporter_id', '=', 'user_trns.user_id');
        $chat->orderby('messages.message_id','DESC');
        $chat->groupBy('chats.chat_id');
        $result = $chat->get();
        return $result;
    }
     public function chat_details($data){
         $chat= DB::table('trns_messages as messages');
        $chat->select('messages.*',
                'user_trns.first_name as trns_name',
                'user_trns.image as trns_image',
                'user_cust.first_name as cust_name',
                'user_cust.image as cust_image'    
                ); 
        $chat->where('messages.chat_id','=',$data['chat_id']); 
        $chat->leftJoin('trns_chats as chats', 'messages.chat_id', '=', 'chats.chat_id');
        $chat->leftJoin('trns_users as user_trns', 'chats.transporter_id', '=', 'user_trns.user_id');
        $chat->leftJoin('trns_users as user_cust', 'chats.user_id', '=', 'user_cust.user_id');
        //$chat->orderby('messages.message_id','DESC');
       // $chat->groupBy('chats.chat_id');
        $result = $chat->get();
        return $result;
    }


    public function get_user_request_keys($user_id){
        $requests = DB::table('trns_user_request_keys as request_keys');
        $requests->select(
                            'request_keys.*'
                        );
        $requests->leftJoin('trns_users as main_user', 'request_keys.user_id', '=', 'main_user.user_id');
        $requests->where('request_keys.user_id', '=',$user_id);
        $requests->where('request_keys.is_deleted','=',0);
        $result = $requests->get();
        return $result ;
    }


}
