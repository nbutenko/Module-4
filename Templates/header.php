<?php
if (!isset($_SESSION['login'])) {
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
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>

    <script src="bootstrap/js/jquery-3.1.0.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

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
                        <li class="active" style="padding-left: 20px"><a href=""><strong>Hello, <?=$_SESSION['login']?>!</strong></a></li>                    
                    <?php endif;?>
                </ul>
            </div><!--/.nav-collapse -->
            <input class="form-control" style="float: right; margin-top: 10px" id="search" name="search" type="text" placeholder="Search...">
        </div>
    </div>
</div>

<div id="here" style="padding-left: 20px; padding-top: 20px; float: right">
</div>


<script>
    $(document).ready(function (e) {
        $('#search').keyup((function () {
            $('#here').show('slow');
            var x = $(this).val();
            console.log(x);
            $.ajax ({
                type: 'GET',
                url: 'live_search.php',
                data: 'q='+x,
                success: function (data) {
                    console.log(data);
                    $('#here').html(data);
                }
            });
        }))
    })
</script>







