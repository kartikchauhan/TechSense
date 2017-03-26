<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="preload" as="script" href="Includes/js/materialize.min.js">
    <link rel="preload" as="script" href="https://use.fontawesome.com/819d78ad52.js">
    <link rel="preload" as="script" href="Includes/js/jquery.min.js">
    <link rel="preload" as="image" href="Includes/images/code5.jpeg">
    <link rel="preload" as="image" href="Includes/images/code3.png">
    <link rel="preload" as="image" href="Includes/images/code2.png">
    <link rel="preload" as="image" href="Includes/images/code4.png">
    <link rel="preload" as="image" href="Includes/images/code1.png">
    <link rel="preload" as="style" href="http://fonts.googleapis.com/icon?family=Material+Icons">
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
        input[type="search"]
        {
            height: 64px !important; /* or height of nav */
        }
        .logo
        {
            height: auto;
            width: 50%;
        }
        #secondary-content
        {
            position: relative;
            top: 100vh;
        }
        #write-blog
        {
            position: relative;
            top: -30%;
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
/*        ._token
        {
            display: none;
        }*/
        a
        {
            text-decoration: none;
            color: none;
        }
        nav ul .dropdown-button
        {
            width: 200px !important;
        }
        /*nav ul .navbar-menu
        {
            width: 167px;
        }*/
    </style>
</head>
<body>

    <?php

        include'header.php';

    ?>

    <div class="slider fullscreen" data-indicators="false">
        <ul class="slides">
            <li>
                <img src="Includes/images/code3.png">
            </li>
            <li>
                <img src="Includes/images/code1.png"> 
            </li>
            <li>
                <img src="Includes/images/code2.png">
            </li>
            <li>
                <img src="Includes/images/code4.png">
            </li>
            <li>
                <img src="Includes/images/code5.jpeg">
            </li>            
        </ul>
        <div id="write-blog" class="center-align">
            <a class="ghost-button" href="">WRITE A BLOG</a>
        </div>
    </div>
    <div id="secondary-content">
        <div class="row">
            
            <div class="col s12 l8">
                <h5 class="center-align">Recent Blogs</h5>
                <!-- <div class="content" id="content"> -->
                    <?php
                        $blogs = DB::getInstance()->sort('blogs', array('created_on', 'DESC'));
                        $num_blogs = $blogs->count();
                        $num_pages = ceil($num_blogs/3);
                        if($num_blogs)  // show blogs if there are any, otherwise show message 'No blogs'
                        {   
                            echo "<div class='content' id='content'>";
                            $blogs = $blogs->results();
                            $blogs = array_slice($blogs, 0, 3);
                            foreach($blogs as $blog)
                            {
                                $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog->id));
                                $blog_tags = $blog_tags->results();
                                $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
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
                                        <div class='col s2 l2 hide-on-small-only'>
                                            <blockquote>".
                                                date('M', $date)."<br>".
                                                date('Y d', $date).
                                            "</blockquote>
                                        </div>
                                        <div class='col s12 l10'>
                                            <div class='row'>
                                                <div class='col s12 l10'>
                                                    <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                                    <h6>".ucfirst($blog->description)."</h6><br>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='measure-count' data-attribute='{$blog->id}'>
                                                    <div class='col s2 l1'>
                                                        <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1 l1'>
                                                        {$blog->views}
                                                    </div>
                                                    <div class='col s2 l1 offset-s1 offset-l1'>
                                                        <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1 l1'>
                                                        {$blog->likes}
                                                    </div>
                                                    <div class='col s2 l1 offset-s1 offset-l1'>
                                                        <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1 l1'>
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
                                        <div class='row hide-on-med-and-up'>
                                            <div class='col s12'>
                                                <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                                <h6>".ucfirst($blog->description)."</h6><br>
                                            </div>
                                        </div>
                                        <div class='hide-on-small-only'>
                                            <h6><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h6>
                                            <p class='description'>".ucfirst($blog->description)."</p><br>
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
                        <h5 class="white-text">TechWit</h5>
                        <p class="grey-text text-lighten-4">A place to read and write blogs about any technology.</p>
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
            $('.slider').slider();  // activate slider

            $(".dropdown-button").dropdown({hover: false});   // activate dropdown in the nav-bar

            $(".button-collapse").sideNav();

            $('.blog-pagination').click(function(e){
                e.preventDefault();
                $('.active').removeClass('active');
                $(this).parent().addClass('active');
                var page_id = $(this).html();
                // var _token = $('#_token').attr('data-attribute');

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {page_id: page_id},
                    dataType: "json",
                    cache: false,
                    success: function(response)
                    {
                        // var response = JSON.parse(response);
                        console.log(response);
                        if(response.error_status === true)
                        {
                            Materialize.toast(response.error, 5000, "red");
                        }
                        else
                        {
                            // console.log(response._token);
                            // $('#_token').attr('data-attribute', response._token);
                            $('.content').html(response.content);
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