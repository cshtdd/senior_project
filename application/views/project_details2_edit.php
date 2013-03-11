<?php $this->load->view("template_header"); ?>

edit the current project

<!--
<?php
	print_r($projectDetails); 
	print_r($suggested_users); 
?>
-->

<form>
	 <input type="text" name="tags" placeholder="Tags" class="tagManager"/>
</form>

<script type="text/javascript">
	$(document).ready(function(){

		jQuery(".tagManager").tagsManager({
			prefilled: ["Pisa", "Rome"],
			CapitalizeFirstLetter: true,
			preventSubmitOnEnter: true,
			typeahead: true,
			typeaheadSource: ["Pisa", "Rome", "Milan", "Florence", "New York", "Paris", "Berlin", "London", "Madrid"],
			hiddenTagListName: 'hiddenTagList',
			tagClass: 'label pull-left'
		});

	});
</script>

<?php $this->load->view("template_footer"); ?>