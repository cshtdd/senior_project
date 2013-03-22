<?php $this->load->view("template_header"); ?>
<!-- <?php $this->load->helper("loading"); ?> -->

<?php 
    if ($no_results) 
    {
?>
        <p>No data for the specified user</p>
<?php 
    } 
    else 
    {
?>
        <div class="pull-right">
            <?php $this->load->view('subviews/user_invite', array(
                'user_summary' => $userDetails,
                'btnClass' => 'btn-primary btn-large'
            )) ?>
        </div>

        <h2>User Details</h2>

        <div class="row-fluid">
            <div class="span4 center-text">
                <?php 
                    echo img(array(
                        'src' => $userDetails->user->picture,
                        'class' => 'user-img-large'
                    ))
                ?>
            </div>

            <div class="span8">
                <h3>
                    <?php echo $userDetails->getFullName() ?>
                </h3>

                <?php 
                    if (isUserLoggedIn($this))
                    {
                        if (isset($userDetails->user->email) && 
                            strlen($userDetails->user->email) > 0)
                        {
                ?> 
                            <p>
                                <?php echo mailto($userDetails->user->email, $userDetails->user->email) ?>
                            </p>
                <?php
                        }
                    }
                ?>

                <p>
                    <?php echo $userDetails->role->name ?>

                    <?php 
                        if (isset($userDetails->user->graduation_term) &&
                            isset($userDetails->user->graduation_term->name) && 
                            strlen($userDetails->user->graduation_term->name) > 0) {
                    ?>
                        Graduating In
                        <?php echo $userDetails->user->graduation_term->name ?>
                    <?php } ?>
                </p>
            </div>
        </div>


        <div class="spaced-top">
            <?php if (isset($userDetails->lSkills) && count($userDetails->lSkills) > 0) { ?>
                <h4>Skills</h4>
                <?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lSkills) )?>
            <?php }?>
        </div>

        <div class="spaced-top">
            <?php if (isset($userDetails->lLanguages) && count($userDetails->lLanguages) > 0) { ?>
                <h4>Languages</h4>
                <?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lLanguages) )?>
            <?php }?>
        </div>

        <div class="spaced-top">
            <?php if(isset($userDetails->user->summary_spw) && strlen($userDetails->user->summary_spw) > 0) {?>
                <h4>Short Bio</h4>
                <?php echo $userDetails->user->summary_spw ?>
            <?php }?>
        </div>

        <div class="spaced-top">
            <?php if(isset($userDetails->user->summary_linkedIn) && strlen($userDetails->user->summary_linkedIn) > 0) {?>
                <h4>Linked In Summary</h4>
                <?php echo $userDetails->user->summary_linkedIn ?>
            <?php }?>
        </div>

        <div class="spaced-top">
            <?php $this->load->view('subviews/experience_list', array('lExperiences' => $userDetails->lExperiences)) ?> 
        </div>
<?php 
    }
?>

<?php $this->load->view("subviews/invite_capable") ?>

<?php $this->load->view("template_footer"); ?>