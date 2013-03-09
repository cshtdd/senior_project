<?php $this->load->view("template_header"); ?>

<!-- START displaying server-side validation errors -->
<?php
	$fullErrorText = validation_errors();

	if(isset($already_registered))
	{
		 $fullErrorText = $fullErrorText.'This email address is already registered';
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
	If you don’t already have an account with us
</p>

<div class="indent">
	<?php 
		echo form_open('register/register', array(
			'class' => 'form-register',
			'id' => 'registration_form'
		));
	?>

	<h2>Create Account</h2>

	<?php
		//echo form_input('email_address',set_value('email_address'),'id="email_address"');
		echo form_input(array(
						'id' => 'email_address',
						'name' => 'email_address',
						'type' => 'email',
						'class' => 'input-block-level input-large',
						'placeholder' => 'email@example.com',
						'value' => set_value('email_address'),
						'required' => '',
						'title' => 'Email address'
					));

		//echo form_password('password_1','','id="password_1"');
		echo form_password(array(
						'id' => 'password_1',
						'name' => 'password_1',
						'class' => 'input-block-level input-large',
						'placeholder' => 'Password',
						'required' => '',
						'title' => 'Password'
					));

		//echo form_password('password_2','','id="password_2"');
		echo form_password(array(
						'id' => 'password_2',
						'name' => 'password_2',
						'class' => 'input-block-level input-large',
						'placeholder' => 'Confirm Password',
						'required' => '',
						'title' => 'Password Confirmation'
					));

		/* $data = array(
			'id' => 'btn',
			'class' => 'btn',
			'name' => 'accounts',
			);

		 echo form_submit($data,'Create Senior Project Account');
		*/
		echo form_submit(array(
			'id' => 'btn',
			'name' => 'accounts',
			'type' => 'Submit',
			'class' => 'btn btn-large btn-primary',
			'value' => 'Register'
		));

	echo form_close() ?>

</div>

<p>
	If you already have an account with us
</p>

<div class="indent">
	<h3>
		<?php echo anchor('login', 'log in here...') ?>    
	</h3>
</div>

<!--
	there are thousands of ways of doing this, 
	none of which involve actual programming it
	I'll implement this correctly
-->
<!--
<script type="text/javascript">

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
-->

 <?php $this->load->view("template_footer"); ?>
