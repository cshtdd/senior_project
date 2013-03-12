<?php $this->load->view("template_header"); ?>

	<h2>User Details</h2>

<?php 
	if ($no_results) 
	{
?>
		<p>No data for the specified user</p>
<?php 
	} 
	else 
	{
?>

		<div class="row-fluid">
			<div class="span4 center-text">
				<?php 
					echo img(array(
						'src' => $userDetails->user->picture,
						'class' => 'user-img-large'
					))
				?>
			</div>

			<div class="span8">
				<h3>
					<?php echo $userDetails->getFullName() ?>
				</h3>

				<?php echo $userDetails->role->name ?>

				<?php 
					if (isset($userDetails->user->graduation_term) &&
						isset($userDetails->user->graduation_term->name) && 
						strlen($userDetails->user->graduation_term->name) > 0) {
				?>
					Graduating In
					<?php echo $userDetails->user->graduation_term->name ?>
				<?php } ?>
			</div>
		</div>


		<div class="spaced-top">
			<?php if (isset($userDetails->lSkills) && count($userDetails->lSkills) > 0) { ?>
				<h4>Skills</h4>
				<?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lSkills) )?>
			<?php }?>
		</div>

		<div class="spaced-top">
			<?php if (isset($userDetails->lLanguages) && count($userDetails->lLanguages) > 0) { ?>
				<h4>Languages</h4>
				<?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lLanguages) )?>
			<?php }?>
		</div>

		<div class="spaced-top">
			<?php if(isset($userDetails->user->summary_spw) && strlen($userDetails->user->summary_spw) > 0) {?>
				<h4>Short Bio</h4>
				<?php echo $userDetails->user->summary_spw ?>
			<?php }?>
		</div>

		<div class="spaced-top">
			<?php if (isset($userDetails->lExperiences) && count($userDetails->lExperiences) > 0) {	?>
				<h4>Experience</h4>
				<ul>
					<?php foreach($userDetails->lExperiences as $iExperience) { ?>
						<li class="well">
							<h5>
								<?php echo $iExperience->title ?>
							</h5>

							<p>
								<?php echo $iExperience->description ?>
							</p>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
<?php 
	}
?>

<?php $this->load->view("template_footer"); ?>