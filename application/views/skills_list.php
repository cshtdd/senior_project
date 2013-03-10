<?php 
	if (isset($lSkills) && count($lSkills) > 0)
	{
?>
		<ul class="inline">
		<?php
			//print_r($lSkills);
			foreach ($lSkills as $iSkill) 
			{
		?>
				<li class="label label-info skill"><?php echo $iSkill->name ?></li>
		<?php	
			}
		?>
		</ul>
<?php
	}
?>