$().ready(function(){
    

    $("#login_form").validate({
        rules:{
            user_email : "required",
            password : {
                        required :true ,
                       // minlength : 6
                        }
        },
        messages : {
            user_email : "Please enter your email id",
            password : {
                required : "Please enter your password",
               // minlength : "Your password must be at list 6 characters long"
            }
        }
    });
    
    
    $("#signup_form").validate({
        rules:{
            user_email : "required",
            password : {
                        required :true ,
                        minlength : 6
                        },
            retypepassword : {
                        required :true ,
                        equalTo : "#password"
                        },
           first_name : "required",
          
        },
        messages : {
            user_email : "Please enter your email id",
            password : {
                required : "Please enter your password",
                minlength : "Your password must be at list 6 characters long"
            },
            retypepassword : {
                required : "Please repeat your password",
                equalTo : "Your retypepassword must be same as your password"
            },
             first_name : "Please enter your first name",
        }
    });
    
    
    $("#company_signup_form").validate({
        rules:{
            user_email : "required",
           company_password : {
                        required :true ,
                        minlength : 6
                        },
            retypepassword : {
                        required :true ,
                        equalTo : "#company_password"
                        },
           first_name : "required",
          
        },
        messages : {
            user_email : "Please enter your email id",
            company_password : {
                required : "Please enter your password",
                minlength : "Your password must be at list 6 characters long"
            },
            retypepassword : {
                required : "Please repeat your password",
                equalTo : "Your retypepassword must be same as your password"
            },
             first_name : "Please enter your first name",
        }
    });
     $("#your_request").validate({
        rules:{
            pickup_location : "required",
           dropoff_location : {
                        required :true ,
                            },
            pickup_date : {
                        required :true ,
                        },
            pickup_time : "required",
            trailer_id : {
            		required :true,
            		min: 1
            		},
            loadtype_id : {
            		required :true,
            		min: 1
            		},
            weight : "required",
            size : "required",
            description : "required"
          
        },
        messages : {
            pickup_location : "Please enter pickup location",
            dropoff_location : {
                required : "Please enter your dropoff location",
                          },
            pickup_date : {
                required : "Please enter pickup date",
                            },
             pickup_time : "Please enter your pickup time",
             trailer_id : "Please enter your Trailer ",
             loadtype_id : "Please enter your Loadtype ",
             weight : "Please enter product weight",
             size : "Please enter product size",
             description : "Please enter product Description",
        }
    });
    
    
    });
    $("#submit_your_request").click(function(){
      console.log("form_oki");
       $("#your_request").submit();
      });

    $("#update_your_request").click(function(){


         console.log("actualiza");
         $("#your_request").submit();
      });    


    $("#submit_login_form").click(function(){
    $("#login_form").submit();
    });
    $("#submit_signup_form").click(function(){
    $("#signup_form").submit();
    });
    $("#submit_company_signup_form").click(function(){
       // console.log("form_ok");
    $("#company_signup_form").submit();
    });