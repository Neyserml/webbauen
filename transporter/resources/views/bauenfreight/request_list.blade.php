@extends('../master_layout/web_shipper')

@section('custom_js')
<!--
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUO8nenuL0gE8nXFKD9QVZ0npzP_Cf6uo&callback=initMap">
</script>-->
@endsection

@section('title')
<title>Bauen | Pedidos </title>
@endsection

@section('banner')
@endsection

@section('main_container')
<div class="right-body">
 @if(Session::has('message'))
                    <div class="alert alert-info">
                        <a class="close" data-dismiss="alert">กั</a>
                        {{Session::get('message')}}<strong>!</strong>
                        {{Session::forget('message')}}
                    </div>
                    @endif
    <div class="shipper-home wow fadeInUp">
        <div id="list">
            <div class="transit-request">
                @if(!empty($get_requests_data))
                    @foreach($get_requests_data as $requests_data)
                        @if ($requests_data->is_deleted == 1 or $requests_data->is_deleted =='1')
                           @continue
                        @endif
                <div class="row-request ">
                    <div class="date"><span>{{date("d",strtotime($requests_data->create_date))}}</span>{{date("M",strtotime($requests_data->create_date))}}</div>
                    <div class="request-info" >
                    <p class="time">{{date("M d, Y h:i A",strtotime($requests_data->create_date))}}</p>
                    @if($requests_data->request_status == 1)
                    <p><b> Bid Placed</b></p>
                    @elseif($requests_data->request_status == 2)
                    <p><b> Bid Accepted By Customer</b></p>
                    @elseif($requests_data->request_status == 3)
                    <p><b> Transporter Accepted</b></p>
                    @elseif($requests_data->request_status == 4)
                    <p><b>Bid Cancelled </b></p>
                    @elseif($requests_data->request_status == 5)
                    <p><b> Driver & Vehicle Assigned </b></p>
                    @elseif($requests_data->request_status == 6)
                    <p><b>In transit or  Arriving</b></p>
                    @elseif($requests_data->request_status == 7)
                    <p><b>Loading </b></p>
                    @elseif($requests_data->request_status == 8)
                    <p><b> Loaded</b></p>
                    @elseif($requests_data->request_status == 9)
                    <p><b>Trip start </b></p>
                    @elseif($requests_data->request_status == 10)
                    <p><b>Reached </b></p>
                    @elseif($requests_data->request_status == 11)
                    <p><b>Unloading </b></p>
                    @elseif($requests_data->request_status == 12)
                    <p><b> Unloaded</b></p>
                    @elseif($requests_data->request_status == 13)
                    <p><b>Completed </b></p>
                     @elseif($requests_data->request_status == 14)
                    <p><b>Expired </b></p>
                    @else
                    <p><b> Subasta en Curso </b></p>
                    @endif


                    <p><i class="fa fa-flag"></i>{{$requests_data->pickup_location}}</p>
                    <p><i class="fa fa-map-marker"></i> {{$requests_data->dropoff_location}}</p>
                    <p class="desc">
                        @if (strlen($requests_data->description) > 150) {{substr($requests_data->description,0,150)."..."}}
                        @else {{$requests_data->description}}
                        @endif
                    </p>
                    <a href="#" class="pull-right btn btn-custom"  onclick="event.preventDefault();
                    document.getElementById('{{'request-delete-form-'.strval($requests_data->request_id)}}').submit();">Eliminar</a>

                      <form id="{{'request-delete-form-'.strval($requests_data->request_id)}}" action="{{ url('/request/delete') }}" method="POST" style="display: none;">
                        <input type="hidden"  name="request_id" value="{{$requests_data->request_id}}">
                        {{ csrf_field() }}
                      </form>

                    <a href="#" class="pull-right btn  btn-custom" onclick="event.preventDefault();
                                                   document.getElementById('{{'request-edit-form-'.strval($requests_data->request_id)}}').submit();">Editar</a>
                                <form id="{{'request-edit-form-'.strval($requests_data->request_id)}}" action="{{route('requestEdit',['request_id' =>$requests_data->request_id ])}}" method="GET" style="display: none;">
                                   <input type="hidden"  name="request_id" value="{{$requests_data->request_id}}">
                                   <input type="hidden"  name="pickup_location" value="{{$requests_data->pickup_location}}">
                                   <input type="hidden"  name="pick_place_id" value="{{$requests_data->pickup_place_id}}">
                                   <input type="hidden"  name="pick_lat" value="{{$requests_data->pickup_latitude}}">
                                   <input type="hidden"  name="pick_long" value="{{$requests_data->pickup_longitude}}">
                                   <input type="hidden"  name="dropoff_location" value="{{$requests_data->dropoff_location}}">
                                   <input type="hidden"  name="drop_place_id" value="{{$requests_data->dropoff_place_id}}">
                                   <input type="hidden"  name="drop_lat" value="{{$requests_data->dropoff_latitude}}">
                                   <input type="hidden"  name="drop_long" value="{{$requests_data->dropoff_longitude}}">
                                   <input type="hidden"  name="pickup_date" value="{{$requests_data->pickup_date}}">
                                   <input type="hidden"  name="pickup_time" value="{{$requests_data->pickup_time}}">
                                   <input type="hidden"  name="trailer_id" value="{{$requests_data->trailer_id}}">
                                   <input type="hidden"  name="weight" value="{{$requests_data->weight}}">
                                   <input type="hidden"  name="size" value="{{$requests_data->size}}">
                                   <input type="hidden"  name="description" value="{{$requests_data->description}}">
<input type="hidden"  name="request_image" value="{{$requests_data->request_image}}">
                                   {{ csrf_field() }}
                                </form>
                    <a href="{{url('request-list-details-'.base64_encode($requests_data->request_id.'||'.env("APP_KEY")))}}" class="pull-right btn  btn-custom">Detalles</a>

                    </div>
                </div>
                    @endforeach
      {!! with(new App\Pagination\HDPresenter($get_requests_data))->render(); !!}
        @endif
            </div>
        </div>

    </div>
</div>
@endsection
