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
     <div class="profile">
@if(!empty($details))	 
	  	<div class="user-info">
	  	<div class="user-photo">
                    
                    @if(!empty($details->image)) 
                    <img src="{{url('../uploads/users/')}}/{{$details->image}}"  alt=""/>
                    @else
                     <img src="{{asset('public/assets/bauenfreight/images/user-placeholder.gif')}}"  alt=""/>
                    @endif
                </div>
 	<div class="info"> {{$details->first_name}} {{$details->last_name}}</div>
  	<a data-toggle="modal" data-target="#edit_picture" class="btn-editpic"><i class="fa fa-pencil"></i> {{trans('pdg.81')}}</a>
        </div>
<div class="row user-details">
	  		<div class="col-sm-6 col-xs-12">
			<p class="title">Contact Info</p>
			
			<p><i class="fa fa-phone"></i> {{trans('pdg.82')}}: {{$details->phone_no}}</p>
			<p><i class="fa fa-envelope"></i> E-mail: <a mailto:>{{$details->email}}</a> </p>
			<p><i class="fa fa-map-marker"></i> {{$details->address}}, {{$details->country_code}}</p>
			</div>
			
			<div class="col-sm-6 col-xs-12">
			<p class="title">Rating</p>
			
			<p> <img src="{{asset('public/assets/bauenfreight/images/star-red.png')}}" alt="">
                            <img src="{{asset('public/assets/bauenfreight/images/star-red.png')}}" alt="">
                            <img src="{{asset('public/assets/bauenfreight/images/star-gray.png')}}" alt=""> 
                            <img src="{{asset('public/assets/bauenfreight/images/star-gray.png')}}" alt="">
                            <img src="{{asset('public/assets/bauenfreight/images/star-gray.png')}}" alt=""></p>
<!--			<p class="title">Over All Bids</p>
			<p>65%</p>-->
			</div>
	  	</div>
	  	<div class="user-details">
			<p class="title">{{trans('pdg.83')}}</p>
			
			<p>
                          {{$details->about_us}}
                        </p>
			</div>
	  
@else
<div>User Details Not Found</div>
@endif
    
    
    </div>
         </div>

<!--------------------------------change password---------------------------------->
<div id="edit_picture" class="modal fade" role="dialog">
  <div class="modal-dialog">

   <!--  Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit picture</h4>
      </div>
        <form action="{{'upload-user-image'}}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{Session::get('web_user_id')}}">
      <div class="modal-body ">
         
        <div class="form-horizontal">
            <div class="control-group">
               <label class="control-label label_class_css" for="">Select image:</label>
               <input type="file" class="form-control"  name="user_image">  
                @if(!empty($details->image)) 
                      <img src="{{url('../uploads/users/')}}/{{$details->image}}" width="42" height="42"  alt=""/>
                    @else
                    <img src="{{asset('public/assets/bauenfreight/images/user-placeholder.gif')}}"  width="42" height="42" alt=""/>
                    @endif
            </div>			<input type="hidden" name="user_id" value="{{$details->user_id}}">
        </div>
           
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
         </form>
    </div>
  </div>
</div>

<!--------------------------------change password---------------------------------->


@endsection