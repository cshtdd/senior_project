<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>No data for the specified project</p>

<?php } else { ?>

<ul class="project_list unstyled">

		<lh><h2><?php echo anchor('project/'.$projectDetails->project->id, $projectDetails->project->title) ?></h2></lh>

		<li class="well">

			<div class="pull-right right-text">
				<p>
					<!-- TODO change this to a horizontal list -->
					<?php echo $projectDetails->getlSkillNames() ?>
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
					<?php $this->load->view('user_summary_full_name', array('user_summary' => $projectDetails->proposedBySummary) )?>
				</li>
			</ul>

			<ul class="unstyled inline">
				<lh class="muted">Mentors:</lh>

				<?php if (isset($projectDetails->lMentorSummaries) && count($projectDetails->lMentorSummaries) > 0) { ?>
					
					<?php foreach ($projectDetails->lMentorSummaries as $iMentorSumm) { ?>
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

				<?php if (isset($projectDetails->lTeamMemberSummaries) && count($projectDetails->lTeamMemberSummaries) > 0) { ?>

					<?php foreach ($projectDetails->lTeamMemberSummaries as $iMemberSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>Join this team for a free beer! Not really...</li>

				<?php }?>
			</ul>

			Delivery Term: <?php echo strtoupper($projectDetails->term->name) ?>
			<!--Project Status: <?php echo $projectDetails->project->status->name ?> -->
		</li>
</ul>

<?php }?>

<?php $this->load->view("template_footer"); ?>