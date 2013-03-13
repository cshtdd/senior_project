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

		<?php echo form_open('usercontroller/update', array(
			'id' => 'form-update-user'
		)); ?>

			<div>

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

						<?php echo anchor('/user/linkedIn_initiate', 'Sync with LinkedIn', array('class' => 'btn btn-primary btn-large pull-right'))  ?>

						<?php 
							echo form_input(array(
								'id' => 'text-first-name',
								'name' => 'text-first-name',
								'type' => 'text',
								'class' => 'input-small',
								'placeholder' => 'First Name...',
								'value' => $userDetails->user->first_name,
								'required' => '',
								'title' => 'First Name'
							));
						?>

						<?php 
							echo form_input(array(
								'id' => 'text-last-name',
								'name' => 'text-last-name',
								'type' => 'text',
								'class' => 'input-large',
								'placeholder' => 'Last Name...',
								'value' => $userDetails->user->last_name,
								'required' => '',
								'title' => 'Last Name'
							));
						?>


							<?php 
								foreach ($userDetails->lRoles as $iRole) 
								{
							?>
								<div class="row-fluid role-term">
									<div class="span4">
										<label class="radio">
										<?php
											echo form_radio(array(
												'id' => 'radio-role-'.$iRole->id,
												'name' => 'radio-role',
												'value' => $iRole->id,
												'checked' => $iRole->id == $userDetails->role->id
											));
										?>
										<?php
											$roleNameStr = $iRole->name;
											if (strtolower($iRole->name) == 'student') $roleNameStr = $roleNameStr.' graduating in ';
											echo $roleNameStr;
										?>
										</label>
									</div>

									<div class="span4">
										<?php 
											if (strtolower($iRole->name) == 'student') 
											{ 
												$arrTermsOptions = array();

												foreach ($userDetails->lTerms as $iTerm) 
												{
													//echo $iTerm->id.' '.$iTerm->name;
													$arrTermsOptions[$iTerm->id] = $iTerm->name;
												}

												echo form_dropdown('dropdown-term', $arrTermsOptions, $userDetails->user->graduation_term->id);
										 	} 
										?>
									</div>
								</div>
							<?php
								}
							?>


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

						<h4>Short Bio</h4>

						<?php 
							echo form_textarea(array(
								'id' => 'text-description',
								'name' => 'text-description',
								//'class' => 'input-large',
								'rows' => '12',
								'placeholder' => 'Enter a description for the project...',
								'value' => $userDetails->user->summary_spw,
								'required' => '',
								'Title' => 'Project Description'
							));
						?>

				</div>

				<?php 
					echo form_submit(array(
						'id' => 'btn-submit',
						'name' => 'btn-submit',
						'type' => 'Submit',
						'class' => 'btn btn-large btn-primary pull-right',
						'value' => 'Save Changes'
					));
				?>

				<div class="spaced-top">
					<?php if(isset($userDetails->user->summary_linkedIn) && strlen($userDetails->user->summary_linkedIn) > 0) {?>
						<h4>Linked In Summary</h4>
						<?php echo $userDetails->user->summary_linkedIn ?>
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

			</div>

		<?php echo form_close() ?>
<?php 
	}
?>

<?php $this->load->view("template_footer"); ?>