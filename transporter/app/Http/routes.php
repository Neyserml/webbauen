<?php

Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/about','Buy_Sell\web\homecontroller@about');
Route::get('/how-it-works','Buy_Sell\web\homecontroller@how_it_works');
Route::get('/carriers','Buy_Sell\web\homecontroller@carriers');

Route::get('/signin','Buy_Sell\auth\web_auth_con@web_login');
Route::post('/post-login','Buy_Sell\auth\web_auth_con@web_post_login');
Route::get('/logout','Buy_Sell\auth\web_auth_con@user_logout');

Route::post('api/user-tabel-list','Buy_Sell\web\homecontroller@user_tabel_list');
Route::post('api/user-delete-row','Buy_Sell\web\homecontroller@user_delete_row');
Route::post('api/user-insert-data','Buy_Sell\web\homecontroller@user_insert_data');

Route::get('/signup','Buy_Sell\auth\web_auth_con@web_registration');
Route::post('/post-signup','Buy_Sell\auth\web_auth_con@web_post_signup');
Route::post('/post-company-signup','Buy_Sell\auth\web_auth_con@web_company_post_signup');

Route::get('privacy-policy','Buy_Sell\web\homecontroller@privacy_policy'); 
Route::get('terms-and-conditions','Buy_Sell\web\homecontroller@terms_and_conditions'); 

Route::post('api/user-chat-details','Buy_Sell\web\homecontroller@message_details');
Route::post('api/write-message','Buy_Sell\web\homecontroller@write_message');
Route::get('api/request-accept-{id}','Buy_Sell\web\homecontroller@request_accept'); 
Route::get('api/get-trailers','Buy_Sell\web\homecontroller@get_trailers'); 
Route::post('api/post-request','Buy_Sell\web\homecontroller@post_request'); 
Route::post('api/lang',array(
                        'before' => 'csrf',
                        'as' => 'language-chooser',
                        'uses' => 'Buy_Sell\LanguageController@changeLanguage'
                        )						
            );

Route::get('/contacts','Buy_Sell\web\homecontroller@contacts');

/*------------------------------------------------------------------------------------------*/
Route::post('user-forgot-password','Buy_Sell\auth\web_auth_con@user_forgot_password');
Route::any('user-update-password-{time}-{email}','Buy_Sell\auth\web_auth_con@user_update_password');
Route::any('user-update-password-process','Buy_Sell\auth\web_auth_con@user_update_password_process');
Route::post('reset-user-password','Buy_Sell\auth\web_auth_con@reset_user_password');

Route::post('send-your-message','Buy_Sell\auth\web_auth_con@send_your_message');  

/*------------------------------------------------------------------------------------------*/
Route::any('user-active-account-{time}-{email}','Buy_Sell\auth\web_auth_con@user_active_account');
/**********************************************************************************************************/

Route::group(['middleware' => ['check_web_login']],  function(){
    Route::get('/shipper','Buy_Sell\web\homecontroller@shipper');
Route::get('/shipper2','Buy_Sell\web\homecontroller@shipper2');
    Route::get('/your-quote','Buy_Sell\web\homecontroller@your_quote');
    Route::get('/request-list','Buy_Sell\web\homecontroller@request_list');
	Route::get('/request-list-details-{id}','Buy_Sell\web\homecontroller@request_list_details'); 
    Route::get('/message','Buy_Sell\web\homecontroller@message');
    Route::get('/profile','Buy_Sell\web\homecontroller@profile');
    Route::get('/past-bids','Buy_Sell\web\homecontroller@past_bids');





    Route::get('/total-notification','Buy_Sell\web\homecontroller@total_notification');
    Route::post('/upload-user-image','Buy_Sell\web\homecontroller@upload_user_image');
    Route::post('post-your-request','Buy_Sell\web\homecontroller@post_request'); 
    Route::post('update-your-request','Buy_Sell\web\homecontroller@update_request');
});

Route::get('send-mail',function(){
	$to = "priyo.ncr@gmail.com";  
$subject = "Test mail";  
$message = "Hello! This is a simple email message.";  
$from = "garai.priyodas@gmail.com";  
$headers = "From: $from";  
mail($to,$subject,$message,$headers);  
echo "Mail Sent.";  
	
});
/*
 * request-list
 * 
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
Route::get('/','Buy_Sell\web\homecontroller@index');
 * 
 */

Route::get('/request_list_edit/{request_id}','Buy_Sell\web\homecontroller@requestEdit')->name('requestEdit');
Route::post('/request/delete','Buy_Sell\web\homecontroller@softdelete_requests')->name('deleteRequest');