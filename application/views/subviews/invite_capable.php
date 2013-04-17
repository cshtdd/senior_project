<?php $this->load->helper('loading'); ?>
<?php $this->load->helper('invitation'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.myUserInviter').each(function(index){
            $(this).click(function(e){
                $(this).parent().append('<?php echo loading_img() ?>');


                var userIdToInvite = $(this).attr('data-idtoinvite');
                var dataProjectIdToInvite = $(this).attr('data-projectidtoinvite');
                var currentUserHasMultipleProjects = false;

                <?php if (currentUserHasMultipleProjects($this)) { ?>
                    currentUserHasMultipleProjects = true;
                <?php } ?>

                var useExistingPageId = false;
                <?php if (isset($projectDetails) && isset($projectDetails->project)) { ?>
                    var projectIdToInviteTemp = '<?php echo $projectDetails->project->id ?>';
                    useExistingPageId = true;
                <?php } else { ?>
                    var projectIdToInviteTemp = '<?php echo getAnyProjectIdForCurrentUser($this) ?>';
                <?php } ?>

                var showListOfProjects = true;
                var projectIdToInvite = 0;

                if (dataProjectIdToInvite && dataProjectIdToInvite.length > 0) //we have a projectId in the button data
                {
                    projectIdToInvite = dataProjectIdToInvite;
                    showListOfProjects = false;
                }

                //we have a project Id field explicitly specified to the page
                //or we only have one project
                if ((showListOfProjects && useExistingPageId) ||
                    (showListOfProjects && !currentUserHasMultipleProjects)) 
                {
                    projectIdToInvite = projectIdToInviteTemp;
                    showListOfProjects = false;
                }


                if (showListOfProjects) //we need to redirect to the invite page
                {   
                    window.location = '<?php echo base_url()?>'+'invite/'+userIdToInvite;
                }
                else 
                {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url()?>' + 'usercontroller/invite',
                        data: 'uid='+userIdToInvite+'&pid='+projectIdToInvite
                    }).always(function(){
                        $('#alert-js-text').text('Your invitation was sent');
                        $('#alert-js').show();
                        $('#loading_img').remove();
                        //location.reload();
                    });           
                }               

            });
        });
    });
</script>