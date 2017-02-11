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
        .slider
        {
            z-index: -1;
        }
        nav
        {
            border-bottom: 1px white solid;
        }
        #write-blog
        {
            position: relative;
            top: 70%;
            z-index: 3;
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

    <div class="slider fullscreen center">
        <div id="write-blog">
            <a class="btn waves-effect white grey-text text-darken-1 btn-large">Write a blog</a>
        </div>
        <ul class="slides">
            <li>
                <img src="Includes/images/science.jpg"> <!-- random image -->
            </li>
            <li>
                <img src="Includes/images/sublime_text.jpeg"> <!-- random image -->
            </li>
            <li>
                <img src="http://lorempixel.com/580/250/nature/3"> <!-- random image -->
            </li>
            <li>
                <img src="http://lorempixel.com/580/250/nature/4"> <!-- random image -->
            </li>
        </ul>
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