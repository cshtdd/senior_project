<?php $this->load->view("template_header"); ?>

Do you already have an account on one of these sites? 

<!-- <img src="<?php echo base_url("img/LogInGoogle.png")?>" /> -->
<img src="https://ssl.gstatic.com/images/logos/google_logo_41.png" />
<img src="https://www.google.com/a/cpanel/fiu.edu/images/logo.gif?service=mail" />

<h2>Senior Project Log In</h2>

<?php echo form_open('admin')?>
	<?php 
	  echo form_label('Email Address:','email_address'); 
	  echo form_input('email_address',set_value('email_address'),'id="email_address"');
	
	  echo form_label('Password:','password'); 
	  echo form_password('password','','id="password"');
	
	  echo form_submit('accounts','Log In');
	?>
<?php echo form_close() ?>


<div id="affiliate-registration-group">
	If you don’t already have an account on any of the above

	<?php echo anchor('register/', 'click here to sign up', 'style="color: #0077CC; cursor:pointer; text-decoration:none"'); ?>    
</div>

<div class="text-error">
	<?php echo validation_errors(); ?>
	<?php

		if(isset($credentials_error))
		{
			 echo $credentials_error; 
		}

	?>
</div>

<?php $this->load->view("template_footer"); ?>