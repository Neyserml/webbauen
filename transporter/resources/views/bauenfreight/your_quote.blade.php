@extends('../master_layout/web_shipper')
@section('custom_js')
<script src="{{asset('public/assets/bauenfreight/my_custom_js.js')}}"></script>
<script>
  /*var pickup_autocomplete,drop_autocomplete;
    function initAutocomplete() {
        pickup_autocomplete = new google.maps.places.Autocomplete((document.getElementById('pickup_location')),{types: ['geocode']});
        pickup_autocomplete.addListener('place_changed', pickup_fillInAddress);     
        drop_autocomplete = new google.maps.places.Autocomplete((document.getElementById('dropoff_location')),{types: ['geocode']});
        drop_autocomplete.addListener('place_changed', drop_fillInAddress);
    }  
       function pickup_fillInAddress() {
        document.getElementById("pick_long").value = "";
        document.getElementById("pick_lat").value = "";
        document.getElementById("pick_place_id").value = "";
        var place = pickup_autocomplete.getPlace();
        console.log(place);
        console.log(place.geometry.location.lng());
        console.log(place.geometry.location.lat());
        console.log(place.place_id);
        document.getElementById("pick_long").value = place.geometry.location.lng();
        document.getElementById("pick_lat").value = place.geometry.location.lat();
        document.getElementById("pick_place_id").value = place.place_id;
        }
       function drop_fillInAddress() {
        document.getElementById("drop_long").value = "";
        document.getElementById("drop_lat").value = "";
        document.getElementById("drop_place_id").value = "";
        var place = drop_autocomplete.getPlace();
        console.log(place);
        console.log(place.geometry.location.lng());
        console.log(place.geometry.location.lat());
        console.log(place.place_id);
        document.getElementById("drop_long").value = place.geometry.location.lng();
        document.getElementById("drop_lat").value = place.geometry.location.lat();
        document.getElementById("drop_place_id").value = place.place_id;
       }*/



     

     var pickup_autocomplete,drop_autocomplete;
  var lastPickupPlace = null, lastDropoffPlace=null;
  var IsPickupPlaceChange;
  var IsDropoffPlaceChange;
    function initAutocomplete() {
        var pickupInputElement = document.getElementById('pickup_location');
        var dropInputElement = document.getElementById('dropoff_location');

        pickup_autocomplete = new google.maps.places.Autocomplete((document.getElementById('pickup_location')),{types: ['geocode']});
        pickup_autocomplete.addListener('place_changed', pickup_fillInAddress);
        drop_autocomplete = new google.maps.places.Autocomplete((document.getElementById('dropoff_location')),{types: ['geocode']});
        drop_autocomplete.addListener('place_changed', drop_fillInAddress);

        document.addEventListener('click', function(event) {
            var isClickInsidePickup = pickupInputElement.contains(event.target);
            var isClickInsideDropoff = dropInputElement.contains(event.target);

            if (!isClickInsidePickup) {

              if (IsPickupPlaceChange == false) {

                  if (lastPickupPlace==null || pickupInputElement.value== ""){
                    pickupInputElement.value= "";
lastPickupPlace = null;

                  }
                  else{
                    pickupInputElement.value= lastPickupPlace.formatted_address;
                  }

              }
              else {

                  //alert($("#txtlocation").val());
              }
            }

            if (!isClickInsideDropoff) {

              if (IsDropoffPlaceChange == false) {

                  if (lastDropoffPlace==null || dropInputElement.value== ""){
                    dropInputElement.value= "";
lastDropoffPlace=null;
                  }
                  else{
                    dropInputElement.value= lastDropoffPlace.formatted_address;
                  }


              }
              else {
                  
                  //alert($("#txtlocation").val());
              }
            }
        });
    }


      $("#pickup_location").keydown(function () {
          IsPickupPlaceChange = false;
      });

      $("#dropoff_location").keydown(function () {
          IsDropoffPlaceChange = false;
      });



       function pickup_fillInAddress() {
        document.getElementById("pick_long").value = "";
        document.getElementById("pick_lat").value = "";
        document.getElementById("pick_place_id").value = "";
        var place = pickup_autocomplete.getPlace();
        console.log(place);
        console.log(place.geometry.location.lng());
        console.log(place.geometry.location.lat());
        console.log(place.place_id);
        document.getElementById("pick_long").value = place.geometry.location.lng();
        document.getElementById("pick_lat").value = place.geometry.location.lat();
        document.getElementById("pick_place_id").value = place.place_id;
        lastPickupPlace = place;
        IsPickupPlaceChange = true;
        }
       function drop_fillInAddress() {
        document.getElementById("drop_long").value = "";
        document.getElementById("drop_lat").value = "";
        document.getElementById("drop_place_id").value = "";
        var place = drop_autocomplete.getPlace();
        console.log(place);
        console.log(place.geometry.location.lng());
        console.log(place.geometry.location.lat());
        console.log(place.place_id);
        document.getElementById("drop_long").value = place.geometry.location.lng();
        document.getElementById("drop_lat").value = place.geometry.location.lat();
        document.getElementById("drop_place_id").value = place.place_id;
        lastDropoffPlace = place;
        IsDropoffPlaceChange = true;
       }






    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIrDEXSDz0Z8QfqxYYJjGgKPHP2ods5RM&libraries=places&callback=initAutocomplete" async defer></script>
