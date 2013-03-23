<?php $this->load->view("template_header"); ?>

<!-- START displaying server-side validation errors -->
<?php
    $fullErrorText = validation_errors();

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

<div class="indent">

    <h2>Change your password</h2>

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
        echo form_open('usercontroller/change_password', array(
            'class' => 'form-register',
            'id' => 'form-change-password'
        ));
    ?>

    <?php
        echo form_password(array(
                        'id' => 'current-password',
                        'name' => 'current-password',
                        'class' => 'input-block-level input-large',
                        'placeholder' => 'Current Password',
                        'required' => '',
                        'title' => 'Current Password'
                    ));


        echo form_password(array(
                        'id' => 'password_1',
                        'name' => 'password_1',
                        'class' => 'input-block-level input-large',
                        'placeholder' => 'New Password',
                        'required' => '',
                        'title' => 'New Password'
                    ));

        echo form_password(array(
                        'id' => 'password_2',
                        'name' => 'password_2',
                        'class' => 'input-block-level input-large',
                        'placeholder' => 'Verify Password',
                        'required' => '',
                        'title' => 'Password Confirmation',
                        'oninput' => 'pwd_should_match()'
                    ));

        echo form_submit(array(
            'id' => 'btn-submit',
            'name' => 'btn-submit',
            'type' => 'Submit',
            'class' => 'btn btn-large btn-primary',
            'value' => 'Save Changes'
        ));

        echo form_close() 
    ?>

</div>

 <?php $this->load->view("template_footer"); ?>
