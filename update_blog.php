<?php

require_once'Core/init.php';

if(Input::exists('get'))
{
	if(Input::get('blog_id'))
	{
		$blog = new Blog;
		$blog->getBlog('blogs', array('id', '=', Input::get('blog_id')));
		if(!$blog->count())
			Redirect::to(404);
		else
		{
			$blog_id = $blog->data()->id;
			$title = $blog->data()->title;
			$description = $blog->data()->description;
			$blog = $blog->data()->blog;
            $blog_tags = DB::getInstance()->get('blog_tags', array('blog_id', '=', $blog_id));
            $blog_tags = $blog_tags->results();
            foreach($blog_tags as $blog_tag)
            {
                $blog_tags_initialized[] = $blog_tag->tags;
            } 
		}
				
	}
	else
	{
		Redirect::to('authors_info.php');
	}
}
else
{
	Redirect::to('authors_info.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="preload" as="script" href="Includes/js/materialize.min.js">
        <link rel="preload" as="script" href="Includes/js/jquery.min.js">
        <link rel="preload" as="style" href="http://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="preload" as="style" href="vendor/tinymce/tinymce/tinymce.min.js">
    	<title>Update Blog</title>
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
                            <input type="text" name="title" id="title" value="<?php echo $title; ?>">
                            <label for="title">Title</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea class="materialize-textarea" name="description" id="description"><?php echo $description; ?></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea name="blog" id="blog"><?php echo $blog; ?></textarea>
                        </div>
                    </div>
                   <div class="row">
                        <div class="chips chips-initial chips-placeholder"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
                            <input type="hidden" name="blog_id" id="blog_id" value="<?php echo $blog_id; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 l3">
                            <button type="button" class="btn waves-effect waves-light blue" name="update_blog" id="update_blog">Update Blog</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    	<script src="Includes/js/jquery.min.js"></script>
        <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
        <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
        	$(document).ready(function(){
                $('.nav-bar').removeClass('transparent');

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
                
                var blog_tags_json_format = <?php echo json_encode($blog_tags_initialized); ?>; // fetching the blog_tags and converting them to json format

                var blog_tags_initialize = [];

                $.each(blog_tags_json_format, function(index, value) {
                    blog_tags_initialize.push({tag: value});
                });

                $('.chips-placeholder').material_chip({
                    placeholder: '+Tag',
                    secondaryPlaceholder: 'Enter a Tag',
                });
                $('.chips-initial').material_chip({
                    data: blog_tags_initialize
                  });

    			$('#update_blog').on('click', function(){
    				var blog_id = $('#blog_id').val();
    				var _token = $('#_token').val();
    				var title = $('#title').val();
    				var description = $('#description').val();
    				var blog = tinyMCE.activeEditor.getContent();
                    var tags_array = $('.chips').material_chip('data');

                    if(!validateData(title, description, blog, tags_array.length, _token))
                    {
                        return false;
                    }
                    var tags = [];
                    $.each(tags_array, function(key, val) {
                        tags.push(val.tag);
                    });

    				$.ajax({
    					type: 'POST',
    					url: 'update_blog_backend.php',
    					data: {blog_id: blog_id, title: title, description: description, blog: blog, _token: _token, blog_tags: tags},
    					cache: false,
    					success: function(response)
    					{
    						var response = JSON.parse(response);
    						$('#_token').val(response._token);
    						console.log(response);
    						if(response.error_status === true)
    						{
    							Materialize.toast(response.error, 5000, "red");
    						}
    						else
    						{
    							Materialize.toast('Your blog has been updated successfully', 5000, "green");
    						}
    					}
    				});
    			});

                function validateData(title, description, blog, num_tags, _token)
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

