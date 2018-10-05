<style>
	.pmargin{
		padding-left: 10%;
		padding-right: 10%;
		text-align:center;
	}
	.color{
		color:rgb(47,84,150);
	}
	.pfz{
		font-size:18pt;
	}
	.pfzx{
		font-size:16pt;
	}
	ul {
		list-style: none;
	}
	
</style>
<div>
  <div class="login-logo">
    <a href="javascript:void(0)"><b>Bauen Freight - “About Us”</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
	
	<p class="pmargin color pfzx">Carga altoque. Presiona un botón y obtén un servicio de carga completo e integral.</p>
	<p class="pmargin">

Bauen se encarga de reunir a los mejores proveedores de cargas del Perú. Con un solo Clic, tendrás propuestas de distintos transportistas homologados por nosotros. Bauen es un SAAS (Software as a Service) para la Industria de Carga Pesada. Somos la primera empresa de transporte sin flota propia del Perú. Nos enfocamos en hacer “match” a las necesidades de cargas con transportistas, brindando a los clientes un servicio integral, con visibilidad al 100% del servicio, status de carga, licitaciones electrónicas, perfiles y más. Nuestro equipo está comprometido con cada uno de los servicios que transitan a través del App. Nos enfocamos en permitir la fluidez de la interfaz, ofreciendo todo tipo de cargas, para cualquier industria. Mineria, Construcción, Agro-Industrial, Energía, Gas, Alimentos, etc. Solo cotiza con nosotros y obtén propuestas serias en minutos. 

</p>
	<div class="row">
	<div class="box-header with-border">
	  <h3 class="box-title color">Contact Us</h3>
	</div>
	<div class="col-md-6 color pfzx" style="margin-top:20px;">
		<p>CEO: Sergio Olcese – 942879517</p>
		<p>Supervisor: Fernando Pazos – 994250619</p>
		<p>Oficina: 01244-4882</p>
	</div>
	<div class="col-md-6">
		<form method="post" action="">
			<div class="box-body">
				<?php if(isset($errors) && !empty($errors)){?>
				<div class="callout callout-danger"><?php echo $errors;?></div>
				<?php }?>
				<?php if(isset($success) && !empty($success)){?>
				<div class="callout callout-success"><?php echo $success;?></div>
				<?php }?>
				
				<div class="form-group">
                  <label for="en_name">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                </div>
				<div class="form-group">
                  <label for="en_name">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                </div>
				<div class="form-group">
                  <label for="en_name">Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone no.">
                </div>
				<div class="form-group">
                  <label for="en_name">Message</label>
                  <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter message"></textarea>
                </div>
				<div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Send">
                </div>
			</div>
		</form>
	</div>
	</div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
