<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
    Redirect::to('index.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Write a blog    
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="keywords" content="blog, technology, code, program, alorithms"/>
        <meta name="description" content="We emphaisze on solving problems">
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
        <style type="text/css">
        /* no added transitions for safari, mozilla, safari and other browsers*/
            .logo
            {
                height: auto;
                width: 50%;
            }
            input[type="search"]
            {
                height: 64px !important; /* or height of nav */
            }
            .col.s12 > .btn
            {
                width: 100%;
            }
        </style>
    </head>
    <body>
        <?php 
            include'header.php';
        ?>
        <div class="container">
            <div class="row">
                <form action="" method="post" class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="title" id="title">
                            <label for="title">Title</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea class="materialize-textarea" name="description" id="description"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea name="blog" id="blog"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 l3">
                            <button type="button" class="btn waves-effect waves-light blue" name="post_blog" id="post_blog">Post Blog</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="Includes/js/jquery.min.js"></script>
        <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
        <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
        <script>
            $(document).ready(function(){
                $('.nav-bar').removeClass('transparent');

                tinymce.init({
                    selector: '#blog',
                    height: 200,
                    theme: 'modern',
                    plugins: [
                      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                      'searchreplace wordcount visualblocks visualchars code fullscreen',
                      'insertdatetime media nonbreaking save table contextmenu directionality',
                      'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
                    ],
                    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
                    image_advtab: true,
                    templates: [
                      { title: 'Test template 1', content: 'Test 1' },
                      { title: 'Test template 2', content: 'Test 2' }
                    ],
                    content_css: [
                      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                      '//www.tinymce.com/css/codepen.min.css'
                    ]
                });

                $('form').on('click', '#post_blog', function(){
                    // var data = new FormData();
                    // var input_data = $('.form').serializeArray();

                    // $.each(input_data, function(key, input)
                    // {
                    //     data.append(input.name, input.value);
                    // });

                    var title = $('#title').val();
                    var description = $('#description').val();
                    var blog = tinyMCE.activeEditor.getContent();
                    var _token = $('#_token').val();

                    if(!validateData())
                    {
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        data: {title: title, description: description, blog: blog, _token: _token},
                        url: 'write_blog_backend.php',
                        cache: false,
                        success: function(response)
                        {
                            var response = JSON.parse(response);
                            console.log(response);
                            $('#_token').val(response._token);
                            if(response.error_status)
                            {
                                Materialize.toast(response.error, 5000, 'red');
                            }
                            else
                            {
                                Materialize.toast("Your blog has been successfully posted.", 5000, "green");
                            }
                        }
                    });
                });
                
                function validateData(title, description, blog)
                {
                    if(title === '')
                    {
                        Materialize.toast('Title is required', 5000, 'red');
                        return false;
                    }
                    if(description === '')
                    {
                        Materialize.toast('Description is required', 5000, 'red');
                        return false;
                    }
                    if(blog === '')
                    {
                        Materialize.toast('Blog is required', 5000, 'red');
                        return false;
                    }
                    return true;
                }
            });
        </script>
    </body>
</html>