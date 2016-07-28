<?php
$dbConnect = new mysqli('localhost', 'root', '', 'module4') or die('Database connection error!');
$dbConnect->select_db('module4') or die('Cannot select database!');
$dbConnect->set_charset('utf8');
mb_internal_encoding('UTF-8');

// Live search
$search_news = array();
if(!empty($_GET['q'])) {
    $q = $_GET['q'];
    $select_search_news = $dbConnect->query("SELECT * FROM news WHERE author LIKE '%$q%' 
                                            OR header LIKE '%$q%' OR content LIKE '%$q%'");
    echo "<h3>" . 'Search results:' . "</h3>";
    echo "<ul>";
    while ($news = $select_search_news->fetch_assoc()) {
        echo "<li><a href='?act=read-news&id=" . $news['id'] . "'>" . $news['header'] . "</li></a>" . "<br>";
    };
    echo "</ul>";
}