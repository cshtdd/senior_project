<?php $this->load->helper('loading'); ?>
<?php $this->load->helper('invitation'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.myUserInviter').each(function(index){
            $(this).click(function(e){
                var userIdToInvite = $(this).attr('data-idtoinvite');

                $(this).parent().append('<?php echo loading_img() ?>');

                <?php
                    if (!isset($projectDetails) && 
                        !isset($projectDetails->project) &&
                        currentUserHasMultipleProjects($this)) //we need to redirect to the invite page
                    {
                ?>
                        window.location = '<?php echo base_url()?>'+'invite/'+userIdToInvite;
                <?php 
                    }
                    else 
                    {
                ?>
                        <?php if (isset($projectDetails) && isset($projectDetails->project)) { ?>
                            var projectIdToInvite = '<?php echo $projectDetails->project->id ?>';
                        <?php } else { ?>
                            var projectIdToInvite = '<?php echo getAnyProjectIdForCurrentUser($this) ?>';
                        <?php } ?>

                        $.ajax({
                            type: 'POST',
                            url: '/usercontroller/invite',
                            data: 'uid='+userIdToInvite+'&pid='+projectIdToInvite
                        }).always(function(){
                            $('#loading_img').remove();
                            //location.reload();
                        });
                <?php
                    }                    
                ?>

            });
        });
    });
</script>