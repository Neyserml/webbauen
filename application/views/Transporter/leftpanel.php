<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$controllername = $this->uri->segment(2);
	$methodname = $this->uri->segment(3);
	$dashboardActive = $driverActive = $vehicleAction = $documenAction = $requestAction = $subadminAction = '';
	
	// drivers 
	$driv_list = $driv_add = '';
	// vehicle 
	$veh_list = $veh_add = '';
	// document 
	$doct_list = $doct_add = '';
	// request 
	$req_list='';
	// subadmin
	$subadmin_list = $subadmin_add = '';
	
	$actClsName = $subActClsName = "active";
	switch(strtolower($controllername)){
		case 'drivers':
			$driverActive=$actClsName;
			switch($methodname){
				case 'add':
					$driv_add=$subActClsName;
					break;
				default:
					$driv_list=$subActClsName;
					break;
			}
			break;
		case 'vehicles':
			$vehicleAction=$actClsName;
			switch($methodname){
				case 'add':
					$veh_add=$subActClsName;
					break;
				default:
					$veh_list=$subActClsName;
					break;
			}
			break;
		case 'documents':
			$documenAction=$actClsName;
			switch($methodname){
				case 'add':
					$doct_add=$subActClsName;
					break;
				default:
					$doct_list=$subActClsName;
					break;
			}
			break;
		case 'requests':
			$requestAction=$actClsName;
			switch($methodname){
				default:
					$req_list=$subActClsName;
					break;
			}
			break;
		case 'subadmins':
			$subadminAction=$actClsName;
			$subadmin_list = $subActClsName;
			break;
		case 'editsubadmin':
			$subadminAction=$actClsName;
			$subadmin_list = $subActClsName;
			break;
		case 'addsubadmin':
			$subadminAction=$actClsName;
			$subadmin_add = $subActClsName;
			break;
		default:
			$dashboardActive=$actClsName;
			break;
	}
?>
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">NAVEGADOR PRINCIPAL</li>
        <?php
			if($this->session->has_userdata(SES_TRANS_ID) && $this->session->userdata(SES_TRANS_ID)>0){
				if($this->session->userdata(SES_TRANS_SUPER)){
					//super admin section 
					?>
					<li class="<?=$dashboardActive?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'dashboard')?>">
					<i class="fa fa-dashboard"></i> <span>Cargas</span>
				  </a>
				</li>
				
				<li class="<?=$driverActive?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'drivers')?>">
					<i class="fa fa-users"></i>
					<span>Conductores</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li class="<?=$driv_list?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'drivers')?>"><i class="fa fa-circle-o"></i>Listado</a></li>
					<li class="<?=$driv_add?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'drivers/add')?>"><i class="fa fa-circle-o"></i>Nuevo</a></li>
				  </ul>
				</li>
				
				<li class="<?=$vehicleAction?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'vehicles')?>">
					<i class="fa fa-truck"></i>
					<span>Vehiculos</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li class="<?=$veh_list?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'vehicles')?>"><i class="fa fa-circle-o"></i>Listado</a></li>
					<li class="<?=$veh_add?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'vehicles/add')?>"><i class="fa fa-circle-o"></i>Nuevo</a></li>
				  </ul>
				</li>
				
				<li class="<?=$documenAction?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'documents')?>">
					<i class="fa fa-book"></i>
					<span>Documentos</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li class="<?=$doct_list?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'documents')?>"><i class="fa fa-circle-o"></i>Listado</a></li>
					<li class="<?=$doct_add?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'documents/add')?>"><i class="fa fa-circle-o"></i>Nuevo</a></li>
				  </ul>
				</li>
				
				<li class="<?=$requestAction?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'requests')?>">
					<i class="fa fa-database"></i>
					<span>Ordenes</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li class="<?=$req_list?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'requests')?>"><i class="fa fa-circle-o"></i>Listado</a></li>
				  </ul>
				</li>
				
				<li class="<?=$subadminAction?> treeview">
				  <a href="<?=base_url(BASE_FOLDER_TRANS.'subadmins')?>">
					<i class="fa fa-user-secret"></i>
					<span>Sub Admins</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li class="<?=$subadmin_list?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'subadmins')?>"><i class="fa fa-circle-o"></i>Listado</a></li>
					<li class="<?=$subadmin_add?>"><a href="<?=base_url(BASE_FOLDER_TRANS.'addsubadmin')?>"><i class="fa fa-circle-o"></i>Nuevo</a></li>
				  </ul>
				</li>
					<?php
				}
				else{
					// other admins  
					if($this->session->userdata(SES_TRANS_TYPE)){
						?><?php
					}
					else{
						// no menu found
						?><?php
					}
				}
			}
		?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>