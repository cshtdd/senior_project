<?php $this->load->view("template_header"); ?>

<h1>Log In</h1>
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
	  echo form_submit('accounts','Log In');
	?>
</p>
<?php echo form_close() ?>

<div class="errors">
<?php echo validation_errors(); ?>
<?php

	if(isset($credentials_error))
	{
		 echo $credentials_error; 
	}

?>
</div>
<?php $this->load->view("template_footer"); ?>