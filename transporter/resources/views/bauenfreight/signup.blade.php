@extends('../master_layout/web')

@section('custom_js')
<script src="{{asset('public/assets/bauenfreight/my_custom_js.js')}}"></script>
<script>
$(document).ready(function () {
     $('input[type="radio"][name="a"]').change(function() {
         var selected_val = $('input[type="radio"][name="a"]:checked').val() ;
         if(selected_val == 1){
              $(".tab1").css("display", "block");
            $(".tab2").css("display", "none"); 
         }else{
             
            $(".tab1").css("display", "none");
            $(".tab2").css("display", "block");
         }
     });
  });

</script>
@endsection

@section('title')
<title>Bauen | Conectando cargas con transportistas homologados - Bauen Freight SAC - Transporte de Carga, Fletes, Carga de Pago, Peru</title>
@endsection

@section('banner')
@endsection

@section('main_container')
<div class="container">
    @if(Session::has('message'))
    <div class="alert alert-info" style= "margin-top : 30px ; text-align: center">
        <a class="close" data-dismiss="alert">×</a>
        {{Session::get('message')}}<strong>!</strong>
        {{Session::forget('message')}}
    </div>
    @endif
    <div class="registration">

        <h5>{{trans('pdg.57')}}</h5>
            <div class="singtabs">
                <div class="user-type">
                    <label class="t1">
                        <input type="radio" name="a" id="select_tab_1" value="1" checked ><span></span>{{trans('pdg.58')}}</label> 
                    <label class="t2">
                        <input type="radio" id="select_tab_2" name="a" value="2" ><span></span>{{trans('pdg.59')}}</label>
                </div>

                <div class="tab1" >
                   <form id="signup_form" action="{{url('post-signup')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="first_name"  placeholder="{{trans('pdg.60')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="last_name"  placeholder="{{trans('pdg.61')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                            <input type="email" class="form-control" name="user_email"   placeholder="{{trans('pdg.62')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                            <input type="tel" class="form-control" name="phone_no"   placeholder="{{trans('pdg.63')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-slack" aria-hidden="true"></i></span>
                            <input type="tel" class="form-control" name="dni_no"   placeholder="DNI"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password"  placeholder="{{trans('pdg.64')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="retypepassword"   placeholder="{{trans('pdg.66')}}"/>
                        </div>
                    </div>
                    <div class="form-group ">
                        <button type="button" id="submit_signup_form" class="btn btn-primary btn-block login-button">{{trans('pdg.10')}}</button>
                    </div>
                    </form>
                </div>

                <div  class="tab2" style="display:none;">
                   <form id="company_signup_form" action="{{url('post-company-signup')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-hospital-o" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="company_name"  placeholder="{{trans('pdg.76')}}"/>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="input-group ">
                            <span class="input-group-addon">
                                <i class="fa fa-building-o" aria-hidden="true"></i>
                            </span>
                            <select name="industry_id" class="form-control" id="industry_id" placeholder="Industry">
                                <option value="0">{{trans('pdg.75')}}</option>
                                @if(!empty($industry_id)) @foreach($industry_id as $cat)
                                <option value="{{$cat->industrytype_id}}">{{$cat->industrytype_name}}</option>
                                @endforeach @endif
                            </select>
                        </div>
                    </div> -->
                    
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-slack" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="RUC_no"  placeholder="RUC"/>
                        </div>
                    </div>
                     <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="first_name"  placeholder="{{trans('pdg.60')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="last_name"  placeholder="{{trans('pdg.61')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="user_email"  placeholder="{{trans('pdg.62')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="phone_no"  placeholder="{{trans('pdg.63')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="company_password" id="company_password"  placeholder="{{trans('pdg.64')}}"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="retypepassword" placeholder="{{trans('pdg.66')}}"/>
                        </div>
                    </div>
                    <div class="form-group ">
                        <button type="button" id="submit_company_signup_form" class="btn btn-primary btn-block login-button">{{trans('pdg.10')}}</button>
                    </div>
                    </form>
                </div>
            </div>
    </div>


</div>

@endsection