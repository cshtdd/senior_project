<?php $this->load->helper('loading'); ?>

<script type="text/javascript">
    $(document).ready(function(){
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
    });
</script>