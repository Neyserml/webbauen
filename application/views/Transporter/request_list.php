<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url('assets/bootstrap/css/bootstrap-datepicker.min.css')?>" />
    <section class="content-header">
      <h1>
        Ordenes en Tránsito
        <small><?php
		if(!empty($user)){
			echo "Of ".ucwords($user['first_name'].' '.$user['last_name']).' ('.phoneno_format($user['phone_no']).' )';
		}
		?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Ordenes en tránsito</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			  <form role="form" method="post">
				<input type="hidden" name="transporter_id" value="<?=$transporter_id?>">
				<input type="hidden" name="user_id" value="<?=$user_id?>">
				  <div class="box-body">
					<div class="form-group col-xs-3">
					  <select class="form-control" name="trailer_id" id="trailer_id">
						<option value="0" selected>Seleccionar Tipo de Trailer</option>
						<?php
						if(!empty($trailers)){
							foreach($trailers as $trailer){
								$selected='';
								if($trailer_id==$trailer['trailer_id']){
									$selected='selected';
								}
								?>
							<option value="<?=$trailer['trailer_id']?>" <?=$selected?>><?=ucwords($trailer['name'])?></option>
								<?php
							}
						}
						?>
					  </select>
					</div>
					<div class="form-group col-xs-3">
					  <select class="form-control" name="loadtype_id" id="loadtype_id">
						<option value="0" selected>Seleccionar Tipo de Carga</option>
						<?php
						if(!empty($loadtypes)){
							foreach($loadtypes as $loadtype){
								$selected='';
								if($loadtype_id==$loadtype['loadtype_id']){
									$selected='selected';
								}
								?>
							<option value="<?=$loadtype['loadtype_id']?>" <?=$selected?>><?=ucwords($loadtype['load_name'])?></option>
								<?php
							}
						}
						?>
					  </select>
					</div>
					<div class="form-group col-xs-3">
					  <select class="form-control" name="request_status" id="request_status">
						<option value="0" selected>Seleccionar Status</option>
						<?php
						if(!empty($request_status)){
							foreach($request_status as $key=>$status){
								$selected='';
								if(($key+1)==$request_status_key){
									$selected='selected';
								}
								?>
							<option value="<?=($key+1)?>" <?=$selected?>><?=ucwords($status)?></option>
								<?php
							}
						}
						?>
					  </select>
					</div>
					<div class="form-group col-xs-3">
						<input type="text" class="form-control" name="email_phone_no" id="email_phone_no" placeholder="Ingrese correo o teléfono" value="<?=$email_phone_no?>" >
					</div>
					
					<div class="form-group col-xs-3">
						<input type="text" class="form-control datepicker" name="pickup_date_from" id="pickup_date_from" placeholder="Ingresar fecha desde" value="<?=$pickup_date_from?>" >
					</div>
					
					<div class="form-group col-xs-3">
						<input type="text" class="form-control datepicker" name="pickup_date_to" id="pickup_date_to" placeholder="Ingresar fecha hasta" value="<?=$pickup_date_to?>" >
					</div>
					
				  </div>
				  <!-- /.box-body -->
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">Filtrar</button>
				  </div>
				</form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="trnstable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Lugar de Recojo</th>
                  <th>Punto de Destino</th>
                  <th>Date & Time</th>
                  <th>¿Que transportamos?</th>
                  <th>Flete</th>
                  <th>Tipo de Trailer</th>
                  <th>Total propuestas</th>
				  <th>Status</th>
                  <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php 
				if(!empty($requests)){
					foreach($requests as $request){
						$row_id = $request['request_id'];
						$is_blocked = $request['is_blocked'];
						$request_status = $request['request_status'];
						?>
				<tr>
                  <td><?=$row_id?></td>
                  <td><?=$request['pickup_location']?></td>
                  <td><?=$request['dropoff_location']?></td>
                  <td><?php
					echo "Date : ".display_date_format($request['pickup_date'])."<br/>";
					echo "Time : ".$request['pickup_time'];
				  ?></td>
                  <td><?php
					echo "Type : ".ucwords($request['load_name'])."<br/>";
					echo "Weight : ".$request['weight']."<br/>";
					echo "Size(WxHxL) : ".$request['size'];
				  ?></td>
                  <td><?php
					echo "Initial : ".number_format($request['request_amount'],2)."<br/>";
					echo "Granted : ".number_format($request['granted_amount'],2);
				  ?></td>
                  <td><?=$request['trailer_name']?></td>
                  <td><?=$request['total_bids']?></td>
                  <td><?php
					$status_txt=request_status($request_status);
					echo $status_txt;
				  ?></td>
                  
                  <td ><?php
					echo anchor(base_url(BASE_FOLDER_TRANS.'requests/details/'.$row_id),"<i class='fa fa-info'></i> Detalles",array('class'=>'btn btn-warning btn-sm'));
					
					if($request_status != REQUEST_COMPLED_STATUS){
						echo str_repeat('&nbsp;','1');
						echo anchor(base_url(BASE_FOLDER_TRANS.'chats/add/'.$row_id),"<i class='fa fa-commenting-o'></i> Chat",array('class'=>'btn btn-info btn-sm'));
					}
					
					echo str_repeat('&nbsp;','1');
					// status wise action button
					if($request['trans_bid_status']==3){
						echo "Cancelled By You";
					}
					elseif($request['trans_bid_status']==2){
						// accepted by transporter
						if($request['bid_id']==$request['trans_bid_id']){
							// now depend on the request status 
							if($request_status==3){
								// assing driver and vehicle 
								echo anchor(base_url(BASE_FOLDER_TRANS.'requests/assigndriver/'.$row_id),"<i class='fa fa-plus'></i> Asignar conductor",array('class'=>'btn btn-primary btn-sm'));
							}
						}
					}
					elseif($request['trans_bid_status']==1){
						// customer accept the bid 
						if($request['bid_id']==$request['trans_bid_id']){
							echo anchor(base_url(BASE_FOLDER_TRANS.'requests/bidaccept/'.$row_id.'/2'),"<i class='fa fa-legal'></i> Confirmar",array('class'=>'btn btn-primary btn-sm'));
							echo anchor(base_url(BASE_FOLDER_TRANS.'requests/bidaccept/'.$row_id.'/3'),"<i class='fa fa-legal'></i> Cancelar",array('class'=>'btn btn-danger btn-sm mt-5'));
						}
					}
					else{
						// only placed or not 
						if($request_status<=1){
							echo anchor(base_url(BASE_FOLDER_TRANS.'requests/bids/'.$row_id),"<i class='fa fa-legal'></i>Cotizar",array('class'=>'btn btn-primary btn-sm'));
						}
					}
				  ?></td>
                </tr>
						<?php
					}
				}
				?>
                
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

	<!-- data table config loading section --->
	<script src="<?=base_url('assets/js/datatableconfig.js')?>" type="text/javascript"></script>
	<script src="<?=base_url('assets/bootstrap/js/bootstrap-datepicker.min.js')?>" type="text/javascript"></script>
	<script>
		$(".datepicker").datepicker({
			autoclose:true,
			format:'mm/dd/yyyy',
		});
	</script>
