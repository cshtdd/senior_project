<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>No data for the specified project</p>

<?php } else { ?>


		<h2><?php echo $projectDetails->project->title ?></h2>


			<div class="pull-right right-text">
				<p>
					<?php $this->load->view('skills_list', array('lSkills' => $projectDetails->lSkills) )?>
				</p>

				<p>
					<?php if ($projectDetails->displayJoin) { ?>
						<button class="btn btn-primary" type="button">Join</button>
					<?php } ?>
					
					<?php if ($projectDetails->displayLeave) { ?>
						<button class="btn btn-warning" type="button">Leave</button>
					<?php } ?>
				</p>
			</div>

			<p>
				<?php echo $projectDetails->project->description ?>
			</p>

			<ul class="unstyled inline">
				<lh class="muted">Proposed By:</lh>
				<li>
					<?php $this->load->view('user_summary_full_name_image', array('user_summary' => $projectDetails->proposedBySummary) )?>
				</li>
			</ul>

			<ul class="unstyled inline">
				<lh class="muted">Mentors:</lh>

				<?php if (isset($projectDetails->lMentorSummaries) && count($projectDetails->lMentorSummaries) > 0) { ?>
					
					<?php foreach ($projectDetails->lMentorSummaries as $iMentorSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name_image', array('user_summary' => $iMentorSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>This team needs a mentor...</li>

				<?php }?>
			</ul>


			<ul class="unstyled inline">
				<lh class="muted">Team Members:</lh>

				<?php if (isset($projectDetails->lTeamMemberSummaries) && count($projectDetails->lTeamMemberSummaries) > 0) { ?>

					<?php foreach ($projectDetails->lTeamMemberSummaries as $iMemberSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name_image', array('user_summary' => $iMemberSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>Join this team for a free beer! Not really...</li>

				<?php }?>
			</ul>

			Delivery Term: <?php echo strtoupper($projectDetails->term->name) ?>
			<!--Project Status: <?php echo $projectDetails->project->status->name ?> -->
		

<?php }?>

<?php $this->load->view("template_footer"); ?>