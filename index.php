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
    .blockquote
    {
        font-size: 12px;
    }
    .description
    {
        font-size: 12px;
    }
    a
    {
        cursor: pointer;
    }
    ._token
    {
        display: none;
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
            <div class="row">
                <div id='_token' data-attribute="<?php echo Token::generate(); ?>"></div>
                <div class="col s8">
                    <h5 class="center-align">Recent Blogs</h5>
                    <?php
                        $blog = DB::getInstance()->sort('blogs', array('created_on', 'DESC'));
                        if($blogs = $blog->fetchRecords(5))
                        {
                            foreach($blogs as $blog)
                            {
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                echo 
                                "<div class='row'>
                                    <div class='col s2'>
                                        <blockquote>".
                                            date('M', $date)."<br>".
                                            date('Y d', $date).
                                        "</blockquote>
                                    </div>
                                    <div class='col s10'>
                                        <h5>".ucfirst($blog->title)."</h5>
                                        <h6>".ucfirst($blog->description)."</h6><br>
                                        <div class='row'>
                                            <div class='measure-count' data-attribute='{$blog->id}'>
                                                <div class='col s1'>
                                                    <a class='views' data-attribute='{$blog->views}'><i class='material-icons' style='color:grey'>remove_red_eye</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->views}
                                                </div>
                                                <div class='col s1 offset-s1'>
                                                    <a class='likes' data-attribute='{$blog->likes}'><i class='material-icons' style='color:grey'>thumb_up</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->likes}
                                                </div>
                                                <div class='col s1 offset-s1'>
                                                    <a class='dislikes' data-attribute='{$blog->dislikes}'><i class='material-icons' style='color:grey'>thumb_down</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->dislikes}
                                                </div>
                                            </div>
                                        </div>
                                        <div class='divider'></div>
                                    </div>
                                </div>";
                            }
                        }
                    ?>
                </div>
                <div class="col s4">
                    <div class="section">
                        <h5 class="center-align">Recommended Blogs</h5>
                    </div>
                    <?php
                        $blog = DB::getInstance()->sort('blogs', array('views', 'DESC'));
                        if($blogs = $blog->fetchRecords(2))
                        {
                            foreach($blogs as $blog)
                            {
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                echo 
                                "<div class='row'>
                                    <div class='col s2'>
                                        <blockquote class='blockquote'>".
                                            date('M', $date)."<br>".
                                            date('Y d', $date).
                                        "</blockquote>
                                    </div>
                                    <div class='col s10'>
                                        <h6>".ucfirst($blog->title)."</h6>
                                        <p class='description'>".ucfirst($blog->description)."</p><br>
                                        <div class='row'>
                                            <div class='measure-count' data-attribute='{$blog->id}'>
                                                <div class='col s1'>
                                                    <a class='views' data-attribute='{$blog->views}'><i class='material-icons' style='color:grey'>remove_red_eye</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->views}
                                                </div>
                                                <div class='col s1 offset-s2'>
                                                    <a class='likes' data-attribute='{$blog->likes}'><i class='material-icons' style='color:grey'>thumb_up</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->likes}
                                                </div>
                                                <div class='col s1 offset-s2'>
                                                    <a class='dislikes' data-attribute='{$blog->dislikes}'><i class='material-icons' style='color:grey'>thumb_down</i></a>
                                                </div>
                                                <div class='col s1'>
                                                    {$blog->dislikes}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                            }
                        }
                    ?>
                </div>
            </div>
    </div>

    <script src="Includes/js/jquery.min.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').slider();

            $('.likes, .dislikes').click(function(e){
                e.preventDefault();
                var className = $(this).attr('class');
                var object = $(this);
                if(className === 'likes')
                {
                    className = 'likes';
                }
                else if(className === 'dislikes')
                {
                    className = 'dislikes';
                }
                var blog_id = $(this).parent().parent().attr('data-attribute');
                var _token = $('#_token').attr('data-attribute');
                var count = $(this).attr('data-attribute');
                // console.log(blog_id);
                // console.log(_token);
                // console.log(count);
                $.ajax({
                    type: 'POST',
                    url: 'blog_attributes.php',
                    data: {blog_id: blog_id, _token: _token, field: className, count: count},
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#_token').attr('data-attribute', response._token);
                        if(response.error_status)
                        {
                            consol.log(response.error);
                            Materialize.toast(response.error, 5000, 'red');
                            return false;
                        }
                        else
                        {
                            $(object).attr('data-attribute', response.count);
                            console.log(response.count);
                            console.log($(object).parent().next().text(response.count));
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>