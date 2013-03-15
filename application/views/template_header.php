<?php $this->load->helper('nav_top') ?>

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

                <ul class="nav pull">
                    <!-- <?php echo get_nav_item('home', 'Home') ?> -->
                    <?php echo get_nav_item('past-projects', 'Past Projects') ?>
                    <?php echo get_nav_item('project', 'My Projects') ?>
                    <?php echo get_nav_item('about', 'About') ?>
                </ul>

                <ul class="nav pull-right">
                    <?php if (isUserLoggedIn($this)) { ?>

                        <!-- <?php echo get_nav_item('logout', 'Logout') ?>  -->

                    <!--
                        <li class="dropdown">
                          <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            Dropdown <b class="caret"></b>
                          </a>
                          <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="http://google.com">Action</a>
                                <a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                    Camilo <b class="caret"></b>
                                </a>
                            </li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                          </ul>
                        </li>

                    -->

                    <!--

                    <?php echo anchor('me', getCurrentUserHeaderName($this), array(
                                'class' => 'dropdown-toggle',
                                'role' => 'button',
                                'data-toggle' => 'dropdown'
                            ))?>

                    --> 


                        <li class="<?php echo isActiveNavItem('me') ? 'dropdown active' : 'dropdown' ?>">
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
                                                $imgSrc = getCurrentUserHeaderImg($this);
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
                            
                            echo form_button(array(
                                'id' => 'btn-search-top',
                                'type' => 'Submit',
                                'class' => 'btn btn-link',
                                'content' => '<i class="icon-search"></i>'
                            ));                             

                        ?>
                    </div>
                <?php echo form_close() ?>     

                

            <!--
                <form class="form-search js-search-form" action="/search" id="global-nav-search">                    
                    <input class="search-input" type="text" id="search-query" placeholder="Search" name="q">
                    <span class="search-icon js-search-action">
                      <button type="submit" class="icon nav-search btn btn-link" tabindex="-1">
                        <i class="icon-search"></i>                        
                      </button>
                    </span>                    
                </form>     
            -->

            <?php } ?>
          </div>
        </div>

        <div id="main-content">
