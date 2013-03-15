<?php 
    echo form_open($form_action);

        echo form_hidden(array(
            'pbUrl' => current_url()
        ));

        echo form_submit(array(
                'name' => 'btn-submit',
                'type' => 'Submit',
                'class' => '',
                'value' => $btn_text
            ));

    echo form_close();
?>