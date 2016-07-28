<?php require('header.php');

if (!isset($_SESSION['login'])) {
    session_start();
}
?>

<link href="bootstrap/css/read.css" rel="stylesheet">

<div style="padding: 20px">

<h1><?=$row['header']?></h1>
<span class="news-info"><strong>Publication date: </strong><?=$row['date']?></span>
<span class="news-info"><strong>Author: </strong><?=$row['author']?></span>
<div>
<span class="news-info"><strong>Topic is reading by: <?=$read_now?> people now.</strong></span>
<span class="news-info"><strong>Topic is reading by: <?=$read_now+$change_val?> people now.</strong></span>
</div>
<div>
    <img class="news-image" src="<?=$row['image']?>">
</div>

<div class="news-content"><?=$row['content']?></div>
    <div>
        <h5>Tags: </h5>
        <?php foreach ($tags as $tag): ?>
        <a href="?act=search_tag&tag=<?=$tag?>"><button type="button" class="btn btn-primary"><?=$tag?></button></a>
        <?php endforeach; ?>
    </div>

    <form action="?act=comment" method="post">
    <div style="padding-top: 20px">
        <textarea placeholder="Comments... " class="form-control" rows="3" name="comment" required maxlength="255"></textarea>
        <input name="news_id" value="<?=$row['id']?>" hidden>
    </div>
    <div>
        <input class="btn btn-default" type="submit" value="Comment" name="submit">
    </div>
    </form>

    <?php if($comments != null):?>
    <table class="table table-striped">
        <tr>
            <td style="color: #006dcc;"><strong>Author</strong></td>
            <td style="color: #006dcc;"><strong>Comment</strong></td>
            <td style="color: #006dcc;"><strong>Date</strong></td>
        </tr>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?=$comment['author']?></td>
                <td><?=$comment['comment']?></td>
                <td><?=$comment['date']?></td>
            </tr>
        <?php endforeach;?>
    </table>
    <?php endif;?>

</div>

<?php require('footer.php');?>
