<?php require('starting_page.php');

if (!isset($_SESSION['login'])) {
    session_start();
}
?>

<link href="bootstrap/css/style.css" rel="stylesheet">

<?php foreach ($categories as $category):?>
    <h1>
        <a href="?act=view-news&id=<?=$category['id']?>"><?=$category['category']?></a>
    </h1>

    <?php foreach ($news as $row):
        if($category['category'] == $row['category']):?>

    <h3><a href="?act=read-news&id=<?=$row['id']?>"><?=$row['header']?></a></h3>
    <span class="news-info"><strong>Publication date: </strong><?=$row['date']?></span>
    <span class="news-info"><strong>Author: </strong><?=$row['author']?></span>
<?php endif;
endforeach;
endforeach;
?>


<?php require('footer.php');?>
