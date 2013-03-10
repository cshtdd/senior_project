<ul class="inline">
	<?php 
		if (isset($listTitle) && strlen($listTitle) > 0)
		{
	?>
		<lh><?php echo $listTitle ?></lh>
	<?php
		}
	?>

	<?php 
		if (isset($lUserSummaries) && count($lUserSummaries) > 0)
		{
			foreach ($lUserSummaries as $iUserSummary) 
			{
		?>
				<li> 
					<?php 
						$this->load->view('user_summary_full_name_image', array(
							'user_summary' => $iUserSummary
							) 
						) 
					?> 
				</li>
		<?php	
			}
		} 
		else 
		{
			if (isset($errorMessage) && strlen($errorMessage) > 0)
			{
		?>
			<li><?php echo $errorMessage ?></li>
		<?php
			}
		}
	?>
</ul>
