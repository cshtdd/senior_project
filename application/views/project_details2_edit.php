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
			//typeaheadAjaxSource: null,
			typeaheadSource: ["Pisa", "Rome", "Milan", "Florence", "New York", "Paris", "Berlin", "London", "Madrid"]
			//delimeters: [44, 188, 13],
			//backspace: [8],
			//blinkBGColor_1: '#FFFF9C',
			//blinkBGColor_2: '#CDE69C',
			//hiddenTagListName: 'hiddenTagList'
		});

	});
</script>

<?php $this->load->view("template_footer"); ?>