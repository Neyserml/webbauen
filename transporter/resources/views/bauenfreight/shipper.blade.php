@extends('../master_layout/web_shipper')

@section('title')
<title>Bauen | Customer admin    |  Conectando cargas con transportistas homologados - Bauen Freight SAC - Transporte de Carga, Fletes, Carga de Pago</title>
@endsection

@section('custom_js')
<script>

    // The following example creates complex markers to indicate beaches near
      // Sydney, NSW, Australia. Note that the anchor is set to (0,32) to correspond
      // to the base of the flagpole.

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: {lat: -12.0202532, lng: -77.1478511}
        });

        setMarkers(map);
      }

      // Data for the markers consisting of a name, a LatLng and a zIndex for the
      // order in which these markers should display on top of each other.
  

      function setMarkers(map) {
        var image = {
          url: "{{asset('public/assets/bauenfreight/images/pin.png')}}",
          // This marker is 20 pixels wide by 32 pixels high.
          size: new google.maps.Size(25, 36),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 32).
          anchor: new google.maps.Point(0, 32)
        };
        // Shapes define the clickable region of the icon. The type defines an HTML
        // <area> element 'poly' which traces out a polygon as a series of X,Y points.
        // The final coordinate closes the poly by connecting to the first coordinate.
        var shape = {
          coords: [1, 1, 1, 20, 18, 20, 18, 1],
          type: 'poly'
        };
        var beaches = [];
        _user_id = "{{$user_data['user_id']}}"
        _device_type = "{{$user_data['device_type']}}"
        _device_unique_code = "{{$user_data['device_unique_code']}}"
        _user_request_key = "{{$user_data['user_request_key']}}"

        $.post('http://www.bauenfreight.com/api/request_list',
                  {
                      device_type: _device_type, // se obtiene de user_request_keys
                      device_unique_code: _device_unique_code, // se obtiene de user_request_keys
                      user_id:_user_id,
                      user_request_key: _user_request_key
                  }, function (data, status) {
                       var data = JSON.parse(data);

                       if (data.status == 1) {
                           
requests =  data.requests;
if (requests==null || !requests){ console.log("Fail");}
else{
console.log("Success");
                           console.log(data);
                           
                                 
                           for (i = 0, len = requests.length; i < len; i++) {
                               currRequest= requests[i];
                               currArrayRequest = [ currRequest['pickup_location'], parseFloat(currRequest['pickup_latitude']), parseFloat(currRequest['pickup_longitude'])]
                               beaches.push(currArrayRequest);
                           }
                           console.log(beaches);

                           
                           for (var i = 0; i < beaches.length; i++) {
                             var beach = beaches[i];
                             var marker = new google.maps.Marker({
                               position: {lat: beach[1], lng: beach[2]},
                               map: map,
                               icon: image,
                               shape: shape,
                               title: beach[0],
                               zIndex: beach[3]
                             });
                           }
}
                       } else {
                           console.log("Fail");
                          
                       }
                  });
        
        
      }
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUO8nenuL0gE8nXFKD9QVZ0npzP_Cf6uo&callback=initMap">
    </script>


      <!--<script type="text/javascript">


        _user_id = "{{$user_data['user_id']}}"
        _device_type = "{{$user_data['device_type']}}"
        _device_unique_code = "{{$user_data['device_unique_code']}}"
        _user_request_key = "{{$user_data['user_request_key']}}"

        $.post('http://www.bauenfreight.com/api/request_list',
                {
                    device_type: _device_type, // se obtiene de user_request_keys
                    device_unique_code: _device_unique_code, // se obtiene de user_request_keys
                    user_id:_user_id,
                    user_request_key: _user_request_key


                    /*request_status:"0",
                  	trailer_id:"3",
                  	load_type_id:"39",
                  	driver_id:"",
                    vehicle_id:"",


                  	request_from:"",
                  	request_to:"",
                  	request_weight:""
                    */



                    // device_push_id:"dsaf"//se obtiene de user_request_keys
                    //
                    //
                    // request_status:"", //se obtiene de requests
                    // trailer_id:"",//se obtiene de requests
                    // load_type_id:"",//se obtiene de requests
                    // driver_id:"",//se obtiene de requests
                    // vehicle_id:"",//se obtiene de requess
                    //
                    //
                    // request_from:"",//se obtiene de request, pickup_location
                    // request_to:"",// se obtiene de request, drop_off_location
                    // request_weight:"" //se obtiene de request, weight

                }, function (data, status) {
                     var data = JSON.parse(data);

                       if (data.status == 1) {
                           console.log("Exito");
                           console.log(data);
                           requests =  data.requests;
                       

                       } else {
                           console.log("error");


                          /*window.location.reload();*/
                       }
                   
                    
               

                });

    </script> -->


@endsection





@section('title')
<title>Bauenfreight</title>
@endsection



