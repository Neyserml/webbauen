<?php

namespace App\Http\Controllers\Buy_Sell\web;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\model\common_function;
use App\model\user;
use App\model\variable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;

class homecontroller extends Controller {

    private $result = 0;
    private $message = 0;
    private $details = 0;
    private $img_url = 0;

    public function __construct() {
        $this->user = new user();
        $this->common_model = new common_function();
        $this->variable = new variable();
    }

    public function index() {
        return view("bauenfreight.home");
    }

    public function about() {
        return view("bauenfreight.about");
    }

    public function how_it_works() {
//         return "test job";
        return view("bauenfreight.how_it _works");
    }

    public function carriers() {
        return view("bauenfreight.carriers");
    }

    public function shipper2() {
        $count_requests_data = array(
            'user_id' => Session::get('web_user_post_id'),
            'get_result_type' => 1
        );
        $count_requests = $this->user->count_requests($count_requests_data);
        $get_requests_data = array(
            'user_id' => Session::get('web_user_post_id'),
            'get_result_type' => 0
        );
        $get_requests_data = $this->user->get_requests($get_requests_data);
        // return array($count_requests);
        return view("bauenfreight.shipper", ['count_requests' => $count_requests, 'get_requests_data' => $get_requests_data]);
    }



   public function shipper() {

        $user_id = Session::get('web_user_post_id');
        $count_requests_data = array(
            'user_id' => $user_id,
            'get_result_type' => 1
        );
        $count_requests = $this->user->count_requests($count_requests_data);

        $get_requests_data = array(
            'user_id' => $user_id,
            'get_result_type' => 0
        );
        //return Session::get('web_user_post_id');

        $get_requests_data = $this->user->get_requests($get_requests_data);
        //return $get_requests_data;
        $get_user_request_keys_data = $this->user->get_user_request_keys($user_id);
        //return dd($get_requests_data);


        $device_type=$device_unique_code=$user_request_key=null;
        if (count($get_user_request_keys_data)>0){
              $user_request_key_object = $get_user_request_keys_data[0];
              $device_type = $user_request_key_object->device_type;
              $device_unique_code = $user_request_key_object->device_unique_id;
              $user_request_key = $user_request_key_object->request_key;


          }

          $user_data= array(
            'user_id'=> $user_id,//Session::get('web_user_post_id')
            'device_type' =>$device_type,
            'device_unique_code' => $device_unique_code,
            'user_request_key' => $user_request_key
          );




        
 
        return view("bauenfreight.shipper", ['count_requests' => $count_requests, 'get_requests_data' => $get_requests_data,'user_data'=>$user_data]);
    }




    public function contacts() {
        return view("bauenfreight.contacts");
    }

    public function your_quote() {
        $trailers_id = $this->common_model->get_table("trns_trailers");
        $loadtypes_id = $this->common_model->get_table("trns_loadtypes");
        
        return view("bauenfreight.your_quote", ['trailers_id' => $trailers_id, 'loadtypes_id' => $loadtypes_id]);
    }

    public function request_list() {

        $get_requests_data = array(
            'user_id' => Session::get('web_user_post_id'),
            'get_result_type' => 0
        );

        $get_requests_data = $this->user->get_requests($get_requests_data);
        // return $get_requests_data;

        return view("bauenfreight.request_list", ['get_requests_data' => $get_requests_data]);
    }

    public function request_list_details($data) {
        $val = explode("||", base64_decode($data));
        $id = $val[0];
        //return $id;
        $get_requests_data = array(
            'request_id' => $id,
            'user_id' => Session::get('web_user_post_id'),
            'get_result_type' => 15,
        );
        $get_requests_data = $this->user->get_requests($get_requests_data);
        $get_bid_list = $this->user->get_requests_details($id);
        //return $get_bid_list;
        return view("bauenfreight.request_list_details", ['requests_data' => $get_requests_data, 'get_bid_list' => $get_bid_list]);
    }

    public function message() {
        $this->value = array(
            'user_id' => Session::get('web_user_id'),
            'get_result_type' => 1
        );
        $this->details = $this->user->fiend_chat_list($this->value);
        // return  $this->details;
        return view("bauenfreight.message", ['message_list' => $this->details]);
    }

