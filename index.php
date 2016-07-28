<?php
error_reporting(E_ERROR);

header('Content-type: image/jpg');
header('Content-type: text/html; charset=UTF-8');
define('USER_AUTH_SESSION_KEY', 'SESSION_ID');

$dbConnect = new mysqli('localhost', 'root', '', 'Module4') or die('Database connection error!');
$dbConnect->select_db('Module4') or die('Cannot select database!');
$dbConnect->set_charset('utf8');
mb_internal_encoding('UTF-8');

/**
 * @return array: Users list from database
 */
function getUsersList() {
    global $dbConnect;
    $users = array();

    $select_users = $dbConnect->query('SELECT id, login, email, password, salt, is_admin FROM users');
    while ($user = $select_users->fetch_assoc()) {
        $users[] = $user;
    }

    return $users;
}

/**
 * @param array $form_fields
 * @return bool
 */
function validation_registration_form(array $form_fields) {
    if (
        !isset($form_fields['login'])
        or !isset($form_fields['email'])
        or !isset($form_fields['password'])
        or !isset($form_fields['password2'])
        or empty($form_fields['login'])
        or empty($form_fields['email'])
        or (
            empty($form_fields['password'])
            or (
                $form_fields['password'] != $form_fields['password2']
            )
        )
    ) {
        return false;
    }
    return true;
}

/**
 * @param array $form_fields
 * @return bool
 */
function validation_auth_form(array $form_fields) {
    if (
        !isset($form_fields['login'])
        or !isset($form_fields['password'])
        or empty($form_fields['login'])
        or empty($form_fields['password'])
    ) {
        return false;
    }
    return true;
}

/**
 * Search users in database by his login
 * @param string $login
 * @return bool|mixed: If user was not found => false, else => true
 */
function find_user($login)
{
    $users = getUsersList();
    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['login'] == $login) {
            return $users[$i];
        }
    }
    return false;
}

/**
 * @param string $param
 * @param string $password
 * @return string
 */
function hash_pass($param, $password)
{
    $restore_pass = md5('MY_SECRET_KEY' . $param . $password . $param);
    return $restore_pass;
}

/**
 * Obfuscation of user password before saving it in database
 * @param string $original_pass : user's password
 * @return array: 'salt' + obfuscated password (MD5-hash)
 */
function obfuscation_password($original_pass) {
    $salt = rand(0, 9999999);
    return array(
        $salt,
        md5(
            'MY_SECRET_KEY' . $salt . $original_pass . $salt
        )
    );
}

/**
 * User's registration in database
 * @param $registration_data
 * @return array: current_user info
 * @throws Exception
 */
function registration_user($registration_data) {
    global $dbConnect;

    list($salt, $hash_password) = obfuscation_password($registration_data['password']);
    $user = [
        'login' => $registration_data['login'],
        'email' => $registration_data['email'],
        'salt' => $salt,
        'password' => $hash_password
    ];

    $current_user_login = $user['login'];
    $current_user_email = $user['email'];
    $current_user_salt = $user['salt'];
    $current_user_password = $user['password'];
    if ($dbConnect->query("INSERT INTO users (login, email, salt, password) 
        VALUES ('$current_user_login', '$current_user_email', '$current_user_salt', '$current_user_password')") === false
    ) {
        throw new Exception('Insert data to database error!');
    }

    $users = getUsersList();
    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['login'] == $current_user_login) {
            $user['id'] = $users[$i]['id'];
            break;
        }
    }

    return $user;
}

/**
 * Текущий, авторизированный, пользователь в системе
 * @return mixed|null: Если пользователь авторизирован,
 *  то возвращаем объект пользователя, инача null
 */
function get_auth_user($current_login)
{
    $user_session_data = $_COOKIE[USER_AUTH_SESSION_KEY]?? null;

    if ($user_session_data === null) {
        return null;
    }
    list($user_id, $session_id) = explode(':', $user_session_data);
    $users = getUsersList();
    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['id'] == $user_id && $users[$i]['login'] == $current_login) {
            return $users[$i];
        }
    }
    return null;
}


/**
 * Printing start_page with list of news
 */
function printStartPage() {
    global $dbConnect;

    $categories = array();
    $news = array();
    $top_commentators = array();

    $select_categories = $dbConnect->query('SELECT * FROM categories');
    $select_news = $dbConnect->query('SELECT categories.category, news.id, news.image, news.date, news.author, news.header FROM categories JOIN news ON categories.id = news.category ORDER BY news.id');

    while ($row = $select_news->fetch_assoc()) {
        $row['date'] = date('Y-m-d H:i:s', $row['date']);
        $news[] = $row;
    }

    $select_commentators = $dbConnect->query('SELECT author, count(author) AS count_comments FROM comments GROUP BY author ORDER BY count_comments DESC limit 3');

    while ($commentator = $select_commentators->fetch_assoc()) {
        $top_commentators[] = $commentator;
    }
    
    $slider_news = array_slice($news, -3);
    
    while ($category = $select_categories->fetch_assoc()) {
        $categories[] = $category;
    }

    require('templates/list.php');
}


//--------------GETTING PARAMS => REDIRECTING ON TEMPLATE PAGES-----------------------------
$act = isset($_GET['act']) ? $_GET['act'] : 'list';

