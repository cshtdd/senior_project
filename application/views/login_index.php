<?php $this->load->view("template_header"); ?>

Do you already have an account on one of these sites? 

<img src="https://ssl.gstatic.com/images/logos/google_logo_41.png" />
<img src="https://www.google.com/a/cpanel/fiu.edu/images/logo.gif?service=mail" />




	<?php echo form_open('admin', array('class' => 'form-signin')) ?>

	<h3>Senior Project Log In</h3>

	<?php 

		//echo form_input('email_address',set_value('email_address'),'id="email_address"');
		echo form_input(array(
						'id' => 'email_address',
						'name' => 'email_address',
						'type' => 'text',
						'class' => 'input-block-level input-large',
						'placeholder' => 'Email address'
					));

		//<input type="password" class="input-block-level" placeholder="Password">
		//echo form_password('password','','id="password"');
		echo form_password(array(
						'id' => 'password',
						'name' => 'password',
						'class' => 'input-block-level input-large',
						'placeholder' => 'Password'
					));

		//<button class="btn btn-large btn-primary" type="submit">Sign in</button>
		//echo form_submit('accounts','Log In');
		echo form_submit(array(
			'id' => 'accounts',
			'name' => 'accounts',
			'type' => 'Submit',
			'class' => 'btn btn-large btn-primary',
			'value' => 'Log In'
		));
	?>
	<?php echo form_close() ?>

<div class="text-error">
	<?php echo validation_errors(); ?>
	<?php

		if(isset($credentials_error))
		{
			 echo $credentials_error; 
		}

	?>
</div>


<div id="affiliate-registration-group">
	If you don’t already have an account on any of the above

	<?php echo anchor('register/', 'click here to sign up', 'style="color: #0077CC; cursor:pointer; text-decoration:none"'); ?>    
</div>


<?php $this->load->view("template_footer"); ?>