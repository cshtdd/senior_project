<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p><?php echo $message?></p>

<?php } else { ?>

	<?php if (isset($lProjects) && count($lProjects) > 0) { ?>
		<?php $this->load->view('project_detail_list', array('lProjects' => $lProjects, 'list_title' => $title,
		                        'lAlikeStudents' => $lAlikeStudents, 'lAlikeMentors' => $lAlikeMentors, 'lEditable' => $lEditable) ) ?>
		<hr>
	<?php } ?>

<?php }?>
	
<?php $this->load->view("template_footer"); ?>