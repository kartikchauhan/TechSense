<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Write a blog    
        </title>
    </head>
    <body>
        <form action="post_blog.php" method="post">
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
            <br>
            <label for="description">Description</label>
            <input type="text" name="description" id="description">
            <br>
            <textarea name="blog" id="blog">Wait your text editor is being loaded</textarea>
            <input type="hidden" name="_token" id="_token" value="<?php echo Token::generate(); ?>">
            <input type="submit" value="submit">
        </form>
        <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
        <script>
            tinymce.init({
            selector: 'textarea',
            height: 300,
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
        </script>
    </body>
</html>