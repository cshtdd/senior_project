<?php $this->load->view("template_header"); ?>

<?php
	if ( count($model) > 0)
	{
?>
		<ul>
			<!--
			<li><?= $model[0]->first_name ?></li>
			-->
			
			<?php 
				foreach ( $model as $iperson )
				{
			?>
					<li>
						First Name: <?= $iperson->first_name ?>
						Last Name: <?= $iperson->last_name ?>
						Age: <?= $iperson->age ?>
					</li>
			<?php
				}
			?>
		</ul>
<?php
	}
	else
	{
?>
		<p>no items in the list</p>
<?php	
	}
?>

<?php $this->load->view("template_header"); ?>