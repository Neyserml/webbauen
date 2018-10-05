<?php

namespace App\Http\Controllers\Buy_Sell\auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\model\user;
use Validator;
use Illuminate\Support\Facades\Response;
use App\model\common_function;
use App\model\variable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Mail;

class web_auth_con extends Controller {

    private $result = 0;
    private $message = 0;
    private $details = 0;
    private $value = 0;
    private $validator_error = 0;

    public function __construct(Guard $auth) {
        $this->gurd = $auth;
        $this->common_model = new common_function();
        $this->user = new user();
        $this->variable = new variable();
    }

    public function web_login() {
        if (Session::has('web_user_id')) {
            return redirect('/shipper');
        } else {
            return view("bauenfreight.signin");
        }
    }

    public function web_registration() {
    	
        if (Session::has('web_user_id')) {
            return redirect('/');
        } else {
            $industry_id = $this->common_model->get_table("trns_industrytypes");
            return view("bauenfreight.signup",['industry_id'=>$industry_id]);
        }
    }

    public function web_post_login(Request $data) {
        $validator = Validator::make($data->all(), [
                    'password' => 'required',
                    'user_email' => 'required|email']
        );
        if ($validator->fails()) {
            $this->result = $this->variable->message[0];
            $this->message = $this->variable->message[30];
            $this->validator_error = $validator->errors();
            Session::put('message', 'Este campo es obligatorio');
            return redirect('signin');
        } else {
            $this->value = array(
                'password' => md5($data->input('password')),
                'email' => $data->input('user_email'),
                'get_result_type' => 1
            );
            $this->details = $this->user->get_user($this->value);
            if (!empty($this->details)) {
                if ($this->details->is_user_verify == 1) {
                    if ($this->details->super_parent_id > 0) {
                        Session::put('web_user_post_id', $this->details->super_parent_id);
                    } else {
                        Session::put('web_user_post_id', $this->details->user_id);
                    }
                    Session::put('web_user_id', $this->details->user_id);
                    Session::put('web_user_name', $this->details->first_name);
                    Session::put('web_user_email', $this->details->email);
                    return redirect('/shipper');
                } else {
                    Session::put('message', 'Tu cuenta aún no ha sido verificada. Por favor, verifica tu cuenta de correo para continuar.');
                    return redirect('signin');
                }
            } else {
                Session::put('message', trans('pdg.26'));
                return redirect('signin');
            }
        }
    }

    public function user_logout() {
        if (Session::has('web_user_id')) {
            Session::flush();
        }
        Session::put('message', "Se ha cerrado sesión sin ningún problema. Muchas gracias.");
        return redirect('signin');
    }

    public function web_company_post_signup(Request $data) {
       // RETURN $data->all();
        
          $validator = Validator::make($data->all(), [
                    'company_name' => 'required',
                    
                    'RUC_no' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'user_email' => 'required|email',
                    'phone_no' => 'required',
                    'company_password' => 'required',
                    'retypepassword' => 'required',
        ]);
        if ($validator->fails()) {
            Session::put('message', 'Este campo es obligatorio');
            return redirect('signin');
        }
        $table_name = 'trns_users';
        $check_data = array(
            'phone_no' => $data->input('phone_no'),
            'email' => $data->input('user_email'),
            'get_result_type' => 3
        );
        
        $check_result = $this->user->get_user($check_data);
        if (empty($check_result)) {
            $this->value = array(
                'first_name' => $data->input('first_name'),
                'last_name' => $data->input('last_name'),
                'firebase_id' => ' ',
                'email' => $data->input('user_email'),
                'phone_no' => $data->input('phone_no'),
                'is_user_verify' => 0,
        	'is_email_verify' => 0,
        	'email_verify_token' => rand(0, 1000),
                'password' => md5($data->input('company_password')),
                'user_type' => 1,
                'company_name' => $data->input('company_name'),
                'dni_no' => $data->input('RUC_no'),
                'create_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
                'is_company'=>1
            );
            $this->details = $this->common_model->insert_table($table_name, $this->value);
            if ($this->details > 0) {
                $link = url('user-active-account-' . time() . '-' . base64_encode($data->input('user_email')));
                $this->value['name'] = $data->input('first_name') . ' ' . $data->input('last_name');
                $this->value['active_account'] = $link;
                $email = $data->input('user_email');
                Mail::send('email.verifying_email', ['data' => $this->value], function($message) use ($email) {
                    $message->from('info@bauenfreight.com', 'Bauen Freight App');
                    $message->to($email)->subject('Verifying your email address:: Bauen Freight App');
                });
                Session::put('message', "Un link de verificación ha sido enviado a su correo. Presione el link que le hemos enviado para continuar.");
                return redirect('signin');
            
            } else {
                Session::put('message', "No se ha podido registrar el Usuario. Intente de nuevo.");
                return redirect('signup');
            }
        } else {
            Session::put('message', "Este correo electrónico o número de teléfono ya ha sido registrado con anterioridad.");
            return redirect('signup');
        }
    }

