<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<div class='project_search_results'>
		<ul class="project_list">
			<lh><h2>Project Results</h2></lh>
			<?php foreach ($lProjects as $iProject) { ?>
				<li>
					<div class="project_summary">
						<div class="right"> <?php echo $iProject->getlSkillNames() ?> </div>
						<h4>
							<a href="<?php echo base_url().'project/'.$iProject->project->id ?>">
								<?php echo $iProject->project->title ?>
							</a>
						</h4>

						<p>
							<?php echo $iProject->getShortDescription() ?>
							<a href="<?php echo base_url().'project/'.$iProject->project->id ?>">More Info...</a>
						</p>

						<ul class="unstyled inline">
							<lh>Team Leader:</lh>
							<li>
								<?php $this->load->view('user_summary_full_name', array('user_summary' => $iProject->teamLeaderSummary) )?>
							</li>
						</ul>

						<ul class="unstyled inline">
							<lh>Team Mentors:</lh>

							<?php if (isset($iProject->lMentorSummaries) && count($iProject->lMentorSummaries) > 0) { ?>
								
								<?php foreach ($iProject->lMentorSummaries as $iMentorSumm) { ?>
									<li>
										<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMentorSumm) )?>
									</li>
								<?php } ?>

							<?php } else { ?>

								<li>This team needs a mentor...</li>

							<?php }?>
						</ul>


						<ul class="unstyled inline">
							<lh>Team Members:</lh>

							<?php if (isset($iProject->lTeamMemberSummaries) && count($iProject->lTeamMemberSummaries) > 0) { ?>

								<?php foreach ($iProject->lTeamMemberSummaries as $iMemberSumm) { ?>
									<li>
										<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
									</li>
								<?php } ?>

							<?php } else { ?>

								<li>Join this team for a free beer! Not really...</li>

							<?php }?>
						</ul>

						Close Date: <?php echo $iProject->term->end_date ?>
						Project Status: <?php echo $iProject->project->status->name ?>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>