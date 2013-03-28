<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>No matches found</p>

<?php } else { ?>
    <?php if (isset($lProjects) && count($lProjects) > 0) { ?>
        <?php $this->load->view('subviews/project_summary_list', array('lProjects' => $lProjects, 'list_title' => 'Projects Results') )?>
    <?php } ?>
    
    <?php if (isset($lMentors) && count($lMentors) > 0) { ?>
        <hr/>
        <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
            'listTitle' => 'Mentor results',
            'lUserSummaries' => $lMentors,
            'errorMessage' => '',
            'noTopViewForCurrentUser' => true,
            'bottomView' => 'subviews/user_invite',
            'noBottomViewForCurrentUser' => true,
            'prefix' => 'usr'
        )) ?>
    <?php } ?>

    <?php if (isset($lStudents) && count($lStudents) > 0) { ?>
        <hr/>
        <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
            'listTitle' => 'Student results',
            'lUserSummaries' => $lStudents,
            'errorMessage' => '',
            'noTopViewForCurrentUser' => true,
            'bottomView' => 'subviews/user_invite',
            'noBottomViewForCurrentUser' => true,
            'prefix' => 'usr'
        )) ?>
    <?php } ?>
<?php }?>

<?php $this->load->view("subviews/invite_capable"); ?>

<?php $this->load->view("template_footer"); ?>