@endsection

@section('custom_css')
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
@endsection

@section('title')
<title>Bauenfreight</title>
@endsection

@section('banner')
@endsection

@section('main_container')
<div class="right-body">
     @if(Session::has('message'))
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert">×</a>
           {{Session::get('message')}}<strong>!</strong>
            {{Session::forget('message')}}
        </div>
        @endif
    <div class="request-quote">
        <div class="row">
            <div class="col-sm-7 col-xs-12">
                <h5>{{trans('pdg.86')}}</h5>
                <form id="your_request" name="your_request" action="{{url('post-your-request')}}" method="post">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                   <p><strong>{{trans('pdg.93')}}</strong></p>
                    <div class="form-group form-group-lg required">
                        <input type="text" class="form-control" name="pickup_location" id="pickup_location"  placeholder="{{trans('pdg.85')}}"/>
                    </div>
                    <div class="form-group form-group-lg required">
                        <input type="text" class="form-control" name="dropoff_location" id="dropoff_location"  placeholder="{{trans('pdg.87')}}"/>
                    </div>
                    <input type="hidden" id="pick_long" name="pick_long" value="">
                    <input type="hidden" id="pick_lat" name="pick_lat" value="">
                    <input type="hidden" name="pick_place_id" id="pick_place_id" value="" >
                    <input type="hidden" id="drop_long" name="drop_long" value="">
                    <input type="hidden" id="drop_lat" name="drop_lat" value="">
                    <input type="hidden" name="drop_place_id" id="drop_place_id" value="" >
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group form-group-lg required">
                                <div class="input-group date" >
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type="text" name="pickup_date" class="form-control" id="pickup_date"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group form-group-lg required">
                                <div class="input-group date" >
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    <input type="text" name="pickup_time" id="pickup_time" class="form-control timepicker" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg required">
                        <select class="form-control" id="trailer_id" name="trailer_id">
                             <option value="0">{{trans('pdg.88')}}</option>
                             @if(!empty($trailers_id))
                            @foreach($trailers_id as $cat)
                             <option value="{{$cat->trailer_id}}">{{$cat->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    
                    
                    <div class="form-group form-group-lg required">
                        <select class="form-control" id="loadtype_id" name="loadtype_id">
                             <option value="0">{{trans('pdg.105')}}</option>
                             @if(!empty($loadtypes_id))
                            @foreach($loadtypes_id as $lt)
                             <option value="{{$lt->loadtype_id}}">{{$lt->load_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group form-group-lg required">
                                <input type="text" class="form-control" name="weight" id="weight"  placeholder="{{trans('pdg.89')}}"/>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group form-group-lg required">

                                <input type="text" class="form-control" name="size" id="size"  placeholder="{{trans('pdg.90')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg required">
                        <textarea  class="form-control" row="4" name="description" id="description" placeholder="{{trans('pdg.91')}}"></textarea>
                    </div>
                    <div class="form-group ">
                        <button type="button" id="submit_your_request" class="btn btn-primary btn-block raq-button btn-lg">{{trans('pdg.92')}}</button>
                    </div>

                </form>
            </div>
            <div class="col-sm-5 col-xs-12 ">
                <div class="benefits pull-right">
                    <p>Bauen se encarga de conseguir el transportista que necesitas. Espera mientras te ponemos en contacto con las mejores empresas de transporte de carga</p>

                    <p>Llenando este formulario, los diferentes transportistas verán tu requerimento y te cotizarán en tiempo real. Tú decides, teniendo precios por adelantado.</p>
                    <ul><li class="benefits-one">
                            Seguridad
                        </li>
                        <li class="benefits-two">
                            Ahorro
                        </li>
                        <li class="benefits-three">
                            Trazabilidad
                        </li>
                        <li class="benefits-four">
                            Tecnología
                        </li>

                    </ul>
                    <p>Recomendamos usar el app disponible en iOS y Android. Cualquier duda o consulta ponerse en contacto a:<strong class="phno">solcese@bauenfreight.com y fpazos@bauenfreight.com</strong>Bauen, la carga que necesitas: a un botón de distancia</p>
                </div>
            </div>
        </div>
</div>
</div>

@endsection