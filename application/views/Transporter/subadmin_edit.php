<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar información de Sub Administrador
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?=base_url(BASE_FOLDER_TRANS.'subadmins')?>">Sub Admins</a></li>
        <li class="active">Editar la información de las personas que tienen cuentas linkeadas contigo</li>
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
              <h3 class="box-title">Sub Admin details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="first_name">Nombre del Sub Admin</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" value="<?=set_value('first_name',$subadmin['first_name'])?>">
				  <?=form_error('first_name')?>
                </div>
				<div class="form-group">
                  <label for="last_name">Apellido del Sub Admin</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" value="<?=set_value('last_name',$subadmin['last_name'])?>">
                </div>
                <div class="form-group">
                  <label for="email">Correo electrónico (Username)</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Esta será la cuenta de usuario" value="<?=set_value('email',$subadmin['email'])?>">
				  <?=form_error('email')?>
                </div>
				<div class="form-group">
                  <label for="phone_no">Número de teléfono</label>
                  <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Ingrese un número de teléfono" value="<?=set_value('phone_no',$subadmin['phone_no'])?>">
				  <?=form_error('phone_no')?>
                </div>
				<div class="form-group">
                  <label for="password">Contraseña</label>
                  <input type="text" class="form-control" id="password" name="password" placeholder="Ingrese la contraseña para esta cuenta" value="<?=set_value('password',$subadmin['showpass'])?>">
				  <?=form_error('password')?>
                </div>
				
				<div class="form-group">
                  <label for="dni_no">DNI No.</label>
                  <input type="text" class="form-control" id="dni_no" name="dni_no" placeholder="Enter DNI no." value="<?=set_value('dni_no',$subadmin['dni_no'])?>">
				  <?=form_error('dni_no')?>
                </div>
				
				<div class="form-group">
                  <label for="max_load">Imagen</label>
				  <?php
					if(!empty($subadmin['image'])){
						?>
					<img src="<?=base_url('uploads/users/'.$subadmin['image'])?>" width="60" height="60" >
						<?php
					}
				  ?>
                  <input type="file" class="form-control" id="image" name="image">
                </div>
				
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
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