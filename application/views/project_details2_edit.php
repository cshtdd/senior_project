<?php $this->load->view("template_header"); ?>
<?php $this->load->helper("skills"); ?>

edit the current project

<!--
<?php
	print_r($projectDetails); 
	print_r($suggested_users); 
?>
-->


<!--
<form action='/projectcontroller/update'>
	 <input type="text" name="tags" placeholder="Tags" class="tagManager"/>

	 <input type="submit" value="Save Changes"/>
</form>
-->

<?php 
	echo form_open('projectcontroller/update', array(
		//'class' => 'form-register',
		'id' => 'edit_project_form'
	));
?>
	<div class="row-fluid">

		<div class="span9 offset3">
			<div class="pull-right">
				<!--<input type="text" name="tags" placeholder="Tags" class="tagManager"/> -->
				<?php 
					echo form_input(array(
						'id' => 'text-new-tag',
						'name' => 'text-new-tag',
						'type' => 'text',
						'class' => 'tagManager input-small',
						'placeholder' => 'Enter skills...',
					));
				?>
			</div>
		</div>

		<div class="span12">
			<?php 
				echo form_textarea(array(
					'id' => 'text-description',
					'name' => 'text-description',
					//'class' => 'input-large',
					'rows' => '20',
					'placeholder' => 'Enter a description for the project...',
					'value' => $projectDetails->project->description
				));
			?>
		</div>

		<?php 
			echo form_submit(array(
				'id' => 'btn-submit',
				'name' => 'btn-submit',
				'type' => 'Submit',
				'class' => 'btn btn-large btn-primary',
				'value' => 'Save Changes'
			));
		?>

	</div>
<?php
	echo form_close();
?>


<script type="text/javascript">
	$(document).ready(function(){

		jQuery(".tagManager").tagsManager({
			//prefilled: ["Pisa", "Rome"],
			prefilled: [ <?php echo $projectDetails->getCurrentSkillNames() ?> ],
			CapitalizeFirstLetter: true,
			preventSubmitOnEnter: true,
			typeahead: true,
			//typeaheadSource: ["Pisa", "Rome", "Milan", "Florence", "New York", "Paris", "Berlin", "London", "Madrid"],
			typeaheadSource: [ <?php echo all_skill_names($this) ?> ],
			hiddenTagListName: 'hiddenTagList',
			tagClass: 'label pull-left'
		});
	});
</script>

<?php $this->load->view("template_footer"); ?>