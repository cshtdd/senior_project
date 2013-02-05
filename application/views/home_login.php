<?php $this->load->view("template_header"); ?>

<p>
	please login now
</p>

<?= $first_name ?>

<p>First Name: <?= $model->first_name ?> </p>
<p>Last Name: <?= $model->last_name ?> </p>
<p>Age: <?= $model->age ?> </p>
	
<?php $this->load->view("template_footer"); ?>