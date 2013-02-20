<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CASH Productions</title>

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



		<ul class="nav nav-pills pull-right">
			<!--
			<li class="active"><a href="./Template · Bootstrap_files/Template · Bootstrap.html">Home</a></li>
			<li><a href="./Template · Bootstrap_files/Template · Bootstrap.html">About</a></li>
			-->
			<li><a href="#">Past Projects</a></li>
			<li>
				<?php echo anchor('project', 'My Project') ?>
			</li>
			<li>
				<?php echo anchor('me', 'My Profile') ?>
			</li>
			<li><a href="#">Logout</a></li>
		</ul>
		<h1 class="muted">
			<?php echo anchor('/', 'Senior Project Website') ?>
		</h1>


		<hr>

		<div id="main-content">
