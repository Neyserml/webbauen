<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<section class="content-header">
      <h1>
        Detalles de la Carga
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'requests')?>">Ordenes de Servicio</a></li>
        <li class="active">Detalles de la Orden</li>
      </ol>
    </section>
	<section class="invoice">
		<div class="row invoice-info">
			<div class="col-sm-3 invoice-col">
			  Cliente
			  <address>
				<b>Nombre: </b><?=ucwords($request['cus_first_name'].' '.$request['cus_last_name'])?><br>
				<b>Correo: </b><?=$request['cus_email']?><br>
				<b>Número de Tlfo.: </b><?=phoneno_format($request['cus_phone_no'])?><br>
				<b>Rating: </b>
			  </address>
			</div>
			<!-- /.col -->
			<div class="col-sm-3 invoice-col">
			  Transportista
			  <address>
				<b>Nombre: </b><?=ucwords($request['trans_first_name'].' '.$request['trans_last_name'])?><br>
				<b>Correo: </b><?=$request['trans_email']?><br>
				<b>Tlfo.: </b><?=phoneno_format($request['trans_phone_no'])?><br>
				<b>Rating: </b>
			  </address>
			</div>
			<div class="col-sm-3 invoice-col">
			  Chofer & Camión
			  <address>
				<b>Conductor: </b><?=ucwords($request['driver_first_name'].' '.$request['driver_last_name'])?><br>
				<b>Cuenta: </b><?=$request['driver_email']?><br>
				<b>Tlfo.: </b><?=phoneno_format($request['driver_phone_no'])?><br>
				<b>Rating: </b> </br>
				<b>Placa: </b><?=$request['plate_no']?></br>
				<b>Color del Vehiculo: </b><?=$request['vehicle_color']?><span style="background-color:<?=$request['vehicle_color']?>">&nbsp;&nbsp;</span>
			  </address>
			</div>
			
			<!-- /.col -->
			<div class="col-sm-3 invoice-col">
			  Request<br>
			  <b>ID: </b><?=$request['request_id']?><br>
			  <b>Tipo de Carga: </b><?=ucwords($request['load_name'])?><br>
			  <b>Tipo de Trailer: </b> <?=ucwords($request['trailer_name'])?><br>
			  <b>Fecha/Hora: </b> <?=display_date_format($request['pickup_date'])?></br>
			  <b>Time: </b><?=$request['pickup_time']?><br>
			  <!--<b>Flete: </b><?=number_format((($request['granted_amount']>0)?$request['granted_amount']:$request['request_amount']),2)?><br>-->
			  <b>Monto Inicial: </b><?=number_format($request['request_amount'],2)?><br>
			  <b>Pactado: </b><?=number_format($request['granted_amount'],2)?><br>
			  <b>Status: </b><?=request_status($request['request_status'])?>
			</div>
		</div>
		<div class="row invoice-info">
			<hr>
			<div class="col-sm-12 invoice-col">
			<b>Instruction: </b><?=$request['description']?>
			</div>
		</div>
	</section>
	
	<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
			<div class="box-header">Cargas disponibles</div>
			<div class="box-body">
				<table id="trnstable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Transportista</th>
                  <th>Monto</th>
                  <th>Fecha de Envío</th>
				  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php 
				if(!empty($requestbids)){
					foreach($requestbids as $bid){
						$row_id = $bid['bid_id'];
						//$is_blocked = $bid['is_blocked'];
						?>
				<tr>
                  <td><?=$row_id?></td>
                  <td><?=ucwords($bid['trans_first_name'].' '.$bid['trans_last_name'])?></td>
                  <td><?=number_format($bid['bid_amount'],2)?></td>
                  <td><?php
					echo "Date : ".display_date_format($request['create_date']);
				  ?></td>
                  <td><?php
					if($request['bid_id']==$row_id){
						echo "Won the Bid";
					}
					else{
						if($request['bid_id']>0){
							echo "Lost";
						}
						else{
							if($bid['bid_status']==1){
								echo "Customer Accepted";
							}
							else{
								echo "Panding";
							}
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
		  </div>
		</div>
	  </div>
	 </section>
	 <!-- data table config loading section --->
	<script src="<?=base_url('assets/js/datatableconfig.js')?>" type="text/javascript"></script>