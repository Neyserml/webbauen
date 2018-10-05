@extends('../master_layout/web_shipper')

@section('custom_js')
@endsection




@section('title')
<title>Bauenfreight</title>
@endsection



@section('banner')
@endsection

@section('main_container')
<div class="right-body">
  
   <div class="request-list">
 <!--  get_requests_data -->
 
  @if(!empty($requests_data))
  
 <div class="row-request">
 	<div class="row">
            <div class="col-sm-12 col-xs">

                <div class="box-map">
                <div class="address">
                    <span class="pull-left"><i class="fa fa-flag"></i>{{$requests_data->pickup_location}}</span>
                     <span class="pull-right"><i class="fa fa-map-marker"></i> {{$requests_data->dropoff_location}}</span>
                </div>
                <div style="width:100%;height:300px" >
                    @if(!empty($requests_data->request_image))
                    <img src="{{url('../uploads/requests/')}}/{{$requests_data->request_image}}" alt="" class="img-map"/> 
                    @else
                    <img src="{{asset('public/assets/bauenfreight/images/img-map.jpg')}}" alt="" class="img-map"/> 
                    @endif
                </div>
                </div>
            </div>			
        </div>
        <p class="desc">{{$requests_data->description}}</p>
 </div>
  @endif
  
   
 @if(!empty($get_bid_list))
 
  @foreach($get_bid_list as $bid)
    
  <div class="col-sm-12 col-xs" style="padding: 10px;background-color:#e9e7e7;margin: 5px">
                <div style="width:100px;height:80px" class="pull-left col-sm-3">
                     <img src="{{asset('public/assets/bauenfreight/images/user-placeholder.gif')}}" alt="" class="img-responsive"/> 
<!--                    @if(!empty($bid->main_image))
                    <img src="{{url('../uploads/users/')}}/{{$bid->main_image}}" alt="" class="img-responsive"/> 
                    @else
                    <img src="{{asset('public/assets/bauenfreight/images/user-placeholder.gif')}}" alt="" class="img-responsive"/> 
                    @endif-->
                </div>
                <div class="pull-right col-sm-9">
                    <div class="pull-left" col-sm-7>
                        <p><b> Transporter Name :</b>{{$bid->main_first_name}} {{$bid->main_last_name}} </p>
                        <p><b> Bid Amount :</b>{{$bid->bid_amount}} </p>
                        @if($bid->bid_status == 1)
                        <p><b> Current Status :</b>Customer Accept</p>
                        @elseif($bid->bid_status == 2)
                        <p><b> Current Status :</b>Transporter Accept</p>
                        @elseif($bid->bid_status == 3)
                        <p><b> Current Status :</b>Transporter Cancel</p>
                        @elseif($bid->bid_status == 4)
                        <p><b> Current Status :</b>Lost</p>
                        @else
                        <p><b> Current Status :</b>Placed</p>
                        @endif
                    </div>
                    <div class="pull-right" col-sm-5>							
					@if($requests_data->request_status == 0 || $requests_data->request_status == 1)
                      <a onclick="return confirm('Are you sure you want to accept  this request?');" href="{{url('api/request-accept-'.$requests_data->request_id)}}" class="btn btn-primary">Accept</a>									
					 @endif
                    </div>
                </div>
            </div>                
       
  @endforeach
   
  @endif

  
  
  
  
    </div>
</div>

@endsection