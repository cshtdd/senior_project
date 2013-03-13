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

    <script type="text/javascript">
        function pwd_should_match()
        {
            var password_confirm = document.getElementById('password_2');
            if (password_confirm.value != document.getElementById('password_1').value)
            {
                password_confirm.setCustomValidity('Passwords do not match');
            }
            else
            {
                password_confirm.setCustomValidity('');
            }
        }
    </script>

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
                        'title' => 'Password Confirmation',
                        'oninput' => 'pwd_should_match()'
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

        echo form_close() 
    ?>

</div>

<p>
    If you already have an account with us
</p>

<div class="indent">
    <h3>
        <?php echo anchor('login', 'log in here...') ?>    
    </h3>
</div>

 <?php $this->load->view("template_footer"); ?>
