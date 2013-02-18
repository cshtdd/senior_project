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
					<br/>Close Date: <?php echo $iProject->term->end_date ?>
					<br/>Team Leader: <a href="#"><?php echo $iProject->teamLeaderSummary->getFullName() ?></a>

					<ul>
						<lh><b>Team Mentors:</b></lh>

						<?php if (isset($iProject->lMentorSummaries) && count($iProject->lMentorSummaries) > 0) { ?>
							
							<?php foreach ($iProject->lMentorSummaries as $iMentorSumm) { ?>
								<li><a href="#">
									<?php echo $iMentorSumm->getFullName() ?>
								</a></li>
							<?php } ?>

						<?php } else { ?>

							<li>This team needs a mentor...</li>

						<?php }?>
					</ul>



					<ul>
						<lh><b>Team Members:</b></lh>

						<?php if (isset($iProject->lTeamMemberSummaries) && count($iProject->lTeamMemberSummaries) > 0) { ?>

							<?php foreach ($iProject->lTeamMemberSummaries as $iMemberSumm) { ?>
								<li><a href="#">
									<?php echo $iMemberSumm->getFullName() ?>
								</a></li>
							<?php } ?>

						<?php } else { ?>

							<li>Join this team for a bree beer! Not really...</li>

						<?php }?>
					</ul>

					<hr/>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>