    public function web_post_signup(Request $data) {
        $table_name = 'trns_users';
        $check_data = array(
            'phone_no' => $data->input('phone_no'),
            'email' => $data->input('user_email'),
            'get_result_type' => 3
        );
        $check_result = $this->user->get_user($check_data);
        if (empty($check_result)) {
            $this->value = array(
                'first_name' => $data->input('first_name'),
                'last_name' => $data->input('last_name'),
                'email' => $data->input('user_email'),
                'firebase_id' => ' ',
                'phone_no' => $data->input('phone_no'),
				'is_user_verify' => 0,
				'is_email_verify' => 0,
				'email_verify_token' => rand(0, 1000),
                'password' => md5($data->input('password')),
                'user_type' => 0,
                'dni_no' => $data->input('dni_no'),
                'create_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
            );
            $this->details = $this->common_model->insert_table($table_name, $this->value);
            if ($this->details > 0) {
                $link = url('user-active-account-' . time() . '-' . base64_encode($data->input('user_email')));
                $this->value['name'] = $data->input('first_name') . ' ' . $data->input('last_name');
                $this->value['active_account'] = $link;
                $email = $data->input('user_email');
                Mail::send('email.verifying_email', ['data' => $this->value], function($message) use ($email) {
                    $message->from('info@bauenfreight.com', 'Bauen Freight App');
                    $message->to($email)->subject('Verifying your email address:: Bauen Freight App');
                });
                Session::put('message', "Un correo de verificación ha sido enviado a su correo. Presione el link que le hemos enviado para continuar.");
                return redirect('signin');
            } else {
                Session::put('message', "No se ha podido completar el Registro");
                return redirect('signup');
            }
        } else {
            Session::put('message', "Este correo electrónico o número de teléfono ya ha sido registrado con anterioridad.");
            return redirect('signup');
        }
    }
     public function user_active_account($time, $email) {
        if (!isset($time) || $time == "") {
            Session::put('message', "Este link no es válido");
            return redirect('signin');
        } elseif (!isset($email) || $email == "") {
            Session::put('message', "Este link no es válido");
            return redirect('signin');
        } else {
            $decoded_email = base64_decode($email);
            $current_time = time();
            $time_dif = $current_time - $time;
            if ($time_dif > 3600) {
                Session::put('message', "Se ha vencido la sesión. Inicie sesión nuevamente");
                return redirect('signin');
            } else {
                $check_data = array(
                    'email' => $decoded_email,
                    'get_result_type' => 4
                );
                $get_email_existence = $this->user->get_user($check_data);
                if (!empty($get_email_existence)) {
                   // return view('forgot_password.forgot', ['user' => $get_email_existence->user_id, 'done_flag' => 0]);
                    $value = array(
                        'is_user_verify' => 1,
                        'is_email_verify' => 1,
                        'email_verify_token' => rand(0, 1000),
                        'update_date' => date('Y-m-d H:i:s')
                    );
                    $result = $this->common_model->update_table('trns_users', $value, 'user_id', $get_email_existence->user_id);
                    if ($result > 0) {
                        Session::put('message', 'Cuenta activada de manera satisfactoria.');
                        return redirect('signin');
                    } else {
                        Session::put('message', 'No se ha podido activar la cuenta de Usuario. Pongánse en contacto con solcese@bauenfreight.com');
                        return redirect('signin');
                    }
                } else {
                    Session::put('message', "Este link ha vencido");
                    return redirect('signin');
                }
            }
        }
    }

