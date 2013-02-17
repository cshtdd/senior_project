<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<div class='project_search_results'>
		<ul>
			<lh>Project Results</lh>
			<?php foreach ($lProjects as $iProject) { ?>
				<li>
					<b><?php echo $iProject->project->title ?></b>
					<!-- <?php echo $iProject->project->description ?> -->
					<?php echo $iProject->getShortDescription() ?>
					<?php echo $iProject->term->end_date ?>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>