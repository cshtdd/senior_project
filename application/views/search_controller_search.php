<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<div class='project_search_results'>
		<ul>
			<lh>Project Results</lh>
			<?php foreach ($lProjects as $iProject) { ?>
				<li>
					<b><?php echo $iProject->title ?></b>
					<?php echo $iProject->description ?>
					<?php echo $iProject->project_close_date ?>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>