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