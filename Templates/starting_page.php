<?php
/**
 * @var $slider_news: 3 last news from database
 * @var $top_commentators: TOP 3 commentators from database
 */

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
    <link href="bootstrap/css/style.css" rel="stylesheet">

    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            padding-bottom: 40px;
            color: #5a5a5a;
        }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    
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
                        <li><a href="#">TOP commentators</a>
                            <ul>
                                <li><button type="button" class="btn btn-primary btn-lg active"><?=$top_commentators[0]['author']?></button>
                                    <ul>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">All comments</button></a>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">User info</button></a>
                                    </ul>
                                </li>
                                <li><button type="button" class="btn btn-primary btn-lg active"><?=$top_commentators[1]['author']?></button>
                                    <ul>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">All comments</button></a>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">User info</button></a>
                                    </ul>
                                </li>
                                <li><button type="button" class="btn btn-primary btn-lg active"><?=$top_commentators[2]['author']?></button>
                                    <ul>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">All comments</button></a>
                                        <li><a href="#"><button type="button" class="btn btn-primary btn-lg active">User info</button></a>
                                    </ul>
                                </li>
                            </ul>
                        </li>



                    <?php endif;?>
                </ul>
            </div><!--/.nav-collapse -->
            
            <input class="form-control" style="float: right; margin-top: 10px" id="search" name="search" type="search" placeholder="Search...">
        </div>
    </div>
</div>

<div class="container">

    <div id="here" style="display: inline !important;">
    </div>
    <!-- Carousel
================================================== -->
    <div id="myCarousel" class="carousel slide">
        <div class="carousel-inner">
            <div class="item active">
                <img src="<?=$slider_news[0]['image']?>" alt="">
                <div class="container">
                    <div class="carousel-caption">
                        <h1 style="background-color: #1a1a1a; padding-left: 30px"><?=$slider_news[0]['header']?></h1>
                        <a class="btn btn-large btn-primary" href="?act=read-news&id=<?=$slider_news[0]['id']?>">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="<?=$slider_news[1]['image']?>" alt="">
                <div class="container">
                    <div class="carousel-caption">
                        <h1 style="background-color: #1a1a1a; padding-left: 30px"><?=$slider_news[1]['header']?></h1>
                        <a class="btn btn-large btn-primary" href="?act=read-news&id=<?=$slider_news[1]['id']?>">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="<?=$slider_news[2]['image']?>" alt="">
                <div class="container">
                    <div class="carousel-caption">
                        <h1 style="background-color: #1a1a1a; padding-left: 30px"><?=$slider_news[2]['header']?></h1>
                        <a class="btn btn-large btn-primary" href="?act=read-news&id=<?=$slider_news[2]['id']?>">Learn more</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>

    </div><!-- /.carousel -->


    <!-- Le javascript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>
        !function ($) {
            $(function(){
                // carousel demo
                $('#myCarousel').carousel()
            })
        }(window.jQuery)
    </script>

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



