<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<p>yes data</p>

<?php }?>

<?php $this->load->view("template_footer"); ?>