<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="preload" as="image" href="Includes/images/landing.jpg">
    <link rel="preload" as="script" href="Includes/js/typed.js">
    <link rel="preload" as="script" href="Includes/js/materialize.min.js">
    <link rel="preload" as="script" href="https://use.fontawesome.com/819d78ad52.js">
    <link rel="preload" as="script" href="Includes/js/jquery.min.js">
    <link rel="preload" as="style" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="blog, technology, code, program, alorithms"/>
    <meta name="description" content="Publish your passions your way. Whether you'd like to share your knowledge, experiences or the latest tech news, create a unique and beautiful blog for free.">
    <title> Home </title>
    <link rel="shortcut icon" href="Includes/images/favicon.png">
    <script src="Includes/js/typed.js" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Typed.new("#typed", {
                stringsElement: document.getElementById('typed-strings'),
                typeSpeed: 30,
                backDelay: 500,
                loop: false,
                contentType: 'text', // or text
                // defaults to null for infinite loop
                loopCount: null,
                resetCallback: function() { newTyped(); }
            });

            var resetElement = document.querySelector('.reset');
            if(resetElement) 
            {
                resetElement.addEventListener('click', function() {
                    document.getElementById('typed')._typed.reset();
                });
            }
        });

        function newTyped(){ /* A new typed object */ }
    </script>
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">

    <style type="text/css">  
        body
        {
            background-color: #fafafa;
        }      
        #fullscreen-hero 
        {
            height: 100vh;
            position: absolute;
            text-align: center;
            width: 100%;
            min-height: 350px;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .home-hero 
        {
            background-image: url(Includes/images/landing.jpg);
            background-color: #bdbdbd;
            background-blend-mode: multiply;
        }
        .hero-container 
        {
            display: table;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
        }
        .hero-content 
        {
            display: table-cell;
            position: relative;
            text-align: center;
            vertical-align: middle;
        }
        /*.home-logo 
        {
            position: absolute;
            top: 32%;
            -webkit-transform: translateX(-50%);
        }*/
        #typed-strings
        {
            display: none;
        }
        .brand-logo
        {
            display: inline-block;
            height: 100%;
        }
        .brand-logo > img {
            vertical-align: middle
        }
        /*nav ul a:hover 
        {
            background-color: white !important;
            color: #231816 !important;
        }*/
        nav ul .dropdown-button
        {
            width: 200px !important;
        }
        input[type="search"]
        {
            height: 64px !important; /* or height of nav */
        }
        #secondary-content
        {
            position: relative;
            top: 90vh;
        }
        .ghost-button
        {
            position: absolute; 
            bottom: 15%;    /* positioning the ghost button from the bottom */
            -webkit-transform: translateX(-50%);    /* centering ghost button */
            -ms-transform: translateX(-50%);
            display: inline-block;
            width: 200px;
            padding: 8px;
            font-family: sans-serif;
            color: #fff;    
            letter-spacing: 0.1em;
            border: 2px solid #fff;
            text-align: center;
            outline: none;
            text-decoration: none;
            text-rendering:optimizeLegibility;
            -webkit-transition: color 300ms, background 500ms, border-color 700ms;
            transition: color 300ms, background 500ms, border-color 700ms;
        }
        .ghost-button:hover
        {
            background: #fff;
            border-color: #fff;
            color: #231816;
        }        
        .description
        {
            font-size: 12px;
        }        
        a
        {
            text-decoration: none;
            color: none;
        }
        .pagination li.active
        {
            background-color: #42A5F5;
        }
        .tabs
        {
            background-color: #fafafa !important;
        }
        .tabs .indicator
        {
            background-color: #42a5f5;
        }
        .tabs .tab a
        {
            color: rgb(3, 155, 229);
        }
        .tabs .tab a.active
        {
            color: rgb(3, 155, 229);
        }
        .tabs .tab a:hover
        {
            color: #1976d2;
        }
        label
        {
            -webkit-transform: none !important; 
            transform: none !important; 
        }
        .loader-container
        {
            display: none;
        }
        .loader
        {
            border: 3px solid #f3f3f3; /* Light grey */
            border-top: 3px solid #42A5F5; /* Blue */
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        div .margin-eliminate
        {
            margin-bottom: 10px;
        }
        p .margin-eliminate
        {
            margin: 0px;
        }
        .typed-cursor
        {
            opacity: 1;
            font-weight: 100;
            -webkit-animation: blink 0.7s infinite;
            -moz-animation: blink 0.7s infinite;
            -ms-animation: blink 0.7s infinite;
            -o-animation: blink 0.7s infinite;
            animation: blink 0.7s infinite;
        }
        @-keyframes blink
        {
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-webkit-keyframes blink
        {
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-moz-keyframes blink
        {
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-ms-keyframes blink
        {
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        @-o-keyframes blink
        {
            0% { opacity:1; }
            50% { opacity:0; }
            100% { opacity:1; }
        }
        .typed-fade-out{
            opacity: 0;
            animation: 0;
            transition: opacity .25s;
        }
        .card .card-content
        {
            padding-bottom: 0px;
            padding-top: 10px;
        }

        /*cards fadeIn */
        .fadedfx {
            background-color: #fe5652;
            visibility: hidden;
        }
        .fadeIn {
            animation-name: fadeIn;
            -webkit-animation-name: fadeIn;
            animation-duration: 1.5s;
            -webkit-animation-duration: 1.5s;
            animation-timing-function: ease-in-out;
            -webkit-animation-timing-function: ease-in-out;
            visibility: visible !important;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0.0;
            }
            100% {
                opacity: 1;
            }
        }
        @-webkit-keyframes fadeIn {
            0% {
                opacity: 0.0;
            }
            100% {
                opacity: 1;
            }
        }

    </style>
</head>
<body>

    <div id="fullscreen-hero" class="home-hero">
        <div class="hero-container">
            <div class="hero-content">
                <!-- <a href="#" class="home-logo">  
                    <img src="Includes/images/logo4.png">
                </a> -->
                <!-- <br> -->
                <h4 class="white-text hide-on-small-only">
                    <div id="typed-strings">
                        <span>Publish your own blogs</span>
                        <p>Read latest tech news</p>
                        <p>Solve problems!!</p>
                    </div>
                    <span id="typed" style="white-space:pre;"></span>
                </h4>
                <h5 class="white-text hide-on-med-and-up">
                    <div id="typed-strings">
                        <span>Publish your own blogs</span>
                        <p>Read latest tech news</p>
                        <p>Solve problems!!</p>
                    </div>
                    <span id="typed" style="white-space:pre;"></span>
                </h5>
                <a class="ghost-button" href="write_blog.php">WRITE A BLOG</a>
            </div>
        </div>
    </div>

    <?php

        include'header.php';

    ?>

    <div id="secondary-content">
        <div class="container">
            <div class="row">
                <div class="col s12 l12">
                    <div class="row">
                        <div class="col offset-s6">
                            <div class="loader-container">
                                <div class="loader"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 l8 offset-l2">
                        <div class="row">
                            <ul class="tabs">
                                <li class="tab col s6 l6"><a class="active" href="#recent_blogs">Recent Blogs</a></li>
                                <li class="tab col s6 l6"><a href="#recommended_blogs">Recommended Blogs</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="recent_blogs" class="col s12 l12">

                        <?php
                            $determining_factor = 'created_on';
                            populateWithBlogContent($determining_factor);
                        ?>
                    </div>
                    <div id="recommended_blogs" class="col s12">
                        <?php
                            $determining_factor = 'views';
                            populateWithBlogContent($determining_factor);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="page-footer blue lighten-1">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">TechSense</h5>
                        <p class="grey-text text-lighten-4">Publish your passions your way. Whether you'd like to share your knowledge, experiences or the latest tech news, create a unique and beautiful blog for free.</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="white-text">View Our Other Projects</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="http://www.silive.in" target="blank">silive.in</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!" target="blank">Blood Donation Campaign 2017</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!" target="blank">Table Tennis Tournament</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container center-align">
                    © 2017 Software Incubator
                </div>
            </div>
        </footer>
    </div>
        
    <?php
        function populateWithBlogContent($determining_factor)   // function to populate with recent_blogs or recommended_blogs
        {
            $blogs = DB::getInstance()->sort('blogs', array($determining_factor, 'DESC'));
            $num_blogs = $blogs->count();
            $num_pages = ceil($num_blogs/5);
            if($num_blogs)
            {
                echo
                "<div class='content'>";
                $blogs = $blogs->results();
                $blogs = array_slice($blogs, 0, 5);
                foreach($blogs as $blog)
                {
                    $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                    $blog_tags = $blog_tags->results();
                    $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                    $writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first();
                    echo 
                    "<div class='fadedfx'>
                        <div class='col s12 m12'>
                            <div class='card horizontal white'>
                                <div class='card-content'> <span class='card-title'>".date('M d Y', $date)."</span>
                                    <div class='row margin-eliminate'>
                                        <div class='col s12'>
                                            <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                            <h6>".ucfirst($blog->description)."</h6>
                                        </div>
                                    </div>
                                    <div class='row margin-eliminate'>  
                                        <div class='valign-wrapper'>
                                            <div class='col l6 s4'>
                                                <div class='valign-wrapper'>
                                                    <i class='material-icons hide-on-small-only' style='color:grey'>book</i>
                                                    <p class='grey-text'>".$blog->blog_minutes_read." min read</p>
                                                </div>
                                            </div>
                                            <div class='col l6 s8'>
                                                <a class='chip' href='/user_profile.php?user={$writer->username}'>
                                                    <img src='".Config::get('url/upload_dir')."/{$writer->image_url}' alt='Contact Person'>{$writer->username}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='measure-count' data-attribute='{$blog->id}'>
                                            <div class='col s2 l1 m1'>
                                                <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
                                            </div>
                                            <div class='col s1 l1 m1'>
                                                {$blog->views}
                                            </div>
                                            <div class='col s2 l1 m1 offset-m1 offset-s1 offset-l1'>
                                                <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
                                            </div>
                                            <div class='col s1 l1 m1'>
                                                {$blog->likes}
                                            </div>
                                            <div class='col s2 l1 m1 offset-m1 offset-s1 offset-l1'>
                                                <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
                                            </div>
                                            <div class='col s1 l1 m1'>
                                                {$blog->dislikes}
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col s12'>";
                                        foreach($blog_tags as $blog_tag)
                                        {
                                            $tag = $blog_tag->tags;
                                            echo "<a class='chip' href='".Config::get('url/endpoint')."/view_blogs_tag.php?tag={$tag}'>{$tag}</a>";
                                        }
                                        echo
                                        "</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
                echo 
                "</div>
                <div class='section center-align'>
                    <ul class='pagination'>";
                            for($x = 1; $x <= $num_pages; $x++)
                            {
                                if($x == 1)
                                {
                                    echo "<li class='waves-effect pagination active'><a href='#' class='blog-pagination'>".$x."</a></li>";
                                }
                                else
                                {
                                    echo "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
                                }
                            }   
                    echo
                    "</ul>
                </div>";
            }
            else
            {
                echo "<div class='section center-align'>No blogs yet. <a href='write_blog.php'>Write the very first blog.</a></div>";
            }
        }
    ?>

    <script src="Includes/js/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/819d78ad52.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
        if(typeof(Storage) !== "undefined")
        {
            console.log('not undefined');
            if(sessionStorage.getItem("flashMessage") !== null)
            {
                Materialize.toast(sessionStorage.getItem("flashMessage"), 5000 ,'green');
                sessionStorage.removeItem('flashMessage');
            }
        }
        $(document).ready(function(){

            $(".dropdown-button").dropdown({hover: false});   // activate dropdown in the nav-bar

            $(".button-collapse").sideNav();

            $('ul.tabs').tabs();

            $('#recent_blogs').on('click', '.blog-pagination', function(e) {
                e.preventDefault();
                pagination('recent_blogs', $(this)); // get pagination content for populating recent_blogs
            });

            $('#recommended_blogs').on('click', '.blog-pagination', function(e) {
                e.preventDefault();
                pagination('recommended_blogs', $(this)); // get pagination content for populating recommended_blogs
            });

            function pagination(determining_factor, obj)
            {
                $('.active').removeClass('active');
                obj.parent().addClass('active');
                var page_id = obj.html();
                console.log(page_id);
                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {determining_factor: determining_factor, page_id: page_id},
                    // dataType: "json",
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        if(response.error_status === true)
                        {
                            Materialize.toast(response.error, 5000, "red");
                        }
                        else
                        {
                            if(determining_factor == 'recent_blogs')
                            {
                                $('#recent_blogs').find('.content').html(response.content);
                            }
                            else if(determining_factor == 'recommended_blogs')
                            {
                                console.log(determining_factor);
                                $('#recommended_blogs').find('.content').html(response.content);
                            }
                        }
                    }
                });
            }

            $(window).scroll(function() {
                $(".fadedfx").each(function() {
                    var imagePos = $(this).offset().top;
                    var topOfWindow = $(window).scrollTop();
                    if (imagePos < topOfWindow + 500) {
                        $(this).addClass("fadeIn");
                    }
                });
            });

            $('.close').on('click', function() {
                $('#search').val('');
            });

        });
    </script>
</body>
</html>

