<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>no data to display</p>

<?php } else { ?>

    <?php $this->load->view('subviews/project_summary_list', array('lProjects' => $lProjects, 'list_title' => 'Projects Results') )?>

    <?php if (isset($lUsers) && count($lUsers) > 0) { ?>
        <hr/>
        <?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
            'listTitle' => 'People results',
            'lUserSummaries' => $lUsers,
            'errorMessage' => '',
            'noTopViewForCurrentUser' => true,
            'noBottomViewForCurrentUser' => true,
            'prefix' => 'usr'
        )) ?>
    <?php } ?>
<?php }?>

<?php $this->load->view("template_footer"); ?>