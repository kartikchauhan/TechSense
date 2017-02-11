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
            top: -20%;
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

    <div class="slider fullscreen center" data-indicators="false">
        <ul class="slides">
            <li>
                <img src="Includes/images/map.jpg">
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
        <div id="write-blog">
            <a class="btn waves-effect white grey-text text-darken-3">Write a blog</a>
        </div>
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