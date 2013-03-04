<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		<?php 
			if (isset($title) && strlen($title) > 0)
			{
				echo $title.' - Senior Project';
			}
			else
			{
				echo 'Senior Project';
			}
		?>
	</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	

	<!-- Bootstrap -->

	<link href="<?php echo base_url() ?>css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url() ?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">

	<link href="<?php echo base_url() ?>css/style.css" rel="stylesheet" media="screen">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>
<div>
	<div>
		 Do you already have an account on one of these sites? 
	</div>

	<div style="float:left; height: 250px; margin: 15px 50px 0px 0px">
		<a href="<?php echo base_url('/login/oauth2')?>">
			<img src="<?php echo base_url("img/LogInGoogle.png")?>" />
		</a>
	</div>

	<div style="height: 250px">
		<div>
			<h2>Senior Project Log In</h2>
			
			<?php echo form_open('admin')?>
			<p>
				<?php 
				  echo form_label('Email Address:','email_address'); 
				  echo form_input('email_address',set_value('email_address'),'id="email_address"');
				?>

				<?php 
				  echo form_label('Password:','password'); 
				  echo form_password('password','','id="password"');
				?>
			</p>
			<p>
				<?php 

				$data = array(
				'id' 		 => 'btn',
			    'class'      => 'btn',
			    'name'       => 'accounts',
			    );

				 echo form_submit($data,'Log In');
				?>
			</p>
			<?php echo form_close() ?>
		</div>
	</div>
</div>
<div id="affiliate-registration-group" style="clear:both">
	<p>If you don’t already have an account on any of the above</p>
	<b style="font-size: 130%">
		<?php echo anchor('register/', 'click here to sign up', 'style="color: #0077CC; cursor:pointer; text-decoration:none"'); ?>
    </b>
</div>
<div class="error">
	<?php echo validation_errors(); ?>
	<?php

		if(isset($credentials_error))
		{
			 echo $credentials_error; 
		}

	?>
</div>


<script>                                         

	$( document ).ready(function() {

	  	$("#btn").on("click", function(e) {
	  		var 
	  			fields = {
	  				email: $.trim($("#email_address").val()),
	  				password1: $.trim($('#password').val()),
	  			};
			if( !fields.email.length ){
				e.preventDefault();
				$(".errors").html("Email address required");
			}else if( !fields.password.length){
				e.preventDefault();
				$(".errors").html("Password fields required");
			}
	  	});
	});

 </script> 

<?php $this->load->view("template_footer"); ?>