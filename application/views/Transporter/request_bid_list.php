<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<section class="content-header">
      <h1>
        Place Bids
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'requests')?>">Requests</a></li>
        <li class="active">Request Bids</li>
      </ol>
    </section>
	<section class="invoice">
		<div class="row invoice-info">
			<div class="col-sm-4 invoice-col">
			  Customer
			  <address>
				<b>Name: </b><?=ucwords($request['cus_first_name'].' '.$request['cus_last_name'])?><br>
				<b>Email: </b><?=$request['cus_email']?><br>
				<b>Phone no.: </b><?=phoneno_format($request['cus_phone_no'])?><br>
				<b>Rating: </b>
			  </address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
			  Request<br>
			  <b>Request ID: </b><?=$request['request_id']?><br>
			  <b>Pickup Date: </b> <?=display_date_format($request['pickup_date'])?></br>
			  <b>Time: </b><?=$request['pickup_time']?><br>
			  <b>Placed Amount: </b><?=number_format($request['request_amount'],2)?><br>
			  <b>Total Bids: </b><?=$request['total_bids']?>
			</div>
			<div class="col-sm-4 invoice-col">
			  Load Details
			  <address>
				<b>Trailer: </b> <?=ucwords($request['trailer_name'])?><br>
				<b>Load Type: </b><?=ucwords($request['load_name'])?><br>
				<b>Weight: </b><?=$request['weight']." Kg"?><br>
				<b>Size (WxHxL): </b><?=$request['size']?><br>
			  </address>
			</div>
			<!-- /.col -->
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
			<div class="box-header">
			<?php
			if($request['request_status']>1){
				echo "<h3><center>You can not place/update this bid</center></h3>";
			}
			else{
				if(!empty($request['trans_bid_amount'])){
					echo "<h3>Your placed bid amount is : ".number_format($request['trans_bid_amount'],2)."</br></h3>";
				}
				else{
					echo "<h3>Your placed bid amount is : 0.00</br></h3>";
				}
			?>
			<label>Place/Update Bid Amount</label>
			<form role="form" method="post">
			<input type="hidden" name="bid_id" id="bid_id" value="<?=$request['trans_bid_id']?>">
			<div class="box-body">
				<div class="form-group col-xs-3">
					<input type="text" class="form-control" name="bid_amount" id="bid_amount" placeholder="Enter bid amount" value="<?=set_value('bid_amount')?>">
					<?=form_error('bid_amount')?>
				</div>
				<button type="submit" class="btn btn-primary ">Save</button>
			</div>
			<!-- /.box-body -->
			<div class="box-footer"></div>
			</form>
			<?php
			}
			?>
			Request's Bids</br>
			</div>
			<div class="box-body">
				
				<table id="trnstable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Transporter Details</th>
                  <th>Bid Amount</th>
                  <th>Place Date</th>
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