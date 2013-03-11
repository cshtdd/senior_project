<?php 
	if (isset($listTitle) && strlen($listTitle) > 0)
	{
?>
	<h4 class="muted"><?php echo $listTitle ?></h4>
<?php
	}
?>

<ul class="inline" id="<?php echo $prefix.'-container' ?>">
	<?php 
		if (isset($lUserSummaries) && count($lUserSummaries) > 0)
		{
			foreach ($lUserSummaries as $user_summary) 
			{
		?>
				<li id="<?php echo $prefix.'-item-'.$user_summary->user->id ?>"> 

					<a href="#" class="myUserRemover" 
						id="<?php echo $prefix.'-btn-rem-'.$user_summary->user->id ?>" 
						tagidtoremove="<?php echo $prefix.'-item-'.$user_summary->user->id ?>" 
						title="Remove">x</a>

					<div class='user-summary-full center-text'>
						<?php 
							$imgUrl = 'http://www.gravatar.com/avatar/';
							if (isset($user_summary->user->picture) &&
								strlen($user_summary->user->picture) > 0)
							{
								$imgUrl = $user_summary->user->picture;
							}

							echo img(array(
								'src' => $imgUrl
							));
						?>
						<?php echo anchor('user/'.$user_summary->user->id, $user_summary->getFullName()) ?>
					</div>
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
