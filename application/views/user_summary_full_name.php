<?php $this->load->helper('url'); ?>
<b><?php echo anchor('user/'.$user_summary->user->id, $user_summary->getFullName()) ?></b>