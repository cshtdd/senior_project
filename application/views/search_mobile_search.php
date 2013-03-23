<?php $this->load->view("template_header"); ?>

<?php echo form_open('searchcontroller/search_string', array( 
    'class' => '', 
    'id' => 'search-form-big',
    'method' => 'GET')) ?>
    <div class="input-append">
        <?php

            echo form_input(array(
                'id' => 'text-search-big',
                'name' => 'q',
                'type' => 'text',
                'class' => '',
                'placeholder' => 'search for people, skills, projects and terms...',
                'required' => '',
                'title' => 'search criteria',
                'value' => ''
            ));
            
            echo form_button(array(
                'id' => 'btn-search-big',
                'type' => 'Submit',
                'class' => 'btn',
                'content' => 'Search'
            ));                             

        ?>
    </div>
<?php echo form_close() ?>  

<?php $this->load->view("template_footer"); ?>