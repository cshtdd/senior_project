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

<?php $this->load->view("template_footer"); ?>

 <script>                                         

	$( document ).ready(function() {

	  $("#btn").click(function() {
  		mismatch();
	  });

	});
	
   	function mismatch(){
   		if( $.trim($("#email_address").val())  == ""){
   			$("#registration_form").on("submit", function(e){return false;});
   			$(".errors").html("Email address required");
   		}else if( $.trim($('#password_1').val())  == "" &&  $.trim($('#password_2').val()) == ""){
   			$("#registration_form").on("submit", function(e){return false;});
   			$(".errors").html("Password fields required");
   		}else if( $('#password_1').val() != $('#password_2').val()){
   			$("#registration_form").on("submit", function(e){return false;});
   			$(".errors").html("Passwords did not match.");
   		}else{
   			return;
   		}
   	}

 </script> 