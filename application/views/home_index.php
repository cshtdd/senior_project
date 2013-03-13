<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>We could not match you with anything</p>

<?php } else { ?>


    <?php if (isset($lSuggestedProjects) && count($lSuggestedProjects) > 0) { ?>
        <?php $this->load->view('subviews/project_summary_list', array('lProjects' => $lSuggestedProjects, 'list_title' => 'Suggested Projects') ) ?>
        <hr>
    <?php } ?>

    <?php if (isset($lRegularProjects) && count($lRegularProjects) > 0) { ?>
        <?php $this->load->view('subviews/project_summary_list', array('lProjects' => $lRegularProjects, 'list_title' => '') )?>
    <?php } ?>

<?php }?>
    
<?php $this->load->view("template_footer"); ?>