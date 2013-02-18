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
					<br/><?php echo $iProject->getShortDescription() ?>
					<br/><?php echo $iProject->getlSkillNames() ?>
					<br/><?php echo $iProject->term->end_date ?>
					<hr/>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>