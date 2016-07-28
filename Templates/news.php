<?php require('header.php');

if (!isset($_SESSION['login'])) {
    session_start();
}
?>

<link href="bootstrap/css/news.css" rel="stylesheet">

<div style="padding: 20px">
<div class="pagination">
    <ul>
        <?php for($i = 1; $i<= $pagination_count_pages; $i++): ?>
        <li class=""><a href="?act=view-news&id=<?=$category_id?>&page=<?=$i?>"><?=$i?></a></li>
        <?php endfor; ?>
    </ul>
</div>


<?php foreach ($news as $row):?>
    <div class="news-block">
    <h3>
        <a href="?act=read-news&id=<?=$row['id']?>"><?=$row['header']?></a>
    </h3>
    <span class="news-info"><strong>Publication date: </strong><?=$row['date']?></span>
    <span class="news-info"><strong>Author: </strong><?=$row['author']?></span>
    <div>
        <img class="news-image" src="<?=$row['image']?>">
    </div>
    <?php if(mb_strlen($row['content']) > 200) {
        $row['content'] = mb_substr(strip_tags($row['content']), 0, 195) . '...';
    }?>
    <div class="news-content"><?=$row['content']?></div>
    </div>
<?php endforeach; ?>
</div>

<?php require('footer.php');?>