switch ($act) {
    // If is starting page
    case 'list':
        printStartPage();
        break;

    // If click on topic => list of news
    case 'view-news':
        $news = array();
        $category_id = $_GET['id'];
        $news_per_page = 5;
        $start = 0;
        
        (isset($_GET['page'])) ? $active_page = $_GET['page'] : $active_page = 1;
        (isset($_GET['page'])) ? $current_page = $_GET['page'] : $current_page = 1;

        $select_sum_pages = $dbConnect->query("SELECT count(*) FROM news WHERE category = $category_id")->fetch_row();

        $count_pages = $select_sum_pages[0];
        $pagination_count_pages = ceil($count_pages/$news_per_page);
        
        if((!isset($_GET['page'])) OR $current_page == 1) {
            $select_news = $dbConnect->query("SELECT * FROM news WHERE category = $category_id LIMIT $start, $news_per_page");
        } else {
            $start =+ ($current_page-1) * $news_per_page;
            $select_news = $dbConnect->query("SELECT * FROM news WHERE category = $category_id LIMIT $start, $news_per_page");
        }

        while ($row = $select_news->fetch_assoc()) {
            $row['date'] = date('Y-m-d H:i:s', $row['date']);
            $news[] = $row;
        }
        require('templates/news.php');
        break;
    

    // If click on news => read the news
    case 'read-news':
        if (!isset($_GET['id'])) die('Missing id parameter');
        $id = intval($_GET['id']);
        $comments = array();

        $row = $dbConnect->query("SELECT * FROM news WHERE id = $id")->fetch_assoc();
        $row['date'] = date('Y-m-d H:i:s', $row['date']);
        if (!$row) die('News not found!');
        
        // get comments
        $get_comments = $dbConnect->query("SELECT * FROM comments WHERE news_id = $id");
        while ($comment = $get_comments->fetch_assoc()) {
            $comments[] = $comment;
        }
        
        // get tags for printing each word
        $tags = explode(' ', $row['tags']);

        // get readers 
        $read_now = rand(0,5);
        $change_val = 0;

        require('templates/read.php');
        break;

    // If click on Register => registration form
    case 'register':
        require('templates/register_page.php');
        break;

    // If click on Login => login form
    case 'login':
        require('templates/login_page.php');
        break;

    // If click on Logout => logout action
    case 'logout':
        session_start();
        unset($_SESSION['login']);
        session_destroy();
        printStartPage();
        break;

    // After sending registration form data
    case 'user_register':
        session_start();
        $user = null;
        $current_user = null;

        if (isset($_POST['register'])) {
            if (validation_registration_form($_POST) === false) {
                throw new Exception('Not all fields are filled!');
            }
            if (find_user($_POST['login'])!== false) {
                throw new Exception('User login already exists!');
            }
            $user = registration_user($_POST);

            setcookie(
                USER_AUTH_SESSION_KEY,
                $user['id'] . ':' . session_id()
            );
            $current_user = $user;
        }
        $_SESSION['login'] = $current_user['login'];
        printStartPage();
        break;

    // After sending login form data
    case 'user_login':
        session_start();

        $user = null;
        $current_user = null;

        $current_user = get_auth_user($_POST['login']);
        
        if ($current_user['is_admin'] == 1) {
            require('templates/admin.php');
            break 1;
        }

        if ($current_user == null) {
            if (isset($_POST['login_page'])) {
                if (validation_auth_form($_POST) === false) {
                    throw new Exception('Fields error!');
                }
                if (($user = find_user($_POST['login'])) === false) {
                    throw new Exception(
                        'User by login "' . $_POST['login'] . '" was not found!'
                    );
                }
                $restore_pass = hash_pass($user['salt'], $_POST['password']);

                if (hash_equals($user['password'], $restore_pass) === false) {
                    throw new Exception(
                        'Password error'
                    );
                        }
            }
            setcookie(
                USER_AUTH_SESSION_KEY,
                $user['id'] . ':' . session_id()
            );
            $current_user = $user;
        }
        $_SESSION['login'] = $current_user['login'];
        $_SESSION['id'] = $current_user['id'];
        printStartPage();
        break;


    case 'search_tag':
        $search_news = array();
        if(!empty($_GET['tag'])) {
            $tag = $_GET['tag'];
            $select_search_news = $dbConnect->query("SELECT * FROM news WHERE header LIKE '%$tag%' 
                                                    OR content LIKE '%$tag%' OR tags LIKE '%$tag%'");
            while ($row = $select_search_news->fetch_assoc()) {
                $row['date'] = date('Y-m-d H:i:s', $row['date']);
                $news[] = $row;
            }
            require('templates/news.php');
            break;
        }

    case 'comment':
        session_start();
        global $dbConnect;

        if (isset($_POST['submit'])) {
            if (empty($_POST['comment'])) {
                throw new Exception('Empty comment field!');
            }
            $comment = $_POST['comment'];
            $author = $_SESSION['login'];
            $date = date('l jS \of F Y h:i:s A');
            $news_id = $_POST['news_id'];
            $comments = array();

            if ($dbConnect->query("INSERT INTO comments (news_id, author, `date`, comment) 
        VALUES ('$news_id', '$author', '$date', '$comment')") === false
            ) {
                throw new Exception('Insert data to database error!');
            }

            $row = $dbConnect->query("SELECT * FROM news WHERE id = $news_id")->fetch_assoc();
            $row['date'] = date('Y-m-d H:i:s', $row['date']);
            if (!$row) die('News not found!');

            // get comments
            $get_comments = $dbConnect->query("SELECT * FROM comments WHERE news_id = $news_id");
            while ($comment = $get_comments->fetch_assoc()) {
                $comments[] = $comment;
            }

            // get tags
            $tags = explode(' ', $row['tags']);

            // ger readers
            $read_now = rand(0, 5);
            $change_val = 0;

            require('templates/read.php');
            break;
        }
}



