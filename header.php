<?php

    require_once'Core/init.php';

    $user = new User;

    if(!$user->isLoggedIn())
    {
        echo
        "<nav class='z-depth-2 blue transparent nav-bar' id='nav-bar'>
            <div class='nav-wrapper container'>
                <a href='index.php' class='brand-logo'><img class='responsive-img logo' src='Includes/images/logo3.png'></a>
                <a href='#' data-activates='mobile-demo' class='button-collapse'><i class='material-icons'>menu</i></a>
                <ul class='right hide-on-med-and-down navbar-menu'>
                    <li>
                        <a href='login.php' class='nav-headers'>LOGIN</a>
                    </li>
                    <li>
                        <a href='register.php' class='nav-headers'>SIGNUP</a>
                    </li>
                </ul>
                <ul class='side-nav' id='mobile-demo'>
                    <li>
                        <a href='login.php' class='nav-headers'>LOGIN</a>
                    </li>
                    <li>
                        <a href='register.php' class='nav-headers'>SIGNUP</a>
                    </li>
                </ul>
            </div>
        </nav>";
    }
    else
    {
        echo
        "<ul id='dropdown1' class='dropdown-content hide-on-med-and-down' data-constrainwidth='false'>
            <li><a href='index.php' class='blue-text'>Home <i class='material-icons right'>home</i></a></li>
            <li class='divider'></li>
            <li><a href='write_blog.php' class='blue-text'>Write Blog <i class='material-icons right'>mode_edit</i></a></li>
            <li class='divider'></li>
            <li><a href='authors_info.php' class='blue-text'>Edit Profile <i class='material-icons right'>account_circle</i></a></li>
            <li class='divider'></li>
            <li><a href='change_password.php' class='blue-text'>Change Password <i class='material-icons right'>settings</i></a></li>
            <li class='divider'></li>
            <li><a href='logout.php' class='blue-text'>Logout <i class='material-icons right'>exit_to_app</i></a></li>
        </ul>
        <nav class='z-depth-2 blue transparent nav-bar' id='nav-bar'>
            <div class='nav-wrapper container'>
                <a href='index.php' class='brand-logo'><img class='responsive-img logo' src='Includes/images/logo3.png'></a>
                <a href='#' data-activates='mobile-demo' class='button-collapse'><i class='material-icons'>menu</i></a>
                <ul class='right hide-on-med-and-down'>
                    <li>
                        <a class='dropdown-button' href='#!' data-activates='dropdown1'>". $user->data()->name ."<i class='material-icons right'>arrow_drop_down</i></a>
                    </li>
                </ul>
                <ul class='side-nav' id='mobile-demo'>
                    <li><a href='index.php' class='blue-text'>Home <i class='material-icons right'>home</i></a></li>
                    <li><a href='write_blog.php' class='blue-text'>Write Blog <i class='material-icons right'>mode_edit</i></a></li>
                    <li><a href='authors_info.php' class='blue-text'>Edit Profile <i class='material-icons right'>account_circle</i></a></li>
                    <li><a href='change_password.php' class='blue-text'>Change Password <i class='material-icons right'>settings</i></a></li>
                    <li><a href='logout.php' class='blue-text'>Logout <i class='material-icons right'>exit_to_app</i></a></li>
                </ul>
            </div>
        </nav>";
    }
?>