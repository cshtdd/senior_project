<?php 
	if (isset($listTitle) && strlen($listTitle) > 0)
	{
?>
	<h4 class="muted"><?php echo $listTitle ?></h4>
<?php
	}
?>

<ul class="inline" id="<?php echo $prefix.'-container' ?>" 
	data-idwithlist="<?php echo $prefix.'hidden-ids'?>">
	<?php 
		if (isset($lUserSummaries) && count($lUserSummaries) > 0)
		{
			$userIdsArray = array();

			foreach ($lUserSummaries as $user_summary) 
			{
				$userIdsArray[] = $user_summary->user->id;
	?>
				<li id="<?php echo $prefix.'-item-'.$user_summary->user->id ?>"> 

					<a href="#" class="myUserRemover" 
						id="<?php echo $prefix.'-btn-rem-'.$user_summary->user->id ?>" 
						data-idtoremove="<?php echo $prefix.'-item-'.$user_summary->user->id ?>" 
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

			$lUserIdsStr = join(', ', $userIdsArray);
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
<?php 
	if (isset($lUserIdsStr) && strlen($lUserIdsStr) > 0) 
	{
?>
		<input type="hidden" 
			name="<?php echo $prefix.'hidden-ids'?>" 
			id="<?php echo $prefix.'hidden-ids'?>" 
			value="<?php echo $lUserIdsStr ?>"/>
<?php
	}
?>