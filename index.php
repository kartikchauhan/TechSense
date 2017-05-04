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
    <link rel="preload" as="image" href="Includes/images/code5.jpeg">
    <link rel="preload" as="image" href="Includes/images/code3.png">
    <link rel="preload" as="image" href="Includes/images/code2.png">
    <link rel="preload" as="image" href="Includes/images/code4.png">
    <link rel="preload" as="image" href="Includes/images/code1.png">
    <link rel="preload" as="style" href="http://fonts.googleapis.com/icon?family=Material+Icons">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="blog, technology, code, program, alorithms"/>
    <meta name="description" content="Publish your passions your way. Whether you'd like to share your knowledge, experiences or the latest tech news, create a unique and beautiful blog for free.">
    <title> Home </title>
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
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">

    <style type="text/css">        
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
            z-index: 10;
            text-align: center;
            vertical-align: middle;
        }
        .home-logo 
        {
            position: absolute;
            top: 32%;
            -webkit-transform: translateX(-50%);
        }
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
        #secondary-content
        {
            position: relative;
            top: 100vh;
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
        blockquote 
        {
            border-left: 5px solid #42A5F5;
        }
        .blockquote
        {
            font-size: 12px;
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
            margin: 0px;
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
    <div class="row">        
        <form class="col s10 l6 offset-l3 offset-s1" onsubmit="return false;">
            <div class="input-field valign-wrapper">
                <!-- <i class='fa fa-search left valign' aria-hidden='true'></i> -->
                <input id="search" type="search" class="valign search" required placeholder="user: username | tags: tag1, tag2...">
                <label for="search"><i class="material-icons">search</i></label>
                <i class="material-icons close">close</i>
            </div>
                <input type="hidden" id="_token" value="<?php echo Token::generate(); ?>">
        </form>
    </div>
        <div class="row">
            <div class="col s12 l8">
                <div class="row">
                    <div class="col offset-s6">
                        <div class="loader-container">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
                <h5 class="center-align">Recent Blogs</h5>
                <!-- <div class="content" id="content"> -->
                    <?php
                        $blogs = DB::getInstance()->sort('blogs', array('created_on', 'DESC'));
                        $num_blogs = $blogs->count();
                        $num_pages = ceil($num_blogs/5);
                        if($num_blogs)  // show blogs if there are any, otherwise show message 'No blogs'
                        {   
                            echo 
                            "<div class='primary-content'>
                                <div class='pagination_item_value' data-attribute='false'></div>";  // data-attribute = false => for default pagination,true => pagination for user, pagination for tags, pagination for title, pagination for name
                                    echo
                                    "<div class='content' id='content'>";
                            $blogs = $blogs->results();
                            $blogs = array_slice($blogs, 0, 5);
                            foreach($blogs as $blog)
                            {
                                $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                                $blog_tags = $blog_tags->results();
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                $writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first()->username;
                                echo 
                                    "<div class='row'>
                                        <div class='col s12 hide-on-large-only'>
                                            <div class='col s6'>
                                                <blockquote>".
                                                    date('M d', $date).' '.
                                                    date('Y', $date).
                                                "</blockquote>
                                            </div>
                                        </div>
                                        <div class='col s2 l2 hide-on-med-and-down'>
                                            <blockquote>".
                                                date('M', $date)."<br>".
                                                date('Y d', $date).
                                            "</blockquote>
                                        </div>
                                        <div class='col s12 l10'>
                                            <div class='row margin-eliminate'>
                                                <div class='col s12 l10'>
                                                    <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                                    <h6>".ucfirst($blog->description)."</h6>
                                                </div>
                                            </div> 
                                            <div class='row margin-eliminate'>                                        
                                                <div class='col l4 s6 m4'>
                                                    <p class='grey-text'>".$blog->blog_minutes_read." minutes read</p>
                                                </div>
                                                <div class='col l4 s6 m4'>
                                                    <p class='grey-text right-align'>- ".$writer."</p>
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
                                                    echo "<div class='chip'>".$blog_tag->tags."</div>";
                                                }
                                                echo
                                                "</div>
                                            </div>
                                            <div class='divider'></div>
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
                                </div>
                            </div>";
                        }
                        else
                        {
                            echo "<div class='section center-align'>No blogs yet. <a href='write_blog.php'>Write the very first blog.</a></div>";
                        }
                    ?>
            </div>
            <div class="col s12 l4">
                <div class="section">
                    <h5 class="center-align">Recommended Blogs</h5>
                </div>
                <?php
                    $blogs = DB::getInstance()->sort('blogs', array('views', 'DESC'));
                    if($blogs->count())
                    {
                        if($blogs = $blogs->fetchRecords(5))
                        {
                            foreach($blogs as $blog)
                            {
                                $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                                $blog_tags = $blog_tags->results();
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                $writer = DB::getInstance()->get('users', array('id', '=', $blog->users_id))->first()->username;
                                echo 
                                "<div class='row'>
                                    <div class='col s12 hide-on-med-and-up'>
                                        <div class='col s6'>
                                            <blockquote>".
                                                date('M d', $date).' '.
                                                date('Y', $date).
                                            "</blockquote>
                                        </div>
                                    </div>
                                    <div class='col l2 hide-on-small-only'>
                                        <blockquote class='blockquote'>".
                                            date('M', $date)."<br>".
                                            date('Y d', $date).
                                        "</blockquote>
                                    </div>
                                    <div class='col s12 l10'>
                                        <div class='row hide-on-med-and-up margin-eliminate'>
                                            <div class='col s12'>
                                                <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                                <h6>".ucfirst($blog->description)."</h6>
                                            </div>
                                        </div>
                                        <div class='hide-on-small-only'>
                                            <h6><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h6>
                                            <p class='description margin-eliminate'>".ucfirst($blog->description)."</p>
                                        </div>
                                        <div class='row margin-eliminate'>                                        
                                            <div class='col l6 s6 m4'>
                                                <p class='minutes_read grey-text'>".$blog->blog_minutes_read." minutes read</p>
                                            </div>
                                            <div class='col l6 s6 m4'>
                                                <p class=' blog_writer grey-text right-align'>- ".$writer."</p>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='measure-count' data-attribute='{$blog->id}'>
                                                <div class='col s2 l1'>
                                                    <i class='fa fa-eye fa-lg' aria-hidden='true' style='color:grey'></i>
                                                </div>
                                                <div class='col s1 l1'>
                                                    {$blog->views}
                                                </div>
                                                <div class='col s2 l1 offset-s1 offset-l1'>
                                                    <i class='fa fa-thumbs-up fa-lg' aria-hidden='true' style='color:grey'></i>
                                                </div>
                                                <div class='col s1 l1'>
                                                    {$blog->likes}
                                                </div>
                                                <div class='col s2 l1 offset-s1 offset-l1'>
                                                    <i class='fa fa-thumbs-down fa-lg' aria-hidden='true' style='color:grey'></i>
                                                </div>
                                                <div class='col s1 l1'>
                                                    {$blog->dislikes}
                                                </div>
                                            </div>
                                        </div>";
                                        foreach($blog_tags as $blog_tag)
                                        {
                                            echo "<div class='chip'>".$blog_tag->tags."</div>";
                                        }
                                        echo
                                        "<div class='section hide-on-med-and-up'>
                                            <div class='divider'></div>
                                        </div>
                                    </div>
                                </div>";
                            }
                        }
                    }
                    else
                    {
                        echo 
                        "<h6 class='center-align'>No blogs yet</h6>";
                    }
                ?>
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

            $('.primary-content').on('click', '.blog-pagination', function(e){
                e.preventDefault();
                console.log('pagination');
                $('.active').removeClass('active');
                $(this).parent().addClass('active');
                var page_id = $(this).html();
                var query_type_status = $('.pagination_item_value').attr('data-attribute'); // variable query_type_status is to moderate the type of pagination, by default it's value will be 0
                if(query_type_status == 'false')
                {
                    var data = {page_id: page_id, query_type_status: query_type_status};
                }
                else
                {
                    var query = $('#search').val();
                    var data = {page_id: page_id, query_type_status: query_type_status, query: query};
                }
                console.log(query_type_status);
                console.log(data);
                // var _token = $('#_token').attr('data-attribute');

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: data,
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
                            $('.content').html(response.content);
                        }
                    }
                });
            });

            $("#search").on("keypress", function(event) {
                if(event.which == 13)   // if the user hits enter
                {
                    var query = $('#search').val();     // fetch the query given by the user
                    var _token = $('#_token').val();
                    console.log("sending data");
                    $('.loader-container').show();
                    $.ajax({
                        type: "POST",
                        url: "search.php",
                        data: {query: query, _token: _token},
                        dataType: "json",
                        success: function(response)
                        {
                            // var response = JSON.parse(response);
                            $('.loader-container').hide();
                            console.log(response);
                            if(response.error_status === true)
                            {
                                if(response.error_code != 1)
                                {
                                    $('#_token').val(response._token);
                                    $('.primary-content').html(response.content);
                                }
                                else
                                {
                                    Materialize.toast(response.error, 5000, "red");
                                }
                            }
                            else
                            {
                                $('#_token').val(response._token);
                                $('.primary-content').html(response.content);
                            }
                        }
                    });
                }
            });

            $('.close').on('click', function() {
                $('.search').val('');
                var query = "default: default";     // query_type default: default is dummy query is to retrieve the results when the user has cleared the previously asked query
                var _token = $('#_token').val();
                $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: {query: query, _token: _token},
                    dataType: "json",
                    success: function(response)
                    {
                        if(response.error_status === true)
                        {
                            if(response.error_code != 1)
                            {
                                $('#_token').val(response._token);
                                $('.primary-content').html(response.content);
                            }
                            else
                            {
                                Materialize.toast(response.error, 5000, "red");
                            }
                        }
                        else
                        {
                            $('#_token').val(response._token);
                            $('.primary-content').html(response.content);
                        }
                    }
                });
            });

            // $('.views').click(function(e){
            //     e.preventDefault();
            //     var blog_id = getBlogId(this);
            //     var _token = getToken();

            // });


            // $('.likes, .dislikes').click(function(e){
            //     e.preventDefault();
            //     var object = $(this);
                
            //     var blog_id = getBlogId(this);
            //     var _token = getToken();
            //     var count = $(this).attr('data-attribute');
            //     var className = getClassName(this);

            //     $.ajax({
            //         type: 'POST',
            //         url: 'blog_attributes.php',
            //         data: {blog_id: blog_id, _token: _token, field: className, count: count},
            //         cache: false,
            //         success: function(response)
            //         {
            //             var response = JSON.parse(response);
            //             console.log(response);
            //             $('#_token').attr('data-attribute', response._token);
            //             if(response.error_status)
            //             {
            //                 consol.log(response.error);
            //                 Materialize.toast(response.error, 5000, 'red');
            //                 return false;
            //             }
            //             else
            //             {
            //                 $(object).attr('data-attribute', response.count);
            //                 console.log(response.count);
            //                 console.log($(object).parent().next().text(response.count));
            //             }
            //         }
            //     });
            // });
   
            // function getToken()
            // {
            //     return $('#_token').attr('data-attribute');
            // } 

            // function getBlogId(object)
            // {
            //     return $(object).parent().parent().attr('data-attribute');
            // }

            // function getClassName(object)
            // {
            //     var className = $(object).attr('class');
            //     if(className === 'likes')
            //     {
            //         className = 'likes';
            //     }
            //     else if(className === 'dislikes')
            //     {
            //         className = 'dislikes';
            //     }

            //     return className;

            // }

        });
    </script>
</body>
</html>

