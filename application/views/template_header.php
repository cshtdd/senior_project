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
    <!-- <link href="<?php echo base_url() ?>css/bootstrap-tagmanager.css" rel="stylesheet" media="screen"> -->

    <link href="<?php echo base_url() ?>css/style.css" rel="stylesheet" media="screen">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap-tagmanager.js"></script>

</head>
<body>
    <div class="container-narrow">


        <div class="navbar">
          <div class="navbar-inner">
            <?php echo anchor('/', 'FIU Senior Project', array('class' => 'brand')) ?>

            <?php if ( !stristr(uri_string(), 'login') ) { ?>

                <?php
                    function get_nav_item_class($targetUrl)
                    {
                        $li_class_str = '';

                        if( trim(strtolower(uri_string())) == trim(strtolower($targetUrl)) )
                        {
                            $li_class_str = 'class="active"';
                        }

                        return $li_class_str;
                    }
                    function get_nav_item_Internal($targetUrl, $innerHTML)
                    {
                        return '<li '.get_nav_item_class($targetUrl).'>'.$innerHTML.'</li>';
                    }
                    function get_nav_item($targetUrl, $displayText)
                    {
                        return get_nav_item_Internal($targetUrl, anchor($targetUrl, $displayText));
                    }
                ?>

                <ul class="nav pull">
                    <!-- <?php echo get_nav_item('home', 'Home') ?> -->
                    <?php echo get_nav_item('past-projects', 'Past Projects') ?>
                    <?php echo get_nav_item('project', 'My Projects') ?>
                    <?php echo get_nav_item('about', 'About') ?>
                </ul>

                <ul class="nav pull-right">
                    <?php if (isUserLoggedIn($this)) { ?>

                        <?php echo get_nav_item('logout', 'Logout') ?> 

                    <?php } else { ?>

                        <?php echo get_nav_item('login', 'Login') ?> 

                    <?php } ?>
                </ul>

                <?php echo form_open('searchcontroller/search_string', array(
                    //'class' => 'search-form form-inline navbar-search pull-right', 
                    'class' => 'navbar-search pull-right', 
                    'id' => 'search-form-top',
                    'method' => 'GET')) ?>
                    <div class="input-append">
                        <?php 
                            echo form_input(array(
                                'id' => 'text-search-top',
                                'name' => 'q',
                                'type' => 'text',
                                'class' => 'span3 search-query',
                                'placeholder' => 'search for people, skills, projects and terms...',
                                'required' => '',
                                'title' => 'search criteria'
                            ));

                            /*
                            echo form_button(array(
                                'id' => 'btn-search-top',
                                'type' => 'Submit',
                                'class' => 'btn',
                                'content' => 'Search'
                            ));
                            */

                        ?>
                    </div>
                <?php echo form_close() ?>                

            <?php } ?>
          </div>
        </div>

        <div id="main-content">
