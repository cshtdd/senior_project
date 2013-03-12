<?php $this->load->view("template_header"); ?>

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

		<?php echo img(array('src' => $userDetails->user->picture)) ?>

		<?php echo $userDetails->getFullName() ?>

		<?php echo $userDetails->role->name ?>

		<?php echo $userDetails->term->description ?>

		<?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lSkills) )?>

		<?php echo $userDetails->user->summary_spw ?>

		<?php $this->load->view('subviews/skills_list', array('lSkills' => $userDetails->lLanguages) )?>



		<?php if (isset($userDetails->lExperiences) && count($userDetails->lExperiences) > 0) {	?>
			<ul>
				<lh>Experiences</lh>
				<?php foreach($userDetails->lExperiences as $iExperience) { ?>
					<li>
						<p>
							<?php echo $iExperience->title ?>
						</p>

						<p>
							<?php echo $iExperience->description ?>
						</p>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

<?php 
	}
?>

<?php $this->load->view("template_footer"); ?>