@section('banner')
@endsection

@section('main_container')
<div class="right-body">
   <div class="shipper-home wow fadeInUp">
   
   
  <ul class="nav nav-tabs custom-tab">
    <li class="active"><a data-toggle="tab" href="#lmap"><i class="fa fa-map-marker"></i>{{trans('pdg.77')}}</a></li>
    <li><a data-toggle="tab" href="#list"><i class="fa fa-list"></i>{{trans('pdg.78')}} </a></li>
  </ul>
  
  <div class="tab-content">
  
    <div id="lmap" class="tab-pane fade in active">
    <div id="map"></div>
    </div>
    
    
    <div id="list" class="tab-pane fade">
    <div class="transit-request">
	<div class="row">
             @if(!empty($count_requests))
  <div class="col-xs-12">
      <?php 
      $request_status_1=0;
      $request_status_4=0;
      $request_status_6=0;
      $request_status_13=0;
      ?>
     @foreach($count_requests as $requests)
     @if($requests->request_status == 1)
     <?php $request_status_1=$requests->total_row; ?>
    @elseif($requests->request_status == 4)
    <?php $request_status_4=$requests->total_row; ?>
    @elseif($requests->request_status == 6)
    <?php $request_status_6=$requests->total_row; ?>
    @elseif($requests->request_status == 13)
    <?php $request_status_13=$requests->total_row; ?>
    @endif
    @endforeach
        <div class="box-stats"><span>{{$request_status_1}}</span>Subasta en curso</div>
        <div class="box-stats"><span>{{$request_status_4}}</span>Viaje en curso</div>
        <div class="box-stats"><span>{{$request_status_6}}</span>Completados</div>
        <div class="box-stats"><span>{{$request_status_13}}</span>Vencidos</div>
  </div>
    @endif
   </div>
   <h5>In Transit Requests</h5>
   
   @if(!empty($get_requests_data))
		@foreach($get_requests_data as $requests_data)
   
   
   <div class="row-request">
		<div class="date"><span>{{date("d",strtotime($requests_data->create_date))}}</span>{{date("M",strtotime($requests_data->create_date))}}</div>
		
		<div class="request-info">
		<a class="icon-map" href="{{url('request-list-details-'.base64_encode($requests_data->request_id.'||'.env("APP_KEY")))}}"><img src="{{asset('public/assets/bauenfreight/images/icon-map.png')}}" alt=""/></a>
		<p class="time">{{date("M d, Y h:i A",strtotime($requests_data->create_date))}}</p>
		
		 @if($requests_data->request_status == 1)
                    <p><b> Subasta en curso</b></p>
                    @elseif($requests_data->request_status == 2)
                    <p><b> Esperando confirmación del transportista</b></p>
                    @elseif($requests_data->request_status == 3)
                    <p><b> El servicio ha sido confirmado por el transportista</b></p>
                    @elseif($requests_data->request_status == 4)
                    <p><b>Subasta cancelada </b></p>
                    @elseif($requests_data->request_status == 5)
                    <p><b> Listo para Iniciar Viaje. Conductor fue asignado </b></p>
                    @elseif($requests_data->request_status == 6)
                    <p><b>En camino al Punto de Origen</b></p>
                    @elseif($requests_data->request_status == 7)
                    <p><b>La unidad se encuentra cargando</b></p>
                    @elseif($requests_data->request_status == 8)
                    <p><b> Listo para partir. Cargado</b></p>
                    @elseif($requests_data->request_status == 9)
                    <p><b>En tránsito al Punto Destino </b></p>
                    @elseif($requests_data->request_status == 10)
                    <p><b>Llegó al destino</b></p>
                    @elseif($requests_data->request_status == 11)
                    <p><b>La unidad esta descargando</b></p>
                    @elseif($requests_data->request_status == 12)
                    <p><b> Descarga completada en destino</b></p>
                    @elseif($requests_data->request_status == 13)
                    <p><b>Servicio concluido</b></p>
                     @elseif($requests_data->request_status == 14)
                    <p><b>La subasta ya esta vencida</b></p>
                    @else
                    <p><b>Subasta en Curso</b></p>
                    @endif
		
		
		<p><i class="fa fa-flag"></i>{{$requests_data->pickup_location}}</p>
		<p><i class="fa fa-map-marker"></i> {{$requests_data->dropoff_location}}</p>
		<p class="desc">
			@if (strlen($requests_data->description) > 150) {{substr($requests_data->description,0,150)."..."}} 
			@else {{$requests_data->description}}
			@endif
		</p>
		</div>
   </div>
    @endforeach
      {!! with(new App\Pagination\HDPresenter($get_requests_data))->render(); !!}
        @endif
  
   </div>
    
    
    </div>
    
  </div>
  
	  </div>
         
         </div>
@endsection