    public function message_details(Request $data) {
        $this->value = array(
            'chat_id' => $data->input('chat_id'),
            'get_result_type' => 1
        );
        $this->details = $this->user->chat_details($this->value);
        if (!empty($this->details)) {
            $this->result = $this->variable->message[1];
            $this->message = 'message get success';
        } else {
            $this->result = $this->variable->message[0];
            $this->message = 'message details not found';
        }
        return Response::make([
                    'result' => $this->result,
                    'message' => $this->message,
                    'details' => $this->details
        ]);
    }

    public function write_message(Request $data) {
        $result = 0;
        $validator = Validator::make($data->all(), [
                    'chat_id' => 'required',
                    'user_id' => 'required',
                    'message_data' => 'required'
        ]);
        if ($validator->fails()) {
            $this->result = $this->variable->message[0];
            $this->message = 'Please enter all value';
        } else {
            $this->value = array(
                'chat_id' => $data->input('chat_id'),
                'user_id' => $data->input('user_id'),
                'message_data' => $data->input('message_data'),
                'create_date' => date('Y-m-d H:i:s')
            );
            $result = $this->common_model->insert_table('trns_messages', $this->value);
            if ($result > 0) {
                $this->result = $this->variable->message[1];
                $this->message = 'Mensaje enviado satisfactoriamente';
            } else {
                $this->result = $this->variable->message[0];
                $this->message = 'Envio de mensaje fallido';
            }
        }
        return Response::make([
                    'result' => $this->result,
                    'message' => $this->message,
                    'details' => $result
        ]);
    }

    public function profile() {
        $this->img_url = $this->variable->message[2];
        $this->value = array(
            'user_id' => Session::get('web_user_id'),
            'get_result_type' => 2
        );
        $this->details = $this->user->get_user($this->value);
        if (!empty($this->details)) {
            return view("bauenfreight.profile", ['details' => $this->details, 'img_url' => $this->img_url]);
        }
        return view("bauenfreight.profile");
    }

