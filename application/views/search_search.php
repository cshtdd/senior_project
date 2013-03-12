<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<?php $this->load->view('subviews/project_summary_list', array('lProjects' => $lProjects, 'list_title' => 'Projects Results') )?>

<?php }?>

<?php $this->load->view("template_footer"); ?>