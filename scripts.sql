select t.trailer_id,t.name,t.image,t.min_load,t.max_load 
from trns_trailers t
inner join trns_language_trailers lt
on t.trailer_id=lt.trailer_id
where t.is_blocked=0 and t.is_deleted=0 and lt.language_id=1



select *from trns_language_trailers
select *from trns_users


select industrytype_id,industrytype_name from trns_industrytypes where is_blocked=0 and is_deleted=0

SELECT industrytype_id,industrytype_name from trns_industrytypes where is_blocked=0 and is_deleted=0


select *from  trns_industrytypes


SELECT *FROM trns_documenttypes

delete from trns_documenttypes where documenttype_id=0

SELECT  documenttype_id,document_title
   from trns_documenttypes where is_blocked=0 and is_deleted=0 and document_for='2'
   
   
   

   
   
   
   INSERT INTO trns_users(is_company,first_name,last_name,email,phone_no,password,dni_no,user_type,ruc_no,company_name,company_licence_no,industrytype_id,country_code,firebase_id,language_id,create_date,update_date)
                   values('0','Antonio','Guzman','luca@gmail.com','9099999','23','48235319','0','','','','0','','',0,'2018-10-05','2018-10-05') 
                   
                   
                   INSERT INTO trns_users (
                   parent_user_id,
                   super_parent_id,
                   user_type,
                   create_date, 
                   first_name,
                   last_name,
                   email,
                   phone_no,
                   password,
                   image,
                   verification_code,
                   is_user_verify, 
                   is_phone_no_verify,
                   is_email_verify,
                   is_company,
                   dni_no,
                   ruc_no,
                   licence_no,
                   industrytype_id,
                   company_name,
                   company_licence_no,
                   country_code,
                   change_pass_token,
                   email_verify_token,
                   showpass,
                   user_status,
                   old_phone_no,
                   support_instruction,
                   support_email,
                   support_contact,
                   about_us,
                   address,
                   latitude,
                   longitude,
                   creater_id,
                   is_blocked,
                   is_deleted,
                   update_date,
                   deleted_date,
                   firebase_id)
                   VALUES (
                   '0',
                   '0', 
                   '0', 
                   '2018-10-05 00:00:00',
                   'Antonio', 
                   'Marquina',
                   'marq@gmail.com',
                   '789456123', 
                   'marquina', 
                   '', 
                   '', 
                   '0',
                   '0', 
                   '0', 
                   '0', 
                   '15236478', 
                   '12345678999', 
                   '', 
                   '0',
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '1',
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '0', 
                   '0', 
                   '0', 
                   '2018-10-05',
                   NULL, 
                   '')
                   
           SET SQL_SAFE_UPDATES = 0;        
          
   select *from trns_users 
   
   
   delete from trns_users where user_id=450
   
   INSERT INTO trns_users(is_company,first_name,last_name,email,phone_no,password,dni_no,user_type,ruc_no,company_name,company_licence_no,industrytype_id,country_code,firebase_id,language_id,create_date,update_date)
                   values('0','Antonio','Guzman','luca@gmail.com','9099999','23','48235319','0','','','','0','','',0,'2018-10-05','2018-10-05') 
                   
                   
               INSERT INTO trns_users (
                   user_type,
                   create_date, 
                   first_name,
                   last_name,
                   email,
                   phone_no,
                   password,
                   verification_code,
                   is_company,
                   dni_no,
                   ruc_no,
                   licence_no,
                   industrytype_id,
                   company_name,
                   company_licence_no,
                   country_code,
                   email_verify_token,
                   showpass,
                   creater_id,
                   update_date,
                   firebase_id)
                   VALUES (
                   '1', 
                   '2018-10-05 00:00:00',
                   'Juancho', 
                   'Marquina',
                   'marq@gmail.com',
                   '789456123', 
                   'marquina', 
                   '', 
                   '0', 
                   '15236478', 
                   '12345678999', 
                   '', 
                   '0',
                   '', 
                   '', 
                   '', 
                   '', 
                   '', 
                   '0',  
                   '2018-10-05',
                   '')
                                
                   
    SELECT  user_id,first_name,last_name,email,phone_no,user_type,image,dni_no,is_company,company_name,company_licence_no,ruc_no,is_user_verify,verification_code,about_us,address,firebase_id,IFNULL(TRUNCATE(AVG(tr.rating),2),0) as rating
		from trns_users tu
        left join trns_industrytypes ti
        on tu.industrytype_id=ti.industrytype_id
        left join trns_user_ratings tr
        on tu.user_id=tr.receiver_user_id
        where tu.user_id=158
        
	SELECT IFNULL(count(request_id),0) as total_request, 
    IFNULL(SUM(IF(request_status='13',1,0)),0)as 
    completed_request, IFNULL(SUM(IF(request_status='14',1,0)),0) as expired_request, 
    IFNULL(SUM(IF((request_status > '5' && request_status < '13'),1,0)),0) as in_transit_request
    from trns_requests 
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
