<?php $this->load->view("template_header"); ?>

<p>
	please login now
</p>

<?php echo $first_name ?>

<p>First Name: <?php echo $model->first_name ?> </p>
<p>Last Name: <?php echo $model->last_name ?> </p>
<p>Age: <?php echo $model->age ?> </p>
	
<?php $this->load->view("template_footer"); ?>