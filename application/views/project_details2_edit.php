<?php $this->load->view("template_header"); ?>
<?php $this->load->helper("skills"); ?>
<?php $this->load->helper("loading"); ?>
<!-- edit the current project -->

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



<?php 
    if (isset($can_leave_project) && $can_leave_project)
    {
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
                'class' => 'btn btn-warning btn-large pull-right',
                'value' => 'Leave Project'
            ));

        echo form_close();
    }
?>

<?php if (isset($creating_new) && $creating_new) { ?>
    <h2>Create Project</h2>
<?php } else { ?>
    <h2>Edit Your Project</h2>
<?php } ?>

<div>
    <?php 
        echo form_open('projectcontroller/update', array(
            'id' => 'form-edit-project'
        ));
    ?>
        <div class="row-fluid">
            <div class="span3">

                <?php 
                    echo form_input(array(
                        'id' => 'text-project-title',
                        'name' => 'text-project-title',
                        'type' => 'text',
                        'class' => 'input-large',
                        'placeholder' => 'Enter the project title...',
                        'value' => $projectDetails->project->title,
                        'required' => '',
                        'title' => 'Project Title'
                    ));
                ?>
            </div>

            <div class="span9">
                <div class="pull-right">
                    <!--<input type="text" name="tags" placeholder="Tags" class="tagManager"/> -->
                    <?php 
                        echo form_input(array(
                            'id' => 'text-new-tag',
                            'name' => 'text-new-tag',
                            'type' => 'text',
                            'class' => 'tagManager input-small',
                            'placeholder' => 'Enter skills...'
                        ));
                    ?>
                </div>
            </div>
        </div>


        <?php 
            echo form_textarea(array(
                'id' => 'text-description',
                'name' => 'text-description',
                //'class' => 'input-large',
                'rows' => '12',
                'placeholder' => 'Enter a description for the project...',
                'value' => $projectDetails->project->description,
                'required' => '',
                'Title' => 'Project Description'
            ));
        ?>

        <?php 
            echo form_submit(array(
                'id' => 'btn-submit',
                'name' => 'btn-submit',
                'type' => 'Submit',
                'class' => 'btn btn-large btn-primary pull-right',
                'value' => 'Save Changes'
            ));
        ?>

        Delivery Term: <?php echo strtoupper($projectDetails->term->name) ?>

        <?php if (!isset($creating_new)) { ?>
            <div class="row-fluid"> 
                <div class="span2">
                    <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
                        'listTitle' => 'Proposed By:',
                        'lUserSummaries' => array($projectDetails->proposedBySummary),
                        'topView' => '',
                        'bottomView' => '',
                        'prefix' => 'prop'
                    )) ?>
                </div>

                <div class="span8">
                    <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
                        'listTitle' => 'Mentors:',
                        'lUserSummaries' => $projectDetails->lMentorSummaries,
                        'errorMessage' => 'This team needs a mentor...',
                        'topView' => 'subviews/user_remove',
                        'noTopViewForCurrentUser' => true,
                        'bottomView' => '',
                        'prefix' => 'mnt'
                    )) ?>
                </div>
            </div>

            <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
                'listTitle' => 'Team Members:',
                'lUserSummaries' => $projectDetails->lTeamMemberSummaries,
                'errorMessage' => 'This team has no members',
                'topView' => 'subviews/user_remove',
                'noTopViewForCurrentUser' => true,
                'bottomView' => '',
                'prefix' => 'usr'
            )) ?>
        <?php } ?>

        <?php
            echo form_hidden(array(
                'pid' => $projectDetails->project->id,
                'pbUrl' => current_url()
                )
            );
        ?>
    <?php
        echo form_close();
    ?>
</div>

<?php 
    if (isset($suggested_mentors) && count($suggested_mentors) > 0)
    {
?>
        <hr>
<?php
        $this->load->view('subviews/user_summaries_full_list_edit_project', array(
            'listTitle' => 'These mentors would be great for your team',
            'lUserSummaries' => $suggested_mentors,
            'errorMessage' => '',
            'topView' => '',
            'bottomView' => 'subviews/user_invite',
            'noBottomViewForCurrentUser' => true,
            'prefix' => 'sugg'
        ));

    }
?>

<?php 
    if (isset($suggested_students) && count($suggested_students) > 0)
    {
?>
        <hr>
<?php
        $this->load->view('subviews/user_summaries_full_list_edit_project', array(
            'listTitle' => 'These students would be great for your team',
            'lUserSummaries' => $suggested_students,
            'errorMessage' => '',
            'topView' => '',
            'bottomView' => 'subviews/user_invite',
            'noBottomViewForCurrentUser' => true,
            'prefix' => 'sugg'
        ));

    }
?>

<script type="text/javascript">
    function buildlUserIds(listId)
    {
        var hiddenFieldId = $('#' + listId).attr('data-idwithlist');

        //alert(listId);
        //alert(hiddenFieldId);

        var lUserIds = [];

        $('#' + listId + ' li').each(function(index){
            lUserIds.push($(this).attr('data-userid'));
        });

        var lUserIdsStr = lUserIds.join();
        //alert(lUserIdsStr);

        $('#' + hiddenFieldId).val(lUserIdsStr);

        var isListEmpty = (lUserIdsStr.length == 0);
        addErrorMessageToEmptyList(listId, isListEmpty);
    }

    function addErrorMessageToEmptyList(listId, isListEmpty)
    {
        if (isListEmpty)
        {
            //alert('empty list');

            var errorMessageStr = '';

            if (listId.indexOf('mnt') == 0) {
                errorMessageStr = 'This team needs a mentor...';
            }

            if (listId.indexOf('usr') == 0) {
                errorMessageStr = 'This team has no members';
            }

            if (errorMessageStr.length > 0) {
                $('#' + listId).append($('<li>' + errorMessageStr + '</li>'));
            }
        }
    }

    //$(document).ready(function(){

        $(".tagManager").tagsManager({
            //prefilled: ["Pisa", "Rome"],
            prefilled: [ <?php echo $projectDetails->getCurrentSkillNames() ?> ],
            CapitalizeFirstLetter: true,
            preventSubmitOnEnter: true,
            typeahead: true,
            //typeaheadSource: ["Pisa", "Rome", "Milan", "Florence", "New York", "Paris", "Berlin", "London", "Madrid"],
            typeaheadSource: [ <?php echo all_skill_names($this) ?> ],
            hiddenTagListName: 'hidden-skill-list',
            tagClass: 'label pull-left'
        });

        $('.myUserRemover').each(function(index){
            $(this).click(function(e){
                e.preventDefault();
                var idToRemove = $(this).attr("data-idtoremove");
                var parentListId = $('#' + idToRemove).parent().attr('id');

                $('#' + idToRemove).remove();
                buildlUserIds(parentListId);
            });
        });

        $('.myUserInviter').each(function(index){
            $(this).click(function(e){
                var userIdToInvite = $(this).attr('data-idtoinvite');

                $(this).parent().append('<?php echo loading_img() ?>');

                $.ajax({
                    type: 'POST',
                    url: '/usercontroller/invite',
                    data: 'uid='+userIdToInvite
                }).always(function(){
                    $('#loading_img').remove();
                });
            });
        });

        /*
        $('#btn-leave').click(function(e){
            $(this).append('<?php echo loading_img() ?>');

            $.ajax({
                    type: 'POST',
                    url: '/projectcontroller/leave',
                    data: 'pid=<?php echo $projectDetails->project->id ?>&pbUrl=<?php echo current_url() ?>'
                });
        });
        */
    //});
</script>

<?php $this->load->view("template_footer"); ?>