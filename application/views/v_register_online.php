<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>U&I | Payment System</title>
	<link rel="icon" type="image/jpg" href="<?php echo base_url() ?>assets/dist/img/ui3.jpg" />
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/blue.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<img src="<?php echo base_url() ?>assets/dist/img/logouniV1.png" width="275">
			<br />
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<?php if (isset($error)) : ?>
				<p class="login-box-msg">
					<font color="red"><?= $error ?></font>
				</p>
			<?php else : ?>
				<p class="login-box-msg">Register</p>
			<?php endif; ?>

			<?php if ($this->session->flashdata('success') != null) : ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?= $this->session->flashdata('success') ?>
				</div>
			<?php else : ?>
			<?php endif; ?>


			<form action="<?php echo base_url() ?>OnlineRegistration/store" method="post">
				<div class="form-group has-feedback">
					<label for="">Full Name</label>
					<input type="text" class="form-control" placeholder="Enter Name" name="name" required>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="">Phone Number</label>
					<input type="text" class="form-control" placeholder="Enter Phone" name="phone" required>
					<span class="glyphicon glyphicon-phone form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="">Address</label>
					<input type="text" class="form-control" placeholder="Enter Address" name="address" required>
					<span class="glyphicon glyphicon-home form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="">School</label>
					<input type="text" class="form-control" placeholder="Enter School" name="school" required>
					<span class="glyphicon glyphicon-list-alt form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="">Grade</label>
					<input type="text" class="form-control" placeholder="Enter Grade" name="grade" required>
					<span class="glyphicon glyphicon-blackboard form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="">Birthday Date</label>
					<select class="form-control select2" style="width: 100%;" name="date" required>
						<option selected="selected" disabled="disabled" value="">-- Choose Date --</option>
						<?php
						for ($i = 1; $i <= 31; $i++) {
						?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php
						}
						?>
					</select>
					<!-- <span class="glyphicon glyphicon-phone form-control-feedback"></span> -->
				</div>
				<div class="form-group has-feedback">
					<label for="">Birthday Moth</label>
					<select class="form-control select2" style="width: 100%;" name="month" required>
						<option selected="selected" disabled="disabled" value="">-- Choose month --</option>
						<?php
						$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
						foreach ($months as $month) {
						?>
							<option value="<?= $month ?>"><?= $month ?></option>
						<?php
						}
						?>
					</select>
					<!-- <span class="glyphicon glyphicon-phone form-control-feedback"></span> -->
				</div>
				<div class="row">
					<!-- /.col -->
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
					</div>
					<div class="col-xs-4">
						<button type="reset" class="btn btn-secondary btn-block btn-flat">Reset</button>
					</div>
					<!-- /.col -->
				</div>
			</form>


		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 3 -->
	<script src="<?php echo base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(function() {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
		});
	</script>
</body>

</html>
