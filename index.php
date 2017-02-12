<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>
      Home
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="blog, technology, code, program, alorithms"/>
    <meta name="description" content="We emphaisze on solving problems">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <style type="text/css">
        /* no added transitions for safari, mozilla, safari and other browsers*/
        .slider
        {
            z-index: -1;
        }
        nav
        {
            border-bottom: 1px white solid;
        }
        #secondary-content
    {
        position: relative;
        top: 100vh;
    }
    #write-blog
    {
        position: relative;
        top: -50%;
        z-index: 3;
    }
    .ghost-button
    {
        display: inline-block;
        width: 200px;
        padding: 8px;
        color: #fff;
        border: 2px solid #fff;
        text-align: center;
        outline: none;
        text-decoration: none;
    }
    .ghost-button:hover, .ghost-button:active
    {
        background-color: #fff;
        color: #000;
        transition: background-color 0.3s ease-in, color 0.3s ease-in;
    }

    </style>
</head>
<body>
    <nav class="z-depth-2 blue transparent">
        <div class="nav-wrapper container">
            <a href="#" class="brand-logo">Logo</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
              <li><a href="login_frontend.php" class="nav-headers">LOGIN</a></li>
              <li><a href="register_frontend.php" class="nav-headers">SIGNUP</a></li>
            </ul>
            <ul class="side-nav" id="mobile-demo">
              <li><a href="login_frontend.php" class="nav-headers">LOGIN</a></li>
              <li><a href="register_frontend.php" class="nav-headers">SIGNUP</a></li>
            </ul>
        </div>
    </nav>

    <div class="slider fullscreen" data-indicators="false">
        <ul class="slides">
            <li>
                <img src="Includes/images/map2.jpg">
                <div class="caption left-align">
                    <h3 class="light white-text">History doesn't repeats itself,<br>but it does rhyme.</h3>
                </div>
            </li>
            <li>
                <div class="caption right-align">
                    <h1 class="light white-text">First solve the problem.<br>Then, write the code.</h1>
                </div>
                <img src="Includes/images/sublime_text.jpeg"> <!-- random image -->
            </li>
            <li>
                <div class="caption center-align">
                    <h4 class="light white-text">Art speaks where words are unable to explain.</h4>
                </div>
                <img src="Includes/images/art1.jpg">
            </li>
            <li>
                <img src="Includes/images/music2.jpg">
                <div class="caption right-align">
                    <h5 class="light grey-text text-lighten-3">Where words fail, Music speaks.</h5>
                </div>
            </li>
            <li>
                <div class="caption left-align">
                    <h4 class="light white-text">Science is the poetry of<br>reality.</h4>
                </div>
                <img src="Includes/images/science.jpg"> <!-- random image -->
            </li>            
        </ul>
        <div id="write-blog" class="center-align">
            <a class="ghost-button" href="">WRITE A BLOG</a>
        </div>
    </div>
    <div id="secondary-content">
        <div class="container">
                    <?php
                        $blog = DB::getInstance()->get('blogs', array('deletion_status', '=', '0'));
                        if($blogs = $blog->fetchRecords(2))
                        {
                            foreach($blogs as $blog)
                            {
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                echo 
                                "<div class='section'>
                                    <div class='row'>
                                        <div class='col s2'>
                                            <blockquote>".
                                                date('M', $date)."<br>".
                                                date('Y d', $date).
                                            "</blockquote>
                                        </div>
                                        <div class='col s8'>
                                            <h5>".ucfirst($blog->title)."</h5>
                                            <h6>".ucfirst($blog->description)."</h6><br>
                                            <div class='row'>
                                                <div class='col s1'>
                                                    <i class='material-icons' style='color:grey'>remove_red_eye</i>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->views}
                                                </div>
                                                <div class='col s1 offset-s2'>
                                                    <i class='material-icons' style='color:grey'>thumb_up</i>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->likes}
                                                </div>
                                                <div class='col s1 offset-s2'>
                                                    <i class='material-icons' style='color:grey'>thumb_down</i>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->dislikes}
                                                </div>
                                            </div>
                                            <div class='divider'></div>
                                        </div>
                                    </div>
                                </div>";
                            }
                        }
                    ?>
        </div>
    </div>

    <script src="Includes/js/jquery.min.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').slider();
        });
    </script>
</body>
</html>