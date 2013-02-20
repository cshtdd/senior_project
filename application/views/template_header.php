<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		<?php 
			if (isset($title) && strlen($title) > 0)
			{
				echo $title.' - Senior Project';
			}
			else
			{
				echo 'Senior Project';
			}
		?>
	</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	

	<!-- Bootstrap -->

	<link href="<?php echo base_url() ?>css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url() ?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">

	<link href="<?php echo base_url() ?>css/style.css" rel="stylesheet" media="screen">

</head>
<body>
	<div class="container-narrow">


<!-- very nice html, but apparently the form-helper is more secure -->
<!--
		<form class="search-form form-inline" action="<?php echo site_url('searchcontroller/search_string') ?>">
			<div class="input-append">
				<input id="text-search-top" name="q" type="text" class="span2" placeholder="just search...">
				<button type="submit" class="btn" >Search</button>
			</div>
		</form>
-->

		<?php echo form_open('searchcontroller/search_string', array(
			'class' => 'search-form form-inline', 
			'id' => 'search-form-top',
			'method' => 'GET')) ?>
			<div class="input-append">
				<?php 
					echo form_input(array(
						'id' => 'text-search-top',
						'name' => 'q',
						'type' => 'text',
						'class' => 'span2',
						'placeholder' => 'just search...'
					));

					echo form_button(array(
						'id' => 'btn-search-top',
						'type' => 'Submit',
						'class' => 'btn',
						'content' => 'Search'
					));

				?>
			</div>
		<?php echo form_close() ?>


		<?php
			function get_nav_item($targetUrl, $displayText)
			{
				$li_class_str = '';

				if( trim(strtolower(uri_string())) == trim(strtolower($targetUrl)) )
				{
					$li_class_str = 'class="active"';
				}

				return '<li '.$li_class_str.'>'.anchor($targetUrl, $displayText).'</li>';
			}
		?>

		<ul class="nav nav-pills pull-right">
			<?php echo get_nav_item('past-projects', 'Past Projects') ?>
			<?php echo get_nav_item('project', 'My Project') ?>
			<?php echo get_nav_item('me', 'My Profile') ?>
			<?php echo get_nav_item('#', 'Logout') ?>
		</ul>


		<h1 class="muted">
			<?php echo anchor('/', 'Senior Project Website') ?>
		</h1>

		<hr>

		<div id="main-content">
