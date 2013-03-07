<ul class="project_list unstyled">
	<?php if (isset($list_title) && strlen($list_title) > 0) { ?>
		<lh><h2><?php echo $list_title ?></h2></lh>
	<?php } ?>

	<?php for ($i = 0; $i < count($lProjects); $i++) { ?>
		<li class="well">

			<div class="pull-right right-text">
				<p>
					<?php echo $lProjects[$i]->getlSkillNames() ?>
				</p>

				<p>
					<?php if ($lProjects[$i]->displayJoin) { ?>
						<button class="btn btn-primary" type="button">Join</button>
					<?php } ?>
					
					<?php if ($lProjects[$i]->displayLeave) { ?>
						<button class="btn btn-warning" type="button">Leave</button>
					<?php } ?>
				</p>
			</div>

			<h4>
				<?php echo anchor('project/'.$lProjects[$i]->project->id, $lProjects[$i]->project->title) ?>
			</h4>

			<p>
				<?php echo $lProjects[$i]->getShortDescription() ?>
				<?php echo anchor('project/'.$lProjects[$i]->project->id, 'More Info...') ?>
			</p>

			<ul class="unstyled inline">
				<lh class="muted">Proposed By:</lh>
				<li>
					<?php $this->load->view('user_summary_full_name', array('user_summary' => $lProjects[$i]->proposedBySummary) )?>
				</li>
			</ul>

			<ul class="unstyled inline">
				<lh class="muted">Mentors:</lh>

				<?php if (isset($lProjects[$i]->lMentorSummaries) && count($lProjects[$i]->lMentorSummaries) > 0) { ?>
					
					<?php foreach ($lProjects[$i]->lMentorSummaries as $iMentorSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMentorSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>This team needs a mentor...</li>

				<?php }?>
			</ul>


			<ul class="unstyled inline">
				<lh class="muted">Team Members:</lh>

				<?php if (isset($lProjects[$i]->lTeamMemberSummaries) && count($lProjects[$i]->lTeamMemberSummaries) > 0) { ?>

					<?php foreach ($lProjects[$i]->lTeamMemberSummaries as $iMemberSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>Join this team for a free beer! Not really...</li>

				<?php }?>
			</ul>

			Delivery Term: <?php echo strtoupper($lProjects[$i]->term->name) ?>

			<BR>Editable: <?php if ($lEditable[$i]) { ?>
							<?php echo 'True' ?>
				      <?php } else { ?>
				      		<?php echo 'False' ?>
				      <?php }?>


			<?php if (isset($lAlikeMentors[$i]) && count($lAlikeMentors[$i]) > 0) { ?>

				<ul class="unstyled inline">
					<lh class="muted">Suggested mentors for your team:</lh>

						<?php for ($j=0; $j < count($lAlikeMentors[$i]); $j++) { ?>
							<?php if (isset($lAlikeMentors[$i][$j]) && count($lAlikeMentors[$i][$j]) > 0) { ?>
									<li>
										<?php $this->load->view('user_summary_full_name', array('user_summary' => $lAlikeMentors[$i][$j]) )?>
									</li>
							<?php } ?>
						<?php } ?>

				</ul>
			<?php }?>

			<?php if (isset($lAlikeStudents[$i]) && count($lAlikeStudents[$i]) > 0) { ?>

				<ul class="unstyled inline">
					<lh class="muted">Suggested students for your team:</lh>

						<?php for ($j=0; $j < count($lAlikeStudents[$i]); $j++) { ?>
							<?php if (isset($lAlikeStudents[$i][$j]) && count($lAlikeStudents[$i][$j]) > 0) { ?>
									<li>
										<?php $this->load->view('user_summary_full_name', array('user_summary' => $lAlikeStudents[$i][$j]) )?>
									</li>
							<?php } ?>
						<?php } ?>
				</ul>
			<?php }?>

		</li>
	<?php } ?>
</ul>