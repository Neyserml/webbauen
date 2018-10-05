<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <section class="content-header">
      <h1>
        User Documents
        <small><?php 
		if(!empty($user)){
			echo ucwords($user['first_name'].' '.$user['last_name']).' ('.phoneno_format($user['phone_no']).')';
		}
		?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">User Documents</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			  <?php 
				//echo anchor(base_url(BASE_FOLDER.'documents/add'),'<i class="fa fa-plus"></i> Document Type',array('class'=>'pull-right btn btn-primary'));
			  ?>
			  <!-- filter oftion -->
			  <form role="form" method="post">
				<input type="hidden" name="user_id" value="<?=$user_id?>">
				<input type="hidden" name="parent_user_id" value="<?=$parent_user_id?>">
				  <div class="box-body">
					<div class="form-group col-xs-3">
					  <select class="form-control" name="documenttype_id" id="documenttype_id">
						<option value="0" selected>Select Document Type</option>
						<?php
						if(!empty($documenttypes)){
							foreach($documenttypes as $doctype){
								$selected='';
								if($documenttype_id==$doctype['documenttype_id']){
									$selected='selected';
								}
								?>
							<option value="<?=$doctype['documenttype_id']?>" <?=$selected?>><?=ucwords($doctype['document_title'])?></option>
								<?php
							}
						}
						?>
					  </select>
					</div>
					<div class="form-group col-xs-3">
						<input type="text" name="email_phone_no" placeholder="Enter user phone no./email" class="form-control" value="<?=$email_phone_no?>">
					</div>
				  </div>
				  <!-- /.box-body -->
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">Filter</button>
				  </div>
				</form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="trnstable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>User Details</th>
                  <th>Document Title</th>
                  <th>Document Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php 
				if(!empty($userdocuments)){
					foreach($userdocuments as $document){
						$row_id = $document['user_document_id'];
						$is_blocked = $document['is_blocked'];
						?>
				<tr>
                  <td><?=$row_id?></td>
				  <td><?php
					echo ucwords($document['first_name'].' '.$document['last_name']).'<br/>';
					echo 'phone : '.phoneno_format($document['phone_no']);
				  ?></td>
                  <td><?=ucwords($document['document_title'])?></td>
                  <td><?php
					if($document['document_status']==1){
						echo "<span class='bg-green'>Approved</span>";
					}
					elseif($document['document_status']==2){
						echo "<span class='bg-red'>Rejected</span>";
					}
					else{
						echo "<span class='bg-yellow'>Panding</span>";
					}
				  ?></td>
                  <td style="width:200px;"><?php
					echo anchor(base_url(BASE_FOLDER.'userdocuments/view/'.$row_id),"<i class='fa fa-edit'></i> View",array('class'=>'btn btn-info btn-sm'));
					echo str_repeat('&nbsp;',1);
					if($is_blocked){
						echo anchor(base_url(BASE_FOLDER.'userdocuments/blockestatuschange/'.$row_id),"<i class='fa fa-unlock'></i> Unblock",array('class'=>'btn btn-warning btn-sm mr-blockunblock','tbl_name'=>'user_documents','url'=>base_url(BASE_FOLDER.'userdocuments/blockestatuschange/'.$row_id)));
					}
					else{
						echo anchor(base_url(BASE_FOLDER.'userdocuments/blockestatuschange/'.$row_id.'/1'),"<i class='fa fa-lock'></i> Block",array('class'=>'btn btn-warning btn-sm mr-blockunblock','tbl_name'=>'user_documents','url'=>base_url(BASE_FOLDER.'userdocuments/blockestatuschange/'.$row_id)));
					}
					echo str_repeat('&nbsp;',1);
					echo anchor(base_url(BASE_FOLDER.'userdocuments/deletetblrecord/'.$row_id),"<i class='fa fa-trash'></i> Delete",array('class'=>'btn btn-danger mr-deleterow btn-sm','tbl_name'=>'user_documents'));
					
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
