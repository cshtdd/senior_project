<?php $this->load->view("template_header"); ?>
<h2>Log In</h2>

<!-- START displaying server-side validation errors -->
<?php 
	$fullErrorText = validation_errors();
	if(isset($credentials_error))
	{
		$fullErrorText = $fullErrorText.$credentials_error; 
	}

	if (strlen($fullErrorText) > 0)
	{ 
?>
		<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
			<div class="errors"> 
<?php 
		echo $fullErrorText;
?>
			</div>
		</div>
<?php
	}
?>
<!-- END displaying server-side validation errors -->

<p>
	Do you already have an account on one of these sites? 
</p>

<div class="indent">
	<div class="row-fluid">

		<div class="span2 well">
			<a href="<?php echo base_url('/login/google_oauth2')?>">
				<img src="https://ssl.gstatic.com/images/logos/google_logo_41.png" />
			</a>
		</div>

		<div class="span2 well">
			<a href="<?php echo base_url('/login/google_oauth2')?>">
			<img src="https://www.google.com/a/cpanel/fiu.edu/images/logo.gif?service=mail" />
			</a>
		</div>

		<div class="span6">
			<?php echo form_open('admin', array('class' => 'form-signin')) ?>

			<h3>Senior Project Log In</h3>

			<?php 

				//echo form_input('email_address',set_value('email_address'),'id="email_address"');
				echo form_input(array(
								'id' => 'email_address',
								'name' => 'email_address',
								'type' => 'email',
								'class' => 'input-block-level input-large',
								'placeholder' => 'Email address',
								'required' => '',
								'title' => 'Email address'
							));

				//<input type="password" class="input-block-level" placeholder="Password">
				//echo form_password('password','','id="password"');
				echo form_password(array(
								'id' => 'password',
								'name' => 'password',
								'class' => 'input-block-level input-large',
								'placeholder' => 'Password',
								'required' => '',
								'title' => 'Password'
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
		</div>

	</div>

</div>


<p>
	If you don’t already have an account on any of the above
</p>

<div class="indent">
	<h3>
		<?php echo anchor('register/', 'click here to sign up') ?>    
	</h3>
</div>


<?php $this->load->view("template_footer"); ?>
