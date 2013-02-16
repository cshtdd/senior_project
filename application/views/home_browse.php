<?php $this->load->view("template_header"); ?>

<?php
	if ( count($model) > 0)
	{
?>
		<ul>
			<!--
			<li><?php echo $model[0]->first_name ?></li>
			-->
			
			<?php 
				foreach ( $model as $iperson )
				{
			?>
					<li>
						First Name: <?php echo $iperson->first_name ?>
						Last Name: <?php echo $iperson->last_name ?>
						Age: <?php echo $iperson->age ?>
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