<?php if (!isset($_SESSION['login'])) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Module-4</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="bootstrap/css/login-register.css" rel="stylesheet">


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>-->
    <script src="bootstrap/js/html5shiv.js"></script>
    <![endif]-->
    <script src="bootstrap/js/jquery-3.1.0.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="?">NEWS</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <?php if (!isset($_SESSION['login'])):?>
                        <li class="active"><a href="?act=register">Register</a></li>
                        <li><a href="?act=login">Login</a></li>
                    <?php endif;?>
                    <?php if (isset($_SESSION['login'])):?>
                        <li><a href="?act=logout">Logout</a></li>
                        <li class="active" style="padding-left: 20px"><a href=""><strong>Hello, <?=$_SESSION['login']?>!</strong></a></li>                    <?php endif;?>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <form class="form-signin" method="post" action="?act=user_register">
        <h2 class="form-signin-heading">Please sign up   </h2><h5 class="prefix">It's free and always will be.</h5>
        <input type="text" class="input-block-level" name="login" placeholder="Login" required maxlength="15">
        <input type="email" class="input-block-level" name="email" placeholder="Email Address" required maxlength="50">
        <input type="password" class="input-block-level" name="password" placeholder="Password" required maxlength="10">
        <input type="password" class="input-block-level" name="password2" placeholder="Confirm password" required maxlength="10">
        
        <button class="btn btn-large btn-primary" type="submit" name="register">Register</button>
    </form>

</div> <!-- /container -->






