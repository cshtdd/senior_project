<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>
		<?php 
			if (isset($message) && strlen($message)) 
			{ 
				echo $message;
			} else { 
		?>
				You have not joined a project yet...
		<?php 
			} 
		?>
	</p>

<?php } else { ?>

	<?php if (isset($lProjects) && count($lProjects) > 0) { ?>
		<?php $this->load->view('project_summary_list', 
			array('lProjects' => $lProjects, 
			'list_title' => $title
			) 
		)?>
	<?php } ?>

<?php }?>

<?php $this->load->view("template_footer"); ?>