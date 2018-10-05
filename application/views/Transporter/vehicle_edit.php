<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- color picker -->
<link rel="stylesheet" href="<?=base_url('assets/plugins/colorpicker/bootstrap-colorpicker.min.css')?>">
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vehicle Edit
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'vehicles')?>">Vehicles</a></li>
        <li class="active">Vehicle Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
		<div class="box box-body">
        <!-- left column -->
        <div class="col-md-8">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Vehicle details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="trailer_id">Trailer</label>
				  <select name="trailer_id" id="trailer_id" class="form-control" >
					<option value="0">Select Trailer</option>
					<?php
					if(!empty($trailers)){
						$trailer_id = set_value('trailer_id',$vehicle['trailer_id']);
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
				  <?=form_error('trailer_id')?>
                </div>
				<div class="form-group">
                  <label for="plate_no">Plate No.</label>
                  <input type="text" class="form-control" id="plate_no" name="plate_no" placeholder="Enter plate no." value="<?=set_value('plate_no',$vehicle['plate_no'])?>">
				  <?=form_error('plate_no')?>
                </div>
                <div class="form-group">
                  <label for="purchase_year">Purchase Year</label>
                  <input type="text" class="form-control" id="purchase_year" name="purchase_year" placeholder="Enter purchase year" value="<?=set_value('purchase_year',$vehicle['purchase_year'])?>">
				  <?=form_error('purchase_year')?>
                </div>
				<div class="form-group">
                  <label for="vehicle_minload">Minimum Load</label>
                  <input type="text" class="form-control" id="vehicle_minload" name="vehicle_minload" placeholder="Enter minimum carrying load" value="<?=set_value('vehicle_minload',$vehicle['vehicle_minload'])?>">
				  <?=form_error('vehicle_minload')?>
                </div>
				<div class="form-group">
                  <label for="vehicle_maxload">Maximum Load</label>
                  <input type="text" class="form-control" id="vehicle_maxload" name="vehicle_maxload" placeholder="Enter maximum carrying load" value="<?=set_value('vehicle_maxload',$vehicle['vehicle_maxload'])?>">
				  <?=form_error('vehicle_maxload')?>
                </div>
				<div class="form-group">
                  <label for="vehicle_color">Vehicle Color</label>
                  <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" placeholder="Enter vehicle hex color" readonly value="<?=set_value('vehicle_color',$vehicle['vehicle_color'])?>">
				  <?=form_error('vehicle_color')?>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
		</div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	<!-- page script -->
	<script src="<?=base_url('assets/plugins/colorpicker/bootstrap-colorpicker.min.js')?>"></script>
	<script>
		$(document).ready(function(){
			$("#vehicle_color").colorpicker();
		});
	</script>