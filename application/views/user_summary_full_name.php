<?php $this->load->helper('url'); ?>

<!-- this didn't work, anchor keeps adding the stupid index.php -->
<!--<b><?php echo anchor('user/'.$user_summary->user->id, $user_summary->getFullName()) ?></b> -->

<a href="<?php echo base_url().'user/'.$user_summary->user->id ?>">
	<?php echo $user_summary->getFullName() ?>
</a>