    public function user_forgot_password(Request $data) {
        //$input = $data->all();
        if (empty($data->input('user_email'))) {
            Session::put('message', "Por favor ingrese el nombre de usuario (correo electrónico)");
            return redirect('signin');
        } else {
            $check_data = array(
                'email' => $data->input('user_email'),
                'get_result_type' => 4
            );
            $get_email_existence = $this->user->get_user($check_data);
            if ($get_email_existence && !empty($get_email_existence)) {
                $link = url('user-update-password-' . time() . '-' . base64_encode($data->input('user_email')));
                $userdata['name'] = $get_email_existence->first_name . ' ' . $get_email_existence->last_name;
                $userdata['forgot_password_url'] = $link;
                $email = $get_email_existence->email;

                Mail::send('forgot_password.forgot_password_email', ['data' => $userdata], function($message) use ($email) {
                    $message->from('info@bauenfreight.com', 'Bauen Freight App');
                    $message->to($email)->subject('Forgot Password :: Bauen Freight App');
                });
                Session::put('message', "Un correo para reestablecer contraseña ha sido enviado a su correo electrónico.");
                return redirect('signin');
            } else {
                Session::put('message', "Este correo no se encuentra registrado con nosotros");
                return redirect('signin');
            }
        }
    }

    public function user_update_password($time, $email) {
        if (!isset($time) || $time == "") {
            Session::put('message', "Este link no es válido");
            return redirect('signin');
        } elseif (!isset($email) || $email == "") {
            Session::put('message', "This link is not valid.");
            return redirect('signin');
        } else {
            $decoded_email = base64_decode($email);
            $current_time = time();
            $time_dif = $current_time - $time;
            if ($time_dif > 3600) {
                Session::put('message', "Este link no es válido");
                return redirect('signin');
            } else {
                $check_data = array(
                    'email' => $decoded_email,
                    'get_result_type' => 4
                );
                $get_email_existence = $this->user->get_user($check_data);
                if (!empty($get_email_existence)) {
                    return view('forgot_password.forgot', ['user' => $get_email_existence->user_id, 'done_flag' => 0]);
                } else {
                    Session::put('message', "Este link ha vencido.");
                    return redirect('signin');
                }
            }
        }
    }

    public function reset_user_password(Request $data) {
        //return $data->all();
        // $table_name = 'trns_users';
        $validator = Validator::make($data->all(), [
                    'user_id' => 'required',
                    'password_1' => 'required']
        );
        if ($validator->fails()) {
            Session::put('message', 'Este campo es obligatorio');
            return redirect('signin');
        } else {
            $value = array(
                'is_user_verify' => 1,
                'is_email_verify' => 1,
                'email_verify_token' => rand(0, 1000),
                'update_date' => date('Y-m-d H:i:s'),
                'password' => md5($data->input('password_1'))
            );
            $result = $this->common_model->update_table('trns_users', $value, 'user_id', $data->input('user_id'));
            if ($result > 0) {
                Session::put('message', 'Su contraseña ha sido reestablecida.');
                return redirect('signin');
            } else {
                Session::put('message', 'Su contraseña no pudo ser reestablecida, pongáse en contacto con solcese@bauenfreight.com');
                return redirect('signin');
            }
        }
    }

    public function send_your_message(Request $data) {
        $validator = Validator::make($data->all(), [
                    'client_name' => 'required',
                    'client_email' => 'required',
                    'client_message' => 'required'
        ]);
        if ($validator->fails()) {
            Session::put('message', 'Este campo es obligatorio');
            return redirect()->back();
        } else {
            $value = array(
                'client_name' => $data->input('client_name'),
                'client_email' => $data->input('client_email'),
                'client_message' => $data->input('client_message')
            );
            $email = "priyo.ncr@gmail.com";
            Mail::send('email.contact_us', ['data' => $value], function($message) use ($email) {
                $message->from('info@bauenfreight.com', 'Bauen Freight App');
                $message->to($email)->subject('Forgot Password :: Bauen Freight App');
            });
        }
        return redirect()->back();
    }

}
