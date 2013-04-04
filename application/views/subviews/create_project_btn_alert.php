<?php $this->load->helper('new_project') ?>
<?php if (shouldPresentWarningOnCreateProject($this)) { ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#btn-create-new-project').click(function(e){
            e.preventDefault();
            e.stopPropagation();

            alertify.confirm("You already have a project. Do you want to leave your current project?", function (e) {
                if (e) {
                    window.location = '<?php echo base_url()?>'+'project/create';
                }
            });

        });
    });
</script>

<?php } ?>