<!DOCTYPE html>
<html lang="en">
	<head>
		<title>SFI</title>

		<!-- BEGIN META -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="your,keywords">
		<meta name="description" content="Short explanation about this website">
		<!-- END META -->
<link rel="icon" href="<?php echo base_url();?>assets/img/favicon.png" type="image/png" sizes="16x16" />

		<!-- BEGIN STYLESHEETS -->
		<link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/materialadmin.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/libs/DataTables/jquery.dataTables.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.min.css" />

		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/chosen/chosen.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/chosen/chosen.min.css" />

<style>
.switch.primary-switch label input[type=checkbox]:checked + .lever {
    background-color: #689DF6;
}
.switch.primary-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #4285F4;
}
.switch.default-switch label input[type=checkbox]:checked + .lever {
    background-color: #4DCCBF;
}
.switch.default-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #2BBBAD;
}
.switch.secondary-switch label input[type=checkbox]:checked + .lever {
    background-color: #C791E2;
}
.switch.secondary-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #a6c;
}
.switch.success-switch label input[type=checkbox]:checked + .lever {
    background-color: #2ACC6C;
}
.switch.success-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #00C851;
}
.switch.info-switch label input[type=checkbox]:checked + .lever {
    background-color: #59C3EB;
}
.switch.info-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #33b5e5;
}
.switch.warning-switch label input[type=checkbox]:checked + .lever {
    background-color: #FFA339;
}
.switch.warning-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #F80;
}
.switch.danger-switch label input[type=checkbox]:checked + .lever {
    background-color: #FF606F;
}
.switch.danger-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #ff3547;
}
.switch.teal-switch label input[type=checkbox]:checked + .lever {
    background-color: #b2dfdb;
}
.switch.teal-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #80cbc4;
}
.switch.pink-switch label input[type=checkbox]:checked + .lever {
    background-color: #f8bbd0;
}
.switch.pink-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #f48fb1;
}
.switch.blue-switch label input[type=checkbox]:checked + .lever {
    background-color: #b3e5fc;
}
.switch.blue-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #81d4fa;
}
.switch.amber-switch label input[type=checkbox]:checked + .lever {
    background-color: #ffe082;
}
.switch.amber-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #ffd54f;
}
.switch.mdb-color-switch label input[type=checkbox]:checked + .lever {
    background-color: #618FB5;
}
.switch.mdb-color-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #3F729B;
}
.switch.indigo-switch label input[type=checkbox]:checked + .lever {
    background-color: #9fa8da;
}
.switch.indigo-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #7986cb;
}
.switch.blue-white-switch label input[type=checkbox]:checked + .lever {
    background-color: #2196f3;
}
.switch.blue-white-switch label input[type=checkbox]:checked + .lever:after {
    background-color: #fff;
}
.switch.blue-white-switch label .lever  {
    background-color: #ccc;
}
.switch.blue-white-switch label .lever:after {
    background-color: #fff;
}


.switch.round label .lever {
    width: 54px;
    height: 34px;
    border-radius: 10em;
}
.switch.round label .lever:after {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    left: 4px;
    top: 4px;
}
.switch.square label .lever {
    width: 54px;
    height: 34px;
    border-radius: 0px;
}
.switch.square label .lever:after {
    width: 26px;
    height: 26px;
    border-radius: 0px;
    left: 4px;
    top: 4px;
}
     
</style>
		<!-- END STYLESHEETS -->
		<script src="<?php echo base_url();?>assets/js/libs/jquery/jquery-1.11.2.min.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/libs/utils/html5shiv.js"></script>
		<script type="text/javascript" src="js/libs/utils/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed menubar-pin ">

		<!-- BEGIN HEADER-->
		<header id="header" >
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="index.html">
									<span class="text-lg text-bold text-primary">
										<img src="<?php echo base_url();?>assets/img/logo.png" alt="SFI">
									</span>
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="headerbar-right">
					<ul class="header-nav header-nav-options">
						<li>
							<!-- Search form -->
							<form class="navbar-search" role="search">
								<div class="form-group">
									<input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
								</div>
								<button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
							</form>
						</li>
					</ul><!--end .header-nav-options -->
					<ul class="header-nav header-nav-profile">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
								<img src="<?php echo base_url();?>assets/img/avatar-2.png" alt="" />
								<span class="profile-info">
									<?php echo ucfirst($userefirst_name) .' '.ucfirst($userelast_name); ?>
									
								</span>
							</a>
							<ul class="dropdown-menu animation-dock">
								<!-- <li><a href="#">My Profile</a></li> -->
								<!-- <li class="divider"></li> -->
								<li><a href="<?php echo base_url();?>admin_master/logout"><i class="fa fa-fw fa-power-off text-danger"></i> Logout</a></li>
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
					</ul><!--end .header-nav-profile -->
				</div><!--end #header-navbar-collapse -->
			</div>
		</header>
		<!-- END HEADER-->