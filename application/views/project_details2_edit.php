<?php $this->load->view("template_header"); ?>
<?php $this->load->helper("skills"); ?>

<!-- edit the current project -->

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

<h2>Edit Your Project</h2>

<?php 
	echo form_open('projectcontroller/update', array(
		//'class' => 'form-register',
		'id' => 'edit_project_form'
	));
?>
	<div class="row-fluid">
		<div class="span3">

			<?php 
				echo form_input(array(
					'id' => 'text-project-title',
					'name' => 'text-project-title',
					'type' => 'text',
					'class' => 'input-large',
					'placeholder' => 'Enter the project title...',
					'value' => $projectDetails->project->title,
					'required' => '',
					'title' => 'Project Title'
				));
			?>
		</div>

		<div class="span9">
			<div class="pull-right">
				<!--<input type="text" name="tags" placeholder="Tags" class="tagManager"/> -->
				<?php 
					echo form_input(array(
						'id' => 'text-new-tag',
						'name' => 'text-new-tag',
						'type' => 'text',
						'class' => 'tagManager input-small',
						'placeholder' => 'Enter skills...'
					));
				?>
			</div>
		</div>
	</div>


			<?php 
				echo form_textarea(array(
					'id' => 'text-description',
					'name' => 'text-description',
					//'class' => 'input-large',
					'rows' => '20',
					'placeholder' => 'Enter a description for the project...',
					'value' => $projectDetails->project->description,
					'required' => '',
					'Title' => 'Project Description'
				));
			?>

			<?php $this->load->view('subviews/user_summaries_full_list', array(
				'listTitle' => 'Proposed By:',
				'lUserSummaries' => array($projectDetails->proposedBySummary)
			)) ?>


			<?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
				'listTitle' => 'Mentors:',
				'lUserSummaries' => $projectDetails->lMentorSummaries,
				'errorMessage' => 'This team needs a mentor...',
				'prefix' => 'mnt'
			)) ?>


			<?php $this->load->view('subviews/user_summaries_full_list_edit_project', array(
				'listTitle' => 'Team Members:',
				'lUserSummaries' => $projectDetails->lTeamMemberSummaries,
				'errorMessage' => 'This team has no members',
				'prefix' => 'usr'
			)) ?>


			<?php 
				echo form_submit(array(
					'id' => 'btn-submit',
					'name' => 'btn-submit',
					'type' => 'Submit',
					'class' => 'btn btn-large btn-primary',
					'value' => 'Save Changes'
				));
			?>

<?php
	echo form_close();
?>


<script type="text/javascript">
	function buildlUserIds(listId)
	{
		var hiddenFieldId = $('#' + listId).attr('data-idwithlist');

		//alert(listId);
		//alert(hiddenFieldId);

		var lUserIds = [];

		$('#' + listId + ' li').each(function(index){
			lUserIds.push($(this).attr('data-userid'));
		});

		var lUserIdsStr = lUserIds.join();
		//alert(lUserIdsStr);

		$('#' + hiddenFieldId).val(lUserIdsStr);

		var isListEmpty = (lUserIdsStr.length == 0);
		addErrorMessageToEmptyList(listId, isListEmpty);
	}

	function addErrorMessageToEmptyList(listId, isListEmpty)
	{
		if (isListEmpty)
		{
			alert('empty list');
		}
		else
		{
			alert('non empty list');
		}
	}

	$(document).ready(function(){

		$(".tagManager").tagsManager({
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

		$('.myUserRemover').each(function(index){
			$(this).click(function(e){
				e.preventDefault();
				var idToRemove = $(this).attr("data-idtoremove");
				var parentListId = $('#' + idToRemove).parent().attr('id');

				$('#' + idToRemove).remove();
				buildlUserIds(parentListId);
			});
		});
	});
</script>

<?php $this->load->view("template_footer"); ?>