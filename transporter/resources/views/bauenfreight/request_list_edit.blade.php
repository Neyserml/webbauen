@extends('../master_layout/web_shipper')

@section('custom_js')
<script src="{{asset('public/assets/bauenfreight/my_custom_js.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIrDEXSDz0Z8QfqxYYJjGgKPHP2ods5RM&libraries=places&callback=initAutocomplete" async defer></script>
<script>
    var options = {
        now: "{{isset($data['pickup_time'])?$data['pickup_time']: '0:0'}}", //hh:mm 24 hour format only, defaults to current time

    };

    var myPicker = $('.timepicker').wickedpicker(options);

    // set time to 2pm
    //myPicker.wickedpicker('setTime', 0, "14:00");
</script>

<script>
  var accentEncode = function (tx)
  {

  	var rp = String(tx);
  	//
      rp = rp.replace(/&aacute;/g, 'á');
    	rp = rp.replace(/&eacute;/g, 'é');
    	rp = rp.replace(/&iacute;/g, 'í');
    	rp = rp.replace(/&oacute;/g, 'ó');
    	rp = rp.replace(/&uacute;/g, 'ú');
    	rp = rp.replace(/&ntilde;/g, 'ñ');
    	rp = rp.replace(/&uuml;/g, 'ü');
    	//
    	rp = rp.replace(/&Aacute;/g, 'Á');
    	rp = rp.replace(/&Eacute;/g, 'É');
    	rp = rp.replace(/&Iacute;/g, 'Í');
    	rp = rp.replace(/&Oacute;/g, 'Ó');
    	rp = rp.replace(/&Uacute;/g, 'Ú');
    	rp = rp.replace(/&Ñtilde;/g, 'Ñ');
    	rp = rp.replace(/&Üuml;/g, 'Ü');
  	//
  	return rp;
  };

  var pickup_autocomplete,drop_autocomplete;
  var lastPickupPlace = accentEncode("{{isset($data['pickup_date'])?$data['pickup_location']:''}}");
  var lastDropoffPlace = accentEncode("{{isset($data['pickup_date'])?$data['dropoff_location']:''}}");
  var IsPickupPlaceChange;
  var IsDropoffPlaceChange;
  var initial_flag_pickup=true;
  var initial_flag_dropoff=true;

  function initAutocomplete() {

      var pickupInputElement = document.getElementById('pickup_location');
      var dropInputElement = document.getElementById('dropoff_location');

      pickup_autocomplete = new google.maps.places.Autocomplete((document.getElementById('pickup_location')),{types: ['geocode']});
      pickup_autocomplete.addListener('place_changed', pickup_fillInAddress);
      drop_autocomplete = new google.maps.places.Autocomplete((document.getElementById('dropoff_location')),{types: ['geocode']});
      drop_autocomplete.addListener('place_changed', drop_fillInAddress);
      pickupInputElement.value = lastPickupPlace;
      dropInputElement.value = lastDropoffPlace;
      //console.log("{{$data['dropoff_location']}}");

      document.addEventListener('click', function(event) {
          var isClickInsidePickup = pickupInputElement.contains(event.target);
          var isClickInsideDropoff = dropInputElement.contains(event.target);

          if (!isClickInsidePickup) {
            //console.log("!isClickInsidePickup is true");
            if (IsPickupPlaceChange == false) {
                //console.log("IsPickupPlaceChange is false");
                if (lastPickupPlace==null || pickupInputElement.value== ""){
                  //console.log("lastPickupPlace==null");
                  //console.log("pickupInputElement:" + pickupInputElement.value);
                  pickupInputElement.value= "";
                  lastPickupPlace = null;
                }
                else{
                    if (initial_flag_pickup){
                      console.log("entra 1");
                      pickupInputElement.value = lastPickupPlace;

                    }
                    else{
                      console.log("entra 2");
                      pickupInputElement.value= lastPickupPlace.formatted_address;
                    }

                }
            }
            else {
                //console.log("IsPickupPlaceChange is true");
            }
          }
          else{
            //console.log("!isClickInsidePickup is false");
          }
          if (!isClickInsideDropoff) {
            if (IsDropoffPlaceChange == false) {
                if (lastDropoffPlace==null || dropInputElement.value== ""){
                  dropInputElement.value= "";
                  lastDropoffPlace=null;
                }
                else{
                  if (initial_flag_dropoff){
                    dropInputElement.value= lastDropoffPlace;
                    //initial_flag_dropoff = false;
                  }
                  else{
                    dropInputElement.value= lastDropoffPlace.formatted_address;
                  }
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
      initial_flag_pickup = false;
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
      initial_flag_dropoff = false;
   }
</script>

@endsection
@section('custom_css')
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
@endsection
@section('title')
<title>Bauenfreight</title>
@endsection





@section('main_container')

<div class="col-sm-7 col-xs-12">
    <h5>Editar datos de la Cotizacion</h5>
    <form id="your_request" name="your_request" action="{{url('update-your-request')}}" method="post">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <p><strong>Editar Cotizacion</strong></p>
        <div class="form-group form-group-lg required">
            <input type="text" class="form-control" name="pickup_location" id="pickup_location"  placeholder="{{trans('pdg.85')}}"/>
        </div>
        <div class="form-group form-group-lg required">
            <input type="text" class="form-control" name="dropoff_location" id="dropoff_location"  placeholder="{{trans('pdg.87')}}"/>
        </div>
        <input type="hidden" id="request_id" name="request_id" value="{{$data['request_id']}}">
        <input type="hidden" id="pick_long" name="pick_long" value="{{isset($data['pick_long'])?$data['pick_long']:''}}">
        <input type="hidden" id="pick_lat" name="pick_lat" value="{{isset($data['pick_lat'])?$data['pick_lat']:''}}">
        <input type="hidden" name="pick_place_id" id="pick_place_id" value="{{isset($data['pick_place_id'])?$data['pick_place_id']:''}}" >
        <input type="hidden" id="drop_long" name="drop_long" value="{{isset($data['drop_long'])?$data['drop_long']:''}}">
        <input type="hidden" id="drop_lat" name="drop_lat" value="{{isset($data['drop_lat'])?$data['drop_lat']:''}}">
        <input type="hidden" name="drop_place_id" id="drop_place_id" value="{{isset($data['drop_place_id'])?$data['drop_place_id']:''}}">
        <input type="hidden" name="previous_request_image" id="previous_request_image" value="{{isset($data['previous_request_image'])?$data['previous_request_image']:''}}" >
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group form-group-lg required">
                    <div class="input-group date" >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input type="text" name="pickup_date" class="form-control" id="pickup_date" value="{{isset($data['pickup_date'])?$data['pickup_date']:''}}" />
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group form-group-lg required">
                    <div class="input-group date" >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                        <input type="text" name="pickup_time" id="pickup_time" class="form-control timepicker"  />
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group form-group-lg required">
            <select class="form-control" id="trailer_id" name="trailer_id">
                 <option value="0">{{trans('pdg.88')}}</option>
                 @if(!empty($trailers_id))
                  @foreach($trailers_id as $cat)
                   @if(isset($data['trailer_id']) && $cat->trailer_id == $data['trailer_id'])
                     <option value="{{$cat->trailer_id}}" selected >{{$cat->name}}</option>
                   @else
                     <option value="{{$cat->trailer_id}}">{{$cat->name}}</option>
                   @endif

                  @endforeach
                @endif
            </select>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group form-group-lg required">
                    <input type="text" class="form-control" name="weight" id="weight"  placeholder="{{trans('pdg.89')}}" value="{{isset($data['weight'])?$data['weight']:''}}"/>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group form-group-lg required">
                    <input type="text" class="form-control" name="size" id="size"  placeholder="{{trans('pdg.90')}}" value="{{isset($data['size'])?$data['size']:''}}"/>
                </div>
            </div>
        </div>
        <div class="form-group form-group-lg required">
            <textarea  class="form-control" row="4" name="description" id="description" placeholder="{{trans('pdg.91')}}">{{isset($data['description'])?$data['description']:''}}</textarea>
        </div>
        <div class="form-group ">
            <button type="button" id="update_your_request" class="btn btn-primary btn-block raq-button btn-lg">{{trans('pdg.106')}}</button>
        </div>

    </form>
</div>




@endsection