    public function upload_user_image(Request $data) {
        $validator_img = Validator::make($data->all(), ['user_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
        $result = 0;
        if ($validator_img->fails()) {
            Session::put('message', 'This profile image not update .');
            return redirect('/profile');
        } else {
            $this->value = array(
                'user_id' => Session::get('web_user_id'),
                'get_result_type' => 2
            );
            $this->details = $this->user->get_user($this->value);
            $path = $this->variable->message[8] . $this->details->image;
            if (file_exists($path) && !empty($this->details->image)) {
                unlink($path);
            }
            $file = $data->file('user_image');
            $user_image = $file->getClientOriginalExtension();
            $user_image = time() . rand() . '.' . $user_image;
            //return $user_image;
            $destinationPath = $this->variable->message[8];
            $file->move($destinationPath, $user_image);
            $user_images_data = array(
                'user_id' => Session::get('web_user_id'),
                'image' => $user_image,
                'update_date' => date('Y-m-d H:i:s')
            );
            $result = $this->common_model->update_table('trns_users', $user_images_data, 'user_id', Session::get('web_user_id'));
        }
        if ($result > 0) {
            Session::put('message', 'This profile image  update succesfuly.');
            return redirect('/profile');
        } else {
            Session::put('message', 'This profile image not updated .');
            return redirect('/profile');
        }
    }

    public function past_bids() {
        // return view("bauenfreight.past_bids");
        $get_requests_data = array(
            'user_id' => Session::get('web_user_post_id'),
            'get_result_type' => 0
        );
        $get_requests_data = $this->user->get_requests($get_requests_data);
        return view("bauenfreight.past_bids", ['get_requests_data' => $get_requests_data]);
    }


    public function total_notification() {
        $this->value = array(
            'user_id' => Session::get('web_user_id'),
            'get_result_type' => 1
        );
        $this->details = $this->user->total_notification($this->value);
        if (!empty($this->details)) {
            $this->result = $this->variable->message[1];
            $this->message = "Notification found";
        } else {
            $this->result = $this->variable->message[0];
            $this->message = "Notification not found";
        }
        return Response::make([
                    'result' => $this->result,
                    'message' => $this->message,
                    'details' => $this->details
        ]);
    }

    public function request_accept($id) {
        $user_images_data = array(
            'request_status' => 2,
            'update_date' => date('Y-m-d H:i:s')
        );
        $this->details = $this->common_model->update_table('trns_requests', $user_images_data, 'request_id', $id);
        if ($this->details > 0) {
            $this->result = $this->variable->message[1];
            $this->message = "Request status updated successfully";
        } else {
            $this->result = $this->variable->message[0];
            $this->message = "Request status update failure";
        }
        Session::put('message', 'Thank , you accept this request .');
        return redirect('/request-list');
        /* return Response::make([
          'result' => $this->result,
          'message' => $this->message,
          'details' => $this->details
          ]); */
    }

    public function get_trailers() {
        $this->details = $this->user->get_trailers();
        if (!empty($this->details)) {
            $this->result = $this->variable->message[1];
            $this->message = "Get trailers list  successfully";
        } else {
            $this->result = $this->variable->message[0];
            $this->message = "Get trailers list failure";
        }
        return Response::make([
                    'result' => $this->result,
                    'message' => $this->message,
                    'details' => $this->details
        ]);
        //return $this->details;
    }

    public function post_request(Request $data) {

        $validator_img = Validator::make($data->all(), [
                    'pickup_location' => 'required',
                    'dropoff_location' => 'required',
                    'pickup_date' => 'required',
                    'pickup_time' => 'required',
                    'trailer_id' => 'required',
                    'loadtype_id' => 'required',
                    'weight' => 'required',
                    'size' => 'required',
                    'description' => 'required',
                    'pick_long' => 'required',
                    'pick_lat' => 'required',
                    'pick_place_id' => 'required',
                    'drop_long' => 'required',
                    'drop_lat' => 'required',
                    'drop_place_id' => 'required'
        ]);
        if ($validator_img->fails()) {
            $this->result = $this->variable->message[0];
            $this->message = $this->variable->message[30];
            $this->details = $validator_img->errors();
        } else {
            $this->value = array(
                'user_id' => Session::get('web_user_id'),
                'creater_id' => Session::get('web_user_post_id'),
                'pickup_location' => $data->input('pickup_location'),
                'dropoff_location' => $data->input('dropoff_location'),
                'pickup_date' => date('Y-m-d H:i:s', strtotime($data->input('pickup_date'))),
                'pickup_time' => $data->input('pickup_time'),
                'trailer_id' => $data->input('trailer_id'),
                'loadtype_id' => $data->input('loadtype_id'),
                'weight' => $data->input('weight'),
                'size' => $data->input('size'),
                'description' => $data->input('description'),
                'create_date' => date('Y-m-d H:i:s'),
                'pickup_place_id' => $data->input('pick_place_id'),
                'pickup_latitude' => $data->input('pick_lat'),
                'pickup_longitude' => $data->input('pick_long'),
                'dropoff_place_id' => $data->input('drop_place_id'),
                'dropoff_latitude' => $data->input('drop_lat'),
                'dropoff_longitude' => $data->input('drop_long')
            );
            $get_map_data['markers'][0] = array(
                'lat' => $this->value['pickup_latitude'],
                'long' => $this->value['pickup_longitude'],
                'place' => 'P',
            );
            $get_map_data['markers'][1] = array(
                'lat' => $this->value['dropoff_latitude'],
                'long' => $this->value['dropoff_longitude'],
                'place' => 'D',
            );
            $get_map = $this->common_model->get_map_by_lng_lat($get_map_data);
            
            /*             * *************************aaaaaaaaaaaaaaaaaaaaaaaaaa**************************************** */
            if (!empty($get_map)) {
                $this->value['request_image'] = $get_map;
                $this->details = $this->common_model->insert_table('trns_requests', $this->value);
                if ($this->details > 0) {
                    $this->result = $this->variable->message[1];
                    $this->message = 'request added successfully';
                } else {
                    $this->result = $this->variable->message[0];
                    $this->message = 'request added failure';
                }
            } else {
                $this->result = $this->variable->message[0];
                $this->message = 'Image not save';
            }
        }
        Session::put('message', $this->message);
        return redirect('/your-quote');
    }
	public function privacy_policy(){
        return view("bauenfreight.privacy_policy");
    }
    public function terms_and_conditions(){
        return view("bauenfreight.terms_and_conditions");
    }
    ///prueba request edit
    public function requestEdit($request_id,Request $data){

      $trailers_id = $this->common_model->get_table("trns_trailers");
      $dataArray =  $data->all();



     /* $this->common_model->find_table_by_field($'trns_requests', $field, $field_value, $id = null, $id_val = null)*/

      $pickup_date =  $dataArray['pickup_date'];
      $pickup_date_array = explode('-',$pickup_date);
      $dataArray['pickup_date'] =  $pickup_date_array[2]."/".$pickup_date_array[1]."/".$pickup_date_array[0];
      $pickup_time = $dataArray['pickup_time'];
      $pickup_time_array =  explode(':', $pickup_time);
      $pickup_time_array[0] = intval($pickup_time_array[0]);
      $pickup_time_array[1] = intval($pickup_time_array[1]);
      $dataArray['pickup_time'] = strval($pickup_time_array[0]).":".strval($pickup_time_array[1]);
      $dataArray['previous_request_image'] =  $dataArray['request_image'];
      
      return view("bauenfreight.request_list_edit",['data' => $dataArray,'trailers_id' => $trailers_id]);
    }

    public function softdelete_requests(Request $data) {
        //return $data['request_id'];
        //return $data->input('request_id');
        $result = $this->common_model->update_table('trns_requests', array('is_deleted'=>1), 'request_id', $data->input('request_id'));
        Session::put('message', 'Solicitud eliminada');
        return redirect('/request-list');

    }

    public function update_request(Request $data) {

        $validator_img = Validator::make($data->all(), [
                    'pickup_location' => 'required',
                    'dropoff_location' => 'required',
                    'pickup_date' => 'required',
                    'pickup_time' => 'required',
                    'trailer_id' => 'required',
                    'weight' => 'required',
                    'size' => 'required',
                    'description' => 'required',
                    'pick_long' => 'required',
                    'pick_lat' => 'required',
                    'pick_place_id' => 'required',
                    'drop_long' => 'required',
                    'drop_lat' => 'required',
                    'drop_place_id' => 'required'
        ]);
        if ($validator_img->fails()) {
            $this->result = $this->variable->message[0];
            $this->message = $this->variable->message[30];
$this->message = 'Error por campo requerido';


            $this->details = $validator_img->errors();

       
        } else {


            $this->value = array(
                'user_id' => Session::get('web_user_id'),
                'creater_id' => Session::get('web_user_post_id'),
                'pickup_location' => $data->input('pickup_location'),
                'dropoff_location' => $data->input('dropoff_location'),
                'pickup_date' => date('Y-m-d H:i:s', strtotime($data->input('pickup_date'))),
                'pickup_time' => $data->input('pickup_time'),
                'trailer_id' => $data->input('trailer_id'),
                'weight' => $data->input('weight'),
                'size' => $data->input('size'),
                'description' => $data->input('description'),
                'create_date' => date('Y-m-d H:i:s'),
                'pickup_place_id' => $data->input('pick_place_id'),
                'pickup_latitude' => $data->input('pick_lat'),
                'pickup_longitude' => $data->input('pick_long'),
                'dropoff_place_id' => $data->input('drop_place_id'),
                'dropoff_latitude' => $data->input('drop_lat'),
                'dropoff_longitude' => $data->input('drop_long')
            );
            $get_map_data['markers'][0] = array(
                'lat' => $this->value['pickup_latitude'],
                'long' => $this->value['pickup_longitude'],
                'place' => 'P',
            );
            $get_map_data['markers'][1] = array(
                'lat' => $this->value['dropoff_latitude'],
                'long' => $this->value['dropoff_longitude'],
                'place' => 'D',
            );
            $get_map = $this->common_model->get_map_by_lng_lat($get_map_data);

            // /***********************************aaaaaaaaaaaaaaaaaaaaaaaaaa**************************************** */
            if (!empty($get_map)) {
                
                $this->value['request_image'] = $get_map;
                $request_id = $data->input('request_id');


$update_data= array('description' => 'aaaaaaaaaa');

                $this->details = $this->common_model->update_table('trns_requests',$this->value,'request_id',$request_id);
                
                if ($this->details > 0) {
                    unlink('../uploads/requests/' . $data->input('previous_request_image'));
                    $this->result = $this->variable->message[1];
                    //$this->message = 'request added successfully';
                    $this->message = 'Solicitud actualizada satisfactoriamente';
                } else {
                    $this->result = $this->variable->message[0];
                    //$this->message = 'request added failure';
                    $this->message = 'Error al actualizar la solicitud';
                }
            } else {
                $this->result = $this->variable->message[0];
                $this->message = 'Image not save';
            }
        }


        Session::put('message', $this->message);
        return redirect('/request-list');
    }


}
