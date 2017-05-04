<?php

require_once'Core/init.php';

$user = new User;

if(!$user->isLoggedIn())
{
    Redirect::to('login.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="preload" as="script" href="Includes/js/materialize.min.js">
        <link rel="preload" as="script" href="Includes/js/jquery.min.js">
        <link rel="preload" as="style" href="http://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="preload" as="script" href="vendor/tinymce/tinymce/tinymce.min.js">
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
            .brand-logo
            {
                display: inline-block;
                height: 100%;
            }
            .brand-logo > img {
                vertical-align: middle
            }
            .col.s12 > .btn
            {
                width: 100%;
            }
            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
              color: #9e9e9e;
            }
            ::-moz-placeholder { /* Firefox 19+ */
              color: #9e9e9e;
            }
            :-ms-input-placeholder { /* IE 10+ */
              color: #9e9e9e;
            }
            :-moz-placeholder { /* Firefox 18- */
              color: #9e9e9e;
            }
            nav ul .dropdown-button
            {
                width: 200px !important;
            }

        </style>
    </head>
    <body>
        <?php 
            include'header.php';
        ?>
        <script type="text/javascript">
            document.getElementById('nav-bar').classList.remove('transparent');
        </script>
        
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
                        <div class="chips chips-placeholder"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col s12 l3 m4">
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
                // $('.nav-bar').removeClass('transparent');

                $(".button-collapse").sideNav();

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
                
                $('.chips-placeholder').material_chip({
                    placeholder: '+Tag',
                    secondaryPlaceholder: 'Enter Tags',
                });
                
                // $('.chips-autocomplete').material_chip({
                //     autocompleteData: {
                //         'Apple': null,
                //         'Microsoft': null,
                //         'Google': null
                //     }
                // });

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
                    var tags_array = $('.chips').material_chip('data');
                    var _token = $('#_token').val();
                    if(!validateData(title, description, blog, tags_array.length, _token))
                    {
                        return false;
                    }
                    var tags = [];
                    $.each(tags_array, function(key, val) {
                        tags.push(val.tag);
                    });
                    console.log(tags);
                    $.ajax({
                        type: 'POST',
                        data: {title: title, description: description, blog: blog, _token: _token, blog_tags: tags},
                        dataType: "json",
                        url: 'write_blog_backend.php',
                        cache: false,
                        success: function(response)
                        {
                            // var response = JSON.parse(response);
                            console.log(response);
                            if(response.error_status === true)
                            {
                                if(response.error_code != 1)
                                {
                                    $('#_token').val(response._token);
                                }
                                Materialize.toast(response.error, 5000, 'red');
                            }
                            else
                            {
                                $('#_token').val(response._token);
                                sessionStorage.setItem('flashMessage', 'Your blog has been successfully posted');
                                window.location = 'authors_info.php';
                                // Materialize.toast("Your blog has been successfully posted.", 5000, "green");
                            }
                        }
                    });
                });
                
                function validateData(title, description, blog, num_tags, _token)
                {
                    if(title === '')
                    {
                        Materialize.toast('Titlesss is required', 5000, 'red');
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
                    if(num_tags === 0)
                    {
                        Materialize.toast('You need to add atleast one tag', 5000, 'red');
                        return false;
                    }
                    return true;
                }
            });
        </script>
    </body>
</html>