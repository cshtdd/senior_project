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

        <?php if ( !stristr(uri_string(), 'login') ) { ?>


            <!-- very nice html, but apparently the form-helper is more secure--> 
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
                            'class' => 'span4',
                            'placeholder' => 'search for people, skills, projects and terms...',
                            'required' => '',
                            'title' => 'search criteria'
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

            <ul class="nav nav-pills pull-right">
                <?php echo get_nav_item('home', 'Home') ?>
                <?php echo get_nav_item('past-projects', 'Past Projects') ?>
                <?php echo get_nav_item('project', 'My Projects') ?>
                <?php echo get_nav_item('about', 'About') ?>
            <!--    
                <?php echo get_nav_item('me', 'My Profile') ?>            
                <?php echo get_nav_item('logout', 'Logout') ?>
            -->

                <?php if (isUserLoggedIn($this)) { ?>
                    <?php echo get_nav_item('me', 'My Profile') ?>            
                    <?php echo get_nav_item('logout', 'Logout') ?>
    <!--
                    <?php 
                        //echo get_nav_item('me', getCurrentUserHeaderName($this)) 

                        $linkText = getCurrentUserHeaderName($this);

                        $imgSrc = getCurrentUserHeaderImg($this);
                        $imgHtml = '';

                        if (strlen($imgSrc) > 0)
                        {
                            //$linkText = $linkText.' '.$imgHtml;
                            $imgHtml = img(array(
                                    'src' => $imgSrc,
                                    'class' => 'img-header-profile',
                                    'alt' => 'User Profile Image'
                                ));
                        }

                        //$navItemHTML = '<a href="'.base_url().'me">'.$linkText.$imgHtml.'</a>';
                        $navItemHTML = anchor('me', $linkText.$imgHtml);

                        //echo get_nav_item('me', $linkText);
                        echo get_nav_item_Internal('me', $navItemHTML);

                        //echo $navItemHTML;
                    ?>
                    
                    <?php echo get_nav_item('logout', 'Logout') ?>
    -->
                    <!--
                    <?php
                         echo get_nav_item('logout', 
                            img(array(
                                    'src' => getCurrentUserHeaderImg($this),
                                    'class' => 'img-header-profile',
                                    'alt' => 'User Profile Image'
                                ))
                            ) 
                    ?>
                    -->

                <?php } else { ?>

                    <?php echo get_nav_item('login', 'Login') ?>

                <?php } ?>
            </ul>
        <?php } ?>


        <h1 class="muted">
            <?php echo anchor('/', 'Senior Project Website') ?>
        </h1>

        <hr>

        <div id="main-content">
