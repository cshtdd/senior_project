<?php $this->load->helper('nav_top') ?>
<?php $this->load->helper('notifications') ?>
<?php $this->load->helper("user_image"); ?>

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
    <link href="<?php echo base_url() ?>css/normalize.css" rel="stylesheet" media="screen">

    <link href="<?php echo base_url() ?>css/style.css" rel="stylesheet" media="screen">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>js/bootstrap-tagmanager.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php echo base_url() ?>js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>
    <div class="container-narrow">


        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
                <?php echo anchor('/', 'FIU Senior Project', array('class' => 'brand')) ?>

                <?php if ( !in_array(strtolower(uri_string()), array('login', 'register')) ) { ?>            

                    <?php
                        function isActiveNavItem($targetUrl)
                        {
                            return trim(strtolower(uri_string())) == trim(strtolower($targetUrl));
                        }
                        function get_nav_item_class($targetUrl)
                        {
                            $li_class_str = '';

                            if( isActiveNavItem($targetUrl) )
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

                        <!-- hamburger button to be displayed when taskbar is collapsed -->
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>


                        <div class="nav-collapse collapse">

                            <ul class="nav pull">
                                <!-- <?php echo get_nav_item('home', 'Home') ?> -->
                                <?php echo get_nav_item('past-projects', 'Past Projects') ?>
                                <?php echo get_nav_item('project', 'My Projects') ?>
                                <?php echo get_nav_item('about', 'About') ?>

                                <li class="visible-phone visible-tablet <?php echo isActiveNavItem('mobile-search') ? 'dropdown active' : 'dropdown' ?>">
                                    <a href="<?php echo base_url().'mobile-search' ?>">
                                        Search
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav pull-right">

                                <?php 
                                    $notificationsCount = getPendingNotificationsCount($this);
                                    if ($notificationsCount > 0)
                                    {
                                ?>
                                        <li id="notifications-badge" <?php echo get_nav_item_class('notifications') ?> >
                                            <a href="<?php echo base_url().'notifications' ?>" class="visible-desktop">
                                                <i class="icon-envelope"></i>
                                                <span class="badge badge-important"><?php echo $notificationsCount ?></span>
                                            </a>

                                            <a href="<?php echo base_url().'notifications' ?>" class="visible-phone visible-tablet">                                                
                                                <span class="badge badge-important"><?php echo $notificationsCount ?></span>
                                                <span>Notifications Pending</span>
                                            </a>
                                        </li>
                                <?php
                                    }
                                ?>

                                <?php if (isUserLoggedIn($this)) { ?>                      

                                    <li class="visible-phone visible-tablet <?php echo isActiveNavItem('me') ? 'dropdown active' : 'dropdown' ?>">
                                        <a href="<?php echo base_url().'me' ?>">
                                            <?php echo getCurrentUserHeaderFullName($this)?> Profile
                                        </a>
                                    </li>
                                    <li class="visible-phone visible-tablet">
                                        <a href="<?php echo base_url().'logout' ?>">
                                            Logout
                                        </a>
                                    </li>


                                    <li class="visible-desktop <?php echo isActiveNavItem('me') ? 'dropdown active' : 'dropdown' ?>">
                                        <a id="link-profile" href="<?php echo base_url().'me' ?>" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-user"></i> <b class="caret"></b>
                                        
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="link-profile">
                                            <li role="presentation">
                                                <a role="menuitem" tabindex="-1" href="<?php echo base_url().'me' ?>">

                                                    <div>
                                                        <div class="pull-right"> 
                                                            <strong><?php echo getCurrentUserHeaderFullName($this)?></strong>
                                                            <small class="block-text">View Profile</small>                                                
                                                        </div>
                                                        <?php 
                                                            $imgSrc = getUserImage($this, getCurrentUserHeaderImg($this));
                                                            if (isset($imgSrc))
                                                            {
                                                                echo img(array(
                                                                        'src' => $imgSrc,
                                                                        'alt' => 'User Profile Image',
                                                                        'class' => 'img-header-profile-big img-polaroid'
                                                                    ));
                                                            }
                                                        ?>
                                                    </div>
                                                </a>                                    
                                            </li>

                                            <?php 
                                                $notificationsCount = getPendingNotificationsCount($this);
                                                if ($notificationsCount > 0)
                                                {
                                            ?>
                                                    <li role="presentation">
                                                        <a role="menuitem" tabindex="-1" href="<?php echo base_url().'notifications' ?>">

                                                            
                                                            <span class="badge badge-important"><?php echo $notificationsCount ?></span>
                                                            Notifications Pending                                             
                                                        

                                                        </a>
                                                    </li>
                                            <?php
                                                }
                                            ?>

                                            <li role="presentation" class="divider"></li>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'logout' ?>">Logout</a></li>
                                        </ul>
                                      </a>

                                    </li>

                                <?php } else { ?>

                                    <?php echo get_nav_item('login', 'Login') ?> 

                                <?php } ?>                    
                                
                            </ul>



                            <?php echo form_open('searchcontroller/search_string', array( 
                                'class' => 'navbar-search pull-right visible-desktop', 
                                'id' => 'search-form-top',
                                'method' => 'GET')) ?>
                                <div class="input-append">
                                    <?php

                                        $searchParam = '';
                                        $uri = uri_string();
                                        $pos = stripos($uri, 'search/');
                                        if (isset($pos) && $pos === 0)
                                        {
                                            $searchParam = urldecode(substr($uri, strlen('search/')));
                                        }

                                        echo form_input(array(
                                            'id' => 'text-search-top',
                                            'name' => 'q',
                                            'type' => 'text',
                                            'class' => 'span2 search-query',
                                            'placeholder' => 'search for people, skills, projects and terms...',
                                            'required' => '',
                                            'title' => 'search criteria',
                                            'value' => $searchParam
                                        ));
                                        
                                        echo form_button(array(
                                            'id' => 'btn-search-top',
                                            'type' => 'Submit',
                                            'class' => 'btn btn-link',
                                            'content' => '<i class="icon-search"></i>'
                                        ));                             

                                    ?>
                                </div>
                            <?php echo form_close() ?>     

                    </div>
                <?php } ?>
            </div>
          </div>
        </div>

        <div id="main-content">     

        <?php if (isFlashMessageAvailable($this)){ ?>
            <div class="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div id="alert-text"><?php echo getFlashMessage($this) ?></div>
            </div>
        <?php } else { ?>
            <div id="alert-js" class="alert">
              <a id="alert-js-close" href="#" class="close">&times;</a>
              <div id="alert-js-text"></div>
            </div>
        <?php } ?>

