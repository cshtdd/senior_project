<?php $this->load->view("template_header"); ?>

<h1>Create Account</h1>
<?php
	$attributes = array(
			'id' => 'registration_form'
	); 
	echo form_open('register/register', $attributes)
?>
<p>
	<?php 

	  echo form_label('Email Address:','email_address'); 
	  echo form_input('email_address',set_value('email_address'),'id="email_address"');

	?>
	<?php 

	  echo form_label('Password:','password_1'); 
	  echo form_password('password_1','','id="password_1"');

	?>
	<?php 

	  echo form_label('Password:','password_2'); 
	  echo form_password('password_2','','id="password_2"');

	?>
</p>
<p>
	<?php 

	$data = array(
	'id' 		 => 'btn',
    'class'      => 'btn',
    'name'       => 'accounts',
    );

	 echo form_submit($data,'Create Senior Project Account');
	?>
</p>
<?php echo form_close() ?>

<div class="errors">
<?php echo validation_errors(); ?>
<?php

	if(isset($already_registered))
	{
		 echo "This email address is already registered"; 	
	}
?>
</div>

<script>                                         

	$( document ).ready(function() {

	  	$("#btn").on("click", function(e) {
	  		var 
	  			fields = {
	  				email: $.trim($("#email_address").val()),
	  				password1: $.trim($('#password_1').val()),
	  				password2: $.trim($('#password_2').val())
	  			};
			if( !fields.email.length ){
				e.preventDefault();
				$(".errors").html("Email address required");
			}else if( !fields.password1.length && !fields.password2.length ){
				e.preventDefault();
				$(".errors").html("Password fields required");
			}else if( fields.password1 !== fields.password2){
				e.preventDefault();
				$(".errors").html("Passwords did not match.");
			}
	  	});
	});

 </script> 

 <?php $this->load->view("template_footer"); ?>
