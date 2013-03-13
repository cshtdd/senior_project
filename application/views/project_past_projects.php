<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>There are no past projects</p>

<?php } else { ?>

    <?php if (isset($lProjects) && count($lProjects) > 0) { ?>
        <?php $this->load->view('subviews/project_summary_list', 
            array('lProjects' => $lProjects, 
            'list_title' => 'Past Projects'
            ) 
        )?>
    <?php } ?>

<?php }?>
    
<?php $this->load->view("template_footer"); ?>