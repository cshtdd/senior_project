<?php 
    echo form_open($form_action, array(
            'class' => 'inline'
        ));

        echo form_hidden(array(
            'pbUrl' => current_url()
        ));

        echo form_submit(array(
                'name' => 'btn-submit',
                'type' => 'Submit',
                'class' => isset($btn_class) && strlen($btn_class) > 0 ? $btn_class : '',
                'value' => $btn_text
            ));

    echo form_close();
?>