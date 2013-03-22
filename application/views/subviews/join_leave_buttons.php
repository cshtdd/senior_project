<?php 
    if ($projectDetails->displayJoin) 
    { 
        //<button class="btn btn-primary" type="button">Join</button>
        echo form_open('projectcontroller/join', array(
            'id' => 'form-join-project-'.$projectDetails->project->id
        ));

        echo form_hidden(array(
            'pid' => $projectDetails->project->id,
            'pbUrl' => current_url()
        ));

        echo form_submit(array(
            'id' => 'btn-join-'.$projectDetails->project->id,
            'name' => 'btn-submit',
            'type' => 'Submit',
            'class' => 'btn btn-primary',
            'value' => 'Join'
        ));

        echo form_close();
    } 
?>

<?php 
    if ($projectDetails->displayLeave) 
    { 
        //<button class="btn btn-warning" type="button">Leave</button>
        echo form_open('projectcontroller/leave', array(
            'id' => 'form-leave-project'
        ));

        echo form_hidden(array(
                'pid' => $projectDetails->project->id,
                'pbUrl' => current_url()
            ));

        //<button id='btn-leave' type="button" class="btn btn-warning btn-large pull-right">Leave Project</button>
        echo form_submit(array(
                'id' => 'btn-leave',
                'name' => 'btn-submit',
                'type' => 'Submit',
                'class' => 'btn btn-warning',
                'value' => 'Leave'
            ));

        echo form_close();
    } 
?>