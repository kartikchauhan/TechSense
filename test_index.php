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

        nav
        {
            border-bottom: 1px white solid;
        }
        input[type="search"]
        {
            height: 64px !important; /* or height of nav */
        }
        #write-blog
        {
            position: relative;
            top: -50%;
            z-index: 3;
        }
        .blockquote
        {
            font-size: 12px;
        }
        .description
        {
            font-size: 12px;
        }
        ._token
        {
            display: none;
        }
        a
        {
            text-decoration: none;
            color: none;
        }
    </style>
</head>
<body>
    <div id="secondary-content">
            <div class="row">
                <div id='_token' class="_token" data-attribute="<?php echo Token::generate(); ?>"></div>
                <div class="col s8">
                    <h5 class="center-align">Recent Blogs</h5>
                    <div id="content" class="content">
                        <?php
                            $blogs = DB::getInstance()->sort('blogs', array('created_on', 'DESC'));
                            $num_blogs = $blogs->count();
                            $num_pages = ceil($num_blogs/1);

                            $blogs = $blogs->results();

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
                                            <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                            <h6>".ucfirst($blog->description)."</h6><br>
                                            <div class='row'>
                                                <div class='measure-count' data-attribute='{$blog->id}'>
                                                    <div class='col s1'>
                                                        <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1'>
                                                        {$blog->views}
                                                    </div>
                                                    <div class='col s1 offset-s1'>
                                                        <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1'>
                                                        {$blog->likes}
                                                    </div>
                                                    <div class='col s1 offset-s1'>
                                                        <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
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
                        ?>
                    </div>
                    <div class=" section center-align">
                        <ul class="pagination">
                            <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                            <?php 
                                for($x = 1; $x <= $num_pages; $x++)
                                {
                                    echo "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
                                }
                            ?>
                            <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>

    <script src="Includes/js/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/17e854d5bf.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){

            $('.blog-pagination').click(function(e){
                e.preventDefault();
                var page_id = $(this).html();

                $.ajax({
                    type: 'POST',
                    url: 'test_index_backend.php',
                    data: {page_id: page_id},
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('.content').html(response.content);
                    }
                });
            });


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
   
        });
    </script>
</body>
</html>