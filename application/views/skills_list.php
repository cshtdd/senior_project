<?php 
	if (isset($lSkills) && count($lSkills) > 0)
	{
?>
		<ul>
		<?php
			//print_r($lSkills);
			foreach ($lSkills as $iSkill) 
			{
		?>
				<li> <?php echo $iSkill->name ?> </li>
		<?php	
			}
		?>
		</ul>
<?php
	}
?>